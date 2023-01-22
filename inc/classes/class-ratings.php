<?php
/**
 * Ratings Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace
use WP_Error;

class ratings{ //Ratings Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'rest_api_init', [$this, 'register_rating']);
        add_action('after_switch_theme', [$this, 'create_datbases']);
        add_action( 'user_rating_submitted', [$this, 'calculate_ratings']);
    }

    function register_rating(){ //Function to handle all rating system registerations

        register_rest_field( "comment", 'rating', array( //Register Rating field to Comment Endpoint
            'get_callback' => [$this, 'get_user_rating'], //Get value callback
        ));

        register_meta( 'post', 'rating', array( //Register Rating meta to the Novel
            'object_subtype'  => 'novel',
            'type'   => 'number',
            'single ' => true,
            'show_in_rest' => true,
        ));

        register_rest_route( 'lnarchive/v1', 'submit_rating/(?P<object_id>\d+)', array( //Register submit rating route/endpoint
            'methods' => 'POST', //Method
            'callback' => [ $this, 'submit_rating'], //Callback after receving request
            'permission_callback' => function(){ //Permission Callback
                return is_user_logged_in();
            },
        ));
    }

    function get_user_rating( $comment ){ //Function to get the user rating
        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'user_ratings'; //Ratings Table name
        return $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$comment['post']." AND user_id=".$comment['author']); //Return the rating
    }

    function submit_rating( $request ){ //Endpoint to submit/update a rating

        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'user_ratings'; //Ratings Table name
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        $object_id = $request['object_id']; //Store the object id
        $body = $request->get_json_params(); //Get the body json

        if( $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$object_id." AND user_id=".$user_id) == null){ //Add a new entry
            $response = $wpdb->insert( $table_name, array( 'rating' => $body['rating'], 'object_id' => $object_id, 'user_id' => $user_id ));
        }
        else{ //If the entry is already present then update the entry
            $response = $wpdb->update( $table_name, array( 'rating' => $body['rating']), array('object_id' => $object_id, 'user_id' => $user_id));
        }
        do_action( 'user_rating_submitted', array( 'object_id'=> $object_id));
        return $response;
    }

    function calculate_ratings( $args ) { //Calculate and Store ratings
        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'user_ratings'; //Ratings Table name
        $ratings = $wpdb->get_results("SELECT rating FROM $table_name WHERE object_id=".$args['object_id']); //Get all the ratings

        $total = 0;

        foreach( $ratings as $rating){ //Calculating Total
            $total+=$rating->rating;
        }

        update_post_meta( $args['object_id'], 'rating', $total/count($ratings)); //Updating the rating of the post
    }

    function create_datbases() { //Function to create custom databases

        global $wpdb; //Wpdb Class
        $charset_collate = $wpdb->get_charset_collate(); //Get the Charset Collate
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //Make sure Upgrade.php is imported

        $ratings_table_name = $wpdb->prefix . 'user_ratings'; //User Ratings Table Name

        $ratings_query = "CREATE TABLE " . $ratings_table_name . " (
        rating_id bigint(20) NOT NULL AUTO_INCREMENT,
        object_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        rating bigint(20) NOT NULL check(rating >= 0 AND rating <= 5),
        PRIMARY KEY  (rating_id)
        ) $charset_collate;"; //Create the Table Args
        
        dbDelta([$ratings_query], true);//Execute the Queries
    }
}
?>
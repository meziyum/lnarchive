<?php
/**
 * Ratings Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

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
        add_action( 'rest_api_init', [$this, 'custom_endpoints']);
        add_action('after_switch_theme', [$this, 'create_datbases']);
        add_action('user_rating_submitted', [$this, 'update_ratings_in_comments']);
        add_action( 'rest_api_init', [$this, 'register_meta']);
    }

    function register_meta(){ //Register Metadatas
        register_meta( 'post', 'rating', array( //Register Rating meta
            'object_subtype'  => 'novel',
            'type'   => 'number',
            'single ' => true,
            'show_in_rest' => true,
        ));
    }

    function custom_endpoints(){ //Function to Register Custom Endpoints
        register_rest_route( 'lnarchive/v1', 'submit_rating/(?P<object_id>\d+)', array( //Register submit rating route
            'methods' => 'POST', //Method
            'callback' => [ $this, 'submit_rating'], //Callback after receving request
        ));
    }

    function submit_rating( $request ){ //Endpoint to submit/update a rating
        if( ! is_user_logged_in()) //Error if the user is not logged in
            return new \WP_Error( 'user_not_logged_in', 'The users cannot submit a rating without logging in');

        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'user_ratings'; //Ratings Table name
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        $object_id = $request['object_id']; //Store the object id
        $body = $request->get_json_params(); //Get the body json

        if( $wpdb->get_var("SELECT rating FROM $table_name WHERE object_type='".$body['object_type']."' AND object_id=".$object_id." AND user_id=".$user_id) == null){ //Add a new entry
            $response = $wpdb->insert( $table_name, array( 'rating' => $body['rating'], 'object_type' => $body['object_type'], 'object_id' => $object_id, 'user_id' => $user_id ));
        }
        else{ //If the entry is already present then update the entry
            $response = $wpdb->update( $table_name, array( 'rating' => $body['rating']), array('object_id' => $object_id, 'user_id' => $user_id, 'object_type' => $body['object_type'] ));
        }
        do_action( 'user_rating_submitted', array( 'user_id'=> $user_id, 'object_type' => $body['object_type'], 'object_id' => $object_id, 'rating' => $body['rating']));
        return $response;
    }

    function create_datbases() { //Function to create custom databases

        global $wpdb; //Wpdb Class
        $charset_collate = $wpdb->get_charset_collate(); //Get the Charset Collate
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); //Make sure Upgrade.php is imported

        $ratings_table_name = $wpdb->prefix . 'user_ratings'; //User Ratings Table Name

        $ratings_query = "CREATE TABLE " . $ratings_table_name . " (
        rating_id bigint(20) NOT NULL AUTO_INCREMENT,
        object_type VARCHAR(100) NOT NULL,
        object_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        rating bigint(20) NOT NULL check(rating >= 0 AND rating <= 5),
        PRIMARY KEY  (rating_id)
        ) $charset_collate;"; //Create the Table Args
        
        dbDelta([$ratings_query], true);//Execute the Queries
    }

    function update_ratings_in_comments( $args){ //function to update the ratings in comments
        $comments = get_comments(
            array(
              'post_id' => $args['object_id'],
              'fields ' => 'ids',
              'author__in ' => [$args['user_id']],
              'status' => 'approve' //Change this to the type of comments to be displayed
            )
          );

        foreach( $comments as $comment){
            update_comment_meta( $comment, 'rating', $args['rating']);
        }
    }

    function calculate_ratings( $args ) { //Calculate and Store ratings

    }
}
?>
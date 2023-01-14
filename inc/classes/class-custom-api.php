<?php
/**
 * Custom API Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace
use WP_Error;

class custom_api{ //Template Class

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
        add_action( 'rest_api_init', [$this, 'register_custom_fields']);
        add_action( 'rest_api_init', [$this, 'addOrderbySupportRest']);
    }

    function register_custom_fields() { //Function to register custom field to wp json

        register_rest_field( "comment", 'user_comment_response', array( //Register Comment Response field in comment info request
            'get_callback' => [$this, 'get_user_comment_response'], //Get value callback
        ));
    }

    function custom_endpoints(){ //Function to Register Custom Endpoints
        register_rest_route( 'lnarchive/v1', 'comment/(?P<action>[a-zA-Z0-9-]+)/(?P<comment_id>\d+)', array( //Register Comment Actions
            'methods' => 'POST', //Method
            'callback' => [ $this, 'comment_actions'], //Callback after receving request
        ));
        register_rest_route( 'lnarchive/v1', 'current_user', array( //Register Current User Actions
            'methods' => 'GET', //Method
            'callback' => [ $this, 'current_user_actions'], //Callback after receving request
        ));
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
        return $response;
    }

    function current_user_actions( $request ){ //Get Current User Data

        if(!is_user_logged_in()) //Return false if the user is not logged in
            return false;

        $user_data = get_userdata(get_current_user_id());
        return $user_data;
    }

    function comment_actions($request) { //Function to handle the comment actions route

        if( ! is_user_logged_in()) //Error if the user is not logged in
            return new \WP_Error( 'user_not_logged_in', 'The users cannot like a post without logging in');

        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'comment_response'; //Response Table name
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        $comment_id = $request['comment_id']; //Store the comment id
        $user_response_value = $wpdb->get_var("SELECT response_type FROM $table_name WHERE comment_id=".$comment_id." AND user_id=".$user_id.""); //return the current user response to the comment
        $user_action = $request['action']; //User current action

        if( $user_action == $user_response_value ){ //If the requests are too sent to fast before the database is updated. Avoids multiple enteries with same action
            return false;
        }
        else if( $user_action == 'none' ){
            $wpdb->delete( $table_name, array( 'user_id' => $user_id, 'comment_id' => $comment_id) );
            $count_action = get_comment_meta($comment_id, $user_response_value.'s', true); //Get the count of curent response comment meta
            $meta_update_output_new = update_comment_meta( $comment_id, $user_response_value.'s', --$count_action); //Update the count of current response comment meta
            return $meta_update_output_new; //Return response
        }
        else if( $user_response_value != $user_action && $user_response_value != null) { //If the user is performing a different action than already stored for example like after dislike
            $wpdb->update( $table_name, array( 'response_type' => $user_action), array( 'user_id' => $user_id, 'comment_id' => $comment_id)); //Update the user_comment response entry
            $count_prev_response = get_comment_meta($comment_id, $user_response_value.'s', true); //Get the count of previous response comment meta
            update_comment_meta( $comment_id, $user_response_value.'s', --$count_prev_response); //Decrease the count of previous response comment meta
        }
        else //If no response exists that is the user_response_value is null in the database (cases like when both user_response and action have same values will never be called as front end designed)
            $wpdb->insert( $table_name, array( 'user_id' => $user_id, 'comment_id' => $comment_id, 'response_type' => $user_action )); //Insert a new response data to the database

        $count_action = get_comment_meta($comment_id, $user_action.'s', true); //Get the count of curent response comment meta
        $meta_update_output_new= update_comment_meta( $comment_id, $user_action.'s', ++$count_action); //Update the count of current response comment meta
        return $meta_update_output_new; //Return response
    }

    function get_user_comment_response( $comment ){ //Function to get a user's response to a comment
        global $wpdb; //Global wpdb class
        $table_name = $wpdb->prefix . 'comment_response'; //Response Table name
        $comment_id = $comment["id"]; //Get comment id
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        return $wpdb->get_results("SELECT response_type FROM $table_name WHERE comment_id=$comment_id AND user_id=$user_id"); //return the results
    }

    function addOrderbySupportRest(){
        
        // Add meta your meta field to the allowed values of the REST API orderby parameter
        add_filter(
            'rest_comment_collection_params',
            function( $params ) {
                $fields = ["likes"];
                foreach ($fields as $key => $value) {
                    $params['orderby']['enum'][] = $value;
                }
                return $params;
            },
            10,
            1
        );
        
        // Manipulate query
        add_filter(
            'rest_comment_query',
            function ( $args, $request ) {
                $fields = ["likes"];
                $order_by = $request->get_param( 'orderby' );
                if ( isset( $order_by ) && in_array($order_by, $fields)) {
                    $args['meta_key'] = $order_by;
                    $args['orderby']  = 'meta_value_num';
                }
                return $args;
            },
            10,
            2
        );
    } // addOrderbySupportRest function ends. 
}
?>
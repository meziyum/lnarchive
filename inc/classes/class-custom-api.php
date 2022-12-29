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
    }

    function comment_actions($request) { //Function to handle the comment actions route

        if( ! is_user_logged_in()) //Error if the user is not logged in
            return new \WP_Error( 'user_not_logged_in', 'The users cannot like a post without logging in');

        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'comment_response'; //Response Table name
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        $comment_id = $request['comment_id']; //Store the comment id
        $user_response_value = $wpdb->get_var("SELECT response_type FROM $table_name WHERE comment_id=".$comment_id." AND user_id=".$user_id.""); //return the current user response to the comment

        if( $user_response_value == $request['action']) //If the user is trying to respond with the same actiona as already done for examplie like again after like
            return new \WP_Error( 'same_response', 'The user has already responded with the same action');
        else if( $user_response_value != $request['action'] && $user_response_value != null) { //If the user is performing a different action than already stored for example like after dislike
            $response_update_output = $wpdb->update( $table_name, array( 'response_type' => $request['action']), array( 'user_id' => $user_id, 'comment_id' => $comment_id)); //Update the user_comment response entry
            if( $response_update_output == false) //If the updating of the row fails
                return new \WP_Error( 'user_comment_response_not_updated', 'Unable to update the new user comment response value since the row was not found');
            $count_prev_response = get_comment_meta($comment_id, $user_response_value.'s', true); //Get the count of previous response comment meta
            $meta_update_output_old = update_comment_meta( $comment_id, $user_response_value.'s', --$count_prev_response); //Decrease the count of previous response comment meta
            if( $meta_update_output_old != true) //If the comment meta update fails
                return new \WP_Error( 'comment_meta_not_updated', 'Unable to update the old comment meta for the user either because of incorrect id or values');
        }
        else{ //If no response exists that is the user_response_value is null in the database
            $response_insert_output = $wpdb->insert( $table_name, array( 'user_id' => $user_id, 'comment_id' => $comment_id, 'response_type' => $request['action'] )); //Insert a new response data to the database
            if( $response_insert_output == false) //If the row update fails
            return new \WP_Error( 'user_comment_response_not_inserted', 'Unable to insert the new user comment response value since the row was not found');
        }

        $count_action = get_comment_meta($comment_id, $request['action'].'s', true); //Get the count of curent response comment meta
        $meta_update_output_new = update_comment_meta( $comment_id, $request['action'].'s', ++$count_action); //Update the count of current response comment meta

        if( $meta_update_output_new != true ) //If the comment meta update fails
            return new \WP_Error( 'comment_meta_not_updated', 'Unable to update the new comment meta for the user either because of incorrect id or values');
            
        $response = new \WP_REST_Response(true); //Generate a successfull response
        $response->set_status(200); //Assign a Status Code
        return $response; //Return boolean value on success or failure and int in case the id is not found
    }

    function get_user_comment_response( $comment ){ //Function to get a user's response to a comment
        global $wpdb; //Global wpdb class
        $table_name = $wpdb->prefix . 'comment_response'; //Response Table name
        $comment_id = $comment["id"]; //Get comment id
        $user_id = get_current_user_id(); //Get current user id (nonce must be used for authentication)
        return $wpdb->get_results("SELECT response_type FROM $table_name WHERE comment_id=".$comment_id." AND user_id=".$user_id.""); //return the results;
    }
}
?>
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
    }

    function custom_endpoints(){ //Function to Register Custom Endpoints
        register_rest_route( 'lnarchive/v1', 'comment/(?P<action>[a-zA-Z0-9-]+)/(?P<comment_id>\d+)', array( //Register Comment Actions
            'methods' => 'POST',
            'callback' => [ $this, 'comment_actions'],
        ));
    }

    function comment_actions($request) { //Function to handle the comment actions route

        if( ! is_user_logged_in()){ //Error if the user is not logged in
            return new \WP_Error( 'user_not_logged_in', 'The users cannot like a post without logging in');
        }

        if( $request['action'] == 'like' ){ //If action is like
            $likes = get_comment_meta($request['comment_id'], 'likes', true); //Get the likes count
            $output = update_comment_meta( $request['comment_id'], 'likes', ++$likes); //Update the likes count
        }
        else if( $request['action'] == 'dislike' ){ //If action is dislike
            $dislikes = get_comment_meta($request['comment_id'], 'dislikes', true); //Get the dislikes count
            $output = update_comment_meta( $request['comment_id'], 'dislikes', ++$dislikes); //Update the dislikes count
        } 
        
        $response = new \WP_REST_Response($output);
        $response->set_status(200);
    
        return $response;
    }
}
?>
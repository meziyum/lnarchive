<?php
/**
 * Users Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class users{ //Users Class

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
        register_rest_route( 'lnarchive/v1', 'current_user/(?P<object_id>\d+)', array( //Register Current User Actions
            'methods' => 'GET', //Method
            'callback' => [ $this, 'current_user_data'], //Callback after receving request
            'permission_callback' => function(){ //Permission Callback
                return is_user_logged_in();
            },
        ));
    }
    
    function current_user_data( $request ){ //Get Current User Data for the current post

        global $wpdb; //WPDB class
        $table_name = $wpdb->prefix . 'user_ratings'; //Ratings Table name
        $object_id = $request['object_id'];
        $user_id = get_current_user_id();
        
        $return = array (
            'user_id' => $user_id,
            'user_rating' => $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$object_id." AND user_id=".$user_id),
        );
        return $return; //Return the data
    }
}
?>
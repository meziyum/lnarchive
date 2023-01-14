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
        register_rest_route( 'lnarchive/v1', 'current_user', array( //Register Current User Actions
            'methods' => 'GET', //Method
            'callback' => [ $this, 'current_user_actions'], //Callback after receving request
        ));
    }
    
    function current_user_actions( $request ){ //Get Current User Data

        if(!is_user_logged_in()) //Return false if the user is not logged in
            return false;

        $user_data = get_userdata(get_current_user_id());
        return $user_data;
    }
}
?>
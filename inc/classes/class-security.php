<?php
/**
 * Security Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class security{ //Security Class

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
        add_filter('the_generator', [$this, 'security_remove_version']);
        add_filter( 'login_errors', [$this, 'no_wordpress_errors'] );
        add_filter('xmlrpc_enabled', [ $this, '__return_false']);
    }

    function security_remove_version() { //Function to hide the Wordpress version
        return '';
    }

    function no_wordpress_errors(){ //Function to hide information on wrong credentials
        return 'Your username or password is incorrect';
    }
}
?>
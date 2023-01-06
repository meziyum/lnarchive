<?php
/**
 * Comment Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class comment{ //Comment Class

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
        add_action( 'rest_api_init', [$this, 'register_comment_meta']);
    }

    function register_comment_meta(){
        
        register_meta('comment', 'likes', [ //Register Like Meta for Comments
            'type' => 'number', //Datatype
            'single' => true, //Only one value
            'show_in_rest' => true, //Show in REST API
         ]);

         register_meta('comment', 'dislikes', [ //Register Dislike Meta for Comments
            'type' => 'number', //Datatype
            'single' => true, //Only one value
            'show_in_rest' => true, //Show in REST API
         ]);
    }

    function default_meta_values(){

    }
}
?>
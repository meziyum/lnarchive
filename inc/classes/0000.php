<?php
/**
 * Template Class
 * 
 * Status: Progress
 * 
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class template{ //Template Class

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
    }
}
?>
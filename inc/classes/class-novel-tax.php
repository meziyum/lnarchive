<?php
/**
 * Novel Post Type Taxonomy Class
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class novel_tax{ //Novel Post Type Taxonomies Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks Function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'init', [ $this, 'register_novel_taxonomies']);
    }

    public function register_novel_taxonomies() { //Register all Novel taxonomies
    }
}
?>
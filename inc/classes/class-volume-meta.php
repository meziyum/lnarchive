<?php
/**
 * Volume Meta Class
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class volume_meta{ //Volume Meta Class

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
        add_action( 'rest_api_init', [$this, 'register_meta']);
    }

    function register_meta(){ //Register Volume meta

        $formats = get_terms('format', array( //Get all the format terms
           'hide_empty' => false, //Include the terms with no enteries
        ));

        foreach( $formats as $format ){ //Loop through all the formats

           if( $format->name == "None") //Continue the loop if its the default format
              continue;

           register_meta( 'post', 'isbn_'.$format->name.'_value', array( //Register ISBN values
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'show_in_rest' => true,
           ));

           register_meta( 'post', 'published_date_value_'.$format->name, array( //Register Publication Date values
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'show_in_rest' => true,
           ));
        }
    }
}
?>
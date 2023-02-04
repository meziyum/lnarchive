<?php
/**
 * Volume Metafields Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class volume_meta{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'rest_api_init', [$this, 'register_meta']);
    }

    function register_meta(){

        $formats = get_terms('format', array(
           'hide_empty' => false,
        ));

        foreach( $formats as $format ){

           if( $format->name == "None")
              continue;

           register_meta( 'post', 'isbn_'.$format->name.'_value', array(
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'show_in_rest' => true,
           ));

           register_meta( 'post', 'published_date_value_'.$format->name, array(
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'show_in_rest' => true,
           ));
        }
    }
}
?>
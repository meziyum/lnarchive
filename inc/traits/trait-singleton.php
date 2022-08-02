<?php

/**
 * 
 * Singleton
 * 
 * @package lnpedia
 * 
 */

namespace fusfan\inc\traits;

trait Singleton {
    public function _construct() {

    }

    public function _clone() {

    }

    final public static function get_instance() {
        static $instance =[];
        $called_class = get_called_class();

        if( !isset($instance[ $called_class])) {
            $instance[ $called_class] = new $called_class;

            do_action( sprintf( 'fusfan_theme_singleton_init', $called_class)); //Creating a action to hook functions
        }

        return $instance[ $called_class];
    }
}
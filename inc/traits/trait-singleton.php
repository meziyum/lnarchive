<?php
/**
 * 
 * Singleton Class
 * 
 * #The Purpose of the Singleton class is to make sure that there can be only one instance of a class at a time
 * 
 * @package LNarchive 
 */

namespace lnarchive\inc\traits;

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

            do_action( sprintf( 'lnarchive_theme_singleton_init', $called_class));
        }

        return $instance[$called_class];
    }
}
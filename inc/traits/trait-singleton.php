<?php
/**
 * 
 * Singleton Class
 * 
 * #The Purpose of the Singleton class is to make sure that there can be only one instance of a class at a time
 * 
 * @package LNarchive 
 */

namespace lnarchive\inc\traits; //Namespace

trait Singleton { //Singleton Class
    public function _construct() { //Default constructor

    }

    public function _clone() { //Clone

    }

    final public static function get_instance() { //get_instance singleton function
        static $instance =[]; //Define a null instance
        $called_class = get_called_class(); //Get the Class

        if( !isset($instance[ $called_class])) { //If instance of that class is not set
            $instance[ $called_class] = new $called_class; //Create new instance for the class

            do_action( sprintf( 'lnarchive_theme_singleton_init', $called_class)); //Creating a action hook for singleton for each class
        }

        return $instance[ $called_class]; //Return the Instance of the class
    }
}
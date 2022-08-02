<?php
/**
 * Bootstraps the Theme
 * 
 * @package fusfan
 */

namespace fusfan\inc; //Namespace Definition

use fusfan\inc\traits\Singleton; //Singleton Directory

class fusfan_theme{ //Fusfan Theme Class

     use Singleton; //Use Singleton

     protected function __construct(){ //Default Constructor

         //Load all Classes
         assets::get_instance();
         menus::get_instance();
         meta_boxes::get_instance();
         novel::get_instance();
         sidebars::get_instance();
         novel_tax::get_instance();

         $this->set_hooks(); //Setting the hook below
     }

     protected function set_hooks() { 
         /**
          * Actions
          */

          add_action( 'after_setup_theme',[ $this, 'setup_theme']);
     }

     public function setup_theme() {

         add_theme_support( 'title-tag'); //Title

         add_theme_support( 'custom-logo', [
            'header-text'          => array( 'site-title', 'site-description' ) //Replace Title/Desc by Logo
         ]); //Custom Logo

         add_theme_support( 'custom-background'); //Custom Background

         add_theme_support( 'post-thumbnails'); //Post Thumbnails

         //Register Image Sizes
         add_image_size('featured-thumbnail', 350, 200, true);

         add_theme_support('widgets'); //Add Widgets Theme

         add_theme_support( 'customize_selective_refresh_widgets' );
     
         add_theme_support( 'html5', array(
            'comment-list', 
            'comment-form',
            'search-form',
            'gallery',
            'caption',
            'script',
            'style',
         ) );

         add_theme_support('wp-block-styles');

         add_theme_support( 'align-wide'); //Wide Alignment for Blocks

         add_theme_support( 'editor-styles' );

         add_editor_style('assets/build/css/editor.css'); //Custom Editor
     
        global $content_width;
        if( ! isset( $content_width) ) {
            $content_width=1240;
        }

        }
}
 ?>
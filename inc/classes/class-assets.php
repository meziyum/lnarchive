<?php
/**
 * Enqueuing all assests
 * 
 * @package LNarchive
 * 
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class assets{ //Assests Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }

    protected function set_hooks() {

         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action('wp_enqueue_scripts', [ $this, 'register_styles']);
        add_action('wp_enqueue_scripts', [ $this, 'register_scripts']);
    }

    public function register_styles() { //Styles

      //Register Styles
      wp_register_style( 'main_css', LNARCHIVE_BUILD_CSS_URI . '/main.css', [], filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/main.css'), 'all'); //Sass Stylsheet
      wp_register_style( 'fusfan_stylesheet', LNARCHIVE_DIR_URI . '/style.css', ['main_css'], filemtime(LNARCHIVE_DIR_PATH . '/style.css'), 'all'); //Main Stylesheet
      wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css', [], '6.2.1' , 'all'); //Fontawesome

      //Enqueue Styles
      wp_enqueue_style('main_css');
      wp_enqueue_style('fusfan_stylesheet');
      wp_enqueue_style('fontawesome');
    }

    public function register_scripts() { //Scripts

         //Registering Scripts
         wp_register_script( 'fusfan_main_script', LNARCHIVE_BUILD_JS_URI . '/main.js', array('wp-api'), filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/main.js'), true ); //Main Javascript File

         if( is_single(get_queried_object()) ){
          wp_register_script( 'post_script', LNARCHIVE_BUILD_JS_URI . '/'.get_post_type().'.js', array('wp-api'), filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/'.get_post_type().'.js'), true ); //Post specific javascript
          wp_enqueue_script('post_script');
         }

         if( is_archive() ){
          wp_register_script( 'archive_script', LNARCHIVE_BUILD_JS_URI . '/archive.js', array('wp-api'), filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive.js'), true ); //Arhive Javascript
          wp_enqueue_script('archive_script');
         }

         //Enqueing Scripts
         wp_enqueue_script('fusfan_main_script');
    }
}
?>
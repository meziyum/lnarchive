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
      wp_register_style( 'main_css', LNARCHIVE_BUILD_CSS_URI . '/main.css', ['bootstrap_css'], filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/main.css'), 'all'); //Sass Stylsheet
      wp_register_style( 'fusfan_stylesheet', LNARCHIVE_DIR_URI . '/style.css', ['main_css'], filemtime(LNARCHIVE_DIR_PATH . '/style.css'), 'all'); //Main Stylesheet
      wp_register_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css', [], '5.0.2' , 'all'); //Bootstrap Stylesheet using CDN

      //Enqueue Styles
      wp_enqueue_style('bootstrap_css');
      wp_enqueue_style('main_css');
      wp_enqueue_style('fusfan_stylesheet');
    }

    public function register_scripts() { //Scripts

         //Registering Scripts
         wp_register_script( 'fusfan_main_script', LNARCHIVE_BUILD_JS_URI . '/main.js', [], filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/main.js'), true ); //Main Javascript File
         wp_register_script( 'bootstrap_bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', [], '5.0.2', true ); //Boostrap Bundle including all plugins and dependencies like popper.js

         //Enqueing Scripts
         wp_enqueue_script('fusfan_main_script');
         wp_enqueue_script('bootstrap_bundle');
    }
}
?>
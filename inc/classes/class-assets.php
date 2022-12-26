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

      $path= '';
      $version_info = '';

      if( is_single(get_queried_object()) || is_page()){ //Post types
        $path= LNARCHIVE_BUILD_CSS_URI . '/'.get_post_type().'.css'; 
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/'.get_post_type().'.css');
      }

      wp_register_style( 'secondary_css', $path, [], $version_info, 'all'); //Sass Stylsheet
      wp_enqueue_style('secondary_css');
    }

    public function register_scripts() { //Scripts

        $path= LNARCHIVE_BUILD_JS_URI . '/main.js'; // Default path for the main javascript file
        $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/main.js'); //Version for the main javascript file
        
        //Override the path and version of the javascript file for the specific pages
        if( is_single(get_queried_object()) || is_page()){ //Post types
          $path= LNARCHIVE_BUILD_JS_URI . '/'.get_post_type().'.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/'.get_post_type().'.js');
        }
        else if( is_archive() ){ //All Novel type archives
          $path= LNARCHIVE_BUILD_JS_URI . '/archive.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive.js');
        }
        else if( is_search() ){ //Search Results
          $path= LNARCHIVE_BUILD_JS_URI . '/search.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/search.js');
        }
        else if( is_category() ){ //Category Archive
          $path= LNARCHIVE_BUILD_JS_URI . '/archive-post.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive-post.js');
        }

        wp_register_script('main', $path, array('wp-api'), $version_info , true ); //Register the Javascript
        wp_enqueue_script('main');//Enqueue the Javascript
    }
}
?>
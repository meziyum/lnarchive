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
      wp_register_style( 'fusfan_stylesheet', LNARCHIVE_DIR_URI . '/style.css', ['main_css'], filemtime(LNARCHIVE_DIR_PATH . '/style.css'), 'all'); //Main Stylesheet
      wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css', [], '6.2.1' , 'all'); //Fontawesome

      //Enqueue Styles
      wp_enqueue_style('fusfan_stylesheet');
      wp_enqueue_style('fontawesome');

      //Default Values for unexpected cases
      $path= LNARCHIVE_BUILD_CSS_URI . '/main.css';
      $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/main.css');

      if( is_single(get_queried_object()) || is_page()){ //Post types
        $path= LNARCHIVE_BUILD_CSS_URI . '/'.get_post_type().'.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/'.get_post_type().'.css');
      }
      else if( !is_front_page() && is_home() || is_category()){ //Blog Page
        $path= LNARCHIVE_BUILD_CSS_URI . '/archive_post.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/archive_post.css');
      }
      else if( is_archive() ){
        $path= LNARCHIVE_BUILD_CSS_URI . '/archive.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/archive.css');
      }
      else if( is_search() ){
        $path= LNARCHIVE_BUILD_CSS_URI . '/search.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/search.css');
      }

      wp_register_style( 'main', $path, [], $version_info, 'all'); //Main CSS
      wp_enqueue_style('main'); //Enqueue the Style
    }

    public function register_scripts() { //Scripts

        $path= LNARCHIVE_BUILD_JS_URI . '/main.js'; // Default path for the main javascript file
        $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/main.js'); //Version for the main javascript file
        
        //Override the path and version of the javascript file for the specific pages
        if( is_single(get_queried_object()) || is_page()){ //Post types
          $path= LNARCHIVE_BUILD_JS_URI . '/'.get_post_type().'.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/'.get_post_type().'.js');
        }
        else if( is_category() ){ //Category Archive( must be checked before general archive )
          $path= LNARCHIVE_BUILD_JS_URI . '/archive-post.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive-post.js');
        }
        else if( is_archive() ){ //All Novel type archives
          $path= LNARCHIVE_BUILD_JS_URI . '/archive.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive.js');
        }
        else if( is_search() ){ //Search Results
          $path= LNARCHIVE_BUILD_JS_URI . '/search.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/search.js');
        }

        wp_register_script('main', $path, array('wp-api'), $version_info , true ); //Register the Javascript
        wp_enqueue_script('main');//Enqueue the Javascript
        wp_localize_script( 'main', 'LNarchive_variables', array( //Localize the script with variables
          'nonce' => wp_create_nonce( 'wp_rest' ), //User Nonce for API Authentication
          'user_id' => get_current_user_id(), //Current User ID
          'object_id' => get_the_ID(), //Object ID
          'object_type' =>  get_post_type(), //Object Type
          'comments_count' => get_comments_number(get_the_ID()), //Comments Count
          'wp_rest_url' => get_rest_url(), //Main REST API url
      ) );
    }
}
?>
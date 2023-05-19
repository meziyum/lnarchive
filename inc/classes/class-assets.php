<?php
/**
 * Enqueuing all assests
 * 
 * @package LNarchive
 * 
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class assets{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('wp_enqueue_scripts', [ $this, 'register_styles']);
        add_action('wp_enqueue_scripts', [ $this, 'register_scripts']);
    }

    public function register_styles() {

      wp_register_style( 'fusfan_stylesheet', LNARCHIVE_DIR_URI . '/style.css', ['main_css'], filemtime(LNARCHIVE_DIR_PATH . '/style.css'), 'all');
      wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0' , 'all');

      wp_enqueue_style('fusfan_stylesheet');
      wp_enqueue_style('fontawesome');

      $path= '';
      $version_info = '';

      if(is_page_template('page-templates/calendar.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/calendar.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/calendar.css');
      }
      else if(is_page_template('page-templates/profile.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/profile.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/profile.css');
      }
      else if(is_single(get_queried_object()) || is_page()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/'.get_post_type().'.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/'.get_post_type().'.css');
      }
      else if(!is_front_page() && is_home() || is_category()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/blog.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/blog.css');
      }
      else if(is_post_type_archive('novel')) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/library.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/library.css');
      }
      else {
        $path= LNARCHIVE_BUILD_CSS_URI . '/default.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/default.css');
      }

      wp_register_style( 'main', $path, [], $version_info, 'all');
      wp_enqueue_style('main');
    }

    public function register_scripts() {

        $path= '';
        $version_info = '';
        $novel_taxs = get_object_taxonomies('novel', 'names');

        if(is_page_template('page-templates/calendar.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/calendar.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/calendar.js');
        }
        else if(is_page_template('page-templates/profile.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/profile.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/profile.js');
        } 
        else if(is_single(get_queried_object()) || is_page()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/'.get_post_type().'.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/'.get_post_type().'.js');
        }
        else if(!is_front_page() && is_home() || is_category()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/blog.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/blog.js');
        }
        else if(is_post_type_archive('novel') || is_tax($novel_taxs)) {
          $path= LNARCHIVE_BUILD_JS_URI . '/library.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/library.js');
        }
        else{
          $path= LNARCHIVE_BUILD_JS_URI . '/default.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/default.js');
        }

        wp_register_script('main', $path, array('wp-api'), $version_info , true );
        wp_enqueue_script('main');
    }
}
?>
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
        add_action('login_enqueue_scripts', [$this, 'login_assests']);
    }

    public function register_styles() {

      wp_register_style( 'lnarchive_stylesheet', LNARCHIVE_DIR_URI . '/style.css', ['main_css'], filemtime(LNARCHIVE_DIR_PATH . '/style.css'), 'all');
      wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', [], '6.5.1' , 'all');

      wp_enqueue_style('lnarchive_stylesheet');
      wp_enqueue_style('fontawesome');

      $path= '';
      $version_info = '';

      if(is_page_template('page-templates/calendar.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/calendar.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/calendar.css');
      }
      else if(is_page_template('page-templates/reading-list.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/reading_list.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/reading_list.css');
      }
      else if(is_page_template('page-templates/profile.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/profile.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/profile.css');
      }
      else if(is_page_template('page-templates/add.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/add_data.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/add_data.css');
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
        else if(is_page_template('page-templates/reading-list.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/reading_list.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/reading_list.js');
        }
        else if(is_page_template('page-templates/profile.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/profile.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/profile.js');
        }
        else if(is_page_template('page-templates/add.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/add_data.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/add_data.js');
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

    public function login_assests(){
      $path_css= LNARCHIVE_BUILD_CSS_URI . '/login.css';
      $version_info_css = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/login.css');
      wp_register_style( 'custom-login', $path_css, [], $version_info_css, 'all');
      wp_enqueue_style('custom-login');

      $path_js= LNARCHIVE_BUILD_JS_URI . '/login.js'; 
      $version_info_js = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/login.js');
      wp_register_script('custom_login_js', $path_js, array('wp-api'), $version_info_js , true );
      wp_enqueue_script('custom_login_js');
    }
}
?>
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
      wp_register_style( 'fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css', [], '6.2.1' , 'all');

      wp_enqueue_style('fusfan_stylesheet');
      wp_enqueue_style('fontawesome');

      $path= '';
      $version_info = '';

      if(is_page_template('page-templates/calender.php')){
        $path= LNARCHIVE_BUILD_CSS_URI . '/calender.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/calender.css');
      }
      else if(is_single(get_queried_object()) || is_page()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/'.get_post_type().'.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/'.get_post_type().'.css');
      }
      else if(!is_front_page() && is_home() || is_category()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/archive_post.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/archive_post.css');
      }
      else if(is_archive()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/archive.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/archive.css');
      }
      else if(is_search()) {
        $path= LNARCHIVE_BUILD_CSS_URI . '/search.css';
        $version_info = filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/search.css');
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
        $localize_vars = array(
          'nonce' => wp_create_nonce( 'wp_rest' ),
          'wp_rest_url' => get_rest_url(),
          'custom_api_url' => get_rest_url().'lnarchive/v1/',
          'login_url' => wp_login_url(),
          'per_page' => get_option( 'posts_per_page' ),
        );

        if(is_page_template('page-templates/calender.php')) {
          $path= LNARCHIVE_BUILD_JS_URI . '/calender.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/calender.js');
        }
        else if(is_single(get_queried_object()) || is_page()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/'.get_post_type().'.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/'.get_post_type().'.js');
          $localize_vars['object_id'] = get_the_ID();
          $localize_vars['object_type'] = get_post_type();
          $localize_vars['comments_count'] = get_comments_number(get_the_ID());
        }
        else if(is_category()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/archive-post.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive_post.js');
        }
        else if(is_archive()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/archive.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/archive.js');
          $localize_vars['novel_count'] = wp_count_posts('novel')->publish;
        }
        else if(is_search()) {
          $path= LNARCHIVE_BUILD_JS_URI . '/search.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/search.js');
        }
        else{
          $path= LNARCHIVE_BUILD_JS_URI . '/default.js'; 
          $version_info = filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/default.js');
        }

        wp_register_script('main', $path, array('wp-api'), $version_info , true );
        wp_enqueue_script('main');
        wp_localize_script( 'main', 'lnarchiveVariables', $localize_vars);
    }
}
?>
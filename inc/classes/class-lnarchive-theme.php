<?php
/**
 * The Main Theme Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
use WP_Query;

class lnarchive_theme{

     use Singleton;

     protected function __construct() {
         assets::get_instance();
         localized_variables::get_instance();
         menus::get_instance();
         sidebars::get_instance();
         admin_dashboard::get_instance();
         custom_settings::get_instance();
         security::get_instance();
         post::get_instance();
         novel::get_instance();
         volume::get_instance();
         comment::get_instance();
         ratings::get_instance();
         popularity::get_instance();
         contribution::get_instance();
         subscription::get_instance();
         weightage::get_instance();
         users::get_instance();
         taxonomies::get_instance();
         category::get_instance();
         post_metafields::get_instance();
         post_filter::get_instance();
         similar_novels::get_instance();
         reading_list::get_instance();
         notification::get_instance();
         $this->set_hooks();
     }

     protected function set_hooks() { 

          add_action( 'after_setup_theme',[ $this, 'setup_theme']);
          add_filter('upload_mimes',[$this, 'restrict_mime']); 
          add_filter( 'login_display_language_dropdown', '__return_false' );

          add_action('do_feed', [$this, 'wp_disable_feeds'] );
          add_action('do_feed_rdf', [$this, 'wp_disable_feeds'] );
          add_action('do_feed_rss', [$this, 'wp_disable_feeds'] );
          add_action('do_feed_rss2', [$this, 'wp_disable_feeds'] );
          add_action('do_feed_atom', [$this, 'wp_disable_feeds'] );

          add_action('do_feed_rss2_comments', [$this, 'wp_disable_feeds'] );
          add_action('do_feed_atom_comments', [$this, 'wp_disable_feeds'] );

          add_action( 'feed_links_show_posts_feed', '__return_false', - 1 );
          add_action( 'feed_links_show_comments_feed', '__return_false', - 1 );
          remove_action( 'wp_head', 'feed_links', 2 );
          remove_action( 'wp_head', 'feed_links_extra', 3 );

          add_action( 'init',[ $this, 'rewrite_rules']);
     }

     public function setup_theme() {

         add_theme_support( 'align-wide' );
         add_theme_support( 'custom-background', array(
            'default-color' => '3a7de8',
            )
         );
         add_theme_support( 'custom-logo', [
            'header-text'          => array( 'site-title', 'site-description' ),
         ]);
         add_theme_support( 'customize_selective_refresh_widgets' );
         add_theme_support('widgets-block-editor');
         add_theme_support( 'post-thumbnails');
         add_theme_support('widgets');
         add_image_size('featured-th)umbnail', 350, 300, true);
         add_image_size('novel-cover', 1240, 1748, true);

         $this->create_theme_setup_pages();
     
         global $content_width;
         if(!isset($content_width) ) {
            $content_width=1240;
         }
      }

      function wp_disable_feeds() {
         wp_redirect(home_url());
         wp_die( __('Error: Feeds are disabled') );
      }

      function restrict_mime($mimes) {
         $mimes = array( 
                        'webp' => 'image/webp',
         );
         return $mimes;
      }

      function rewrite_rules() {
            add_rewrite_rule('^profile/(.+)?', 'index.php?pagename=profile', 'top');
      }

      function create_theme_setup_pages() {

         $add_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'title' => 'Add Data',
         );
         $add_query = new WP_Query($add_query_args);

         if(!$add_query->have_posts()) {
            $add_args = array(
               'post_title'    => 'Add Data',
               'post_status'   => 'publish',
               'post_type'     => 'page',
               'page_template' => 'page-templates/add.php',
           );
           wp_insert_post($add_args);
         }

         $profile_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'title' => 'Profile',
         );
         $profile_query = new WP_Query($profile_query_args);

         if(!$profile_query->have_posts()) {
            $profile_args = array(
               'post_title'    => 'Profile',
               'post_status'   => 'publish',
               'post_type'     => 'page',
               'page_template' => 'page-templates/profile.php',
           );
           wp_insert_post($profile_args);
         }

         $calendar_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'title' => 'Release Calendar',
         );
         $calendar_query = new WP_Query($calendar_query_args);

         if(!$calendar_query->have_posts()) {
            $calendar_args = array(
               'post_title'    => 'Release Calendar',
               'post_status'   => 'publish',
               'post_type'     => 'page',
               'page_template' => 'page-templates/calendar.php',
           );
           wp_insert_post($calendar_args);
         }

         $reading_list_query_args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'title' => 'Reading List',
         );
         $reading_list_query = new WP_Query($reading_list_query_args);

         if(!$reading_list_query->have_posts()) {
            $reading_list_args = array(
               'post_title'    => 'Reading List',
               'post_status'   => 'publish',
               'post_type'     => 'page',
               'page_template' => 'page-templates/reading-list.php',
           );
           wp_insert_post($reading_list_args);
         }
     }
}
?>
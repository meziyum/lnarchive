<?php
/**
 * The Main Theme Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class lnarchive_theme{

     use Singleton;

     protected function __construct() {
         assets::get_instance();
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
         weightage::get_instance();
         users::get_instance();
         taxonomies::get_instance();
         category::get_instance();
         post_metafields::get_instance();
         post_filter::get_instance();
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
     
         global $content_width;
         if( ! isset( $content_width) ) {
            $content_width=1240;
         }
      }

      function wp_disable_feeds() {
         wp_redirect( home_url() );
         wp_die( __('Error: Feeds are disabled') );
      }

      function restrict_mime($mimes) {
         $mimes = array( 
                        'webp' => 'image/webp',
         );
         return $mimes;
      }
}
?>
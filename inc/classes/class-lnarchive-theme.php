<?php
/**
 * The Main Theme Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition

use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class lnarchive_theme{ //LNarchive Theme Class

     use Singleton; //Use Singleton

     protected function __construct(){ //Default Constructor

         //Load all Classes
         assets::get_instance();
         menus::get_instance();
         sidebars::get_instance();
         admin_dashboard::get_instance();
         custom_settings::get_instance();
         security::get_instance();
         novel::get_instance();
         volume::get_instance();
         volume_meta::get_instance();
         comment::get_instance();
         ratings::get_instance();
         users::get_instance();
         taxonomies::get_instance();
         taxonomies_metafields::get_instance();
         post_metafields::get_instance();
         post_filter::get_instance();

         $this->set_hooks(); //Setting the hook below
     }

     protected function set_hooks() { 
         /**
          * Actions
          */
          add_action( 'after_setup_theme',[ $this, 'setup_theme']);
          add_action( 'template_redirect', [$this, 'rewrite_search_url']);
          add_filter('upload_mimes',[$this, 'restrict_mime']); 
          add_filter( 'login_display_language_dropdown', '__return_false' ); //Disable login page language switcher

          //Disable Global RSS Feeds
          add_action('do_feed', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rdf', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rss', [$this, 'wp_disable_feeds']);
          add_action('do_feed_rss2', [$this, 'wp_disable_feeds']);
          add_action('do_feed_atom', [$this, 'wp_disable_feeds']);

          //Disable Comment Feeds
          add_action('do_feed_rss2_comments', [$this, 'wp_disable_feeds']);
          add_action('do_feed_atom_comments', [$this, 'wp_disable_feeds']);

          //Remove the RSS Links from HTML
          add_action( 'feed_links_show_posts_feed', '__return_false', - 1 );
          add_action( 'feed_links_show_comments_feed', '__return_false', - 1 );
          remove_action( 'wp_head', 'feed_links', 2 );
          remove_action( 'wp_head', 'feed_links_extra', 3 );
     }

     public function setup_theme() { //Main Setup Theme

         add_theme_support( 'align-wide' ); //Wide Alignment for Blocks
         add_theme_support( 'custom-background', array( //Custom Background
            'default-color' => '3a7de8',
            )
         );
         add_theme_support( 'custom-logo', [
            'header-text'          => array( 'site-title', 'site-description' ), //Replace Title/Desc by Logo
         ]); //Custom Logo
         add_theme_support( 'customize_selective_refresh_widgets' ); //Selective Refresh Support for Widgets
         add_theme_support('widgets-block-editor'); //Widgets Blocks Editor
         add_theme_support( 'post-thumbnails'); //Post Thumbnails
         add_theme_support('widgets'); //Add Widgets support

         //Register Image Sizes
         add_image_size('featured-thumbnail', 350, 300, true); //Thumbnail Size
         add_image_size('novel-cover', 1240, 1748, true); //Novel Cover Size
     
         global $content_width; //Global Content Width Variable
         if( ! isset( $content_width) ) { //If $content_width is not set
            $content_width=1240; //Set Default Content Width
         }
      }

      function rewrite_search_url() { //Rewrite the search result url for better SEO
         if ( is_search() && ! empty( $_GET['s'] ) ) { //If search and the search query not empty
             wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) ); //Restructure the URL
             exit(); //Exit
         }
      }

      function wp_disable_feeds() { //Disable all Feeds
         wp_redirect( home_url() ); //Redirect to Homepage if trying to access Feeds
         wp_die( __('Error: Feeds are disabled') ); //Error Message
      }

      function restrict_mime($mimes) {  //Function to restrict image upload types
         $mimes = array( 
                        'webp' => 'image/webp',
         );
         return $mimes;
      }
}
?>
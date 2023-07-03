<?php
/**
 * Localized Variables Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class localized_variables {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('wp_enqueue_scripts', [ $this, 'localize_variables']);
    }

    public function localize_variables() {
        $blog_page_id = get_option('page_for_posts');
        $object_id = get_the_ID();
        $user_id = get_current_user_id();
        $novel_taxs = get_object_taxonomies('novel', 'names');

        $localize_vars = array(
            'nonce' => wp_create_nonce( 'wp_rest' ),
            'websiteURL' => get_site_url(),
            'blogURL' => get_permalink($blog_page_id),
            'wp_rest_url' => get_rest_url().'wp/v2/',
            'custom_api_url' => get_rest_url().'lnarchive/v1/',
            'login_url' => wp_login_url(),
            'per_page' => get_option( 'posts_per_page' ),
            'object_id' => $object_id,
            'isLoggedIn' => is_user_logged_in(),
            'user_id' => $user_id,
        );

        if(is_page_template('page-templates/calendar.php')) {
        }
        else if(is_page_template('page-templates/profile.php')) {
        } 
        else if(is_single(get_queried_object()) || is_page()) {
          $object_type = get_post_type();
          $comments_enabled = comments_open();
          $localize_vars['commentsEnabled'] = boolval($comments_enabled);
          $localize_vars['object_type'] = $object_type;

          if($comments_enabled) {
            $localize_vars['comments_count'] = get_comments_number(get_the_ID());
          }

          $profile_page_args = array(
            'post_type' => 'page',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_wp_page_template',
                    'value' => 'page-templates/profile.php',
                ),
            ),
          );
          $profile_pages = get_posts($profile_page_args);
          $profile_slug = '';

          if(!empty($profile_pages)) {
            $profile_slug = get_post_field('post_name', $profile_pages[0]);
          }
          $localize_vars['profileName'] = $profile_slug;
          
          if($object_type == 'novel') {
            $localize_vars['rating'] = get_post_meta($object_id, 'rating', true);
            $localize_vars['popularity'] = get_post_meta($object_id, 'popularity', true);
            $localize_vars['user_rating'] = get_user_rating(array('post' => $object_id, 'author' => $user_id));
            $localize_vars['user_subsription'] = get_user_subscription_status($user_id, $object_id);
            $localize_vars['reading_status'] = get_user_reading_status($user_id, $object_id);
            $localize_vars['progress'] = get_user_novel_progress($user_id, $object_id);
          }
        }
        else if(!is_front_page() && is_home() || is_category()) {
        }
        else if(is_post_type_archive('novel') || is_tax($novel_taxs)) {
        }
        else{
        }
        wp_localize_script( 'main', 'lnarchiveVariables', $localize_vars);
    }
}
?>
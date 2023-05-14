<?php
/**
 * Users Main Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class users{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'rest_api_init', [$this, 'custom_endpoints']);
    }

    function register_user_meta($user_id){
        register_meta( 'user', 'gender', array(
            'type' => 'string',
            'description' => 'Gender of the user',
            'single' => true,
            'show_in_rest' => true,
        ));
        register_meta( 'user', 'dob', array(
            'type' => 'string',
            'description' => 'Date of birth of the user',
            'single' => true,
            'show_in_rest' => true,
        ));
    }

    function custom_endpoints(){
        register_rest_route( 'lnarchive/v1', 'current_user/(?P<object_id>\d+)', array(
            'methods' => 'GET',
            'callback' => [ $this, 'current_user_data'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
    }
    
    function current_user_data($request){

        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        $object_id = $request['object_id'];
        $user_id = get_current_user_id();
        
        $return = array (
            'user_id' => $user_id,
            'firstName' => get_the_author_meta('first_name', $user_id),
            'lastName' => get_the_author_meta('last_name', $user_id),
            'nickname' => get_the_author_meta('nickname', $user_id),
            'displayName' => get_the_author_meta('display_name', $user_id),
            'desc' => get_the_author_meta('description', $user_id),
            'coverURL' => get_avatar_url($user_id),
            'user_rating' => $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$object_id." AND user_id=".$user_id),
        );
        return $return;
    }
}
?>
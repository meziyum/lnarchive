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
        add_action( 'init', [$this, 'register_user_meta']);
        add_filter( 'login_headerurl', [$this, 'login_logo_url'] );
        add_filter( 'login_headertext', [$this, 'login_logo_url_title'] );
    }

    function register_user_meta($user_id){
        register_meta('user', 'gender', array(
            'type' => 'string',
            'description' => 'Gender of the user',
            'single' => true,
            'sanitize_callback' => function ($value) {
                return sanitize_gender($value);
            },
            'show_in_rest' => true,
        ));
        register_meta('user', 'dob', array(
            'type' => 'string',
            'description' => 'Date of birth of the user',
            'single' => true,
            'sanitize_callback' => function ($value) {
                return sanitize_date($value);
            },
            'show_in_rest' => true,
        ));
    }

    function login_logo_url() {
        return home_url();
    }
    
    
    function login_logo_url_title() {
        return get_bloginfo('name');
    }

    function custom_endpoints(){
    }
}
?>
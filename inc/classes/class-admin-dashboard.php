<?php
/**
 * Admin Dashboard
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class admin_dashboard{ //Admin Dashboard Template

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions and Filters
          */

        //Adding functions to the hooks
        add_action('admin_init', [$this,'remove_dashboard_meta']);
        add_filter( 'get_user_option_admin_color', [$this,'update_user_option_admin_color']);
        add_filter( 'admin_head-profile.php', [$this,'remove_color_scheme']);
        add_action( 'admin_enqueue_scripts', [$this,'load_admin_assets']);
    }

    function load_admin_assets() { //Load Admin Assets

        wp_register_style( 'admin_css', LNARCHIVE_BUILD_CSS_URI . '/admin.css', [], filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/admin.css'), 'all'); //Register Admin stylesheet
        wp_enqueue_style( 'admin_css' ); //Enque Admin Stylesheet

        wp_register_script( 'admin_js', LNARCHIVE_BUILD_JS_URI . '/admin.js', ['jquery'], filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/admin.js'), true ); //Admin Javascript File
        wp_enqueue_script( 'admin_js' ); //Enqueue the Script
    }

    function remove_dashboard_meta() { //Function to remove dashboard functionalities on admin-init

        //Hide Dashboard Widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Wordpress News
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //At a glance
        remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Recent Activity
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); //Quick Draft

        remove_action( 'welcome_panel', 'wp_welcome_panel' ); //Welcome Message
    }

    function update_user_option_admin_color( $color_scheme ) { //Function to have default admin color scheme
        $color_scheme = 'dashboard-theme'; //default color scheme
        return $color_scheme; //Return the default color scheme
    }

    function remove_color_scheme() { //Function to remove the color scheme picker feature
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' ); //Remvoe Color Scheme picker
    }
}
?>
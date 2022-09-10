<?php
/**
 * Admin Dashboard
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class admin_dashboard{ //Admin Dashboard Template

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action('admin_init', [$this,'remove_dashboard_meta']);
        add_filter( 'get_user_option_admin_color', [$this,'update_user_option_admin_color']);
        add_filter( 'admin_head-profile.php', [$this,'remove_color_scheme']);

    }

    function remove_dashboard_meta() { //Function to remove dashboard functionalities on admin-init

        //Hide Dashboard Widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Wordpress News
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //At a glance
        remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Recent Activity
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); //Quick Draft

        remove_action( 'welcome_panel', 'wp_welcome_panel' ); //Welcome Message

        remove_submenu_page( 'options-general.php', 'options-permalink.php' ); //Hide the Permalinks Settings
        
    }

    function update_user_option_admin_color( $color_scheme ) { //function to have default admin color scheme
        $color_scheme = 'modern';
        return $color_scheme;
    }
 
    function remove_color_scheme() { //function to remove the color scheme picker feature
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }
}
?>
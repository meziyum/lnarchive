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
          * Actions
          */

        //Adding functions to the hooks
        add_action('admin_init', [$this,'remove_dashboard_meta']);
        add_filter( 'get_user_option_admin_color', [$this,'update_user_option_admin_color']);
        add_filter( 'admin_head-profile.php', [$this,'remove_color_scheme']);


        add_action( 'admin_enqueue_scripts', [$this,'load_admin_style']);

        add_action('admin_init', [$this,'dashboard_theme_admin_color_scheme']);
    }

    
    function load_admin_style() {
    wp_register_style( 'admin_css', LNARCHIVE_DIR_URI . '/admin-style.css', false, '1.0.0', 'all' );
    wp_enqueue_style( 'admin_css');
    }

    function remove_dashboard_meta() { //Function to remove dashboard functionalities on admin-init

        //Hide Dashboard Widgets
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Wordpress News
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); //At a glance
        remove_meta_box('dashboard_activity', 'dashboard', 'normal'); //Recent Activity
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); //Quick Draft

        remove_action( 'welcome_panel', 'wp_welcome_panel' ); //Welcome Message

        remove_submenu_page( 'options-general.php', 'options-privacy.php' ); //Hide the Privacy Page Settings
        remove_submenu_page( 'options-general.php', 'options-permalink.php' ); //Hide the Permalinks Settings  
    }

    function update_user_option_admin_color( $color_scheme ) { //function to have default admin color scheme
        $color_scheme = 'dashboard-theme';
        return $color_scheme;
    }
 
    function remove_color_scheme() { //function to remove the color scheme picker feature
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }

    function dashboard_theme_admin_color_scheme() {
        //Get the theme directory
        $theme_dir = get_stylesheet_directory_uri();
      
        //dashboard-theme
        wp_admin_css_color( 'dashboard-theme', 'dashboard-theme',
          $theme_dir.'/dashboard-theme.css',
          array( '#1d2327', '#fff', '#23c247' , '#4180e0')
        );
    }
}
?>
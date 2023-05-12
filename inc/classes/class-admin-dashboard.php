<?php
/**
 * Admin Dashboard
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class admin_dashboard{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('admin_init', [$this,'remove_dashboard_meta']);
        add_filter( 'get_user_option_admin_color', [$this,'update_user_option_admin_color']);
        add_filter( 'admin_head-profile.php', [$this,'remove_color_scheme']);
        add_action( 'admin_enqueue_scripts', [$this,'load_admin_assets']);
        add_filter( 'show_admin_bar', [$this, 'hide_admin_bar'] );
        add_filter( 'gettext', [$this, 'wpse22764_gettext']);
    }

    private function load_admin_assets() {

        wp_register_style( 'admin_css', LNARCHIVE_BUILD_CSS_URI . '/admin.css', [], filemtime(LNARCHIVE_BUILD_CSS_DIR_PATH . '/admin.css'), 'all');
        wp_enqueue_style( 'admin_css' );

        wp_register_script( 'admin_js', LNARCHIVE_BUILD_JS_URI . '/admin.js', ['jquery'], filemtime(LNARCHIVE_BUILD_JS_DIR_PATH . '/admin.js'), true );
        wp_enqueue_script( 'admin_js' );
    }

    private function remove_dashboard_meta() {

        remove_meta_box('dashboard_primary', 'dashboard', 'normal');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');

        remove_action( 'welcome_panel', 'wp_welcome_panel' );
    }

    private function update_user_option_admin_color( $color_scheme ) {
        $color_scheme = 'dashboard-theme';
        return $color_scheme;
    }

    private function remove_color_scheme() {
        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    }

    private function hide_admin_bar(){
        return false;
    }

    private function wpse22764_gettext( $original ) {

        if(get_post_type() == 'post')
            return $original;

        if ( 'Excerpt' == $original ) {
            return 'Description';
        } else {

            $pos = strpos($original, 'Excerpts are optional hand-crafted summaries of your');

            if ($pos !== false) {
                return  ''; 
            }
        }
        return $original;
    }
}
?>
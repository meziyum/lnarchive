<?php
/**
 * Notification Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class notification {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('after_switch_theme', [$this, 'create_datbases']);
    }

    function create_datbases() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $notification_table = $wpdb->prefix . 'notifications';
        $notification_interaction_table = $wpdb->prefix . 'notification_interaction';
        $query_array = array();
        dbDelta($query_array, true);
    }
}
?>
<?php
/**
 * Reading List Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class reading_list {
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
        $reading_list_table_name = $wpdb->prefix . 'reading_list';
        $reading_list_item_table_name = $wpdb->prefix . 'reading_list_items';
        $progress_status_table_name = $wpdb->prefix . 'progress_status';
        $query_array = array();

        if ($wpdb->get_var("SHOW TABLES LIKE '$reading_list_table_name'") !== $reading_list_table_name) {
            $reading_list_query = "CREATE TABLE " . $reading_list_table_name . " (
            list_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            name VARCHAR(20) NOT NULL,
            public TINYINT(1) NOT NULL,
            PRIMARY KEY (list_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
            ) $charset_collate;";
            array_push($query_array, $reading_list_query);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$reading_list_item_table_name'") !== $reading_list_item_table_name) {
            $reading_list_item_query = "CREATE TABLE " . $reading_list_item_table_name . " (
            item_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_id bigint(20) UNSIGNED NOT NULL,
            list_id bigint(20) UNSIGNED NOT NULL,
            PRIMARY KEY (item_id),
            FOREIGN KEY (object_id) REFERENCES {$wpdb->prefix}posts(ID),
            FOREIGN KEY (list_id) REFERENCES $reading_list_table_name(list_id)
            ) $charset_collate;";
            array_push($query_array, $reading_list_item_query);
        }

        if ($wpdb->get_var("SHOW TABLES LIKE '$progress_status_table_name'") !== $progress_status_table_name) {
            $progress_status_item_query = "CREATE TABLE " . $progress_status_table_name . " (
            entry_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            object_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            progress bigint(20) UNSIGNED NOT NULL,
            status VARCHAR(20) NOT NULL,
            PRIMARY KEY (entry_id),
            FOREIGN KEY (object_id) REFERENCES {$wpdb->prefix}posts(ID),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
            ) $charset_collate;";
            array_push($query_array, $progress_status_item_query);
        }

        dbDelta($query_array, true);
    }
}
?>
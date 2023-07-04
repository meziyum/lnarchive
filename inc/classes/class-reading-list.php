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
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_routes() {
            'methods' => 'POST',
            'callback' => [ $this, 'updateReadingList'],
            'permission_callback' => function(){
            },
        ));
    }

    function updateReadingList($request) {
        $body = $request->get_json_params();
        $object_id = $body['object_id'];
        $status = $body['status'];
        $progress = $body['progress'];
        $reading_lists = $body['progress'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'reading_list_items';
    }

    function get_reading_lists_route($request) {
        $user_id = $request['user_id'];
        return $this->get_reading_lists($user_id);
    }

    function updateUserReadingStatusProgress($user_id, $object_id, $status, $progress) {

        if ($status == 'none' && $progress == 0) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'progress_status';
        $current_status = get_user_reading_status($user_id, $object_id);
        $current_progress = get_user_novel_progress($user_id, $object_id);

        if (!$current_status && !$current_progress) {
            $wpdb->insert($table_name, array('object_id' => $object_id, 'user_id' => $user_id, 'status' => $status, 'progress' => $progress));
        } else {
            $updated_array = array();
            if ($current_progress != $progress) {
                $updated_array['progress'] = $progress;
            }
                
            if ($current_status != $status) {
                $updated_array['status'] = $status;
            }
            $wpdb->update($table_name, $updated_array, array('user_id' => $user_id, 'object_id' => $object_id));
        }
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
            public TINYINT(1) NOT NULL DEFAULT '1,
            progress TINYINT(1),
            status VARCHAR(2-) DEFAULT 'none',
            ratings TINYINT(1),
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
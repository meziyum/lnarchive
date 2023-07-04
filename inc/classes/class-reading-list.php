<?php
/**
 * Reading List Class
 */

namespace lnarchive\inc;

use Error;
use lnarchive\inc\traits\Singleton;
use WP_REST_Response;

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
        register_rest_route( 'lnarchive/v1', 'reading_list', array(
            'methods' => 'POST',
            'callback' => [$this, 'updateReadingList'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
        register_rest_route( 'lnarchive/v1', 'reading_list/(?P<list_id>\d+)', array(
            'methods' => 'GET',
            'callback' => [$this, 'getReadingListRoute'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
    }

    function getReadingListRoute($request){
        $list_id = $request['list_id'];
        $user_id = get_current_user_id();
        global $wpdb;
        $list_table_name = $wpdb->prefix . 'reading_list';
        $list = $wpdb->get_results("SELECT user_id, public, progress, status, ratings FROM $list_table_name WHERE list_id = $list_id", 'ARRAY_A')[0];

        if ($user_id != $list['user_id']  && !$list['public']) {
            return new WP_REST_Response('Access Denied', 403);
        }
        
        $args = array(
            'list_id' => $list_id,
            'progress' => $list['progress'],
            'rating' => $list['ratings'],
            'status' => $list['status'],
        );
        return get_reading_list_items($args);
    }

    function updateReadingList($request) {
        $body = $request->get_json_params();
        $user_id = get_current_user_id();
        $object_id = $body['object_id'];
        $status = $body['status'];
        $progress = $body['progress'];
        $reading_lists = $body['lists'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'reading_list_items';
        $this->updateUserReadingStatusProgress($user_id, $object_id, $status, $progress);

        foreach($reading_lists as $reading_list) {
            $list_id = $reading_list['list_id'];
            $action = $reading_list['action'];
            
            if ($action == 0) {
                $wpdb->delete($table_name, array('object_id' => $object_id, 'list_id' => $list_id));
            } else if($action == 1) {
                $wpdb->insert($table_name, array('object_id' => $object_id, 'list_id' => $list_id));
            }
        }
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

            if(count($updated_array)>0) {
                $wpdb->update($table_name, $updated_array, array('user_id' => $user_id, 'object_id' => $object_id));
            }
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
            public TINYINT(1) NOT NULL DEFAULT '1',
            progress TINYINT(1) NOT NULL DEFAULT '1',
            status TINYINT(1) NOT NULL DEFAULT '1',
            ratings TINYINT(1) NOT NULL DEFAULT '1',
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
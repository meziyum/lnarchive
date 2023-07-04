<?php
/**
* 
* User Helper Functions
* 
* @package LNarchive
*/

    function get_reading_lists_with_novel_status($user_id, $novel_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reading_list';
        $reading_lists = $wpdb->get_results("SELECT list_id, name FROM $table_name WHERE user_id=$user_id", 'ARRAY_A');

        for($i=0; $i<count($reading_lists); $i++) {
            if (is_present_in_reading_list($novel_id, $reading_lists[$i]['list_id'])) {
                $reading_lists[$i]['present'] = 1;
            } else {
                $reading_lists[$i]['present'] = 0;
            }
        }
        return $reading_lists;
    }
?>
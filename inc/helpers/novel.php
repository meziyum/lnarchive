<?php
/**
* 
* Novel Helper Functions
* 
* @package LNarchive
*/

    function get_user_rating($args) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        return $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$args['post']." AND user_id=".$args['author']);
    }

    function get_user_subscription_status($user_id, $novel_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_subscriptions';
        $already_subscribed = $wpdb->get_var(
            $wpdb->prepare("SELECT subscription_id FROM $table_name WHERE user_id=%d AND object_id = %d",
            $user_id,
            $novel_id,
        ));

        if ($already_subscribed) {
            return true; 
        }
        return false;
    }

    function get_user_reading_status($user_id, $novel_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'progress_status';
        $current_status = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT status FROM $table_name WHERE user_id=%d AND object_id=%d",
                $user_id,
                $novel_id,
            )
        );
        return $current_status;
    }

    function get_user_novel_progress($user_id, $novel_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'progress_status';
        $current_progress = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT progress FROM $table_name WHERE user_id=%d AND object_id=%d",
                $user_id,
                $novel_id,
            )
        );
        return $current_progress;
    }

    function is_present_in_reading_list($novel_id, $list_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'reading_list_items';
        $already_present = $wpdb->get_var(
            $wpdb->prepare("SELECT item_id FROM $table_name WHERE object_id=%d AND list_id = %d",
            $novel_id,
            $list_id,
        ));

        if ($already_present) {
            return true; 
        }
        return false;
    }
?>
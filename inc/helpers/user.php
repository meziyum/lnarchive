<?php
/**
* 
* User Helper Functions
* 
* @package LNarchive
*/

    function get_user_rating($comment) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        return $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$comment['post']." AND user_id=".$comment['author']);
    }

    function get_user_subscription_status($user_id, $object_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_subscriptions';
        $already_subscribed = $wpdb->get_var("SELECT subscription_id FROM $table_name WHERE user_id=$user_id AND object_id = $object_id");

        if ($already_subscribed) {
            return true; 
        }
        return false;
    }
?>
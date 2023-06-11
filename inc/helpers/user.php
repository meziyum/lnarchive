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
?>
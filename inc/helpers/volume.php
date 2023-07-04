<?php
/**
* 
* Volume Helper Functions
* 
* @package LNarchive
*/

    function get_total_volumes_count($novel_id) {
        $vol_args = array(
            'post_type' => 'volume',
            'posts_per_page' => -1,
            'meta_key' => 'series_value',
            'meta_value' => $novel_id,
            'fields' => 'ids',
        );                       
        $vquery = new WP_Query($vol_args);
        return $vquery->post_count;
    }
?>
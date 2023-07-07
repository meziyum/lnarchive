<?php
/**
* 
* General Helper Functions
* 
* @package LNarchive
*/

    function get_contributor_roles(){
        return array(
            'Novice Contributor' => 0,
            'Active Contributor' => 100,
            'Dedicated Contributor' => 500,
            'Veteran Contributor' => 1500,
            'Elite Contributor' => 3000,
            'Master Contributor' => 6000,
            'Grandmaster Contributor' => 12000,
            'Legendary Contributor' => 25000,
            'Supreme Contributor' => 50000,
            'God Contributor' => 100000
        );
    }

    function get_reading_list_items($args) {
        global $wpdb;
        $items_table_name = $wpdb->prefix . 'reading_list_items';
        $user_id = get_current_user_id();
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT object_id FROM $items_table_name WHERE list_id = %d",
                $args['list_id'],
            ),
            'ARRAY_N'
        );
        $novels_list = array();
        
        foreach($results as $novel_entry) {
            $response= array();
            $novel = get_post($novel_entry[0]);
            $response['ID'] = $novel->ID;
            $response['title'] = $novel->post_title;
            $response['cover'] = get_the_post_thumbnail_url($novel->ID);

            if ($args['rating']) {
                $response['rating'] = get_user_rating(array('post' => $novel->ID, 'author' => $user_id));
            }
            
            if ($args['status']) {
                $response['status'] = get_user_reading_status($user_id, $novel->ID);
            }

            if ($args['comments']) {
                $response['comments'] = get_user_novel_list_comments($user_id, $novel->ID);
            }

            if ($args['progress']) {
                $response['progress'] = get_user_novel_progress($user_id, $novel->ID);
                $response['volumesCount'] = get_total_volumes_count($novel->ID);
            }

            array_push($novels_list, $response);
        }

        return $novels_list;
    }
?>
<?php
/**
 * Ratings Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class ratings{

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('rest_api_init', [$this, 'register_rating']);
        add_action('after_switch_theme', [$this, 'create_datbases']);
        add_action('user_rating_submitted', [$this, 'calculate_ratings']);
    }

    function register_rating() {
        register_rest_field( "comment", 'rating', array(
            'get_callback' => [$this, 'get_user_rating'],
        ));

        register_meta( 'post', 'rating', array(
            'object_subtype'  => 'novel',
            'type'   => 'number',
            'single ' => true,
            'show_in_rest' => true,
        ));

        register_rest_route( 'lnarchive/v1', 'submit_rating/(?P<object_id>\d+)', array(
            'methods' => 'POST',
            'callback' => [ $this, 'submit_rating'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
    }

    function get_user_rating($comment) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        return $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$comment['post']." AND user_id=".$comment['author']);
    }

    function submit_rating($request) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        $user_id = get_current_user_id();
        $object_id = $request['object_id'];
        $body = $request->get_json_params();

        if( $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$object_id." AND user_id=".$user_id) == null) {
            $response = $wpdb->insert( $table_name, array( 'rating' => $body['rating'], 'object_id' => $object_id, 'user_id' => $user_id ));
        }
        else {
            $response = $wpdb->update( $table_name, array( 'rating' => $body['rating']), array('object_id' => $object_id, 'user_id' => $user_id));
        }
        do_action( 'user_rating_submitted', array( 'object_id'=> $object_id));
        return $response;
    }

    function calculate_ratings( $args ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        $ratings = $wpdb->get_results("SELECT rating FROM $table_name WHERE object_id=".$args['object_id']);

        $total = 0;

        foreach( $ratings as $rating) {
            $total+=$rating->rating;
        }

        update_post_meta( $args['object_id'], 'rating', $total/count($ratings));
    }

    function create_datbases() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $ratings_table_name = $wpdb->prefix . 'user_ratings';

        $ratings_query = "CREATE TABLE " . $ratings_table_name . " (
        rating_id bigint(20) NOT NULL AUTO_INCREMENT,
        object_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        rating bigint(20) NOT NULL check(rating >= 0 AND rating <= 5),
        PRIMARY KEY  (rating_id)
        ) $charset_collate;";
        
        dbDelta([$ratings_query], true);
    }
}
?>
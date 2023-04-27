<?php
/**
 * Comment Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
class comment {

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'rest_api_init', [$this, 'register_comment_system']);
        add_action( 'rest_api_init', [$this, 'addOrderbySupportRest']);
        add_action('after_switch_theme', [$this, 'create_datbases']);
    }

    function register_comment_system(){
        
        register_meta('comment', 'likes', [
            'type' => 'number',
            'single' => true,
            'default ' => 0,
            'show_in_rest' => array(
                'schema' => array(
                    'type'  => 'number',
                    'default' => 0,
                ),
            ),
         ]);

         register_meta('comment', 'dislikes', [
            'type' => 'number',
            'single' => true,
            'default ' => 0,
            'show_in_rest' => array(
                'schema' => array(
                    'type'  => 'number',
                    'default' => 0,
                ),
            ),
         ]);

         register_meta('comment', 'progress', [
            'type' => 'number',
            'single' => true,
            'default ' => 0,
            'show_in_rest' => array(
                'schema' => array(
                    'type'  => 'number',
                    'default' => 0,
                    'context'     => [ 'view', 'edit' ],
                ),
            ),
         ]);

         register_rest_field( "comment", 'user_comment_response', array(
            'get_callback' => [$this, 'get_user_comment_response'],
        ));

        register_rest_route( 'lnarchive/v1', 'submit_comment', array(
            'methods' => 'POST',
            'callback' => [ $this, 'submit_comment_route'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));

        register_rest_route( 'lnarchive/v1', 'comment_(?P<action>[a-zA-Z0-9-]+)/(?P<comment_id>\d+)', array(
            'methods' => 'POST',
            'callback' => [ $this, 'comment_actions'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
    }

    function submit_comment_route( $request ) {

        $current_user = wp_get_current_user();
        $body = $request->get_json_params();
            
        $comment_data = array(
            'comment_post_ID'      => $body['postID'],
            'comment_content'      => $body['content'],
            'user_id'              => $current_user->ID,
            'comment_author'       => $current_user->user_login,
            'comment_author_email' => $current_user->user_email,
            'comment_author_url'   => $current_user->user_url,
            'comment_meta'         => array(
                'likes' => 0,
                'dislikes' => 0,
                'progress' => $body['progress'],
            ),
        );
        wp_insert_comment($comment_data);

        return new \WP_REST_Response( array( 'message' => 'Comment successfully created!' ), 201 );
    }

    function comment_actions($request) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'comment_response';
        $user_id = get_current_user_id();
        $comment_id = $request['comment_id'];
        $user_response_value = $wpdb->get_var("SELECT response_type FROM $table_name WHERE comment_id=".$comment_id." AND user_id=".$user_id."");
        $user_action = $request['action'];

        if( $user_action == $user_response_value ) {
            return false;
        }
        else if( $user_action == 'none' ){
            $wpdb->delete( $table_name, array( 'user_id' => $user_id, 'comment_id' => $comment_id) );
            $count_action = get_comment_meta($comment_id, $user_response_value.'s', true);
            $meta_update_output_new = update_comment_meta( $comment_id, $user_response_value.'s', --$count_action);
            return $meta_update_output_new;
        }
        else if( $user_response_value != $user_action && $user_response_value != null) {
            $wpdb->update( $table_name, array( 'response_type' => $user_action), array( 'user_id' => $user_id, 'comment_id' => $comment_id));
            $count_prev_response = get_comment_meta($comment_id, $user_response_value.'s', true);
            update_comment_meta( $comment_id, $user_response_value.'s', --$count_prev_response);
        }
        else
            $wpdb->insert( $table_name, array( 'user_id' => $user_id, 'comment_id' => $comment_id, 'response_type' => $user_action ));

        $count_action = get_comment_meta($comment_id, $user_action.'s', true);
        $meta_update_output_new= update_comment_meta( $comment_id, $user_action.'s', ++$count_action);
        return $meta_update_output_new;
    }

    function get_user_comment_response( $comment ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'comment_response';
        $comment_id = $comment["id"];
        $user_id = get_current_user_id();
        return $wpdb->get_results("SELECT response_type FROM $table_name WHERE comment_id=$comment_id AND user_id=$user_id");
    }

    function addOrderbySupportRest(){
        
        add_filter(
            'rest_comment_collection_params',
            function( $params ) {
                $fields = ["likes", "progress", "author"];
                foreach ($fields as $value) {
                    $params['orderby']['enum'][] = $value;
                }
                return $params;
            },
            30,
            1
        );
        
        add_filter(
            'rest_comment_query',
            function ( $args, $request ) {

                $metas = array( 'likes', 'progress');
                $order_by = $request->get_param( 'orderby' );
                if( isset( $order_by ) ){
                    if ( in_array( $order_by, $metas) ) {
                        $args['meta_key'] = $order_by;
                        $args['orderby'] = 'meta_value_num';
                    }
                    else if( $order_by=='author' ) {
                        $args['user_id'] = get_current_user_id();
                    }
                }
                return $args;
            },
            10,
            2
        );
    }

    function create_datbases() {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $comment_response_table_name = $wpdb->prefix . 'comment_response';

        $comment_response_query = "CREATE TABLE " . $comment_response_table_name . " (
        response_id bigint(20) NOT NULL AUTO_INCREMENT,
        comment_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        response_type VARCHAR(100) NOT NULL,
        PRIMARY KEY  (response_id)
        ) $charset_collate;";
        
        dbDelta([$comment_response_query], true);
    }
}
?>
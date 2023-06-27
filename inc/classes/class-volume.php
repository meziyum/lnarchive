<?php
/**
 * Volume Main Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;

use DateTime;
use lnarchive\inc\traits\Singleton;
use WP_Query;

class volume{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [ $this, 'register_volume']);
        add_action('save_post_volume', [$this, 'auto_update_volume']);
        add_action( 'rest_api_init', [$this, 'addOrderbySupportRest']);
        add_action( 'rest_api_init', [$this, 'register_routes']);
        add_action( 'rest_api_init', [$this, 'register_rest_fields']);
        add_action( 'rest_api_init', [$this, 'register_meta']);
        add_action('template_redirect', [$this, 'redirect_volume_to_404']);
        add_filter( 'post_row_actions', [$this, 'remove_view_action_from_list'], 10, 2 );
        add_filter( 'draft_to_publish', [$this, 'new_volume_publish'], 10, 1);
        add_filter( 'trash_volume', [$this, 'trash_existing_volume'], 10, 3);
        /*add_filter( 'updated_post_meta', [$this, 'update_volume_meta'], 10, 4);*/
        add_filter( 'post_updated_messages', [$this, 'custom_post_updated_messages'] );
    }

    public function register_volume() {

        $labels = array(
            'name'                => 'Volumes',
            'singular_name'       => 'Volume',
            'menu_name'           => 'Volumes',
            'all_items'           => 'All Volumes',
            'view_item'           => 'View Volume',
            'view_items'           => 'View Volumes',
            'add_new_item'        => 'Add New Volume',
            'add_new'             => 'Add New',
            'edit_item'           => 'Edit Volume',
            'update_item'         => 'Update Volume',
            'search_items'        => 'Search Volume',
            'not_found'           => 'The Volume was not found',
            'not_found_in_trash'  => 'The Volume was not found in the trash',
            'archives' => 'Volumes Archive',
            'attributes' => 'Volume Attributes',
            'insert_into_item' => 'Insert into Volume',
            'uploaded_to_this_item' => 'Uploaded to this Volume',
            'featured_image' => 'Cover',
            'set_featured_image' => 'Set Cover',
            'remove_featured_image' => 'Remove Cover',
            'use_featured_image' => 'Use Cover',
            'filter_items_list' => 'Filter Volumes',
            'filter_by_date' => 'Filter by release date',
            'items_list_navigation' => 'Volume Navigation',
            'items_list' => 'Volumes Library',
            'item_published' => 'Volume published',
            'item_published_privately' => 'Volume published privately',
            'item_reverted_to_draft' => 'Volume reverted to Draft',
            'item_scheduled' => 'Volume release scheduled',
            'item_updated' => 'Volume Updated',
            'item_link' => 'Volume Link',
            'item_link_description' => 'A link to a Volume',
        );

        $args = array(
            'label'               => 'Volume',
            'description'         => 'All volumes data',
            'labels'              => $labels,
            'public'              => false,
            'hierarchical'        => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => true,
            'rest_base'           => "volumes",
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-book',
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            'supports'            => array( 'title','excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            'register_meta_box_cb' => null,
            'taxonomies'          => array( 'genre', 'translator', 'format'),
            'has_archive' => true,
            'rewrite'   => [
                            'slug'  => 'volume',
                            'with_front'    => true,
                            'feeds' => false,
                            'pages' => false,
            ],

            'query_var' => 'volume',
            'can_export'          => true,
            'delete_with_user'  => false,
        );

        register_post_type( 'volume', $args );
    }

    function register_meta(){

        $formats = get_terms('format', array(
           'hide_empty' => false,
        ));

        foreach( $formats as $format ){

           if( $format->name == "None")
              continue;

           register_meta( 'post', 'isbn_'.$format->name.'_value', array(
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'show_in_rest' => true,
           ));

           register_meta( 'post', 'published_date_value_'.$format->name, array(
              'object_subtype'  => 'volume',
              'type'   => 'string',
              'sanitize_callback' => function($value) {
                return sanitize_date($value);
            },
              'show_in_rest' => true,
           ));
        }
    }

    public function register_rest_fields(){
        register_rest_field( "volume", 'novel_link', array(
            'get_callback' => [$this, 'get_novel_link'],
        ));
    }

    public function register_routes() {
        register_rest_route( 'lnarchive/v1', 'formats_list', array(
            'methods' => 'GET',
            'callback' => [ $this, 'get_volume_formats'],
            'permission_callback' => function(){
                return true;
            },
        ));
        register_rest_route( 'lnarchive/v1', 'volume_filters', array(
            'methods' => 'GET',
            'callback' => [ $this, 'get_volume_filters'],
            'permission_callback' => function(){
                return true;
            },
        ));
    }

    public function get_novel_link($volume) {
        $volume_id = $volume['id'];
        $novel_id = get_post_meta($volume_id, 'series_value', true);
        return get_permalink($novel_id);
    }

    public function get_volume_filters() {
        $filter_taxonomies = get_object_taxonomies('volume', 'objects');
        $response = array();

        foreach($filter_taxonomies as $tax){
            array_push($response, array(
                'taxQueryName' => $tax->rest_base,
                'taxLabel' => $tax->label,
                'list' => get_terms_except_default($tax),
            ));
        }
        return $response;
    }

    public function get_volume_formats() {

        $formats = get_terms( array(
            'taxonomy'   => 'format',
            'hide_empty' => false,
        ));
        $response = array();

        foreach( $formats as $format){
            if( $format->name == "None")
                continue;
            array_push($response, $format->name);
        }
        return $response;
    }

    function addOrderbySupportRest(){
        
        add_filter(
            'rest_volume_collection_params',
            function( $params ) {
                $formats = get_terms( array(
                    'taxonomy'   => 'format',
                    'hide_empty' => false,
                ));

                foreach ($formats as $format) {
                    $params['orderby']['enum'][] = 'published_date_value_'.$format->name;
                }
                return $params;
            },
            30,
            1
        );
        
        add_filter(
            'rest_volume_query',
            function ( $args, $request ) {
                $formats = get_terms( array(
                    'taxonomy'   => 'format',
                    'hide_empty' => true,
                ));
                $order_by_formats=array();
                foreach ($formats as $format) {
                    array_push($order_by_formats, 'published_date_value_'.$format->name);
                }
                $order_by = $request->get_param('orderby');
                $current_month = date('m');
                $current_year = date('Y');
                $month_param = $request->get_param('month');
                $year_param = $request->get_param('year');
                $search_param = $request->get_param('search');
                $month = isset($month_param) ? $month_param+1 : $current_month;
                $year = isset($year_param) ? $year_param : $current_year;
                $start_date = $year.'-'.$month.'-01';
                $end_date = $year.'-'.$month.'-31';
                $meta_filters = array('relation' => 'AND');

                if (isset($order_by)) {
                    if (in_array($order_by, $order_by_formats)) {
                        array_push($meta_filters,
                            array(
                                'key' => $order_by,
                                'value' => '',
                                'compare' => '!='
                            ),
                            array(
                                'key' => $order_by,
                                'value' => date('Y-m-d'),
                                'compare' => '>=',
                                'type' => 'DATE'
                            )
                        );

                        if($search_param == '') {
                            array_push($meta_filters,
                                array(
                                    'key' => $order_by,
                                    'value' => $start_date,
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                ),
                                array(
                                    'key' => $order_by,
                                    'value' => $end_date,
                                    'compare' => '<=',
                                    'type' => 'DATE'
                                )
                            );
                        }
                        $args['meta_key'] = $order_by;
                        $args['order'] = 'asc';
                        $args['orderby'] = 'meta_value';
                    }

                    $args['meta_query'] =$meta_filters;
                }
                return $args;
            },
            10,
            2
        );
    }

    function auto_update_volume($post_id) {
        
        $formats = get_terms('format', array(
            'hide_empty' => false,
        ));
 
        foreach($formats as $format){
            $isbn = get_post_meta( $post_id, 'isbn_'.$format->name.'_value');
            $date = get_post_meta( $post_id, 'published_date_value_'.$format->name);

            if( !empty($isbn) || !empty($date))
                wp_set_post_terms( $post_id, [ $format->term_id], 'format', true);
        }
    }

    function new_volume_publish($post) {

        if ($post->post_type != 'volume') {
            return;
        }

        $post_id = $post->ID;
        global $wpdb;
        $novel_id = $wpdb->get_var(
            $wpdb->prepare(
                "
                SELECT meta_value
                FROM $wpdb->postmeta
                WHERE post_id = %d
                AND meta_key = %s
                ",
                $post_id,
                'series_value'
            )
        );
        $no_of_volumes = get_post_meta($novel_id, 'no_of_volumes', true);
        error_log('Volume ID:'.$post_id);
        error_log('Novel ID:'.$novel_id);
        error_log('No of Volumes:'.$no_of_volumes);

        if ($no_of_volumes == '') {
            $no_of_volumes=0;
        }

        $result = update_post_meta($novel_id, 'no_of_volumes', $no_of_volumes+1);
        error_log('Result:'.$result);
        error_log('New No of Volumes:'.$no_of_volumes+1);
    }

    function trash_existing_volume($post_id, $post, $old_status) {
        global $wpdb;
        $novel_id = $wpdb->get_var(
            $wpdb->prepare(
                "
                SELECT meta_value
                FROM $wpdb->postmeta
                WHERE post_id = %d
                AND meta_key = %s
                ",
                $post_id,
                'series_value'
            )
        );
        $no_of_volumes = get_post_meta($novel_id, 'no_of_volumes', true);

        error_log('Volume ID:'.$post_id);
        error_log('Novel ID:'.$novel_id);
        error_log('No of Volumes:'.$no_of_volumes);

        if ($no_of_volumes-1>0) {
            update_post_meta($novel_id, 'no_of_volumes', $no_of_volumes-1);
            error_log('Updating');
        } else {
            delete_post_meta($novel_id, 'no_of_volumes', $no_of_volumes);
            error_log('Deleting');
        }
    }

    function update_all_novels_volumes() {
        $nargs = array(
            'post_type' => 'novel',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        );
        $novel_posts = get_posts($nargs);
        
        foreach ($novel_posts as $post_id) {
            $vargs = array(
                'post_type' => 'volume',
                'meta_key' => 'series_value',
                'meta_value' => $post_id,
                'posts_per_page' => -1,
            );
            $query = new WP_Query($vargs);
            $count = $query->found_posts;
            update_post_meta($post_id, 'no_of_volumes', $count);
        }
    }

    function redirect_volume_to_404() {
        if (is_singular('volume') || is_post_type_archive('volume')) {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            nocache_headers();
            include(get_404_template());
            exit;
        }
    }

    function remove_view_action_from_list( $actions, $post ) {
        if ( $post->post_type === 'volume' ) {
            unset( $actions['view'] );
        }
        return $actions;
    }

    function custom_post_updated_messages( $messages ) {
        global $post, $post_ID;
    
        $messages['volume'] = array(
            0 => '',
            1 => sprintf( __('Volume updated. <a href="%s">Back to volume list</a>', 'your-text-domain'), esc_url( admin_url( 'edit.php?post_type=volume' ) ) ),
            2 => __('Custom field updated.'),
            3 => __('Custom field deleted.'),
            4 => __('Volume updated.'),
            5 => isset($_GET['revision']) ? sprintf( __('Volume restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Volume published. <a href="%s">Back to volume list</a>', 'your-text-domain'), esc_url( admin_url( 'edit.php?post_type=volume' ) ) ),
            7 => __('Volume saved.'),
            8 => sprintf( __('Volume submitted. <a href="%s">Back to volume list</a>', 'your-text-domain'), esc_url( admin_url( 'edit.php?post_type=volume' ) ) ),
            9 => sprintf( __('Volume scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Back to volume list</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( admin_url( 'edit.php?post_type=volume' ) ) ),
            10 => sprintf( __('Volume draft updated. <a target="_blank" href="%s">Back to volume list</a>'), esc_url( admin_url( 'edit.php?post_type=volume' ) ) ),
        );
    
        return $messages;
    }
}
?>
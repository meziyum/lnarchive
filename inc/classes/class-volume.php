<?php
/**
 * Volume Main Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

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

    public function register_routes() {
        register_rest_route( 'lnarchive/v1', 'formats_list', array(
            'methods' => 'GET',
            'callback' => [ $this, 'get_volume_formats'],
            'permission_callback' => function(){
                return true;
            },
        ));
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
                $publication_date_order_by=array();
                foreach ($formats as $format) {
                    array_push($publication_date_order_by, 'published_date_value_'.$format->name);
                }
                $order_by = $request->get_param('orderby');
                if( isset( $order_by ) ) {
                    if(in_array($order_by, $publication_date_order_by)) {
                        $args['meta_query'] = array(
                            'relation' => 'AND',
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
                        $args['meta_key'] = $order_by;
                        $args['orderby'] = 'meta_value_num';
                    }
                }
                return $args;
            },
            10,
            2
        );
    }

    function auto_update_volume($post_id){
        
        $formats = get_terms('format', array(
            'hide_empty' => false,
        ));
 
        foreach( $formats as $format ){
            $isbn = get_post_meta( $post_id, 'isbn_'.$format->name.'_value');
            $date = get_post_meta( $post_id, 'published_date_value_'.$format->name);

            if( !empty($isbn) || !empty($date))
                wp_set_post_terms( $post_id, [ $format->term_id], 'format', true);
        }
    }
}
?>
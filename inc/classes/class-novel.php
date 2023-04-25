<?php
/**
 * Novel Post Type
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class novel{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [ $this, 'register_novel']);
        add_action('save_post_novel', [$this, 'auto_novel']);
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_novel() {

        $labels = array(
            'name'                => 'Novels',
            'singular_name'       => 'Novel',
            'menu_name'           => 'Novels',
            'all_items'           => 'All Novels',
            'view_item'           => 'View Novel',
            'view_items'           => 'View Novels',
            'add_new_item'        => 'Add New Novel',
            'add_new'             => 'Add New',
            'edit_item'           => 'Edit Novel',
            'update_item'         => 'Update Novel',
            'search_items'        => 'Search Novel',
            'not_found'           => 'The Novel was not found',
            'not_found_in_trash'  => 'The Novel was not found in the trash',
            'archives' => 'Novels Library',
            'attributes' => 'Novel Attributes',
            'insert_into_item' => 'Insert into Novel',
            'uploaded_to_this_item' => 'Uploaded to this Novel',
            'featured_image' => 'Cover',
            'set_featured_image' => 'Set Cover',
            'remove_featured_image' => 'Remove Cover',
            'use_featured_image' => 'Use Cover',
            'filter_items_list' => 'Filter Novels Library',
            'filter_by_date' => 'Filter by release date', 
            'items_list_navigation' => 'Novels Library navigation',
            'items_list' => 'Novels Library',
            'item_published' => 'Novel published',
            'item_published_privately' => 'Novel published privately',
            'item_reverted_to_draft' => 'Novel reverted to Draft',
            'item_scheduled' => 'Novel release scheduled',
            'item_updated' => 'Novel Updated',
            'item_link' => 'Novel Link',
            'item_link_description' => 'A link to a Novel',
        );

        $args = array(
            'label'               => 'Novel',
            'description'         => 'All novels data',
            'labels'              => $labels,
            'public'              => true,
            'hierarchical'        => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'show_in_rest'        => true,
            'rest_base'           => "novels",
            'menu_position'       => null,
            'menu_icon'           => 'dashicons-book',
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
            
            'supports'            => array( 'title', 'author', 'comments', 'thumbnail', 'revisions', 'custom-fields','page-attributes'),

            'register_meta_box_cb' => null,

            'taxonomies'          => array( 'genre', 'publisher', 'writer', 'translator', 'post_tag', 'illustrator','novel_status','language'),

            'has_archive' => true,

            'rewrite'   => [
                            'slug'  => 'novel',
                            'with_front'    => true,
                            'feeds' => false,
                            'pages' => false,
            ],

            'query_var' => 'novel',
            'can_export'          => true,
            'delete_with_user'  => false,
        );

        register_post_type( 'novel', $args );
    }

    public function register_routes(){
        register_rest_route( 'lnarchive/v1', 'novel_filters', array(
            'methods' => 'GET',
            'callback' => [ $this, 'get_novel_filters'],
            'permission_callback' => function(){
                return true;
            },
        ));
    }

    public function get_novel_filters(){

        $filter_taxonomies = array( 'genre', 'post_tag', 'novel_status', 'language', 'publisher', 'writer', 'illustrator',);
        $response = array();

        foreach( $filter_taxonomies as $tax){

            $terms = get_terms( $tax, array(
                'hide_empty' => true,
            ));
            
            $terms_list=array();
            foreach( $terms as $term){
                array_push($terms_list, array(
                    'term_id' => $term->term_id,
                    'term_name' => $term->name,  
                ));
            }

            $taxObj = get_taxonomy($tax);

            array_push($response, array(
                'tax_query_name' => $taxObj->rest_base,
                'tax_label' => $taxObj->label,
                'list' => $terms_list,
            ));
        }

        return $response;
    }

    public function auto_novel( $post_id ){

        $status = wp_get_post_terms( $post_id, 'novel_status');
        
        if( $status != null ){
            $oneshot = 'Oneshot';
            $args = array(
                    'posts_per_page' => -1,
                    'numberposts' => -1,
                    'post_type' => 'volume',
                    'meta_key'     => 'series_value',
                    'meta_value'   => $post_id,
            );
            $posts = get_posts( $args);

            if( has_tag($oneshot) && (count($posts) != 1 || $status[0]->name !='Completed'))
                wp_remove_object_terms($post_id, $oneshot, 'post_tag');
            else if( count($posts) == 1 && $status[0]->name =='Completed')
                wp_set_post_terms( $post_id, [$oneshot], 'post_tag', true);
        }
    }
}
?>
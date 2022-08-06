<?php
/**
 * Novel Post Type Taxonomy Class
 * 
 * Status: Progress
 * 
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class novel_tax{ //Novel Post Type Taxonomies Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks Function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'init', [ $this, 'register_novel_taxonomies']);
    }

    public function register_novel_taxonomies() { //Register all Novel taxonomies

        register_taxonomy('series', ['novel', 'post'], array(
            'hierarchical' => false,
            'labels' => array(
                'name' => 'Series',
                'singular_name' => 'Series',
                'search_items' =>  'Search Series',
                'all_items' => 'All Series',
                'parent_item' => 'Parent Series',
                'parent_item_colon' => 'Parent Series: ',
                'edit_item' => 'Edit Series',
                'update_item' => 'Update Series',
                'add_new_item' => 'Add New Series',
                'new_item_name' => 'New Series Name',
                'menu_name' => 'Series',
                ),
            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'series',
                'with_front' => false, //Hide the base slug that is series
                'hierarchical' => false
            ),
            'show_in_rest' => true, //Show in Guttenburg
            'show_admin_column' => true,
            'show_ui' => true,
        ));

        register_taxonomy('publisher', 'novel', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Publisher',
                'singular_name' => 'Publisher',
                'search_items' =>  'Search Publishers',
                'all_items' => 'All Publishers',
                'parent_item' => 'Parent',
                'parent_item_colon' => 'Parent: ',
                'edit_item' => 'Edit Publisher',
                'update_item' => 'Update Publisher',
                'add_new_item' => 'Add New Publisher',
                'new_item_name' => 'New Publisher Name',
                'menu_name' => 'Publishers',
                ),
            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'publisher',
                'with_front' => false, //Hide the base slug that is publisher
                'hierarchical' => true
            ),
            'show_in_rest' => true, //Show in Guttenburg
            'show_admin_column' => true,
            'show_ui' => true,
        ));
    }
}
?>
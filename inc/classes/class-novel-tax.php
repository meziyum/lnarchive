<?php
/**
 * Novel Taxnomoies
 * 
 * @package lnpedia
 * 
 */
namespace fusfan\inc; //Namespace Definition
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class novel_tax{ //Assests Class
    use Singleton; //Using Sinlgeton
    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }
    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'init', [ $this, 'register_novel_taxonomies']);
    }

    public function register_novel_taxonomies() {
        //Register Series
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
        //Register Genre
        register_taxonomy('genre', 'novel', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Genre',
                'singular_name' => 'Genre',
                'search_items' =>  'Search Genre',
                'all_items' => 'All Genre',
                'parent_item' => 'Parent Genre',
                'parent_item_colon' => 'Parent Genre: ',
                'edit_item' => 'Edit Genre',
                'update_item' => 'Update Genre',
                'add_new_item' => 'Add New Genre',
                'new_item_name' => 'New Genre Name',
                'menu_name' => 'Genre',
                ),
            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'genre',
                'with_front' => false, //Hide the base slug that is genre
                'hierarchical' => true
            ),
            'show_in_rest' => true, //Show in Guttenburg
            'show_admin_column' => true,
            'show_ui' => true,
        ));
        //Register Publisher
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
<?php
/**
 * Novel Post Type and Volume Post Type Common Class
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class novel_volume_tax{ //Novel Post Type Taxonomies Class

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
        add_action( 'init', [ $this, 'register_novel_volume_taxonomies']);
    }

    public function register_novel_volume_taxonomies() { //Register all Novel and Volume Common taxonomies

        //Register Genre Taxonomy
        register_taxonomy('genre', ['novel','volume'], array(
            
            //All Genre Labels
            'labels' => array(
                'name' => 'Genre', //General Name
                'singular_name' => 'Genre', //Singular Taxonomy Name
                'search_items' =>  'Search Genre', //Search
                'all_items' => 'All Genres', //List of all
                'parent_item' => 'Parent Genre', //Parent
                'parent_item_colon' => 'Parent Genre: ', //Parent with colon
                'name_field_description' => 'Name of the Genre', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'parent_field_description' => 'Assign a parent genre if its a sub-genre.', //Desc for the Parent field
                'desc_field_description' => 'A short informational description of the novel', //Desc of the Description field
                'edit_item' => 'Edit Genre', //Edit
                'view_item' => 'View Genre', //View
                'update_item' => 'Update Genre', //Update
                'add_new_item' => 'Add New Genre', //Add New
                'new_item_name' => 'New Genre Name', //New Item Name
                'not_found' => 'No genres found', //Not Found Msg
                'no_terms' => 'No genres', //Post and Media tables
                'filter_by_item' => 'FIlter by Genre', //Filter msg
                'most_used' => 'Most Used Genre', //Most Used Msg
                'back_to_items' => 'Back to Genres', //Back to Genres List
                'item_link' => 'Genre Link', //Genre Link in Block Editor
                'item_link_description' => 'A link to a genre', //Desc for Genre Link in Block Editor
                'menu_name' => 'Genre', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'genre', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A category of literary composition characterized by a particular style, form, or content', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'genre', //Query name for the wp_query
            'hierarchical' => true, //Hierarchy

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'genre',
                'with_front' => false, //Hide the base slug that is genre
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities for Genre Actions
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Genre Taxonomy
    }
}
?>
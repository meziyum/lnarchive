<?php
/**
 * Novel Post Type Class
 * 
 * @package lnpedia
 * 
 */

namespace fusfan\inc; //Namespace Definition
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class novel{ //Assests Class

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
        add_action( 'init', [ $this, 'register_novel']);
    }

    public function register_novel() {

        //Labels for various actions
        $labels = array(
            'name'                => 'Novels', //General Name of the post type
            'singular_name'       => 'Novel',  //Singular Name of the post type
            'menu_name'           => 'Novels', //Name of the post type in the menu
            'all_items'           => 'All Novels',  //All listing
            'view_item'           => 'View Novel', //View button
            'view_items'           => 'View Novels', //View button
            'add_new_item'        => 'Add New Novel', //Adding a new post type
            'add_new'             => 'Add New', //Add a new post type
            'edit_item'           => 'Edit Novel', //Edit the post type
            'update_item'         => 'Update Novel', //Update the post type
            'search_items'        => 'Search Novel', //Seardh Post type list
            'not_found'           => 'The Novel is not found', //When the post type is not found
            'not_found_in_trash'  => 'The Novel is not found in the trash', //When the novel is not found in the trash
            'archives' => 'Novels Library', //Archive
            'attributes' => 'Novel Attributes', //Novel attributes meta 
            'featured_image' => 'Cover', //Novel Cover
            'set_featured_image' => 'Set Cover', //Set Novel Cover
            'remove_featured_image' => 'Remove Cover', //Remove Cover
            'use_featured_image' => 'Use Cover', //Use Cover
            'filter_items_list' => ' Filter Novels Library', //Fitler the novels
            'items_list' => 'Novels Library', //Novels list
            'item_published' => 'Novel published', //Novel published
            'item_published_privately' => 'Novel published privately', //Novel published privately
            'item_reverted_to_draft' => 'Novel reverted to Draft', //Novel reverted to draft
            'item_scheduled' => 'Novel release scheduled', //Novel release scheduled
            'item_updated' => 'Novel Updated', //Novel updated
        );

        //Options for the Novel Custom Post Type  
        $args = array(
            'label'               => 'novel', //the id of the post type
            'description'         => 'All novels data', //The desctription of the post type 
            'labels'              => $labels, //All the labels inserted using an array
            
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title','excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),

            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( 'genre', 'publisher', 'writer', 'translator', 'series' ),

            //Options
            'hierarchical'        => false, //If sub novels possible
            'public'              => true, //Visibility
            'show_ui'             => true, //Show in User Interface
            'show_in_menu'        => true, //Show in Menu
            'show_in_nav_menus'   => true, //Show in Nav Menu
            'show_in_admin_bar'   => true, //Show in Admin Bar
            'can_export'          => true, //Export Functionality
            'has_archive'         => true, //IF has archive page for the post type
            'exclude_from_search' => false, //If to exclude from search
            'publicly_queryable'  => true, //For public
            'capability_type'     => 'post', //Capability required for the novel post type
            'show_in_rest'        => true, //If to include the post type in Rest API
            'menu_position'       => null, //Menu index position
            'menu_icon'           => 'dashicons-book', //Menu Icon
        );

        //Register the Novel post type
        register_post_type( 'novel', $args );
    }
}
?>
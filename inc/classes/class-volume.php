<?php
/**
 * Volume Post Type
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class volume{ //Assests Class

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
        add_action( 'init', [ $this, 'register_volume']);
    }

    public function register_volume() {

        //Labels for various actions
        $labels = array(
            'name'                => 'Volumes', //General Name of the post type
            'singular_name'       => 'Volume',  //Singular Name of the post type
            'menu_name'           => 'Volumes', //Name of the post type in the menu
            'all_items'           => 'All Volumes',  //All listing
            'view_item'           => 'View Volume', //View button
            'view_items'           => 'View Volumes', //View button
            'add_new_item'        => 'Add New Volume', //Adding a new post type
            'add_new'             => 'Add New', //Add a new post type
            'edit_item'           => 'Edit Volume', //Edit the post type
            'update_item'         => 'Update Volume', //Update the post type
            'search_items'        => 'Search Volume', //Seardh Post type list
            'not_found'           => 'The Volume was not found', //When the post type is not found
            'not_found_in_trash'  => 'The Volume was not found in the trash', //When the volume is not found in the trash
            'archives' => 'Volumes Archive', //Archive
            'attributes' => 'Volume Attributes', //attributes meta
            'insert_into_item' => 'Insert into Volume', //Label for the media frame button
            'uploaded_to_this_item' => 'Uploaded to this Volume', //Label for the media frame filter
            'featured_image' => 'Cover', //Volume Cover
            'set_featured_image' => 'Set Cover', //Set Volume Cover
            'remove_featured_image' => 'Remove Cover', //Remove Cover
            'use_featured_image' => 'Use Cover', //Use Cover
            'filter_items_list' => 'Filter Volumes', //Fitler the volumes
            'filter_by_date' => 'Filter by release date', 
            'items_list_navigation' => 'Volume Navigation', //Label for the table pagination
            'items_list' => 'Volumes Library', //Volimes list
            'item_published' => 'Volume published', //published
            'item_published_privately' => 'Volume published privately', //published privately
            'item_reverted_to_draft' => 'Volume reverted to Draft', //reverted to draft
            'item_scheduled' => 'Volume release scheduled', //release scheduled
            'item_updated' => 'Volume Updated', //updated
            'item_link' => 'Volume Link', //Title for Nav Link
            'item_link_description' => 'A link to a Volume', //Title for the Block Variation
        );

        //Options for the Volume Custom Post Type  
        $args = array(
            'label'               => 'Volume', //the name shown in the menu
            'description'         => 'All volumes data', //The desctription of the post type 
            'labels'              => $labels, //All the labels inserted using an array
            'public'              => true, //Visibility
            'hierarchical'        => false, //If sub volumess possible
            'exclude_from_search' => true, //If to exclude from search
            'publicly_queryable'  => true, //For public
            'show_ui'             => true, //Show in User Interface
            'show_in_menu'        => true, //Show in Menu
            'show_in_nav_menus'   => true, //Show in Nav Menu
            'show_in_admin_bar'   => true, //Show in Admin Bar
            'show_in_rest'        => true, //If to include the post type in Rest API
            'menu_position'       => null, //Menu index position
            'menu_icon'           => 'dashicons-book', //Menu Icon
            'capability_type'     => 'post', //Capability required for the volume post type
            'map_meta_cap'        => true, //Whether to use the internal default meta map capability handling
            
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title','excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),

            'register_meta_box_cb' => null, //Callback function to setup the metabox in edit form

            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( 'genre', 'translator', 'post_tag', ),

            'has_archive' => true, //Whether the post type has archive

            'rewrite'   => [ //Post Types rewrite
                            'slug'  => 'volume', //slug
                            'with_front'    => true,
                            'feeds' => false, //if to generate feeds
                            'pages' => false, //IF permastruct should provide for pagination
            ],

            'query_var' => 'volume',
            'can_export'          => true, //Export Functionality
            'delete_with_user'  => false, //Whether to delete the post type with the user

            /*
                Post Type Template and Template Lock
            */
        );

        //Register the Volume post type
        register_post_type( 'volume', $args );
    }
}//End of Class
?>
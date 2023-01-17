<?php
/**
 * Novel Post Type Taxonomies
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace
use WP_Error;

class taxonomies{ //Novel Taxonomy Class

    use Singleton; //Using Sinlgetons

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
        add_action('save_post',[ $this, 'save_post_function']);
    }

    public function register_novel_volume_taxonomies() { //Register all the novel taxonomies

        //Register Publisher Taxonomy
        register_taxonomy('publisher', ['novel'], array(
            
            //All Publisher Labels
            'labels' => array(
                'name' => 'Publisher', //General Name
                'singular_name' => 'Publisher', //Singular Taxonomy Name
                'search_items' =>  'Search Publisher', //Search
                'all_items' => 'All Publishers', //List of all
                'parent_item' => 'Parent Publisher', //Parent
                'parent_item_colon' => 'Parent Publisher: ', //Parent with colon
                'name_field_description' => 'Name of the Publisher/Publishing Label', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'parent_field_description' => 'Assign a publisher if its a publishing label.', //Desc for the Parent field
                'desc_field_description' => 'A short informational description of the publisher/publishing label', //Desc of the Description field
                'edit_item' => 'Edit Publisher', //Edit
                'view_item' => 'View Publisher', //View
                'update_item' => 'Update Publisher', //Update
                'add_new_item' => 'Add New Publisher', //Add New
                'new_item_name' => 'New Publisher Name', //New Item Name
                'not_found' => 'No publishers found', //Not Found Msg
                'no_terms' => 'No publishers', //Post and Media tables
                'filter_by_item' => 'FIlter by Publisher', //Filter msg
                'most_used' => 'Most Used Publisher', //Most Used Msg
                'back_to_items' => 'Back to Publishers', //Back to Publishers List
                'item_link' => 'Publisher Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the publisher', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Publisher', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'publisher', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => 'The entity responsible for the distribution of the light novel and its associated labels',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A company or label publishing the novels', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'publisher', //Query name for the wp_query
            'hierarchical' => true, //Hierarchy

            //Default Publisher Term
            'default_term' => array(
                'name' => 'Unknown', //Name
                'slug' => 'unknown', //Slug
                'description' => 'Default term for when no publisher is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'publisher',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Publisher Taxonomy

        //Register Author Taxonomy
        register_taxonomy('writer', ['novel'], array(
            
            //All Author Labels
            'labels' => array(
                'name' => 'Author', //General Name
                'singular_name' => 'Author', //Singular Taxonomy Name
                'search_items' =>  'Search Author', //Search
                'popular_items' => 'Popular Authors', //Popular
                'all_items' => 'All Authors', //List of all
                'name_field_description' => 'Name of the Author of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Author', //Desc of the Description field
                'edit_item' => 'Edit Author', //Edit
                'view_item' => 'View Auhtor', //View
                'update_item' => 'Update Author', //Update
                'add_new_item' => 'Add New Author', //Add New
                'new_item_name' => 'New Author Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove author', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No author found', //Not Found Msg
                'no_terms' => 'No authors', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Authors', //Back to Authors List
                'item_link' => 'Author Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the author', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Author', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'writer', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => 'The author of the light novel in its source language.',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An author is the creator or originator of any written work', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'writer', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Writer Term
            'default_term' => array(
                'name' => 'Unknown', //Name
                'slug' => 'Unknown', //Slug
                'description' => 'Default term for when no author is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'writer',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Author Taxonomy

        //Register Illustrator Taxonomy
        register_taxonomy('illustrator', ['novel'], array(
            
            //All Illustrator Labels
            'labels' => array(
                'name' => 'Illustrator', //General Name
                'singular_name' => 'Illustrator', //Singular Taxonomy Name
                'search_items' =>  'Search Illustrator', //Search
                'popular_items' => 'Popular Illustrators', //Popular
                'all_items' => 'All Illustrators', //List of all
                'name_field_description' => 'Name of the Illustrator of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Illustrator', //Desc of the Description field
                'edit_item' => 'Edit Illustrator', //Edit
                'view_item' => 'View Illustrator', //View
                'update_item' => 'Update Illustrator', //Update
                'add_new_item' => 'Add New Illustrator', //Add New
                'new_item_name' => 'New Illustrator Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove illustrator', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No illustrator found', //Not Found Msg
                'no_terms' => 'No illustrators', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Illustrators', //Back to List
                'item_link' => 'Illustrator Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the illustrator', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Illustrator', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'illustrator', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => 'The artist responsible for the illustrations of the light novel.',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An illustrator is an artist who specializes in enhancing writing or elucidating concepts by providing a visual representation that corresponds to the content of the associated text or idea.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'illustrator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Illustrator Term
            'default_term' => array(
                'name' => 'Unknown', //Name
                'slug' => 'unknown', //Slug
                'description' => 'Default term for when no illustrator is assigned' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'illustrator',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Illustrator Taxonomy

        //Register Language Taxonomy
        register_taxonomy('language', ['novel'], array(
            
            //All Language Labels
            'labels' => array(
                'name' => 'Language', //General Name
                'singular_name' => 'Language', //Singular Taxonomy Name
                'search_items' =>  'Search Language', //Search
                'popular_items' => 'Popular Languages', //Popular
                'all_items' => 'All Languages', //List of all
                'name_field_description' => 'Name of the language of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the language', //Desc of the Description field
                'edit_item' => 'Edit Language', //Edit
                'view_item' => 'View Language', //View
                'update_item' => 'Update Language', //Update
                'add_new_item' => 'Add New Language', //Add New
                'new_item_name' => 'New Language Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove language', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No language found', //Not Found Msg
                'no_terms' => 'No languages', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Languages', //Back to List
                'item_link' => 'Language Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the language', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Language', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'language', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_dropdown_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'The source language of the novel from which its translated from.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'language', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Language Term
            'default_term' => array(
                'name' => 'Japanese', //Name
                'slug' => 'japanese', //Slug
                'description' => 'Official language of Japan and primary language of the light novels' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'language',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Language Taxonomy

        //Register Status Taxonomy
        register_taxonomy('novel_status', ['novel'], array(
            
            //All Status Labels
            'labels' => array(
                'name' => 'Status', //General Name
                'singular_name' => 'Status', //Singular Taxonomy Name
                'search_items' =>  'Search Statsu type', //Search
                'popular_items' => 'Popular Status types', //Popular
                'all_items' => 'All Status types', //List of all
                'name_field_description' => 'Name of the Status type', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the status', //Desc of the Description field
                'edit_item' => 'Edit Status', //Edit
                'view_item' => 'View Status', //View
                'update_item' => 'Update Status', //Update
                'add_new_item' => 'Add New Status', //Add New
                'new_item_name' => 'New Status Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove status type', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No status types found', //Not Found Msg
                'no_terms' => 'No status types', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Status', //Back to List
                'item_link' => 'Status Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the status', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Status', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'novel_status', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_dropdown_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'The current publishing status of the series.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'status', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Status Term
            'default_term' => array(
                'name' => 'Ongoing', //Name
                'slug' => 'ongoing', //Slug
                'description' => 'The novel is in-print that is the story is ongoing.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'status',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Status Taxonomy

        //Register Formats Taxonomy
        register_taxonomy('format', ['volume'], array(
            
            //All Format Labels
            'labels' => array(
                'name' => 'Format', //General Name
                'singular_name' => 'Format', //Singular Taxonomy Name
                'search_items' =>  'Search Formats', //Search
                'popular_items' => 'Popular Formats', //Popular
                'all_items' => 'All Formats', //List of all
                'name_field_description' => 'Name of the Format', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the format', //Desc of the Description field
                'edit_item' => 'Edit Format', //Edit
                'view_item' => 'View Format', //View
                'update_item' => 'Update Format', //Update
                'add_new_item' => 'Add New Format', //Add New
                'new_item_name' => 'New Format Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove format', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No format found', //Not Found Msg
                'no_terms' => 'No formats', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Formats', //Back to List
                'item_link' => 'Format Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the format', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Format', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'format', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => 'The formats in which the novels are published in',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'format', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Format Term
            'default_term' => array(
                'name' => 'None', //Name
                'slug' => 'none', //Slug
                'description' => 'Default term when no formats are assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'format',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Format Taxonomy

        //Register Translator Taxonomy
        register_taxonomy('translator', ['volume'], array(
            
            //All Translator Labels
            'labels' => array(
                'name' => 'Translator', //General Name
                'singular_name' => 'Translator', //Singular Taxonomy Name
                'search_items' =>  'Search Translator', //Search
                'popular_items' => 'Popular Translators', //Popular
                'all_items' => 'All Translators', //List of all
                'name_field_description' => 'Name of the Translator of the novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Translator', //Desc of the Description field
                'edit_item' => 'Edit Translator', //Edit
                'view_item' => 'View Translator', //View
                'update_item' => 'Update Translator', //Update
                'add_new_item' => 'Add New Translator', //Add New
                'new_item_name' => 'New Translator Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove translator', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No translator found', //Not Found Msg
                'no_terms' => 'No translators', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Translators', //Back to Translators List
                'item_link' => 'Translator Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the translator', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Translator', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'translator', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => '',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A person responsible for translating the light novel from its source language', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'translator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Translator Term
            'default_term' => array(
                'name' => 'Unknown', //Name
                'slug' => 'unknown', //Slug
                'description' => 'Default term for when no translator is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'translator',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Translator Taxonomy

        //Register Narrator Taxonomy
        register_taxonomy('narrator', ['volume'], array(
            
            //All Narrator Labels
            'labels' => array(
                'name' => 'Narrator', //General Name
                'singular_name' => 'Narrator', //Singular Taxonomy Name
                'search_items' =>  'Search Narrators', //Search
                'popular_items' => 'Popular Narrators', //Popular
                'all_items' => 'All Narrators', //List of all
                'name_field_description' => 'Name of the Narrator of the audiobook', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Narrator', //Desc of the Description field
                'edit_item' => 'Edit Narrator', //Edit
                'view_item' => 'View Narrator', //View
                'update_item' => 'Update Narrator', //Update
                'add_new_item' => 'Add New Narrator', //Add New
                'new_item_name' => 'New Narrator Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove narrator', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No narrator found', //Not Found Msg
                'no_terms' => 'No narrators', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Narrators', //Back to Narrators List
                'item_link' => 'Narrators Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the narrator', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Narrator', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'narrator', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'], //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'description' => '',
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A person responsible for translating the light novel from its source language', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'narrator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Default Narrator Term
            'default_term' => array(
                'name' => 'Unknown', //Name
                'slug' => 'unknown', //Slug
                'description' => 'Default term for when no narrator is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'narrator',
                'with_front' => false, //Hide the base slug
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Narrator Taxonomy

        //Register Genre Taxonomy
        register_taxonomy('genre', ['novel'], array(
            
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
                'desc_field_description' => 'A short informational description of the genre', //Desc of the Description field
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

            //Default Genre Term
            'default_term' => array(
                'name' => 'None', //Name
                'slug' => 'none', //Slug
                'description' => 'Default term for when no genre is assigned.' //Desc
            ),

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'genre',
                'with_front' => false, //Hide the base slug that is genre
                'hierarchical' => false, //If to display hierarchy in the url
            ),

            //Capabilities
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),

            'sort' => false, //Whether this taxonomy should remember the order in which terms are added to objects
            '_builtin' => false //IF native or build in taxonomy(Only for Core Development)

        ));//End of Genre Taxonomy

        //Unregister Taxonomies
        unregister_taxonomy_for_object_type('post_tag', 'volume');
    }

    public function save_post_function($post_id) { //Function to handle the default values of all the taxonmies

        $default_tag = "None";//Default Tag Static Definition
        $tags = get_the_tags(); //Get the tags of the post
        
        if(empty($tags)) //If there are no tags already assigned
            wp_set_post_tags( $post_id, $default_tag, true ); //Assign the default tag
        else if( count($tags)>1){ //If there are tags assigned
            foreach ($tags as $tag) { //Loop through all the tag terms
                //if category is the default, then remove it
                if ($tag->name == $default_tag) { //IF there is NO Tag with other tags
                    wp_remove_object_terms($post_id, $default_tag, 'post_tag'); //Remove the Default Tag
                }
            }
        }

        $args = array( //Taxonomy Var args
            'public'   => true,
            '_builtin' => false
        ); 

        $taxonomies = get_taxonomies( $args, 'objects'); //Get all public taxonomies using args

        foreach( $taxonomies as $tax ) { //Loop through all the public taxonomies
        
            $tax_name = $tax->name; //Get the taxonomy name
            $terms = get_the_terms( $post_id, $tax_name); //Get all the terms of the taxonomy present in the post
            
            if( !empty($terms) && count($terms)>1){ //If there are more than one value and its not empty(to deal with new post_type case)
                foreach( $terms as $term) { //Loop through all the terms

                    $term_name = $term->name; //Get the term name

                    if( $term_name == $tax->default_term['name']){ //If the default term of the taxonomy is the current term
                        wp_remove_object_terms($post_id, $term_name, $tax_name); //Remove the term
                    }
                }
            }
        }
                    
    }

    function taxonomies_datalist_display( $post, $box ) { //Function to display the taxonomies as datalist choice

        $defaults = array(); //Default args

        if( !isset($box['args']) || !is_array($box['args'])){ //If there are no arguments present or if its not an array
            $args=array();//Intialize an empty args
        }
        else{ //If there is args
            $args = $box['args'];//Store the args
        }

        extract(wp_parse_args($args, $defaults), EXTR_SKIP); //Merge the default and args and make local variables out of them

        $tax = get_taxonomy($taxonomy); //Get the taxonomy
        $hierarchical = $tax->hierarchical; //Flag to store if the taxonomy is heirarchical or not
        ?>
            <div id="taxonomy-<?php echo $taxonomy;?>" class="selectdiv"> <!-- Taxonomy Datalist Div -->
                <?php
                    if (current_user_can($tax->cap->edit_terms)): //If the user has necessary capabilities
                            ?>
                                <input list="tax_list" 
                                name="<?php echo "tax_input[$taxonomy][]";?>" 
                                id="<?php echo "tax_input[$taxonomy][]";?>" 
                                class="widefat" 
                                autocomplete="on" 
                                <?php

                                    $value = get_the_terms( $post, $taxonomy ); //Get the value

                                    if( !empty( $value )){ //If there is value
                                        echo 'value="'.$value[0]->name.'"'; //Value
                                    }
                                ?>
                                > <!-- Series Input -->
                                <datalist name="tax_list" id="tax_list"><!-- Series Datalist -->
                                    <option value="0">All</option> <!-- All Option -->
                                    <?php

                                        $terms = get_terms($taxonomy, array('hide_empty' => false)); //Get all the terms

                                        foreach( $terms as $term){ //Loop through all the series options
                                            ?>
                                                <option value="<?php echo $term->name ;?>"> <!-- Option -->
                                            <?php
                                        }
                                    ?>
                                </datalist>
                            <?php
                    endif;
                ?>
            </div>
        <?php
    }

    function taxonomies_dropdown_display( $post, $box ){ //Function to display the taxonomies as dropdown menu choice

        $defaults = array( 'taxonomy' => 'language' ); //Default args

        if( !isset($box['args']) || !is_array($box['args'])){ //If there are no arguments present or if its not an array
            $args=array();//Intialize an empty args
        }
        else{ //If there is args
            $args = $box['args'];//Store the args
        }

        extract(wp_parse_args($args, $defaults), EXTR_SKIP); //Merge the default and args and make local variables out of them

        $tax = get_taxonomy($taxonomy); //Get the taxonomy
        $selected = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids')); //Get the selected term
        $hierarchical = $tax->hierarchical; //Flag to store if the taxonomy is heirarchical or not
        ?>
            <div id="taxonomy-<?php echo $taxonomy;?>" class="selectdiv"> <!-- Taxonomy Dropdown Div -->
                <?php
                    if (current_user_can($tax->cap->edit_terms)): //If the user has necessary capabilities

                        if($hierarchical){ //IF its a heirarchical taxonomy
                            wp_dropdown_categories(array( //Generate Taxonomy Dropdown
                                'taxonomy' => $taxonomy, //Taxonomy
                                'class' => 'widefat', //Classes
                                'hide_empty' => 0, //Whether to hide the empty taxonomies
                                'name' => "tax_input[$taxonomy][]", //Name field
                                'selected' => count($selected) >= 1 ? $selected[0] : '', //Selected value
                                'orderby' => 'name',  //Orderby
                                'hierarchical' => 1, //Heirarchical
                           ));
                        }
                        else{ //If its not a heirarchical taxonomy
                        ?> 
                          <select name="<?php echo "tax_input[$taxonomy][]"; ?>" class="widefat"> <!--Select -->
                            <?php 
                                foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term): //Loop through all the taxonomy terms
                                    ?>
                                        <option value="<?php echo esc_attr($term->slug); ?>" <?php echo selected($term->term_id, count($selected) >= 1 ? $selected[0] : ''); ?>><?php echo esc_html($term->name); ?></option> <!-- Option -->
                                    <?php 
                                endforeach; 
                            ?>
                          </select>
                        <?php
                        }
                    endif; 
                ?>
            </div>
        <?php
    }
}//End of Class
?>
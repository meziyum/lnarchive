<?php
/**
 * Novel Post Type Taxonomies
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class novel_tax{ //Novel Taxonomy Class

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
    }

    public function register_novel_volume_taxonomies() { //Register all the novel taxonomies

        //Register Series Taxonomy
        register_taxonomy('series', ['novel', 'post'], array(
            
            //All Series Labels
            'labels' => array(
                'name' => 'Series', //General Name
                'singular_name' => 'Series', //Singular Taxonomy Name
                'search_items' =>  'Search Series', //Search
                'popular_items' => 'Popular Series', //Popular
                'all_items' => 'All Series', //List of all
                'name_field_description' => 'Name of the Series of the Novel', //Desc for name field on edit screen
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.', //Desc for the slug field
                'desc_field_description' => 'Information about the Series', //Desc of the Description field
                'edit_item' => 'Edit Series', //Edit
                'view_item' => 'View Series', //View
                'update_item' => 'Update Series', //Update
                'add_new_item' => 'Add New Series', //Add New
                'new_item_name' => 'New Series Name', //New Item Name
                'separate_items_with_commas' => '', //Msg to separate non hierachy taxonomies
                'add_or_remove_items' => 'Add or remove series', //Add/Remove Metabox
                'choose_from_most_used' => '', //Choose from most used msg
                'not_found' => 'No series found', //Not Found Msg
                'no_terms' => 'No series', //Post and Media tables
                'most_used' => '', //Most Used Msg
                'back_to_items' => 'Back to Series', //Back to the List
                'item_link' => 'Series Link', //Taxonomy Link in Block Editor
                'item_link_description' => 'A link to the series', //Desc for taxonomy Link in Block Editor
                'menu_name' => 'Series', //Name in Menu
            ),

            'public' => true, //Public Use
            'publicly_queryable' => true, //If its for front end
            'show_ui' => true, //Show Default UI
            'show_in_menu' => true, //Show in Admin Menu
            'show_in_nav_menus' => true, //If it can be added to Nav Menus
            'show_in_rest' => true, //Show in Guttenburg or REST API to be more specific
            'rest_base' => 'series', //Base URL
            'show_tagcloud' => false, //Tag Cloud Widget
            'show_in_quick_edit' => false, //Quick Edit
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'Series is the collection of the novels', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'series', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

            //Modify the Taxonomy Slug
            'rewrite' => array(
                'slug' => 'series',
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

        ));//End of Series Taxonomy

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
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A company or label publishing the novels', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'publisher', //Query name for the wp_query
            'hierarchical' => true, //Hierarchy

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
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An author is the creator or originator of any written work', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'writer', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

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
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'An illustrator is an artist who specializes in enhancing writing or elucidating concepts by providing a visual representation that corresponds to the content of the associated text or idea.',
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'illustrator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

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

        //Register Translator Taxonomy
        register_taxonomy('translator', ['novel'], array(
            
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
            'meta_box_cb' => null, //If to use custom callbacks for the taxonomy or default ones (not supported by the Gutenberg Editor)
            'show_admin_column' => true, //Show Automatic Taxonomy Columns on Post Types
            'description' => 'A translator is a person who translates from one language into another', //Taxonomy Desc
            'update_count_callback' => '', //Callback for when the taxonomy count is updated
            'query_var' => 'translator', //Query name for the wp_query
            'hierarchical' => false, //Hierarchy

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
    }
}//End of Class
?>
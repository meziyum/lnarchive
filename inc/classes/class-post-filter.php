<?php
/**
 * Posts and Custom Posts Filter Admin
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class post_filter{ //Post or Custom Post Type filter

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions
          */

        $taxs = array('genre','publisher','status','language','writer', 'illustrator', 'translator', 'manager',); //List of the taxonomies for which to get filters

        foreach( $taxs as $tax) { //Loop through all the taxonomies
            add_action('restrict_manage_posts',[ $this, 'add_'.$tax.'_filter_to_posts_admin' ]); //Add the filter for the taxonomy
        }

        add_action('pre_get_posts',[ $this, 'add_taxonomy_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_manager_filter_to_posts_query' ]);
        

        //Adding functions to the hooks
    }

    function add_genre_filter_to_posts_admin( $post_type ) { //Add Genre Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied and there are elements
            $post_formats_args= array(
                'show_option_all'   => 'All Genres', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 1, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'genre_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'genre', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the genre if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['genre_filter'])){ //If the posts are already filtered
                $post_formats_args['selected'] = sanitize_text_field($_GET['genre_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($post_formats_args); //Display the Taxonomy Dropdown
        }
    }

    function add_writer_filter_to_posts_admin( $post_type ) { //Add Author Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $author_args= array(
                'show_option_all'   => 'All Authors', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 0, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'writer_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'writer', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['writer_filter'])){ //If the posts are already filtered
                $author_args['selected'] = sanitize_text_field($_GET['writer_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($author_args); //Display the Taxonomy Dropdown
        }
    }

    function add_illustrator_filter_to_posts_admin( $post_type ) { //Add Illustrator Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $author_args= array(
                'show_option_all'   => 'All Illustrators', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 0, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'illustrator_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'illustrator', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['illustrator_filter'])){ //If the posts are already filtered
                $author_args['selected'] = sanitize_text_field($_GET['illustrator_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($author_args); //Display the Taxonomy Dropdown
        }
    }

    function add_language_filter_to_posts_admin( $post_type ) { //Add Language Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $author_args= array(
                'show_option_all'   => 'All Languages', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 0, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'language_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'language', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['language_filter'])){ //If the posts are already filtered
                $author_args['selected'] = sanitize_text_field($_GET['language_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($author_args); //Display the Taxonomy Dropdown
        }
    }

    function add_status_filter_to_posts_admin( $post_type ) { //Add Status Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $author_args= array(
                'show_option_all'   => 'All Status', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 0, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'status_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'status', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['status_filter'])){ //If the posts are already filtered
                $author_args['selected'] = sanitize_text_field($_GET['status_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($author_args); //Display the Taxonomy Dropdown
        }
    }

    function add_publisher_filter_to_posts_admin( $post_type ) { //Add Publisher Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $publisher_args= array(
                'show_option_all'   => 'All Publisher/Labels', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 1, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'publisher_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'publisher', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['publisher_filter'])){ //If the posts are already filtered
                $publisher_args['selected'] = sanitize_text_field($_GET['publisher_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($publisher_args); //Display the Taxonomy Dropdown
        }
    }

    function add_translator_filter_to_posts_admin( $post_type ) { //Add Translator Filter Admin

        if( $post_type == 'novel') { //If the post_type is satisfied
            $translator_args= array(
                'show_option_all'   => 'All Translators', //Label for all taxonomy
                'show_option_none'  => '', //Label for None
                'orderby'           => 'name', //Order by
                'order'             => 'ASC', //Order ASC or DESC
                'show_count'        => 0, //Show count of the posts of the taxonomy
                'hide_empty'        => 1, //Hide Empty Taxonomy
                'child_of'          => 0, //Whether to show child of property
                'exclude'           => array(), //Taxonomy Values to exclude from the dropdown
                'echo'              => 1, //Whether to print the dropdown or not
                'selected'          => 0, //Default selected id in the dropdown
                'hierarchical'      => 0, //IF the taxonomy is displayed hierarchicaly
                'name'              => 'translator_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'translator', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['translator_filter'])){ //If the posts are already filtered
                $translator_args['selected'] = sanitize_text_field($_GET['translator_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($translator_args); //Display the Taxonomy Dropdown
        }
    }

    function add_taxonomy_filter_to_posts_query($query) { //Taxonomies Filter WP_QUERY

        global $post_type, $pagenow; //Global post_type and current page var

        if( $pagenow == 'edit.php' && $post_type == 'novel') { //Check if current page is edit.php and the post_type is satisfied

            $filters = array();
            $taxs = array('publisher', 'genre', 'translator', 'writer', 'illustrator', 'status', 'language');

            foreach( $taxs as $tax) { //Run loop through all the taxonomies
                if( isset($_GET[$tax.'_filter']) && sanitize_text_field($_GET[$tax.'_filter']) != 0 ) { //If a value is selected and the selected value is not all
                    array_push( //Push the query in to the filters array
                        $filters,
                        array( //Array to store the args for the wp_query
                            'taxonomy' => $tax, //The taxonomy which is to be filtered
                            'field' => 'ID', //Slug
                            'terms' => sanitize_text_field($_GET[$tax.'_filter']), //Filter the term by the selected dropdown option
                        ),
                    );
                }
            }

            if( !empty($filters)) { //If at least one query has been applied
                $query->query_vars['tax_query'] = array( //Setting the taxonomy query values to the desired one
                    'relation' => 'AND', //Apply all the Queries
                        $filters,
                );
            }
        }
    }

    function add_manager_filter_to_posts_admin() { //Add Manager Filter Admin 

        global $post_type;//Global post_type variable

            //All User Dropdown Arguments
            $user_args = array(
                'show_option_all'   => 'All Managers', //Dropdown Defaults
                'show_option_none'  => '', //Label for None
                'option_none_value'       => -1, //Value for the None
                'hide_if_only_one_author' => '', //Whether to hide the dropdown if only one user is found
                'orderby'           => 'display_name', //Order by 
                'order'             => 'ASC', //ASC or DESC
                'include'           => '', //Users to include
                'exclude'           => '', //Users to exclude
                'multi'             => 0, //Whether to add the id to selected html element
                'show'              => 'display_name', //The display name to show
                'echo'              => 1, //Whether to print the Dropdown or not
                'name'              => 'manager_admin_filter',
                'class'             => '', //The class to add to the element
                'id'                => '', //The IDs to add to the selected element
                'blog_id'                 => get_current_blog_id(), //The Blog ID
                'role'                    => array(), //The roles which must be matched
                'role__in'                => array(), //The matched users must have at least one of these roles
                'role__not_in'            => array(), //The matched users should not have these roles
                'capability'               => ['publish_posts'], //The capability which must be matched 
                'capability__in'          => array(), //The matched user must have one of these capabilities
                'capability__not_in'      => array(), //The matched user must not have one of these capabilities
                'selected'                => 0, //ID to be selected by the default
                'include_selected'  => false//Whether to include the selected user in the dropdown
            );

            if(isset($_GET['manager_admin_filter'])){ //If the Author filter is already applied
                $user_args['selected'] = (int)sanitize_text_field($_GET['manager_admin_filter']); //Sets the value of the dropdown to the current selected author id
            }

            wp_dropdown_users($user_args); //Display the Users Dropdown
    }

    function add_manager_filter_to_posts_query($query){ //Query Manager Filter Admin

        global $post_type, $pagenow; //Global Posttype and current page var

        if($pagenow == 'edit.php'){ //If the current page is edit.php and the post_types match
            if(isset($_GET['manager_admin_filter'])){ //If the Author filter is applied
                $author_id = (int)sanitize_text_field($_GET['manager_admin_filter']); //Get the Author variable from the dropdown
                if($author_id != 0){ //All option not selected in the dropdown
                    $query->query_vars['author'] = $author_id; //Set the Author variable of the query to the desired value from the dropdown
                }
            }
        }
    }
}
?>
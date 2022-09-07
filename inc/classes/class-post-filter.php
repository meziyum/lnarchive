<?php
/**
 * Posts and Custom Posts Filter Admin
 * 
 * @package LNarchive
 */

namespace fusfan\inc; //Namespace
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

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

        add_action('restrict_manage_posts',[ $this, 'add_genre_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this, 'add_publisher_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this, 'add_series_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this, 'add_author_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this,'add_manager_filter_to_posts_administration' ]);
        add_action('restrict_manage_posts',[ $this, 'add_translator_filter_to_posts_admin' ]);
        add_action('pre_get_posts',[ $this, 'add_taxonomy_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_manager_filter_to_posts_query' ]);
        

        //Adding functions to the hooks
    }

    function add_genre_filter_to_posts_admin() { //Add Genre Filter Admin

        global $post_type; //Global Post Type

        if( $post_type == 'novel') { //If the post_type is satisfied
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
                'name'              => 'manage_genre_filter', //name of the taxonomy filter
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

            if(isset($_GET['manage_genre_filter'])){ //If the posts are already filtered
                $post_formats_args['selected'] = sanitize_text_field($_GET['manage_genre_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($post_formats_args); //Display the Taxonomy Dropdown
        }
    }

    function add_author_filter_to_posts_admin() { //Add Author Filter Admin

        global $post_type; //Global Post TypeSS

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
                'name'              => 'manage_author_filter', //name of the taxonomy filter
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

            if(isset($_GET['manage_author_filter'])){ //If the posts are already filtered
                $author_args['selected'] = sanitize_text_field($_GET['manage_author_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($author_args); //Display the Taxonomy Dropdown
        }
    }

    function add_publisher_filter_to_posts_admin() { //Add Publisher Filter Admin

        global $post_type; //Global Post Type

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
                'name'              => 'manage_publisher_filter', //name of the taxonomy filter
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

            if(isset($_GET['manage_publisher_filter'])){ //If the posts are already filtered
                $publisher_args['selected'] = sanitize_text_field($_GET['manage_publisher_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($publisher_args); //Display the Taxonomy Dropdown
        }
    }

    function add_translator_filter_to_posts_admin() { //Add Translator Filter Admin

        global $post_type; //Global Post Type

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
                'name'              => 'manage_translator_filter', //name of the taxonomy filter
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

            if(isset($_GET['manage_translator_filter'])){ //If the posts are already filtered
                $translator_args['selected'] = sanitize_text_field($_GET['manage_translator_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($translator_args); //Display the Taxonomy Dropdown
        }
    }

    function add_series_filter_to_posts_admin() { //Add Series Filter Admin

        global $post_type; //Global Post Type

        if( $post_type == 'novel') { //If the post_type is satisfied
            $series_args= array(
                'show_option_all'   => 'All Series', //Label for all taxonomy
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
                'name'              => 'manage_series_filter', //name of the taxonomy filter
                'id'                => '', //The id of html element
                'class'             => '', //The Class for the html element
                'depth'             => 0, //Depth of the Element
                'tab_index'         => 0, //Tabindex of the select element
                'taxonomy'          => 'series', //The taxonomy id
                'hide_if_empty'     => false, //Whether to hide the taxonomy if it has no posts
                'option_none_value' => -1, //Option none default value
		        'value_field'       => 'term_id', //value in the dropdown
                'required'          => false, //if the HTML5 is required in the select element
            );

            if(isset($_GET['manage_series_filter'])){ //If the posts are already filtered
                $series_args['selected'] = sanitize_text_field($_GET['manage_series_filter']); //Change the dropdown value to the selected one
            }

            wp_dropdown_categories($series_args); //Display the Taxonomy Dropdown
        }
    }

    function add_taxonomy_filter_to_posts_query($query) { //Taxonomies Filter WP_QUERY

        global $post_type, $pagenow; //Global post_type and current page var

        if( $pagenow == 'edit.php' && $post_type == 'novel') { //Check if current page is edit.php and the post_type is satisfied
            if( isset($_GET['manage_author_filter'])) { //Check if a value is set in the dropdown
                
                if( sanitize_text_field($_GET['manage_author_filter']) != 0 ) { //If All is not selected in theDropdown
                $tax_array1 = array( //Array to store the args for the wp_query
                    'taxonomy' => 'writer', //The taxonomy which is to be filtered
                    'field' => 'ID', //Slug
                    'terms' => sanitize_text_field($_GET['manage_author_filter']), //Filter the term by the selected dropdown option
                );
                } else { //If All is selected in the Dropdown
                    $tax_array1 = get_terms( 'writer', array( //Array to store all the terms since all is selected
                        'hide_empty' => true, //Whether to hide the empty terms or not
                    ) );
                }

                if( sanitize_text_field($_GET['manage_publisher_filter']) != 0 ) { //If All is not selected in the Dropdown
                $tax_array2 = array( //Array to store the args for the wp_query
                    'taxonomy' => 'publisher', //The taxonomy which is to be filtered
                    'field' => 'ID', //Slug
                    'terms' => sanitize_text_field($_GET['manage_publisher_filter']), //Filter the term by the selected dropdown option
                );
                }else {
                    $tax_array2 = get_terms( 'publisher', array( //Array to store all the terms since all is selected
                        'hide_empty' => true, //Whether to hide the empty terms or not
                    ) );      
                }

                if( sanitize_text_field($_GET['manage_genre_filter']) != 0 ) { //If All is not selected in the Dropdown
                $tax_array3 = array( //Array to store the args for the wp_query
                    'taxonomy' => 'genre', //The taxonomy which is to be filtered
                    'field' => 'ID', //Slug
                    'terms' => sanitize_text_field($_GET['manage_genre_filter']), //Filter the term by the selected dropdown option
                );
                } else {
                    $tax_array3 = get_terms( 'genre', array( //Array to store all the terms since all is selected
                        'hide_empty' => true, //Whether to hide the empty terms or not
                    ) );  
                }

                if( sanitize_text_field($_GET['manage_translator_filter']) != 0 ) { //If All is not selected in the Dropdown
                    $tax_array4 = array( //Array to store the args for the wp_query
                        'taxonomy' => 'translator', //The taxonomy which is to be filtered
                        'field' => 'ID', //Slug
                        'terms' => sanitize_text_field($_GET['manage_translator_filter']), //Filter the term by the selected dropdown option
                    );
                } else {
                    $tax_array4 = get_terms( 'translator', array( //Array to store all the terms since all is selected
                        'hide_empty' => true, //Whether to hide the empty terms or not
                    ) );  
                }

                if( sanitize_text_field($_GET['manage_series_filter']) != 0 ) { //If All is not selected in the Dropdown
                    $tax_array5 = array( //Array to store the args for the wp_query
                        'taxonomy' => 'series', //The taxonomy which is to be filtered
                        'field' => 'ID', //Slug
                        'terms' => sanitize_text_field($_GET['manage_series_filter']), //Filter the term by the selected dropdown option
                    );
                } else {
                    $tax_array5 = get_terms( 'series', array( //Array to store all the terms since all is selected
                        'hide_empty' => true, //Whether to hide the empty terms or not
                    ) );  
                }

                $query->query_vars['tax_query'] = array( //Setting the taxonomy query values to the desired one
                    'relation' => 'AND', //Apply all the Queries
                        $tax_array1,
                        $tax_array2,
                        $tax_array3,
                        $tax_array4,
                        $tax_array5,
                );
            }
        }
    }

    function add_manager_filter_to_posts_administration() { //Add Manager Filter Admin 

        global $post_type;//Global post_type variable

        if($post_type == 'novel' || $post_type == 'post'){ //Check if the post_type is novel

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
    }

    function add_manager_filter_to_posts_query($query){ //Query Manager Filter Admin

        global $post_type, $pagenow; //Global Posttype and current page var

        if($pagenow == 'edit.php' && ($post_type == 'novel' || $post_type == 'post')){ //If the current page is edit.php and the post_types match
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
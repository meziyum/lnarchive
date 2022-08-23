<?php
/**
 * Posts and Custom Posts Filter Admin
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

        add_action('restrict_manage_posts',[ $this,'add_author_filter_to_posts_administration' ]);
        add_action('pre_get_posts',[ $this, 'add_author_filter_to_posts_query' ]);

        //Adding functions to the hooks
    }
 
    function add_author_filter_to_posts_administration(){ //Add Author Filter Admin 

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

    function add_author_filter_to_posts_query($query){ //Query the Author Filter Admin

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
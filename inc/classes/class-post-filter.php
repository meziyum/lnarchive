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

        //Adding functions to the hooks

        add_action('restrict_manage_posts', [$this, 'add_taxonomy_filters']);
        add_action('restrict_manage_posts',[ $this, 'add_series_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this, 'add_manager_filter_to_posts_admin' ]);

        add_action('pre_get_posts',[ $this, 'add_taxonomy_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_metadata_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_manager_filter_to_posts_query' ]);
    }

    function add_taxonomy_filters( $post_type ) { //Add taxonomy filters to the post listing

        $taxs = array(); //Initialize an empty function

        if( $post_type == 'novel' ){ //Novel Post Type          
            $taxs = array('publisher', 'genre', 'post_tag', 'writer', 'illustrator', 'novel_status', 'language'); //Possible taxonomy filters
        }
        else if( $post_type == 'volume' ) { //Volume Post Type
            $taxs = array('format', 'translator', 'narrator'); //Possible taxonomy filters 
        }

        foreach( $taxs as $tax ){ //Loop throug all the taxonomy values
            $this->filter_search_dropdown($tax); //Call the display function
        }
    }

    function filter_search_dropdown( $taxonomy) { //Function to display the datalist for the taxonomy

        $terms = get_terms( $taxonomy, array( //Get all the terms of the taxonomy
            'hide_empty' => true, //Get only the terms with elements in them
        ) );

        ?>
            <input  list="<?php echo esc_attr($taxonomy);?>_filter_list" 
                    name="<?php echo esc_attr($taxonomy);?>_filter" 
                    id="<?php echo esc_attr($taxonomy);?>_filter" 
                    autocomplete="on"
                    <?php
                        if(!empty($_GET[esc_attr($taxonomy).'_filter'])){ //If a filter is already applied
                            echo 'value="'.$_GET[esc_attr($taxonomy).'_filter'].'"'; //Assign a value
                        }else{ //IF a filter is not applied
                            echo 'placeholder="All '.esc_attr(get_taxonomy_labels(get_taxonomy($taxonomy))->name).'" '; //Default placeholder value
                        }
                    ?>
            > <!-- Taxonomy Term Filter Input -->

            <datalist name="<?php echo esc_attr($taxonomy);?>_filter_list" id="<?php echo esc_attr($taxonomy);?>_filter_list"> <!-- Datalist -->
                <option value="All <?php echo esc_attr(get_taxonomy_labels(get_taxonomy($taxonomy))->name);?>">
                <?php
                    foreach( $terms as $term) { //loop through all the valid terms
                        ?>
                        <option value="<?php echo esc_attr($term->name) ;?>"> <!-- Option -->
                        </option>
                        <?php
                    }
                ?>
            </datalist>
        <?php
    }

    function add_taxonomy_filter_to_posts_query($query) { //Taxonomies Filter WP_QUERY

        global $post_type, $pagenow; //Global post_type and current page var

        if( $pagenow == 'edit.php') { //Check if current page is edit.php
            if($post_type == 'novel'){ //If the post_type is novel
                $taxs = array('publisher', 'genre', 'post_tag', 'writer', 'illustrator', 'novel_status', 'language'); //Possible taxonomy filters
                $this->filter_by_taxonomy( $taxs, $query ); //Filter function
            }
            else if( $post_type == 'volume' ) { //If the post type is volume
                $taxs = array('format', 'translator', 'narrator'); //Possible taxonomy filters
                $this->filter_by_taxonomy( $taxs, $query ); //Filter function
            }
        }
    }

    function filter_by_taxonomy( array $taxs, $query ) { //Function to apply the filter to tax_query

        $filters = array(); //Intialize Empty filters for the query

        foreach( $taxs as $tax ) { //Loop through all the taxonomies
            if( isset($_GET[$tax.'_filter']) && term_exists( $_GET[$tax.'_filter'], $tax )){ //IF value is set and the value is a taxonomy term
                array_push( //Push the query in to the filters array
                    $filters, //Filter array
                    array( //Array to store the args for the wp_query
                        'taxonomy' => $tax, //The taxonomy which is to be filtered
                        'field' => 'name', //Slug
                        'terms' => sanitize_text_field($_GET[$tax.'_filter']), //Filter the term by the selected dropdown option
                    ),
                );
            }

            if( !empty($filters)) { //If at least one query has been applied
                $query->query_vars['tax_query'] = array( //Setting the taxonomy query values to the desired one
                    'relation' => 'AND', //Apply all the Queries
                        $filters,
                );
            }
        }
    }

    function add_series_filter_to_posts_admin( $post_type  ){ //function to add serues filter

        if( $post_type == 'volume'){ //If the post type is novel

            $series_args = array( //Get the series
                'numberposts' => -1, //All series 
                'post_type' => 'novel', //Post type
            );

            $series = get_posts( $series_args );
            ?>
            <input list="series_filter" 
            name="series_choice" 
            id="series_choice" 
            autocomplete="on" 
            <?php
                if(!empty($_GET['series_choice'])){ //If series choice is selected
                    echo 'value="'.esc_attr($_GET['series_choice']).'"'; //Value
                }else{
                    echo 'placeholder="All Series"'; //Placeholder
                }        
            ?>
            > <!-- Series Input -->
            <datalist name="series_filter" id="series_filter"><!-- Series Datalist -->
                <option value="All Series">
                <?php
                    foreach( $series as $novel){ //Loop through all the series options
                        ?>
                            <option value="<?php echo esc_attr($novel->post_title);?>"> <!-- Series Option -->
                        <?php
                    }
                ?>
            </datalist>
            <?php
        }        
    }

    function add_metadata_filter_to_posts_query( $query ){ //Metadata Filter WP_QUERY

        global $post_type, $pagenow; //Global post_type and current page var

        if( $pagenow == 'edit.php') { //Check if current page is edit.php
            if($post_type == 'volume'){ //If the post_type is novel

                $novel = null;

                if( isset($_GET['series_choice']))
                $novel = get_page_by_title(sanitize_text_field($_GET['series_choice']), OBJECT, 'novel'); //Get the novel

                if( $novel != null ) { //If the series_choice is not valid
                    $query->query_vars['meta_query'] = array( //Setting the taxonomy query values to the desired one
                        array(
                            'key' => 'series_value', //Meta key
                            'value' => $novel->ID, //Meta value
                        ),
                    );
                }
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
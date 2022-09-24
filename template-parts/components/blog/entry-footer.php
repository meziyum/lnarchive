<?php
/**
 * 
 * Template for Entry Footer
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID(); //Get the ID
$article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms
$post_type = get_post_type( get_queried_object_id()); //Get the Post Type
?>

 <div class="entry-footer"> <!-- Blog Entry Footer Div -->
    <?php

        if( ($post_type == 'post' || $post_type == 'novel' ||is_home() && !is_front_page()) && !empty( $article_terms ) && is_array( $article_terms )){ //If its post or is blog page and there are articles in the category
        foreach( $article_terms as $key => $article_term) { //Loops through all article terms
        ?>
            <button onclick="location.href='<?php echo get_term_link( $article_term);?>'" type="button" class="category-button btn btn-success btn-sm"> <!-- Category Button -->
                <a class= "entry-footer-link text-white"> <!-- The Category text -->
                    <?php echo $article_term->name //Print the article?>
                </a>
            </button><?php
        }
        }
        
        if( current_user_can('edit_posts')){ //Check if the user has capability to edit the post
        ?>
            <button onclick="location.href='<?php echo get_edit_post_link()?>'" type="button" class="edit--button btn btn-info btn-sm float-end"> <!-- Edit Button -->
                        <a class= "entry-footer-link text-white"> <!-- The Edit Button Text -->
                            Edit
                        </a>
            </button>
        <?php
        }
    ?>
 </div>
<?php
/**
 * Sinlge Post Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row-->
        <div class="post-wrap content-wrap col"> <!-- Post Content Div -->
            <?php
            if( have_posts(  ) ) { //If there are posts
                while( have_posts(  )) : the_post(); //Loop through the post
                
                    $the_post_id = get_the_ID(); //Get the ID
                    $article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms
                    $post_type = get_post_type( get_queried_object_id()); //Get the Post Type
                    $series = get_post_meta( $the_post_id, 'series_value', true ); //Get the Series

                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( get_the_title()), //Get the Title
                    );

                    if( !empty( $article_terms ) && is_array( $article_terms )){ //If its array and its not empty
                        foreach( $article_terms as $key => $article_term) { //Loops through all article terms
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_term_link( $article_term));?>'" type="button" class="category-button"> <!-- Category Button -->
                                <a class= "category-link"> <!-- The Category text -->
                                    <?php echo $article_term->name //Print the article?>
                                </a>
                            </button>
                            <?php
                        }
                    }

                    if( $series != 'none') { //If the Series is not set
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_post_permalink($series));?>'" type="button" class="series-button float-end"> <!-- Series Button -->
                                <a class= "series-link"> <!-- The Series text -->
                                    <?php echo esc_html(get_the_title($series)); //Print the series?>
                                </a>
                            </button>
                        <?php
                    }

                    the_content(); //Display the Content

                    wp_link_pages( //Display the Pages of the post
                        [
                        'before' => '<div class="page-links d-flex justify-content-center">', //Before Page
                        'after' =>  '</div>', //After Page
                        'link_before' =>'<button class="post-page-no">', //Before Page No
                        'link_after' => '</button>', //After Page No
                        ]
                    );
                    ?>
                    <div class="post-footer border-top border-5 border-secondary">
                    <?php

                        $prev_post = get_previous_post(); //Get the Prev Post
                        $next_post = get_next_post(); //Get the Next Post

                        if( !empty($prev_post)) { //If there is a previous post
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_permalink($prev_post->ID));?>'" type="button" class="prev-post float-start"> <!-- Prev Post Button -->
                                <a class="prev-link"> <!-- Next Post Text -->
                                    Previous
                                </a>
                            </button>
                        <?php
                        }
                    
                        if( !empty($next_post)) { //If there is a next post
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_permalink($next_post->ID));?>'" type="button" class="next-post float-end"> <!-- Next Post Button -->
                                <a class="next-link"> <!-- Next Post Text -->
                                    Next
                                </a>
                            </button>
                        <?php
                        }         
                    ?>
                    </div>
                    <?php
                    if( current_user_can('edit_posts')){ //Check if the user has capability to edit the post
                    ?>
                        <button onclick="location.href='<?php echo esc_url(get_edit_post_link());?>'" type="button" class="edit-button float-end"> <!-- Edit Button -->
                            <a class= "entry-footer-link text-white"> <!-- The Edit Button Text -->
                                Edit <?php echo ucwords($post_type);?>
                            </a>
                        </button>
                    <?php
                    }
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col-lg-3 d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </aside>
    </div>
</main> <!-- End of Main -->

<?php get_footer();?> <!-- Get the Footer -->
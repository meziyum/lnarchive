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
        <div class="post-wrap content-wrap col-lg-9"> <!-- Post Content Div -->
            <?php
            if( have_posts(  ) ) { //If there are posts
                while( have_posts(  )) : the_post(); //Loop through the post
                
                    $the_post_id = get_the_ID(); //Get the ID
                    $series = get_post_meta( $the_post_id, 'series_value', true ); //Get the Series

                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( get_the_title()), //Get the Title
                    );
                    ?>
                    <div>
                    <?php

                    get_template_part('template-parts/post/category-list'); //Get the Category List
                    
                    if( $series != 'none') { //If the Series is not set
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_post_permalink($series));?>'" type="button" class="series-button float-end"> <!-- Series Button -->
                                <a class= "series-link"> <!-- The Series text -->
                                    <?php echo esc_html(get_the_title($series)); //Print the series?>
                                </a>
                            </button>
                        <?php
                    }
                    ?>
                    </div>
                    <?php
                    the_content(); //Display the Content
                    get_template_part('template-parts/post/page-nav'); //Get the Page Navigation
                    get_template_part('template-parts/post/date'); //Get the Date Template
                    ?>
                    <div class="post-footer border-top border-5 border-secondary"> <!--Post Footer -->
                    <?php

                        $prev_post = get_previous_post(); //Get the Prev Post
                        $next_post = get_next_post(); //Get the Next Post

                        if( !empty($prev_post)) { //If there is a previous post
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_permalink($prev_post->ID));?>'" type="button" class="prev-post"> <!-- Prev Post Button -->
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
                    get_template_part('template-parts/edit-btn'); //Get the Edit Button
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </aside>
    </div>
</main> <!-- End of Main -->

<?php get_footer();?> <!-- Get the Footer -->
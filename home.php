<?php
/**
 *  Blog List Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="blog-wrap col-lg-9"> <!-- Blog Content Div -->
            <?php

            //Title
            printf(
                '<h1 class="page-title">%1$s</h1>', //Page Title Div
                wp_kses_post( get_the_title(get_option('page_for_posts', true))), //Get the Title
            );
            
            if(have_posts()) { //If there is post
                ?>
                <div class="row"><?php
                    while( have_posts(  )) : the_post(); //Loop through all the posts
                        get_template_part('template-parts/post/post-list'); //Get the Blog Card
                    endwhile; //End While Loop    
                    get_template_part('template-parts/post/pagination'); //Get the Pagination
                    ?>
                </div>
            <?php
            }
        ?>
        </div>
        <aside class="blog-sidebar col d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>


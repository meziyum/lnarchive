<?php
/**
 * Sinlge Post Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row">
        <div class="post-wrap col-lg-9 col-md-9 col-sm-12"> <!-- Post Content Div -->
            <?php
            if( have_posts(  ) ) { //If there are posts
                while( have_posts(  )) : the_post(); //Loop through the post 
                    get_template_part('template-parts/components/blog/entry-meta'); //Get the Meta Data
                    get_template_part('template-parts/components/blog/entry-content'); //Get the Content
                    get_template_part('template-parts/components/blog/entry-footer'); //Get the Footer
                endwhile;
            }
            ?>
            <div class="prev-next-link mt-3 border-top border-primary">
                <div align="left" class="previous_link col-lg-6 col-md-6 col-sm-12"><?php previous_post_link();?></div>
                <div align="right" class="next_link col-lg-6 col-md-6 col-sm-12"><?php next_post_link();?></div>
            </div>
        </div>
        <div class="sidebar-wrap col-lg-3 col-md-3 col-sm-12"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </div>
    </div>
</main> <!-- End of Main -->

<?php get_footer();?> <!-- Get the Footer -->
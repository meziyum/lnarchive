<?php
/**
 * Sinlge Page Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row">
        <div class="page-wrap col-lg-9"> <!-- Page Content Div -->
            <?php
            if( have_posts(  ) ) { //If there are pages
                while( have_posts(  )) : the_post(); //Loop through the pages
                    get_template_part('template-parts/components/blog/entry-meta'); //Get the Meta Data
                    get_template_part('template-parts/components/blog/entry-content'); //Get the Content
                    get_template_part('template-parts/components/blog/entry-footer'); //Get the Footer
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
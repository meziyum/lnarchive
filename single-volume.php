<?php
/**
 * Volume Template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="volume-wrap col-lg-9"> <!-- Volume Content Div -->
        <?php
            if( have_posts() ) {
                while(have_posts()) : the_post();
                    
                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( get_the_title()), //Get the Title
                    );
                    ?>

                    <div class="info-section"> <!-- Volume Info Div -->

                    </div>
                    <?php
                endwhile;
            }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
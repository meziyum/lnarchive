<?php
/**
 * Sinlge Page Template
 * 
 * @package lnpedia
 */
get_header();
?>

<main id="main" class="site-content container bg-white" role="main">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="page-title border-bottom border-primary mt-3 mb-3">
                <?php
                //Title
                printf(
                    '<h1 class="page-title text-dark">%1$s</h1>',
                    wp_kses_post( get_the_title())
                );
                ?>
            </div>  
            <?php
            if( have_posts(  ) ) { //If there are posts
                ?>
                    <div class="page-wrap">
                        <?php
                        while( have_posts(  )) : the_post();
                            the_content(); //Display the Content
                        endwhile;
                        ?>
                    </div>
                <?php
            }
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <?php
                get_sidebar('sidebar-main'); //Get the Sidebar
            ?>
        </div>
    </div>
</main>

<?php get_footer();?>
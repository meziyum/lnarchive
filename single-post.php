<?php
/**
 * Sinlge Post Type Template
 * 
 * @package lnpedia
 */
get_header();
?>

<main id="main" class="site-content container bg-white" role="main">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <?php
            if( have_posts(  ) ) { //If there are posts
                ?>
                    <div class="post-wrap">
                        <?php
                        while( have_posts(  )) : the_post();
                            get_template_part('template-parts/components/blog/entry-meta'); //Get the Meta Data
                            get_template_part('template-parts/components/blog/entry-content'); //Get the Content
                            get_template_part('template-parts/components/blog/entry-footer'); //Get the Footer
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
    <div class="row prev-next-link mt-3 border-top border-primary">
        <div align="left" class="previous_link col-lg-6 col-md-6 col-sm-12"><?php previous_post_link();?></div>
        <div align="right" class="next_link col-lg-6 col-md-6 col-sm-12"><?php next_post_link();?></div>
    </div>
</main>

<?php get_footer();?>
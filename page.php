<?php
/**
 * Sinlge Page Template
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr(get_the_ID());?>" class="row main-row">
        <div class="page-wrap content-wrap col-lg-9">
            <?php
            if( have_posts(  ) ) {
                while( have_posts(  )) : the_post();
                    printf(
                        '<h1 id="page-title">%1$s</h1>',
                        wp_kses_post( get_the_title()),
                    );
                    the_content();
                    get_template_part('template-parts/page-nav');
                    get_template_part('template-parts/edit-btn');
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-main');?>
        </aside>
    </div>
</main>

<?php get_footer();?>
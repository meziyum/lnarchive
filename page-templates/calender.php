<?php
/**
 * Template Name: Calender
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr(get_the_ID());?>" class="row main-row">
        <div class="calender-wrap content-wrap col-lg-9">
            <?php
            if( have_posts(  ) ) {
                while( have_posts(  )) : the_post();
                    printf(
                        '<h1 class="page-title">%1$s</h1>',
                        'Calender',
                    );
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
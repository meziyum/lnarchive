<?php
/**
 * Template Name: Add Data
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr(get_the_ID());?>" class="main-row row">
        <div class="add_data-wrap col-lg-9">
            <?php
            if( have_posts(  ) ) {
                while( have_posts(  )) : the_post();
                    printf(
                        '<h1 id="page-title">%1$s</h1>',
                        'Add Data',
                    );
                endwhile;
            }
            ?>
            <div id="add-data-main">
            </div>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-main');?>
        </div>
    </div>
</main>

<?php get_footer();?>
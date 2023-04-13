<?php
/**
 * Category Template
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div class="row main-row">
        <div class="archive-wrap col-lg-9">
        <?php
        printf(
            '<h1 class="page-title">%1$s</h1>',
            wp_kses_post( get_the_archive_title()),
        );
        if(have_posts()) {
            post_list( $wp_query, 'category-post-list');
        }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-main');?>
        </div>
    </div>
</main>

<?php get_footer();?>
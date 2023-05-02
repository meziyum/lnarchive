<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div class="main-row">
        <?php
        printf(
            '<h1 class="page-title">%1$s</h1>',
            wp_kses_post( get_the_archive_title()),
        );
        ?>

        <?php 
            if(is_post_type_archive('novel')) {
                ?>
                <div id="archive-wrap">
                </div>
                <?php
            } else {
                ?>
                <div id="blog-wrap">
                    <p>Yes</p>
                </div>
                <?php
            }
            ?>
    </div>
</main>

<?php get_footer();?>
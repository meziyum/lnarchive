<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div class="row main-row">

        <?php
        printf(
            '<h1 class="page-title">%1$s</h1>',
            wp_kses_post( get_the_archive_title()),
        );
        ?>

        <div id="archive-wrap">
        </div>
    </div>
</main>

<?php get_footer();?>
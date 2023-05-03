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
            if(is_post_type_archive('novel')) {
                ?>
                <h1 class="page-title">Library</h1>
                <div id="archive-wrap">
                </div>
                <?php
            } else {
                ?>
                <h1 class="page-title">Blog</h1>
                <div id="blog-wrap"></div>
                <?php
            }
            ?>
    </div>
</main>

<?php get_footer();?>
<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header();

$novel_taxs = get_object_taxonomies('novel', 'names');
?>

<main id="main" class="main-content" role="main">
    <div class="main-row">
        <?php
            if(is_post_type_archive('novel') || is_tax($novel_taxs)) {
                ?>
                <h1 id="page-title">Library</h1>
                <div id="archive-wrap">
                </div>
                <?php
            } else {
                ?>
                <h1 id="page-title">Blog</h1>
                <div id="blog-wrap"></div>
                <?php
            }
            ?>
    </div>
</main>

<?php get_footer();?>
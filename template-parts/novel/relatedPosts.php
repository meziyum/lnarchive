<?php
/**
 * Related Posts Template Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID();

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 4,
    'meta_key' => 'series_value',
    'meta_value' => $the_post_id,
);

$loop = new WP_Query( $args );

if($loop->have_posts()) {
    ?>
        <section id="posts-section">
            <div class="row">
                <h2>Related Articles</h2>
                <?php post_list( $loop, 'novel-articles' );?>
            </div>
        </section>
    <?php
}
wp_reset_query();

?>
 

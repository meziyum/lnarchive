<?php
/**
 * Similar Novels Tempalte Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID();
$the_post_type = get_post_type($the_post_id);
$tag_terms = get_the_terms($the_post_id, 'post_tag');
$max_posts = get_option('posts_per_page');
$universe_novels = array_merge( get_post_siblings( $the_post_id ), get_post_ancestors( $the_post_id ), get_post_children( $the_post_id ) );
$rtags = array();

foreach( $tag_terms as $tag ){
    array_push( $rtags, $tag->term_id );
}

$similar_args = array(
    'post_type' => $the_post_type,
    'posts_per_page' => $max_posts,
    'orderby' => 'rand',
    'tag__in' => $rtags,
    'post__not_in'   => array_merge( $universe_novels ,array($the_post_id) ),
);

$squery = new WP_Query($similar_args);

if($squery->have_posts()) {
    ?>
        <section id="related-section" class="novels-list-section">
            <h2>Similar Novels</h2>
            <?php novel_list( $squery, array( 'name' => 'similar', 'novel_no' => 6) );?>
        </section>
    <?php
}

wp_reset_query();

?>
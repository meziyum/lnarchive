<?php
/**
 * Same Universe Novels Tempalte Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID();
$the_post_type = get_post_type($the_post_id);
$universe_novels = array_merge( get_post_siblings( $the_post_id ), get_post_ancestors( $the_post_id ), get_post_children( $the_post_id ) );

if( !empty($universe_novels) ) {
    $uquery_args = array(
        'post_type' => $the_post_type,
        'posts_per_page' => -1,
        'orderby' => 'rand',
        'post__not_in'   => array( $the_post_id ),
        'post__in' => $universe_novels,
    );

    $uquery = new WP_Query( $uquery_args );

    if($uquery->have_posts()) {
        ?>
            <section id="child-section" class="novels-list-section">
                <h2>Novels from same Universe</h2>
                <?php novel_list( $uquery, array( 'name' => 'child') );?>
            </section>
        <?php
    }

    wp_reset_query();
}
?>

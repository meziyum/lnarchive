<?php
/**
 * Volume List Template Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID();

$vol_args = array(
    'post_type' => 'volume',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
    'meta_key' => 'series_value',
    'meta_value' => $the_post_id,
);                       

$vquery = new WP_Query($vol_args);

if($vquery->post_count > 1 ) {
    ?>
        <section id="volumes-section" class="novels-list-section">
            <h2 id="volumes-no">Volumes</h2>
            <?php novel_list( $vquery, array( 'name' => 'volume'));?>
        </section>
    <?php
}

wp_reset_query();
?>
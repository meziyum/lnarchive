<?php 
/**
 * Novel Series Display
 * 
 * @package LNarchive
 */
$series = get_post_meta( $post->ID, 'series_value', true ); //Get the Series    
?>

<div class="series-wrap"> <!-- Series Wrap -->
    <?php
        if( $series != 'none') { //If the Series is not set
            ?>
                <h3> Series: <a href="<?php echo get_post_permalink($series)?>"><?php echo get_the_title($series)?></a></h3>
            <?php
        } 
    ?>
</div>
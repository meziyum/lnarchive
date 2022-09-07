<?php
/**
 * Template for Blog Entry Meta
 * 
 * @package LNarchive
 */
?>

<div class="entry-meta"> <!-- Blog Entry Meta Div -->
    <?php 
        printf(
            '<h3 class="entry-title mb-0"><a class="text-dark fw-light" href="%1$s">%2$s</a></h3>', //The Title
            get_the_permalink(), //Argument 1
            wp_kses_post( get_the_title()) //Argument 2
        );
    ?>
</div>
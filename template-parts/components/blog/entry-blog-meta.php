<?php
/**
 * Template for Entry Meta
 * 
 * @package lnpedia
 * 
 */
?>

<div class="entry-meta mb-3">
    <?php 
        printf(
            '<h3 class="entry-title"><a class="text-dark" href="%1$s">%2$s</a></h3>', //The Title
            get_the_permalink(), //Argument 1
            wp_kses_post( get_the_title()) //Argument 2
        );
        posted_on(); //Post Date
    ?>
</div>
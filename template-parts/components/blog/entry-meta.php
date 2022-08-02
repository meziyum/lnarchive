<?php
/**
 * Template for Entry Meta
 * 
 * @package lnpedia
 * 
 */
?>

<div class="entry-meta border-bottom border-primary mt-3">
    <?php
        //Title
        printf(
            '<h1 class="page-title text-dark">%1$s</h1>',
            wp_kses_post( get_the_title())
        );  
        posted_on(); //Post Date
    ?>
</div>
<?php
/**
 * Template for Post Entry Meta
 * 
 * @package LNarchive
 */
?>

<div class="entry-main"> <!-- Entry Main Div -->
    <?php
        //Title
        printf(
            '<h1 class="page-title">%1$s</h1>', //HTML
            wp_kses_post( get_the_title()), //Get the Title
        );
    ?>
</div>
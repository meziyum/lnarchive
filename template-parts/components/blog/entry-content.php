<?php
/**
 * 
 * Entry Content Template
 * 
 * @package LNarchive
 * 
 */

?>

<div class="entry-content"> <!--Entry Content -->
    <?php
        the_content(); //Display the Content
        wp_link_pages( //Display the Pages of the post
            [
            'before' => '<div class="page-links">' . 'Pages: ', //Before Page 
            'after' =>  '</div>', //After Page
            ]
        );
    ?>
</div>
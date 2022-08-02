<?php
/**
 * 
 * Template for Entry Content
 * 
 * @package lnpedia
 * 
 */

?>

<div class="mt-3 mb-5">
    <?php
        the_content(); //Display the Content
        wp_link_pages( //Display the Previos/Next Button
            [
            'before' => '<div class="page-links">' . 'Pages: ',
            'after' =>  '</div>',
            ]
        );
    ?>
</div>
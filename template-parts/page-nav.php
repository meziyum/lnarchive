<?php
/**
 * 
 * Page Navigation Template
 * 
 * @package LNarchive
 */

wp_link_pages(
    [
        'before' => '<div class="page-links d-flex justify-content-center">',
        'after' =>  '</div>',
        'link_before' =>'<button class="post-page-no pagination-button">',
        'link_after' => '</button>',
    ]
);
?>
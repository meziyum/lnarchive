<?php
/**
 * 
 * Page Navigation Template
 * 
 * @package LNarchive
 */

wp_link_pages( //Display the Pages of the post
    [
    'before' => '<div class="page-links d-flex justify-content-center">', //Before Page
    'after' =>  '</div>', //After Page
    'link_before' =>'<button class="post-page-no pagination-button">', //Before Page No
    'link_after' => '</button>', //After Page No
    ]
);
?>
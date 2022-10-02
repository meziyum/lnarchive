<?php
/**
 * 
 * Post Type Taxonomies Helper Functions
 * 
 * @package LNarchive
 * 
 */

 //Function to get the post thumbnail for listing
function get_the_post_custom_thumbnail( $post_id, $size, $additional_attributes ) {

    $custom_thumbnail =''; //decalre a null local variable to store the image

    if( null === $post_id) { //If the post id is null then get the post id
       $post_id=get_the_ID();
    }

    if( has_post_thumbnail( $post_id) ) { //If the post has thumbnail then lazy load
        $default_attributes = [
            'loading' => 'lazy'
        ];
    }

    $attributes = array_merge($additional_attributes, $default_attributes); //Merge the additional attributes with the lazy load attributes

    $custom_thumbnail = wp_get_attachment_image( //Get the Custom Thumbnail and store it
       get_post_thumbnail_id($post_id), //Get the Post Thumbnail ID
       $size, //Get the Size
       false,  //Whether to treat the image as a icon
       $attributes //The attributes for the image
    );

    return $custom_thumbnail; //Return the Thumbnail
}

function the_post_custom_thumbnail( $post_id, $size, $additional_attributes) { //Function to display the Post Thumbnail
    echo get_the_post_custom_thumbnail($post_id, $size, $additional_attributes); //Echo the Thumbnail
}
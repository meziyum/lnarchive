<?php
/**
 * 
 * Tags Template
 * 
 * @package lnpedia
 * 
 */

 //Function to get the post thumbnail for listing
function get_the_post_custom_thumbnail( $post_id, $size, $additional_attributes = []) { 
    $custom_thumbnail =''; //decalre a null local variable to store the image
   if( null === $post_id) { //If the post id is null then get the post id
       $post_id=get_the_ID();
   }
   if( has_post_thumbnail( $post_id) ) { //If the post has thumbnail then lazy load
        $default_attributes = [
            'loading' => 'lazy'
        ];
   }
   $attributes = array_merge($additional_attributes, $default_attributes); //Merge the additional attributes with the lazy load
   $custom_thumbnail = wp_get_attachment_image( //Get the Custom Thumbnail and store it
       get_post_thumbnail_id($post_id),
       $size,
       false,
       $additional_attributes
   );
   return $custom_thumbnail; //Return the Thumbnail
}

//Function to display the Post Thumbnail
function the_post_custom_thumbnail( $post_id, $size, $additional_attributes = []) {
    echo get_the_post_custom_thumbnail($post_id, $size, $additional_attributes);
}

//Function to display the Date of the post
function posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>'; //Define the time string if the published date is the modified date
    //Define the time string if the post was modified
    if( get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    //Modifying the string to store the values
    $time_string = sprintf( $time_string,
        get_the_date( DATE_W3C ), //Get the exact publication date with time
        get_the_date(), //Get the normal publication date
        get_the_modified_date( DATE_W3C ), //Get the detailed modification date
        get_the_modified_date() //Get the general modification date
    );
    //The final string to display
    $posted_on = sprintf(
        'Posted on %s',
        '<a href="'. esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );
    //Display the date
    echo '<span class="posted-on text-secondary">' . $posted_on . '</span>';
}

/*Function to display the author of the post
function posted_by() {
    //Author String
    $byline = sprintf(
        ' by %s',
        '<span class="author vcard"><a href="'. get_author_posts_url( get_the_author_meta('ID')) .'">' . get_the_author() . '</a></span>'
    );
    //Display the string
    echo '<span class="byline text-secondary">' . $byline . '</span>';
}*/

//Function to display the custom excerpt
function the_custom_excerpt( $trim_character_count) {
    //Return conditions if there is no excerpt or default excerpt
    if( ! has_excerpt() || 0 === $trim_character_count) {
        the_excerpt();
        return;
    }
    $excerpt = wp_strip_all_tags( get_the_excerpt()); //Remove the HTML element from the custom excerpt
    $excerpt = substr( $excerpt, 0, $trim_character_count); //Substring the excerpt to the specified number of characters
    $excerpt = substr( $excerpt, 0, strrpos( $excerpt, ' ')); //Show the excerpt till the last ' ' so the words arent left incomplete
    echo $excerpt; //Display the Exerpt
}

/*Function to display the Read more button
function excerpt_more() {
    $more = sprintf('<button class="mt-4 btn btn-info"><a class="read-more text-white" href="%1$s">%2$s</a></button>', //Styling
        get_permalink( get_the_ID()), //Link to the Post
        'Read more' //Read More Button Text
    );
    return $more;
}*/

//Function to display the Custom Pagination
function custom_pagination() {
    //HTML tags allowed inside the wp_kses
    $allowed_tags = [
        'span' => [
            'class' => []
            ],
        'a' => [
                'class' => [],
                'href' => []
            ]
    ];
    //Pagination Function Arguments
    $args = [
        'before_page_number' => '<span class="btn border border-primary mr-2 mb-2">',
        'after_page_number' => '</span>',
        'prev_text' => '<span class="btn border border-primary mr-2 mb-2">' . 'Previous' . '</span>',
        'next_text' => '<span class="btn border border-primary mr-2 mb-2">' . 'Next' . '</span>',
    ];
    //Display the Pagination
    printf( '<nav class="custom_pagination">%s</nav>', wp_kses( paginate_links( $args), $allowed_tags ));
}
?>
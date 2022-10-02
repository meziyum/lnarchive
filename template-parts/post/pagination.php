<?php
/**
 * 
 * Pagination Template Part
 * 
 * @package LNarchive
 */

//HTML tags allowed inside the wp_kses
$allowed_tags = [
    'button' => [ //Span Div
        'class' => [], //Class
    ],
    'span' => [ //Span
        'class' =>[], //Class
    ],
    'a' => [ //A div
        'class' => [], //Class
        'href' => [], //Href
        
    ]
];

//Pagination Function Arguments

$args = [
    'before_page_number' => '<button class="blog-page-no pagination-button">',
    'after_page_number' => '</button>',
    'prev_text' => '<button class="blog-page-prev pagination-button">' . '«' . '</button>',
    'next_text' => '<button class="blog-page-next pagination-button">' . '»' . '</button>',
];

//Display the Pagination
printf( '<nav class="blog-links d-flex justify-content-center">%s</nav>', wp_kses( paginate_links( $args), $allowed_tags ));

?>
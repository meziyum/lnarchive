<?php
/**
 *  Blog List Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="blog-wrap col-lg-9"> <!-- Blog Content Div -->
            <?php

            //Title
            printf(
                '<h1 class="page-title">%1$s</h1>', //Page Title Div
                wp_kses_post( get_the_title(get_option('page_for_posts', true))), //Get the Title
            );
            
            if(have_posts()) { //If there is post
                post_list($wp_query, 'post-blog'); //Post List
            }

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
                'before_page_number' => '<button class="blog-page-no pagination-button">', //HTML before page no
                'after_page_number' => '</button>', //HTML after page no
                'prev_text' => '<button class="blog-page-prev pagination-button">' . '«' . '</button>', //HTML for next button
                'next_text' => '<button class="blog-page-next pagination-button">' . '»' . '</button>', //HTML for prev button
            ];

            //Display the Pagination
            printf( '<nav class="blog-links d-flex justify-content-center">%s</nav>', wp_kses( paginate_links( $args), $allowed_tags ));
        ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>


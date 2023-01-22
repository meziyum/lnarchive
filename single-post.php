<?php
/**
 * Sinlge Post Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function

$the_post_id = get_the_ID(); //Get the ID
$series = get_post_meta( $the_post_id, 'series_value', true ); //Get the Series
$the_post_title = get_the_title(); //Get the Title
$the_post_type = get_post_type( $the_post_id ); //Get the Post Type
$prev_post = get_previous_post(); //Get the Prev Post
$next_post = get_next_post(); //Get the Next Post
$max_posts = get_option('posts_per_page'); //Get the max posts value
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row"> <!-- Main Row-->
        <div class="post-wrap content-wrap col-lg-9"> <!-- Post Content Div -->
            <?php
            if( have_posts() ) { //If there are posts
                while( have_posts(  )) : the_post(); //Loop through the post
                
                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( $the_post_title), //Get the Title
                    );
                    ?>
                    <div>
                    <?php

                    taxonomy_button_list(wp_get_post_terms( $the_post_id, ['category']),'category'); //Get the Category List
                    
                    if( $series != null) { //If the Series is not set
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_post_permalink($series));?>'" type="button" class="series-button float-end"> <!-- Series Button -->
                                <a class= "series-link"> <!-- The Series text -->
                                    <?php echo esc_html(get_the_title($series)); //Print the series?>
                                </a>
                            </button>
                        <?php
                    }

                    get_template_part('template-parts/edit-btn'); //Get the Edit Button
                    ?>
                    </div>
                    <?php
                    the_content(); //Display the Content
                    get_template_part('template-parts/post/page-nav'); //Get the Page Navigation
                    ?>
                    <div class="d-flex justify-content-between post-footer"> <!-- Post Footer -->
                        <?php
                            echo '<h6 class="posted-by">Posted by '.ucfirst(esc_html(get_the_author_meta('nickname'))); //Post Author
                            get_template_part('template-parts/post/date'); //Get Post Date
                        ?>
                    </div>
                    <?php

                    $related_args = array(  //Arguments for the Loop
                        'post_type' => $the_post_type, //Post Type
                        'posts_per_page' => $max_posts, //Posts on one page
                        'orderby' => 'rand', //Order by date
                        'post__not_in'   => array($the_post_id), //Exclude the post itself
                        'meta_key'   => 'series_value', //Meta Key
                        'meta_value' => $series, //Meta Value
                        'date_query' => array( //Last 1 Month posts
                            array(
                              'after'   => '-1 month', //Only posts from last 1 month
                            ),
                        ),          
                    );

                    $rquery = new WP_Query($related_args); //Related Posts Query

                    if($rquery->have_posts()) { //If there are any related posts
                        ?>
                            <h2 class="page-title">You might also like: </h2> <!-- Related Section Heading -->
                            <?php post_list( $rquery, 'child' ); //Print Post List?>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data
                    ?>
                        <section id="reviews-section" class="py-0 px-2"/> <!-- Review Section -->
                    <?php
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-post'); //Get the Sidebar?>
        </aside>
    </div>
</main> <!-- End of Main -->

<?php get_footer();?> <!-- Get the Footer -->
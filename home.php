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
        <div class="blog-wrap col"> <!-- Blog Content Div -->
            <?php

            //Title
            printf(
                '<h1 class="page-title">%1$s</h1>', //Page Title Div
                wp_kses_post( get_the_title(get_option('page_for_posts', true))), //Get the Title
            );
            
            if(have_posts()) { //If there is post
                ?>
                <div class="row"><?php
                    while( have_posts(  )) : the_post(); //Loop through all the posts
                        ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                            <article id="post-<?php esc_attr(the_ID());?>" class="blog-entry card">
                                <?php
                                if( has_post_thumbnail(get_the_ID())) { //If the entry has a thumbnail

                                    $the_post_id = get_the_ID(); //Get the Post ID
                                    $has_post_thumbnail = get_the_post_thumbnail( $the_post_id ); //Get the Post Thumbnail
                                    
                                    ?>
                                    <a href="<?php echo esc_url(get_permalink( $the_post_id ));?>"> <!-- The Permalink -->
                                        <?php
                                        //Display the Featured Image
                                        the_post_custom_thumbnail(
                                        $the_post_id, //The post ID
                                        'featured-thumbnail', //Name of the size
                                        [
                                            'class' => 'attachment-featured-img card-img-top', //Class attachment for css
                                            'alt'  => esc_attr(get_the_title()), //Attach the title as the default alt for the img
                                        ]
                                        );
                                    ?>
                                    </a>

                                    <div class="blog-entry-info card-body"> <!-- Blog Entry Card -->
                                        <?php
                                        printf(
                                            '<h5 class="entry-title card-title mb-0"><a class="blog-entry-title" href="%1$s">%2$s</a></h5>', //The Title
                                            get_the_permalink(), //Argument 1
                                            wp_kses_post( get_the_title()) //Argument 2
                                        );
                                        get_template_part('template-parts/post/date'); //Get the Date Template
                                        ?>
                                        <div>
                                            <?php             
                                            get_template_part('template-parts/post/category-list'); //Get the Category List
                                            get_template_part('template-parts/edit-btn'); //Get the Edit Button
                                        ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </article>
                        </div>
                        <?php
                    endwhile; //End While Loop
                    
                    //HTML tags allowed inside the wp_kses
                    $allowed_tags = [
                        'button' => [ //Span Div
                            'class' => [], //class
                        ],
                        'span' => [
                            'class' =>[],
                        ],
                        'a' => [ //A div
                            'class' => [], //class
                            'href' => [], //href
                            
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
                </div>
            <?php
            }
            //If there is not post
            else {
                get_template_part( 'template-parts/content-none' ); //Get the empty Template
            }
        ?>
        </div>
        <aside class="blog-sidebar col-lg-3 d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>


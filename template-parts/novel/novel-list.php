<?php 
/**
 * Novel List Template
 * 
 * @package LNarchive
 */
?>

<div class="row archive-wrap"> <!-- Archive Div -->
        <?php
            if(have_posts()) { //If there is post
                //Loop through the Posts
                while( have_posts()) : the_post(); //While there are posts
                    if (has_post_thumbnail( $post->ID )) {
                    ?>
                            <div class="archive-entry col-lg-2 col-md-2 col-sm-3 col-4"> <!-- Add Entry -->
                                <?php get_template_part('template-parts/components/blog/entry-blog-header'); //Get the Header
                                printf(
                                    '<h5 class="novel-title"><a class="text-dark fw-light" href="%1$s">%2$s</a></h5>', //The Title
                                    get_the_permalink(), //Argument 1
                                    wp_kses_post( get_the_title()) //Argument 2
                                );
                                ?>
                            </div>
                    <?php
                    }
                endwhile; //End While Loop
            }
            //If there is not post
            else { //If there are no posts
                get_template_part( 'template-parts/content-none' ); //Displat Empty Template
            }
        ?>
</div>
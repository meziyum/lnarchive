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
                    ?>
                            <div class="archive-entry col-lg-2 col-md-3 col-sm-4 col-6"> <!-- Add Entry -->
                                <?php get_template_part('template-parts/components/blog/entry-blog-header'); //Get the Header?>
                            </div>
                    <?php
                endwhile; //End While Loop
            }
            //If there is not post
            else { //If there are no posts
                get_template_part( 'template-parts/content-none' ); //Displat Empty Template
            }
        ?>
</div>
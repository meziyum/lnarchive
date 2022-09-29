<?php 
/**
 * Novel List Template
 * 
 * @package LNarchive
 */
?>

<div class="row novel-list"> <!-- Archive List Div -->
        <?php
            if(have_posts()) { //If there is post
                //Loop through the Posts
                while( have_posts()) : the_post(); //While there are posts
                    if (has_post_thumbnail( $post->ID )) { //If there is a post thumbnail
                    ?>
                        <div class="archive-entry-col col-lg-2 col-md-3 col-sm-4 col-6"> <!-- Archive Entry Col -->
                            <div class="archive-entry"> <!-- Add Entry -->
                            <a href="<?php echo get_permalink( $post->ID );?>"> <!-- The Permalink -->
                            <?php

                                //Display the Featured Image
                                the_post_custom_thumbnail(
                                $post->ID, //The post ID
                                'novel-cover', //Name of the size
                                [
                                    'class' => 'novel-cover', //Class attachment for css
                                    'alt'  => get_the_title(), //Attach the title as the default alt for the img
                                ]
                                );
                            ?>
                            </a>
                            </div>
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
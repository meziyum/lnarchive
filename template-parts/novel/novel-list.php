<?php 
/**
 * Novel List Template
 * 
 * @package LNarchive
 */

if (has_post_thumbnail( $post->ID )) { //If there is a post thumbnail
    ?>
        <div class="archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4"> <!-- Archive Entry Col -->
            <div class="archive-entry"> <!-- Add Entry -->
            <a href="<?php echo get_permalink( $post->ID );?>"> <!-- The Permalink -->
            <?php

                //Display the Featured Image
                the_post_custom_thumbnail(
                $post->ID, //The post ID
                'novel-cover', //Name of the size
                [
                    'class' => 'novel-cover', //Class attachment for css
                    'alt'  => esc_html(get_the_title()), //Attach the title as the default alt for the img
                ]
                );
            ?>
            </a>
            </div>
        </div>
    <?php
}
?>
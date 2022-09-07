<?php
/**
 * 
 * Blog Entry Header Template
 * 
 * @package LNarchive
 * 
 */

$the_post_id = get_the_ID(); //Get the Post ID
$has_post_thumbnail = get_the_post_thumbnail( $the_post_id ); //Get the Post Thumbnail

?>

<div class="entry-header"> <!-- Entry Header Div -->
    <a href="<?php echo get_permalink( $the_post_id );?>"> <!-- The Permalink -->
        <?php
             //Display the Featured Image
            the_post_custom_thumbnail(
                $the_post_id, //The post ID
                'featured-thumbnail', //Name of the 
                [
                    'class' => 'attachment-featured-img', //Class attachment for css
                    'alt'  => get_the_title(), //Attach the title as the default alt for the img
                ] 
            );
        ?>
     </a>
</div>
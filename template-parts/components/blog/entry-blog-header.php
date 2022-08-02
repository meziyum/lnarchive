<?php
/**
 * 
 * Template for Blog Entry Header
 * 
 * @package lnpedia
 * 
 */

$the_post_id = get_the_ID(); //Get the Post ID
$has_post_thumbnail = get_the_post_thumbnail( $the_post_id ); //Get the Post Thumbnail

?>

<header class="entry-header">
    <?php
        //Featured Image
        if( $has_post_thumbnail) {
        ?>
                <div class="entry-blog-image mt-3"> 
                    <a href="<?php get_permalink();?>">
                       <?php
                            //Display the Featured Image
                            the_post_custom_thumbnail(
                                $the_post_id,
                                'featured-thumbnail',
                                [
                                    'class' => 'attachment-featured-img' //Class attachment for css
                                ]
                            );
                        ?>
                    </a>
                </div>
            <?php
        }
    ?>
</header>
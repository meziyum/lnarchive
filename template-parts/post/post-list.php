<?php
/**
 * 
 * Post Template Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID(); //Get the Post ID
?>

<div class="col-lg-4 col-md-6 col-sm-12 col-12"> <!-- Blog Entry -->
<article id="post-<?php echo esc_attr($the_post_id);?>" class="blog-entry card"> <!--Entry Card -->
    <?php
    if( has_post_thumbnail($the_post_id)) { //If the entry has a thumbnail

        $has_post_thumbnail = get_the_post_thumbnail( $the_post_id ); //Get the Post Thumbnail
        $post_link = get_permalink( $the_post_id ); //Get Post Permalink
        $post_title = get_the_title( $the_post_id ); //Get Post Title
        
        ?>
        <a href="<?php echo esc_url($post_link)?>"> <!-- The Permalink -->
            <?php
            //Display the Featured Image
            the_post_custom_thumbnail(
            $the_post_id, //The post ID
            'featured-thumbnail', //Name of the size
            [
                'class' => 'attachment-featured-img', //Class attachment for css
                'alt'  => esc_attr($post_title), //Attach the title as the default alt for the img
            ]
            );
        ?>
        </a>

        <div class="blog-entry-info card-body"> <!-- Blog Entry Card -->
            <?php
            printf(
                '<h5 class="entry-title card-title mb-0"><a class="blog-entry-title" href="%1$s">%2$s</a></h5>', //The Title
                get_the_permalink(), //Argument 1
                wp_kses_post( $post_title) //Argument 2
            );
            get_template_part('template-parts/post/date'); //Get the Date Template
            ?>
            <div>
                <?php             
                taxonomy_button_list(wp_get_post_terms( $the_post_id, ['category']),'category'); //List the Category
                get_template_part('template-parts/edit-btn'); //Get the Edit Button
            ?>
            </div>
        </div>
        <?php
    }
    ?>
</article>
</div>
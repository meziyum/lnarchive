<?php
/**
* 
* Post Type Helper Functions
* 
* @package LNarchive
*/

function get_the_post_custom_thumbnail( $post_id, $size, $additional_attributes ) { //Function to get the post thumbnail for listing

    $custom_thumbnail =''; //decalre a null local variable to store the image

    if( null === $post_id) { //If the post id is null then get the post id
       $post_id=get_the_ID();
    }

    if( has_post_thumbnail( $post_id) ) { //If the post has thumbnail then lazy load
        $default_attributes = [
            'loading' => 'lazy'
        ];
    }

    $attributes = array_merge($additional_attributes, $default_attributes); //Merge the additional attributes with the lazy load attributes

    $custom_thumbnail = wp_get_attachment_image( //Get the Custom Thumbnail and store it
       get_post_thumbnail_id($post_id), //Get the Post Thumbnail ID
       $size, //Get the Size
       false,  //Whether to treat the image as a icon
       $attributes //The attributes for the image
    );

    return $custom_thumbnail; //Return the Thumbnail
}

function the_post_custom_thumbnail( $post_id, $size, $additional_attributes) { //Function to display the Post Thumbnail
    echo get_the_post_custom_thumbnail($post_id, $size, $additional_attributes); //Echo the Thumbnail
}

function taxonomy_button_list( $tax_terms, $tax_name ) { //Function to List Taxonomy in Button forms
    if(!empty($tax_terms)) {
        foreach( $tax_terms as $tax) { //Loops through all article terms
            ?>
                <button onclick="location.href='<?php echo esc_attr(get_term_link( $tax))?>'" type="button" class="<?php echo esc_attr($tax_name);?>-button"> <!-- Taxonomy Button -->
                    <a class="<?php echo esc_attr($tax_name);?>-button-link" href="<?php echo esc_attr(get_term_link($tax, $tax_name))?>"> <!-- The Taxonomy text -->
                        <?php echo esc_html($tax->name) //Print name of taxonomy?>
                    </a>
                </button>
            <?php
        }
    }
}

function novel_list( $loop, $name ) { //Function to display Novels List
    ?>
        <div class="row <?php echo $name;?>-list"> <!-- Child List Row -->
            <?php
                while( $loop->have_posts()) : $loop->the_post(); //While there are volumes
                    
                    $post_id = get_the_ID(); //Get the post ID

                    if (has_post_thumbnail( $post_id )) { //If there is a post thumbnail
                        ?>
                            <div class="archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4"> <!-- Archive Entry Col -->
                                <div class="archive-entry"> <!-- Add Entry -->
                                    <a href="<?php echo get_permalink( $post_id );?>"> <!-- The Permalink -->
                                        <?php
                                
                                            //Display the Featured Image
                                            the_post_custom_thumbnail(
                                            $post_id, //The post ID
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
                endwhile;
            ?>
        </div>
    <?php
}

function post_list( $loop , $label) { //Function to display Posts List
    ?>
        <div class="row <?php echo $label;?>"> <!-- Post Row -->
            <?php
                while( $loop->have_posts()) : $loop->the_post(); //While there are posts
                    
                    $the_post_id = get_the_ID(); //Get the Post ID
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12"> <!-- Blog Entry -->
                        <article id="post-<?php echo esc_attr($the_post_id);?>" class="blog-entry card"> <!--Entry Card -->
                            <?php
                            if( has_post_thumbnail($the_post_id)) { //If the entry has a thumbnail
                        
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
                    <?php
                endwhile;
            ?>
        </div>
    <?php
}

/**
 * Retrieves the IDs of the children of the post
 *
 * @param int $post_id          Optional. The ID of the object.
 * 
 * @return WP_Post[]|int[]      Array of siblings post IDs.
 */
function get_post_children( $post_id) {

    if( null === $post_id) {
        $post_id=get_the_ID();
    }

    if( empty( get_children() ) ){
        return array();
    }
    
    $args = array(
        'post_type' => get_post_type( $post_id ),
        'posts_per_page' => -1,
        'post__not_in'   => array( $post_id ),
        'fields' => 'ids',
        'post_parent' => $post_id,
    );

    return get_posts( $args ); //Return the posts
}

/**
 * Retrieves the IDs of the siblings of the post
 *
 * @param int $post_id          Optional. The ID of the object.
 * 
 * @return WP_Post[]|int[]      Array of sibling post IDs.
 */
function get_post_siblings( $post_id) {

    if( null === $post_id) {
        $post_id=get_the_ID();
    }

    if( wp_get_post_parent_id( $post_id ) == 0 ){
        return array();
    }
    
    $args = array(
        'post_type' => get_post_type( $post_id ),
        'posts_per_page' => -1,
        'post__not_in'   => array( $post_id ),
        'fields' => 'ids',
        'post_parent' => wp_get_post_parent_id( $post_id ),
    );

    return get_posts( $args );
}
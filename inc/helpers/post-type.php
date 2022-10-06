<?php
/**
 * 
 * Post Type Helper Functions
 * 
 * @package LNarchive
 * 
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


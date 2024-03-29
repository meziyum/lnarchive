<?php
/**
* 
* General Helper Functions
* 
* @package LNarchive
*/

function get_the_post_custom_thumbnail( $post_id, $size, $additional_attributes ) {

    if( ! has_post_thumbnail( $post_id) )
        return null;

    $custom_thumbnail ='';
    $default_attributes = [
        'loading' => 'lazy'
    ];

    if( null === $post_id) {
       $post_id=get_the_ID();
    }

    $attributes = array_merge($additional_attributes, $default_attributes);

    $custom_thumbnail = wp_get_attachment_image(
       get_post_thumbnail_id($post_id),
       $size,
       false,
       $attributes
    );

    return $custom_thumbnail;
}

function the_post_custom_thumbnail( $post_id, $size, $additional_attributes) {
    echo get_the_post_custom_thumbnail($post_id, $size, $additional_attributes);
}

function novel_list( $loop, array $args ) {
    $name = $args['name'];
    $display_title = array_key_exists('display_title', $args)? $args['display_title'] : false;

    if (array_key_exists("novel_no",$args))
        $novel_no = $args['novel_no'];
    else
        $novel_no=$loop->post_count;

    ?>
        <div class="row novel-list" id="<?php echo $name;?>-list">
            <?php
                while ($loop->have_posts() && $novel_no>0) : $loop->the_post();
                    $post_id = get_the_ID();
                    novel_item($post_id, $name, $display_title);
                    --$novel_no;
                endwhile;
            ?>
        </div>
    <?php
}

function novel_item($post_id, $name, $display_title) {
    ?>
        <div class="<?php echo esc_attr($name);?>-entry-col novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
            <div class="<?php echo esc_attr($name);?>-entry archive-entry novel-entry">
                <a id="<?php echo esc_attr($post_id)?>" class="<?php echo $name;?>-link novel-link" <?php if( get_post_type($post_id) != 'volume') echo 'href="'.esc_url(get_permalink($post_id)).'"';?>>
                    <?php
                    if (!has_post_thumbnail($post_id)) {
                        ?>
                            <h4 class="novel-cover">No Cover Image Found</h4>
                        <?php
                    } else {
                        the_post_custom_thumbnail(
                        $post_id,
                        'novel-cover',
                        [
                            'class' => 'novel-cover',
                            'alt'  => esc_html(get_the_title()),
                        ]
                        );
                    }
                    if ($display_title) {
                        ?>
                            <h6 class="novel-title"><?php 
                        echo esc_html(get_the_title($post_id));?></h6>
                        <?php
                    }
                    ?>
                </a>
            </div>
        </div>
    <?php
}

function post_list( $loop , $label) {
    ?>
        <div class="row <?php echo $label;?>">
            <?php
                while( $loop->have_posts()) : $loop->the_post();               
                    $the_post_id = get_the_ID();
                    if( has_post_thumbnail($the_post_id)) {
                    ?>
                        <div class="post-entry-col archive-entry-col col-lg-4 col-md-6 col-sm-12 col-12">
                        <article class="archive-entry post-entry">
                            <?php
                            
                                $post_link = get_permalink( $the_post_id );
                                $post_title = get_the_title( $the_post_id );
                                
                                ?>
                                <a id="post-<?php echo esc_attr($the_post_id);?>" class='post=link' href="<?php echo esc_url($post_link)?>">
                                    <?php
                                    the_post_custom_thumbnail(
                                    $the_post_id,
                                    'featured-thumbnail',
                                    [
                                        'class' => 'post-img',
                                        'alt'  => esc_attr($post_title),
                                    ]
                                    );
                                ?>
                                </a>
                        
                                <div class="post-entry-info">
                                    <?php
                                    printf(
                                        '<a><h5 class="entry-title" href="%1$s">%2$s</h5></a>',
                                        get_the_permalink(),
                                        wp_kses_post( $post_title)
                                    );
                                    post_date($the_post_id, false);
                                    ?>
                                        <?php             
                                        taxonomy_button_list('post', wp_get_post_terms( $the_post_id, ['category']),'category');
                                    ?>
                                </div>
                                <?php
                            ?>
                        </article>
                        </div>
                    <?php
                    }     
                endwhile;
            ?>
        </div>
    <?php
}

function post_date($the_post_id, $date_visibility) {
    $article_terms = wp_get_post_terms( $the_post_id, ['category']);

    if( !empty( $article_terms ) && is_array( $article_terms )) {
        foreach( $article_terms as $article_term) {
            if( get_term_meta( $article_term->term_id, 'date_visible_value', true) == 'yes' ||  !$date_visibility)  {
                
                $time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>';

                if( get_the_time('U') !== get_the_modified_time('U')) {
                    $time_string = '<time class="entry-date d-none" datetime="%1$s">%2$s</time><time class="entry-date" datetime="%3$s">%4$s</time>';
                }

                $time_string = sprintf( $time_string,
                    get_the_date( DATE_W3C ),
                    get_the_date(),
                    get_the_modified_date( DATE_W3C ),
                    get_the_modified_date()
                );

                $posted_on = sprintf(
                    '%s',
                    '<a rel="bookmark">' . $time_string . '</a>'
                );
                ?>
                    <h6 class="posted-on"><?php echo $posted_on;?></h6>
                <?php

                break;
            }
        }
    }
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
?>
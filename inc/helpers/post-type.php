<?php
/**
* 
* Post Type Helper Functions
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

function taxonomy_button_list( $post_type, $tax_terms, $tax_name ) {
    if(!empty($tax_terms)) {
        foreach( $tax_terms as $term) {
            $term_name = $term->name;
            ?>
                <a class="<?php echo esc_attr($tax_name);?>-button anchor-button" href='<?php 
                    if($post_type =="novel") {
                        echo esc_attr(get_post_type_archive_link('novel')).'?'.$tax_name.'_filter'.'='.$term_name;
                    } else {
                        echo esc_attr(get_term_link($term));
                    } 
                    ?>'>
                    <?php echo esc_html($term_name)?>
                </a>
            <?php
        }
    }
}

function novel_list( $loop, array $args ) {

    $name = $args['name'];

    if( array_key_exists("novel_no",$args) )
        $novel_no = $args['novel_no'];
    else
        $novel_no=$loop->post_count;

    ?>
        <div class="row novel-list" id="<?php echo $name;?>-list">
            <?php
                while( $loop->have_posts() && $novel_no>0 ) : $loop->the_post();
                    
                    $post_id = get_the_ID();
                    $tid = $post_id;

                    if ( !has_post_thumbnail( $post_id )) {

                        $volume1_args = array(
                            'post_type' => 'volume',
                            'posts_per_page' => 1,
                            'orderby' => 'date',
                            'order' => 'ASC',
                            'meta_key' => 'series_value',
                            'meta_value' => $post_id
                        );                       
                        $volume1 = get_posts($volume1_args);
                        $tid = $volume1[0]->ID;
                    }
                        ?>
                            <div class="<?php echo esc_attr($name);?>-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
                                <div class="<?php echo esc_attr($name);?>-entry archive-entry">
                                    <a id="<?php echo esc_attr($post_id)?>" class="<?php echo $name;?>-link" <?php if( get_post_type($post_id) != 'volume') echo 'href="'.esc_url(get_permalink()).'"';?>>
                                        <?php
                                            the_post_custom_thumbnail(
                                            $tid,
                                            'novel-cover',
                                            [
                                                'class' => 'novel-cover',
                                                'alt'  => esc_html(get_the_title()),
                                            ]
                                            );
                                        ?>
                                    </a>
                                </div>
                            </div>
                        <?php
                    --$novel_no;
                endwhile;
            ?>
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
                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                        <article id="post-<?php echo esc_attr($the_post_id);?>" class="blog-entry">
                            <?php
                            
                                $post_link = get_permalink( $the_post_id );
                                $post_title = get_the_title( $the_post_id );
                                
                                ?>
                                <a href="<?php echo esc_url($post_link)?>">
                                    <?php
                                    the_post_custom_thumbnail(
                                    $the_post_id,
                                    'featured-thumbnail',
                                    [
                                        'class' => 'attachment-featured-img',
                                        'alt'  => esc_attr($post_title),
                                    ]
                                    );
                                ?>
                                </a>
                        
                                <div class="blog-entry-info">
                                    <?php
                                    printf(
                                        '<h5 class="entry-title mb-0"><a class="blog-entry-title" href="%1$s">%2$s</a></h5>',
                                        get_the_permalink(),
                                        wp_kses_post( $post_title)
                                    );
                                    get_template_part('template-parts/post/date');
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
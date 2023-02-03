<?php
/**
 * Sinlge Post Template
 * 
 * @package LNarchive
 */
get_header();

$the_post_id = get_the_ID();
$series = get_post_meta( $the_post_id, 'series_value', true );
$max_posts = get_option('posts_per_page');
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row">
        <div class="post-wrap content-wrap col-lg-9">
            <?php
            if( have_posts() ) {
                while( have_posts(  )) : the_post();
                    printf(
                        '<h1 class="page-title">%1$s</h1>',
                        wp_kses_post(get_the_title()),
                    );
                    ?>
                        <div>
                            <?php
                            taxonomy_button_list(wp_get_post_terms( $the_post_id, ['category']),'category');
                            
                            if( $series != null) {
                                ?>
                                    <button onclick="location.href='<?php echo esc_url(get_post_permalink($series));?>'" type="button" class="series-button float-end">
                                        <a class= "series-link">
                                            <?php echo esc_html(get_the_title($series));?>
                                        </a>
                                    </button>
                                <?php
                            }

                            get_template_part('template-parts/edit-btn');
                            ?>
                        </div>
                    <?php
                    the_content();
                    get_template_part('template-parts/post/page-nav');
                    ?>
                    <div class="d-flex justify-content-between post-footer">
                        <?php
                            echo '<h6 class="posted-by">Posted by '.ucfirst(esc_html(get_the_author_meta('nickname')));
                            get_template_part('template-parts/post/date');
                        ?>
                    </div>
                    <?php
                    $related_post_args = array(
                        'post_type' => 'post',
                        'posts_per_page' => $max_posts,
                        'orderby' => 'rand',
                        'post__not_in' => array($the_post_id),
                        'meta_key' => 'series_value',
                        'meta_value' => $series,
                        'date_query' => array(
                            array(
                            'after' => '-1 month',
                            ),
                        ),
                    );
                    $related_posts_query = new WP_Query($related_post_args);

                    if( $related_posts_query->have_posts()){
                        ?>
                            <h2 class="page-title">You might also like: </h2>
                        <?php
                        post_list($related_posts_query, 'related');
                    }

                    wp_reset_postdata();
                    ?>
                        <section id="reviews-section" class="py-0 px-2"/>
                    <?php
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-post');?>
        </aside>
    </div>
</main>

<?php get_footer();?>
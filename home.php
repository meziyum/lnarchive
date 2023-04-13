<?php
/**
 *  Blog List Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main">
    <div class="row main-row">
        <div class="blog-wrap col-lg-9">
            <?php
            printf(
                '<h1 class="page-title">%1$s</h1>',
                wp_kses_post(get_the_title(get_option('page_for_posts', true)))
            );

            if(have_posts()) {
                post_list($wp_query, 'post-blog');
            }

            $allowed_tags = [
                'button' => [
                    'class' => [],
                ],
                'span' => [
                    'class' =>[],
                ],
                'a' => [
                    'class' => [],
                    'href' => [],
                ]
            ];

            $args = [
                'before_page_number' => '<button class="blog-page-no pagination-button">',
                'after_page_number' => '</button>',
                'prev_text' => '<button class="blog-page-prev pagination-button">' . '«' . '</button>',
                'next_text' => '<button class="blog-page-next pagination-button">' . '»' . '</button>',
            ];

            printf('<nav class="blog-links d-flex justify-content-center">%s</nav>', wp_kses(paginate_links($args), $allowed_tags));
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-main'); ?>
        </aside>
    </div>
</main>

<?php get_footer(); ?>

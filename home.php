<?php
/**
 *  Blog List Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row">
        <div class="blog-wrap col-lg-9"> <!-- Blog Content Div -->
            <?php
            printf(
                '<h1 class="page-title text-dark">%1$s</h1>', //Page Title Div
                wp_kses_post( get_the_title(get_option('page_for_posts', true))), //Get the Title
            );
            if(have_posts()) { //If there is post

                //Loop through the Posts
                while( have_posts(  )) : the_post(); //While there are posts
                    ?>              
                        <article id="post-<?php the_ID();?>" <?php post_class('post');?>>
                            <div class="row blog-entry"> <!-- Row Div -->
                                <?php
                                if( has_post_thumbnail(get_the_ID())) { //If the current post in the loop has a thumbnail
                                ?>
                                    <div class="post-thumbnail col-lg-3 col-md-12 col-sm-12"> <!-- Post Thumbnail Div -->
                                    <?php get_template_part('template-parts/components/blog/entry-blog-header'); //Get the Header?>
                                    </div>
                                    <div class="post-blog-info col-lg-9 col-md-12 col-sm-12"> <!-- Post Info Div when thumbnail -->
                                    <?php
                                }
                                else { 
                                ?> 
                                    <div class="post-blog-info"> <!-- Blog Info Div when no thumbnail-->
                                    <?php
                                }
                                    get_template_part('template-parts/components/blog/entry-blog-meta'); //Get the Meta Data
                                    the_custom_excerpt(140); //Get the excerpt
                                    get_template_part('template-parts/components/blog/entry-footer'); //Get the Footer
                                    ?>
                                    </div> <!-- End of Blog Entry Div -->
                            </div>         
                        </article>
                        <?php
                    endwhile; //End While Loop
                    ?>
                <?php
                    custom_pagination(); //Display the Custom Pagination
                ?>
            <?php
            }
            //If there is not post
            else {
                get_template_part( 'template-parts/content-none' ); //Get the empty Template
            }
        ?>
        </div>
        <aside class="blog-sidebar col-lg-3 d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>


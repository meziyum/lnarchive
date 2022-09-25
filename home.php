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
                '<h1 class="page-title">%1$s</h1>', //Page Title Div
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
                                    <?php get_template_part('template-parts/components/thumbnail'); //Get the Header?>
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
                                    $the_post_id = get_the_ID(); //Get the ID
                                    $article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms
                                    $post_type = get_post_type( get_queried_object_id()); //Get the Post Type
                                    foreach( $article_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <button onclick="location.href='<?php echo get_term_link( $article_term);?>'" type="button" class="category-button btn btn-success btn-sm"> <!-- Category Button -->
                                                <a class= "entry-footer-link text-white"> <!-- The Category text -->
                                                    <?php echo $article_term->name //Print the article?>
                                                </a>
                                            </button><?php
                                    }

                                    if( current_user_can('edit_posts')){ //Check if the user has capability to edit the post
                                        ?>
                                            <button onclick="location.href='<?php echo get_edit_post_link()?>'" type="button" class="edit--button btn btn-info btn-sm float-end"> <!-- Edit Button -->
                                                        <a class= "entry-footer-link text-white"> <!-- The Edit Button Text -->
                                                            Edit
                                                        </a>
                                            </button>
                                        <?php
                                    }
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


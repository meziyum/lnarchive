<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row">
        <div class="novel-wrap col-lg-9">
        <?php
                
            if( have_posts(  ) ) { 
            while( have_posts(  )) : the_post();
                get_template_part('template-parts/components/blog/entry-meta');
                ?>
                    <div class="row novel-entry">
                        <div class="novel-cover col-lg-4 col-md-5 cold-sm-12">
                            <?php 
                            
                                if (has_post_thumbnail( $post->ID )) {
                                    the_post_custom_thumbnail(
                                        get_the_ID(), //The novel ID
                                        'novel-cover', //Name of the size
                                        [
                                            'class' => 'novel-cover-img', //Class attachment for css
                                            'alt'  => get_the_title(), //Attach the title as the default alt for the img
                                        ]
                                    );
                                }
                                else {
                                    //Default Wallpaper for light novels
                                }
                                ?>
                                <table class="novel-info-table">
                                <?php

                                //List of all Iterms to display
                                $taxs = array('novel_status', 'language', 'publisher', 'writer', 'illustrator', 'translator');

                                foreach( $taxs as $tax) { //Loop through all items

                                    $terms = get_the_terms(get_the_ID(), $tax); //Get all the Terms

                                    if( !empty($terms)) {
                                        foreach( $terms as $key => $article_term) { //Loops through all article terms
                                            ?>
                                                <tr>
                                                    <th><?php echo get_taxonomy_labels(get_taxonomy($tax))->name?> <th> <!-- Display the Name Label -->
                                                    <td><a href="<?php echo get_term_link($article_term, $tax)?>"><?php echo $article_term->name?></a></td>
                                                </tr>
                                            <?php
                                        }
                                    }
                                }

                                ?>
                                </table>
                        </div>

                        <div class="novel-info col">
                            <h2>Description</h2>
                            <?php
                                the_excerpt();

                                $genre_terms = get_the_terms(get_the_ID(), 'genre');

                                if(!empty($genre_terms)) {
                                    ?>
                                    <h2>Genres</h2>
                                    <?php
                                    foreach( $genre_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <button onclick="location.href='<?php echo get_term_link( $article_term)?>'" type="button" class="genre-button btn btn-success btn-sm"> <!-- Category Button -->
                                                <a class="genre-button-link text-white" href="<?php echo get_term_link($article_term, 'genre')?>"> <!-- The Category text -->
                                                    <?php echo $article_term->name //Print the article?>
                                                </a>
                                            </button>
                                        <?php
                                    }   
                                }
                            ?>
                        </div>
                    </div>

                    <div class="col novel-articles"> <!-- Novel News Articles Div -->
                    <h2>Related Articles</h2>
                    <?php

                    $args = array(  //Arguments for the Loop
                        'post_type' => 'post', //Post Type
                        'post_status' => 'publish', //Status of the Post
                        'posts_per_page' => 5, //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'ASC', //ASC or DEC
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => get_the_ID(), //Meta value
                    );

                    $loop = new WP_Query( $args ); //Create the Loop

                    if($loop->have_posts()) { //If there is post
                        //Loop through the Posts
                        while ( $loop->have_posts() ) : $loop->the_post(); //Loop through the posts
                            ?>              
                            <article id="post-<?php the_ID();?>" <?php post_class('post');?>>
                                <div class="row novel-entry"> <!-- Row Div -->
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
                            </article>
                            <?php
                        endwhile; //End While Loop
                        ?>
                    <?php
                        custom_pagination(); //Display the Custom Pagination
                    }
                    ?>
                    </div>
                <?php
            endwhile;
            }
        ?>
        </div>

        <div class="sidebar-wrap col-lg-3 d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function

$the_post_id = get_the_ID();
$the_post_title = get_the_title();
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="novel-wrap col-lg-9"> <!-- Novel Content Div -->
        <?php               
            if( have_posts(  ) ) {  //If there are posts
            while( have_posts(  )) : the_post(); //Loop through the posts
                
                //Title
                printf(
                    '<h1 class="page-title">%1$s</h1>', //HTML
                    wp_kses_post( $the_post_title), //Get the Title
                );
                ?>
                    <div class="info-section"> <!-- Novel Info Div -->
                        <div class="row novel-row">
                            <div class="novel-cover-div col-lg-4 col-md-5 cold-sm-12">
                                <?php 
                                
                                    if (has_post_thumbnail( $the_post_id )) {
                                        the_post_custom_thumbnail(
                                            $the_post_id, //The novel ID
                                            'novel-cover', //Name of the size
                                            [
                                                'class' => 'novel-cover', //Class attachment for css
                                                'alt'  => esc_html($the_post_title), //Attach the title as the default alt for the img
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

                                        $terms = get_the_terms($the_post_id, $tax); //Get all the Terms

                                        if( !empty($terms)) {
                                            foreach( $terms as $key => $article_term) { //Loops through all article terms
                                                ?>
                                                    <tr>
                                                        <th><?php echo esc_attr(get_taxonomy_labels(get_taxonomy($tax))->name)?> <th> <!-- Display the Name Label -->
                                                        <td><a href="<?php echo esc_attr(get_term_link($article_term, $tax))?>"><?php echo esc_html($article_term->name)?></a></td>
                                                    </tr>
                                                <?php
                                            }
                                        }
                                    }

                                    ?>
                                    </table>
                            </div>

                            <div class="novel-info col"> <!-- Novel Info Col --> 
                                <h2>Description</h2><?php //Desc Title
                                the_excerpt(); //Get the excerpt
                                ?><h2>Genre</h2><?php //Genre Title
                                taxonomy_button_list( get_the_terms($the_post_id, 'genre'), 'genre'); //List the Genre Taxonomy
                                ?><h2>Tag</h2><?php //Tag Title
                                taxonomy_button_list( get_the_terms($the_post_id, 'post_tag'), 'post-tag'); //List the Tag Taxonomy
                                get_template_part('template-parts/edit-btn'); //Get the Edit Button Template
                                ?>
                            </div>
                        </div>
                    </div>                       
                <?php

                    $vol_args = array(  //Arguments for the Loop
                        'post_type' => 'volume', //Post Type
                        'post_status' => 'publish', //Status of the Post
                        'posts_per_page' => get_option('posts_per_page'), //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'ASC', //ASC or DEC
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );                       

                    $vquery = new WP_Query($vol_args); //Volumes List Query

                    if($vquery->have_posts()) { //If there are any volumes
                        ?>
                            <div class="volumes-section">
                                <h2>Volumes</h2> <!-- Volumes Section Heading -->
                                <?php novel_list( $vquery, 'volume'); //Print Novel List ?>
                            </div>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data           

                    $child_args = array(  //Arguments for the Loop
                        'post_type' => 'novel', //Post Type
                        'post_status' => 'publish', //Status of the Post
                        'posts_per_page' => get_option('posts_per_page'), //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'ASC', //ASC or DEC
                        'post_parent' => $the_post_id, //Parent Novel ID
                    );

                    $cquery = new WP_Query($child_args); //Children List Query

                    if($cquery->have_posts()) { //If there are any children
                        ?>
                            <div class="child-section">
                                <h2>Novels from same Universe</h2> <!-- Child Novels Section Heading -->
                                <?php novel_list( $cquery, 'child' ); //Print Novel List?>
                            </div>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data

                    $args = array(  //Arguments for the Loop
                        'post_type' => 'post', //Post Type
                        'post_status' => 'publish', //Status of the Post
                        'posts_per_page' => get_option('posts_per_page'), //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'DEC', //ASC or DEC
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );

                    $loop = new WP_Query( $args ); //Create the Loop

                    if($loop->have_posts()) { //If there is post
                        ?>
                            <div class="posts-section">
                            <div class="row"> <!-- Novel News Articles Div -->
                                <h2>Related Articles</h2>
                                <?php
                                while ( $loop->have_posts() ) : $loop->the_post(); //Loop through the posts
                                    get_template_part('template-parts/post/post-list'); //Get Posts List
                                endwhile; //End While Loop
                                ?>
                            </div>
                            </div>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data                   
            endwhile;
            }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
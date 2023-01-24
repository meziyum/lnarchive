<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function

$the_post = get_post();
$the_post_id = get_the_ID(); //Get the Post ID
$the_post_type = get_post_type( $the_post_id ); //Get the Post Type
$max_posts = get_option('posts_per_page'); //Get the max posts value
$volume1_args = array(  //Arguments for the Loop
    'post_type' => 'volume', //Post Type
    'posts_per_page' => 1, //Posts on one page
    'orderby' => 'date', //Order by date
    'order' => 'ASC', //ASC or DEC
    'meta_key' => 'series_value', //Meta Key
    'meta_value' => $the_post_id, //Meta value
);                       
$volume1 = get_posts($volume1_args); //Get the first volume
$has_volume1 = !empty($volume1) ? true : false; //If the novel has volume 1
$the_post_title = $has_volume1 ? $volume1[0]->post_title : get_the_title(); //Get the Novel Title
if( $has_volume1){ //Get the first volume id and formats if there is first volume
    $volume1_id = $volume1[0]->ID; //Volume 1 ID
    $formats = get_the_terms($volume1_id, 'format'); //Get the formats
}
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row"> <!-- Main Row -->
        <div class="novel-wrap col-lg-9"> <!-- Novel Content Div -->
        <?php               
            if( have_posts() ) {  //If there are posts
            while( have_posts(  )) : the_post(); //Loop through the posts
                
                //Title
                printf(
                    '<h1 class="page-title">%1$s</h1>', //HTML
                    wp_kses_post( $the_post_title ), //Get the Title
                );
                ?>
                    <section id="info-section"> <!-- Novel Info Div -->
                        <div class="row novel-row">
                            <div class="novel-info-left col-lg-4 col-md-4 cold-sm-12">
                                <?php
                                    the_post_custom_thumbnail(
                                        $the_post_id, //The Novel ID
                                        'novel-cover', //Name of the size
                                        [
                                            'class' => 'novel-cover', //Class attachment for css
                                            'alt'  => esc_html($the_post_title), //Attach the title as the default alt for the img
                                        ]
                                    );
                                ?>
                                <table> <!-- Novel Taxonomies Table -->
                                    <?php
                                        //List of all Iterms to display
                                        $taxs = array('novel_status', 'publisher','writer', 'illustrator', 'translator','narrator');

                                        foreach( $taxs as $tax) { //Loop through all items

                                            if( taxonomy_exists($tax) ){
                                                $terms = get_the_terms($the_post_id, $tax); //Get all the Terms
                                                if( empty($terms) && $has_volume1)//If there are no terms found for the taxonomy then the taxonomy is for the volume not the novel (the post should have volume1)
                                                    $terms = get_the_terms($volume1_id, $tax); //Get the taxonomy terms from the first volume of the novel
                                            }

                                            if( is_array($terms)){ //If there are terms then display the taxonomy information
                                                ?>
                                                    <tr id="<?php echo esc_attr(get_taxonomy_labels(get_taxonomy($tax))->name).'_row' ?>">
                                                        <th><?php
                                                                echo esc_html(get_taxonomy_labels(get_taxonomy($tax))->name); //Output the Label of the Taxonomy Info Column
                                                            ?>
                                                        </th> <!-- Display the Name Label -->
                                                        <td id="<?php echo esc_attr($tax).'_info_value';?>">
                                                            <?php
                                                                foreach( $terms as $key => $article_term) { //Loops through all article terms
                                                                    ?>
                                                                        <a href="<?php echo esc_attr(get_term_link($article_term, $tax))?>"><?php echo esc_html($article_term->name)?></a> <!-- Entry -->
                                                                        <br> <!-- New Line for next entry -->
                                                                    <?php
                                                                }       
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                        }
                                    
                                    if( $has_volume1){ //Display the ISBN and Publication Information if there is volume1
                                        ?>
                                        <tr> <!-- ISBN Info Volume 1-->
                                            <th>
                                                ISBN
                                            </th>
                                            <td id="ISBN_info_value">
                                                <a><?php echo get_metadata( 'post', $volume1_id, 'isbn_'.$formats[0]->name.'_value')[0];?></a> <!-- Print the ISBN metadata -->
                                            </td>
                                        </tr>
                                        <tr> <!-- Publication Date Info Volume 1-->
                                            <th>
                                                Publication Date                                         
                                            </th>
                                            <td id="Publication Date_info_value">
                                            <a><?php echo get_metadata( 'post', $volume1_id, 'published_date_value_'.$formats[0]->name)[0];?></a> <!-- Print the Publication Date metadata-->
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>

                            <div class="novel-info-right col"> <!-- Novel Info Col -->
                                <div id="novel-actions"></div> <!-- Novel Actions Section -->
                                <h2>Description</h2><?php //Desc Title
                                if( $has_volume1){ //Display the Desc and Formats if there is Volume 1
                                echo '<div id="novel-excerpt">'.apply_filters('the_content', ($volume1[0]->post_excerpt)).'</div>'; //Display the volume 0 excerpt
                                ?>
                                    <ul id="format_info_value" class="d-flex justify-content-center"> <!-- Format List -->
                                        <?php
                                            for( $i=0; $i<count($formats); $i++){ //Loop through all the formats
                                                $format_name = $formats[$i]->name; //Get the format Name
                                                ?>
                                                    <button 
                                                    id="<?php echo esc_attr($format_name.'-format');//ID of the format button?>" 
                                                    class="format-button <?php if( $i==0 ) //Assign selected format class if first iteration of the format loop
                                                            echo "selected-format";?>" 
                                                    isbn="<?php echo get_metadata( 'post', $volume1_id, 'isbn_'.$format_name.'_value' )[0];?>"
                                                    publication_date="<?php echo get_metadata( 'post', $volume1_id, 'published_date_value_'.$format_name)[0];?>"
                                                    >
                                                        <?Php echo esc_html($format_name);?> <!-- Output the format -->
                                                    </button> <!--Format Button -->
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                <?php
                                }

                                $alt_names = get_post_meta( $the_post_id, 'alternate_names_value', true ); //Get the Alt Name field
                                $alt_names_array = explode( ",", $alt_names ); //Separate the multiple names using the comma

                                if( !empty( $alt_names_array[0] )){ //If there are alternate names
                                ?>
                                    <h4>Alternate Names</h4> <!-- Title -->
                                    <p>
                                        <?php
                                            foreach( $alt_names_array as $alt) { //Loop through all the names
                                                ?>
                                                    <?php echo $alt;//Print the name?>
                                                    <br> <!--Break the Line -->
                                                <?php
                                            }
                                        ?>
                                    </p>
                                    <?php
                                }                           

                                $genre_terms = get_the_terms($the_post_id, 'genre'); //Get the genres
                                $tag_terms = get_the_terms($the_post_id, 'post_tag'); //Get the tags

                                if( !empty( $genre_terms )) { //If there are genres assigned
                                    ?><h3>Genre</h3><?php //Genre Title
                                    taxonomy_button_list( $genre_terms , 'genre'); //List the Genre Taxonomy
                                }

                                if( !empty( $tag_terms ) && $tag_terms[0]->name != 'None'){ //Perform static check that the only tag is not None and there are tags
                                    ?><h3>Tag</h3><?php //Tag Title
                                    taxonomy_button_list( $tag_terms, 'post-tag'); //List the Tag Taxonomy
                                }

                                get_template_part('template-parts/edit-btn'); //Get the Edit Button Template
                                ?>
                            </div>
                        </div>
                    </section>                       
                <?php

                    $vol_args = array(  //Arguments for the Loop
                        'post_type' => 'volume', //Post Type
                        'posts_per_page' => $max_posts, //Posts on one page
                        'orderby' => 'date', //Order by date
                        'order' => 'ASC', //ASC or DEC
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );                       

                    $vquery = new WP_Query($vol_args); //Volumes List Query

                    if($vquery->post_count > 1 ) { //If there are more than one volumes for the novel
                        ?>
                            <section id="volumes-section" class="novels-list-section">
                                <h2 id="volumes-no">Volumes</h2> <!-- Volumes Section Heading -->
                                <?php novel_list( $vquery, array( 'name' => 'volume')); //Print Novel List ?>
                            </section>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data

                    $universe_novels = array_merge( get_post_siblings( $the_post_id ), get_post_ancestors( $the_post_id ), get_post_children( $the_post_id ) ); //Get the novels in the same Universe

                    if( !empty($universe_novels) ) {
                        $uquery_args = array( //Arguments for the Loop
                            'post_type' => $the_post_type, //Post Types
                            'posts_per_page' => -1, //Posts on one page
                            'orderby' => 'rand', //Order by date
                            'post__not_in'   => array( $the_post_id ), //Exclude the current post
                            'post__in' => $universe_novels, //The queried posts should be present in the Universe Novels List
                        );

                        $uquery = new WP_Query( $uquery_args ); //Children List Query

                        if($uquery->have_posts()) { //If there are any children
                            ?>
                                <section id="child-section" class="novels-list-section">
                                    <h2>Novels from same Universe</h2> <!-- Child Novels Section Heading -->
                                    <?php novel_list( $uquery, array( 'name' => 'child') ); //Print Novel List?>
                                </section>
                            <?php
                        }

                        wp_reset_postdata(); //Reset the $POST data
                    }

                    $rtags = array(); //Initialize an empty array to store all the term ids of the tags

                    foreach( $tag_terms as $tag ){ //Loop through all the post tags
                        array_push( $rtags, $tag->term_id ); //Push the tag into the array
                    }

                    $related_args = array(  //Arguments for the Loop
                        'post_type' => $the_post_type, //Post Type
                        'posts_per_page' => $max_posts, //Posts on one page
                        'orderby' => 'rand', //Order by date
                        'tag__in' => $rtags, //Tag
                        'post__not_in'   => array_merge( $universe_novels ,array($the_post_id) ), //Exclude the post itself
                    );

                    $rquery = new WP_Query($related_args); //Related Posts Query

                    if($rquery->have_posts()) { //If there are any related posts
                        ?>
                            <section id="related-section" class="novels-list-section">
                                <h2>Recommendations</h2> <!-- Related Section Heading -->
                                <?php novel_list( $rquery, array( 'name' => 'related', 'novel_no' => 6) ); //Print Novel List?>
                            </section>
                        <?php
                    }

                    wp_reset_postdata(); //Reset the $POST data

                    $args = array(  //Arguments for the Loop
                        'post_type' => 'post', //Post Type
                        'posts_per_page' => 3, //Posts on one page
                        'meta_key' => 'series_value', //Meta Key
                        'meta_value' => $the_post_id, //Meta value
                    );

                    $loop = new WP_Query( $args ); //Create the Loop

                    if($loop->have_posts()) { //If there is post
                        ?>
                            <section id="posts-section">
                                <div class="row"> <!-- Novel News Articles Div -->
                                    <h2>Related Articles</h2>
                                    <?php post_list( $loop, 'novel-articles' ); //Post List?>
                                </div>
                            </section>
                        <?php
                    }
        
                    wp_reset_postdata(); //Reset the $POST data
                    ?>
                        <section id="reviews-section" class="py-0 px-2"/> <!-- Review Section -->
                    <?php
            endwhile;
            }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-novel'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
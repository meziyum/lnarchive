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
            $args = array(  
                'post_type' => 'novel',
                'post_status' => 'publish',
            );
        
            $loop = new WP_Query( $args ); 
                
            if( have_posts(  ) ) { 
            while( have_posts(  )) : the_post();
                get_template_part('template-parts/components/blog/entry-meta');
                ?>
                    <div class="row novel-entry">
                        <div class="novel-cover col-lg-4 col-md-5 cold-sm-12">
                            <?php the_post_custom_thumbnail(
                                get_the_ID(), //The novel ID
                                'novel-cover', //Name of the size
                                [
                                    'class' => 'novel-cover-img', //Class attachment for css
                                    'alt'  => get_the_title(), //Attach the title as the default alt for the img
                                ] 
                                );

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

                                $series_terms = get_the_terms(get_the_ID(), 'series'); //Get all the Terms

                                ?>
                                <table>
                                <?php

                                if( !empty($series_terms)) {
                                    foreach( $series_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <tr>
                                                <th>Series: <th>
                                                <td><a href="<?php echo get_term_link($article_term, 'series')?>"><?php echo $article_term->name?></a></td>
                                            </tr>
                                        <?php
                                    }
                                }                           

                                $publisher_terms = get_the_terms(get_the_ID(), 'publisher'); //Get all the Terms

                                if( !empty($publisher_terms)) {
                                    foreach( $publisher_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <tr>
                                                <th>Publisher: <th>
                                                <td><a href="<?php echo get_term_link($article_term, 'publisher')?>"><?php echo $article_term->name?></a></td>
                                            </tr>
                                        <?php
                                    }
                                } 

                                $writer_terms = get_the_terms(get_the_ID(), 'writer'); //Get all the Terms

                                if( !empty($writer_terms)) {
                                    foreach( $writer_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <tr>
                                                <th>Author: <th>
                                                <td><a href="<?php echo get_term_link($article_term, 'writer')?>"><?php echo $article_term->name?></a></td>
                                            </tr>
                                        <?php
                                    }
                                }

                                $artist_terms = get_the_terms(get_the_ID(), 'illustrator'); //Get all the Terms

                                if( !empty($artist_terms)) {
                                    foreach( $artist_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <tr>
                                                <th>Illustrator: <th>
                                                <td><a href="<?php echo get_term_link($article_term, 'illustrator')?>"><?php echo $article_term->name?></a></td>
                                            </tr>
                                        <?php
                                    }
                                } 

                                $translator_terms = get_the_terms(get_the_ID(), 'translator'); //Get all the Terms

                                if( !empty($translator_terms)) {
                                    foreach( $translator_terms as $key => $article_term) { //Loops through all article terms
                                        ?>
                                            <tr>
                                                <th>Translator: <th>
                                                <td><a href="<?php echo get_term_link($article_term, 'translator')?>"><?php echo $article_term->name?></a></td>
                                            </tr>
                                        <?php
                                    }
                                }

                                ?>
                                </table>
                        </div>

                        <div class="novel-cover col-lg-8 col-md-7 col-sm-12">
                            <h2>Description</h2>
                            <?php
                                the_excerpt();
                            ?>
                        </div>
                    </div>
                <?php
            endwhile;
            }
        ?>
        </div>

        <aside class="sidebar-wrap col-lg-3 d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
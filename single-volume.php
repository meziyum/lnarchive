<?php
/**
 * Volume Template
 * 
 * @package LNarchive
*/
get_header(); //Get the Header function

$the_post_id = get_the_ID(); //Get the Post ID
$the_post_title = get_the_title(); //Get the Title
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row"> <!-- Main Row -->
        <div class="volume-wrap col-lg-9"> <!-- Volume Content Div -->
        <?php
            if( have_posts() ) { //If there are posts
                while(have_posts()) : the_post(); //While there are posts
                    
                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( $the_post_title), //Get the Title
                    );
                    ?>

                    <div class="info-section"> <!-- Volume Info Div -->
                        <div class="row volume-row">
                            <div class="volume-cover-div col-lg-3 col-md-4 cold-sm-12">
                                <?php
                                if (has_post_thumbnail( $the_post_id )) {
                                        the_post_custom_thumbnail(
                                            $the_post_id, //The volume ID
                                            'novel-cover', //Name of the size
                                            [
                                                'class' => 'novel-cover', //Class attachment for css
                                                'alt'  => esc_html($the_post_title), //Attach the title as the default alt for the img
                                            ]
                                        );
                                    }
                                ?>
                            </div>

                            <div class="volume-info col">
                                <h4>Formats</h4>
                                <?php
                                    $format_terms = get_the_terms( get_post_meta( $the_post_id, 'series_value', true ), 'format'); //Get the formats
                                    taxonomy_button_list( $format_terms , 'format'); //List the Formats Taxonomy
                                ?>
                            </div>
                        </div>
                    </div>
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
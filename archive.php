<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Container -->
    <div class="row main-row"> <!-- Main Row -->

        <?php
        printf( //Get the Title
            '<h1 class="page-title">%1$s</h1>', //Page Title Div
            wp_kses_post( get_the_archive_title()), //Get the Title
        );
        ?>

        <div class="filter-wrap col d-none d-lg-block">
            <h3>Filters</h3>
            <?php

                $taxs = array( 'genre', 'post_tag');

                foreach( $taxs as $tax ){
                    ?>
                        <div>
                            <h5><?php echo ucfirst(get_taxonomy($tax)->label);?></h5>
                            <?php
                                $terms = get_terms( array(
                                    'taxonomy' => $tax,
                                    'hide_empty' => true,
                                ) );

                                foreach( $terms as $term ){
                                    ?>
                                        <input type="checkbox" id="<?php echo $term->name.'_checkbox';?>" name="<?php echo $term->name.'_checkbox';?>" value="<?php echo $term->term_id;?>">
                                        <label for="<?php echo $term->name.'_checkbox';?>"><?php echo $term->name;?></label><br>
                                    <?php
                                }
                            ?>
                        </div>
                    <?php
                }
            
                $taxs = array( 'novel_status', 'language', 'publisher', 'writer', 'illustrator');

                foreach( $taxs as $tax ){
                    ?>
                        <div>
                        <h5><?php echo ucfirst(get_taxonomy($tax)->label);?></h5>
                        <input list="<?php echo $tax;?>_filter" name="<?php echo $tax;?>_filter_input" id="<?php echo $tax;?>_filter_input" autocomplete="on">
                        <datalist id="<?php echo $tax;?>_filter">
                    <?php

                    $terms = get_terms( array(
                        'taxonomy' => $tax,
                        'hide_empty' => true,
                    ) );

                    foreach( $terms as $term ){
                        ?>
                            <option id="option_<?php echo $term->name?>" value="<?php echo $term->name;?>">
                                <?php echo $term->term_id?>
                            </option>
                        <?php
                    }
                    ?>
                        </datalist>
                        </div>
                    <?php
                }
            ?>
            <button id="filter-apply">Filter</button>
        </div>

        <div id="archive-wrap"> <!-- Archive Div -->
        <?php
            if(have_posts()) { //If there is post
                novel_list( $wp_query, array( 'name' => 'novel' )); //Print Novel List
            }
        ?>
        </div>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>
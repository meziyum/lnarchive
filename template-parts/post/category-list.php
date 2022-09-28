<?php
/**
 * 
 * Category List Template
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID(); //Get the ID
$article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms

if( !empty( $article_terms ) && is_array( $article_terms )){ //If its array and its not empty
    foreach( $article_terms as $key => $article_term) { //Loops through all article terms
    ?>
        <button onclick="location.href='<?php echo esc_url(get_term_link( $article_term));?>'" type="button" class="category-button"> <!-- Category Button -->
            <a class= "category-link"> <!-- The Category text -->
                <?php echo esc_html($article_term->name); //Print the article?>
            </a>
        </button>
        <?php
    }
}
?>
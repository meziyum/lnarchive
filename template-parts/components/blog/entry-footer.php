<?php
/**
 * 
 * Template for Entry Footer
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID(); //Get the ID
$article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms

if( empty( $article_terms ) || ! is_array( $article_terms )) { //Display nothing if there are no categories
    return;
}
?>

 <div class="entry-footer"> <!-- Blog Entry Footer Div -->
    <?php
        foreach( $article_terms as $key => $article_term) { //Loops through all article terms
        ?>
            <button onclick="location.href='<?php echo get_term_link( $article_term)?>'" type="button" class="category-button btn btn-success btn-sm"> <!-- Category Button -->
                <a class= "entry-footer-link text-white"> <!-- The Category text -->
                    <?php echo $article_term->name //Print the article?>
                </a>
            </button><?php
        }
    ?>
 </div>
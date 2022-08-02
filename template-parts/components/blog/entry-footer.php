<?php
/**
 * 
 * Template for Entry Footer
 * 
 * Uses the Wordpress The Loop
 * 
 * @package lnpedia
 * 
 */

$the_post_id = get_the_ID(); //Get the ID
$article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms

if( empty( $article_terms ) || ! is_array( $article_terms )) { //Display nothing if there are no categories
    return;
}
?>

 <div class="entry-footer">
    <?php
        foreach( $article_terms as $key => $article_term) { //Loops through all article terms
        ?>
            <button class="btn btn-sm btn-primary">
                <a class= "entry-footer-link text-white" href="<?php echo get_term_link( $article_term)?>">
                    <?php echo $article_term->name //Print the article?>
                </a>
            </button><?php
        }
    ?>
 </div>
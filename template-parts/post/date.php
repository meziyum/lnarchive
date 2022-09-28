<?php
/**
 * 
 * Date Template Part
 * 
 * @package LNarchive
 */

    $the_post_id = get_the_ID(); //Get the ID
    $article_terms = wp_get_post_terms( $the_post_id, ['category']); //Get all the Category terms

    if( !empty( $article_terms ) && is_array( $article_terms )) { //If there are category terms
        foreach( $article_terms as $article_term) { //Loop through all the terms
            if( get_term_meta( $article_term->term_id, 'date_visible_value', true) == 'yes') { //If Date Visibility is set to yes
                
                $time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>'; //Define the time string if the published date is the modified date

                //Define the time string if the post was modified
                if( get_the_time('U') !== get_the_modified_time('U')) {
                    $time_string = '<time class="entry-date d-none" datetime="%1$s">%2$s</time><time class="entry-date" datetime="%3$s">%4$s</time>';
                }

                //Modifying the string to store the values
                $time_string = sprintf( $time_string,
                    get_the_date( DATE_W3C ), //Get the exact publication date with time
                    get_the_date(), //Get the normal publication date
                    get_the_modified_date( DATE_W3C ), //Get the detailed modification date
                    get_the_modified_date() //Get the general modification date
                );

                //The final string to display
                $posted_on = sprintf(
                    '%s',
                    '<a rel="bookmark">' . $time_string . '</a>' //Argument 1
                );

                //Display the date
                echo '<h6 class="posted-on">' .$posted_on . '</h6>'; //Output the Date

                break; //Break the Loop
            }
        }
    }
?>
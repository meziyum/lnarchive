<?php
/**
 * 
 * Date Template Part
 * 
 * @package LNarchive
 */

    $the_post_id = get_the_ID();
    $article_terms = wp_get_post_terms( $the_post_id, ['category']);

    if( !empty( $article_terms ) && is_array( $article_terms )) {
        foreach( $article_terms as $article_term) {
            if( get_term_meta( $article_term->term_id, 'date_visible_value', true) == 'yes' || !(is_single() && get_post_type( $the_post_id ) == "post"))  {
                
                $time_string = '<time class="entry-date" datetime="%1$s">%2$s</time>';

                if( get_the_time('U') !== get_the_modified_time('U')) {
                    $time_string = '<time class="entry-date d-none" datetime="%1$s">%2$s</time><time class="entry-date" datetime="%3$s">%4$s</time>';
                }

                $time_string = sprintf( $time_string,
                    get_the_date( DATE_W3C ),
                    get_the_date(),
                    get_the_modified_date( DATE_W3C ),
                    get_the_modified_date()
                );

                $posted_on = sprintf(
                    '%s',
                    '<a rel="bookmark">' . $time_string . '</a>'
                );

                echo '<h6 class="posted-on">' .$posted_on . '</h6>';

                break;
            }
        }
    }
?>
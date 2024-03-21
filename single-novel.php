<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header();

$the_post = get_post();
$the_post_id = get_the_ID();
$the_post_title = get_the_title();
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row">
        <div class="novel-wrap">
        <?php               
            if( have_posts() ) {
            while( have_posts(  )) : the_post();
                printf(
                    '<h1 id="page-title">%1$s</h1>',
                    wp_kses_post($the_post_title),
                );
                ?>
                    <section id="info-section">
                        <div class="row novel-row">
                            <div class="novel-info-left col-lg-4 col-md-4 col-sm-12 col-12">
                                <div id="volume-cover">
                                    <?php
                                        the_post_custom_thumbnail(
                                            $the_post_id,
                                            'novel-cover',
                                            [
                                                'class' => 'novel-cover',
                                                'alt'  => esc_html($the_post_title),
                                            ]
                                        );
                                    ?>
                                </div>
                                <table>
                                    <?php
                                        $taxs = get_object_taxonomies('novel', 'names');
                                        foreach( $taxs as $tax) {
                                            if(get_option('novel-display-'.$tax)) {
                                                $terms = get_the_terms($the_post_id, $tax);

                                                if( is_array($terms)){
                                                    $tax_label_name = esc_html(get_taxonomy_labels(get_taxonomy($tax))->name);
                                                    ?>
                                                        <tr id="<?php echo $tax_label_name.'_row'?>">
                                                            <th><?php
                                                                    echo $tax_label_name;
                                                                ?>
                                                            </th>
                                                            <td id="<?php echo esc_attr($tax).'_info_value';?>">
                                                                <?php
                                                                    foreach( $terms as $key => $article_term) {
                                                                        ?>
                                                                            <a href="<?php echo esc_attr(get_post_type_archive_link('novel')).'?'.$tax.'_filter'.'='.$article_term->term_id?>"><?php echo esc_html($article_term->name)?></a>
                                                                            <br>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                    <tbody id="volume-info"></tbody>
                                </table>
                            </div>

                            <div class="novel-info-right col">
                                <div id="novel-actions"></div>
                                <div id="volume-desc"></div>
                                <div id="formats-list"></div>
                                <?php

                                $alt_names = get_post_meta( $the_post_id, 'alternate_names_value', true );
                                $alt_names_array = explode( ",", $alt_names );

                                if( !empty( $alt_names_array[0] )){
                                ?>
                                    <h4>Alternate Names</h4> 
                                    <p>
                                        <?php
                                            foreach( $alt_names_array as $alt) {
                                                ?>
                                                    <?php echo $alt;?>
                                                    <br>
                                                <?php
                                            }
                                        ?>
                                    </p>
                                    <?php
                                }                           

                                $genre_terms = get_the_terms($the_post_id, 'genre');
                                $tag_terms = get_the_terms($the_post_id, 'post_tag');

                                if( !empty( $genre_terms )) {
                                    ?><h3>Genre</h3><?php
                                    taxonomy_button_list( 'novel', $genre_terms , 'genre');
                                }

                                if( !empty( $tag_terms ) && $tag_terms[0]->name != 'None'){
                                    ?><h3>Tag</h3><?php
                                    taxonomy_button_list( 'novel', $tag_terms, 'tags');
                                }

                                get_template_part('template-parts/edit-btn');
                                ?>
                            </div>
                        </div>
                    </section>                       
                <?php
                    get_template_part('template-parts/novel/volumeList');
                    get_template_part('template-parts/novel/universeNovels');
                    get_template_part('template-parts/novel/similarNovels');
                    get_template_part('template-parts/novel/relatedPosts');
                    ?>
                        <section id="reviews-section"/>
                    <?php
            endwhile;
            }
        ?>
        </div>
    </div>
</main>

<?php get_footer();?>
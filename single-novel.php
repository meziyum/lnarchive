<?php
/**
 * Novel template
 * 
 * @package LNarchive
*/
get_header();

$the_post = get_post();
$the_post_id = get_the_ID();
$the_post_type = get_post_type( $the_post_id );
$max_posts = get_option('posts_per_page');
$volume1_args = array(
    'post_type' => 'volume',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'ASC',
    'meta_key' => 'series_value',
    'meta_value' => $the_post_id,
);                       
$volume1 = get_posts($volume1_args);
$has_volume1 = !empty($volume1) ? true : false;
$the_post_title = $has_volume1 ? $volume1[0]->post_title : get_the_title();
if( $has_volume1) {
    $volume1_id = $volume1[0]->ID;
    $formats = get_the_terms($volume1_id, 'format');
}
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr($the_post_id);?>" class="row main-row">
        <div class="novel-wrap col-lg-9">
        <?php               
            if( have_posts() ) {
            while( have_posts(  )) : the_post();
                
                //Title
                printf(
                    '<h1 class="page-title">%1$s</h1>',
                    wp_kses_post( $the_post_title ),
                );
                ?>
                    <section id="info-section">
                        <div class="row novel-row">
                            <div class="novel-info-left col-lg-4 col-md-4 col-sm-12 col-12">
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
                                <table>
                                    <?php
                                        $taxs = array('novel_status', 'publisher','writer', 'illustrator', 'translator','narrator');

                                        foreach( $taxs as $tax) {

                                            if( taxonomy_exists($tax) ){
                                                $terms = get_the_terms($the_post_id, $tax);
                                                if( empty($terms) && $has_volume1)
                                                    $terms = get_the_terms($volume1_id, $tax);
                                            }

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
                                                                        <a href="<?php echo esc_attr(get_post_type_archive_link('novel')).'?'.$tax.'_filter'.'='.$article_term->name?>"><?php echo esc_html($article_term->name)?></a>
                                                                        <br>
                                                                    <?php
                                                                }       
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                            }
                                        }
                                    
                                    if( $has_volume1){
                                        ?>
                                        <tr>
                                            <th>
                                                ISBN
                                            </th>
                                            <td id="ISBN_info_value">
                                                <a><?php echo get_metadata( 'post', $volume1_id, 'isbn_'.$formats[0]->name.'_value')[0];?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Publication Date                                         
                                            </th>
                                            <td id="Publication Date_info_value">
                                            <a><?php echo get_metadata( 'post', $volume1_id, 'published_date_value_'.$formats[0]->name)[0];?></a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>

                            <div class="novel-info-right col">
                                <div id="novel-actions"></div>
                                <h2>Description</h2><?php
                                if( $has_volume1){
                                echo '<div id="novel-excerpt">'.apply_filters('the_content', ($volume1[0]->post_excerpt)).'</div>';
                                ?>
                                    <ul id="format_info_value" class="d-flex justify-content-center">
                                        <?php
                                            for( $i=0; $i<count($formats); $i++){
                                                $format_name = $formats[$i]->name;
                                                ?>
                                                    <button 
                                                    id="<?php echo esc_attr($format_name.'-format');?>" 
                                                    class="format-button <?php if( $i==0 )
                                                            echo "selected-format";?>" 
                                                    isbn="<?php echo get_metadata( 'post', $volume1_id, 'isbn_'.$format_name.'_value' )[0];?>"
                                                    publication_date="<?php echo get_metadata( 'post', $volume1_id, 'published_date_value_'.$format_name)[0];?>"
                                                    >
                                                        <?Php echo esc_html($format_name);?>
                                                    </button>
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                <?php
                                }

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
                                    taxonomy_button_list( $genre_terms , 'genre');
                                }

                                if( !empty( $tag_terms ) && $tag_terms[0]->name != 'None'){
                                    ?><h3>Tag</h3><?php
                                    taxonomy_button_list( $tag_terms, 'tags');
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
        <div class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-novel');?>
        </div>
    </div>
</main>

<?php get_footer();?>
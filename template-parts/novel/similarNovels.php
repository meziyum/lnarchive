<?php
/**
 * Similar Novels Template Part
 * 
 * @package LNarchive
 */

$the_post_id = get_the_ID();
$name='similar';
$novel_no=6;
$similar_novels = get_post_meta($the_post_id, 'similar_novels', true);

if ($similar_novels != '') {
    ?>
        <section id="related-section" class="novels-list-section">
            <h2>Similar Novels</h2>
            <div class="row novel-list" id="<?php echo $name;?>-list">
                <?php            
                    foreach ($similar_novels as $novel_id) {
                        novel_item($novel_id, $name, true);
                        --$novel_no;
                    }
                ?>
            </div>
        </section>
    <?php
}
?>
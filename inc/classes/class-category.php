<?php
/**
 * Category Class
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class category {
  use Singleton;

  protected function __construct() {
    $this->set_hooks();
  }

  protected function set_hooks() {
    add_action( 'category_add_form_fields', [$this, 'category_add_meta'] );
    add_action( 'category_edit_form_fields', [$this, 'category_edit_meta'] );
    add_action( 'created_category', [$this,'category_save_meta'] );
    add_action( 'edited_category', [$this, 'category_save_meta'] );
  }

  private function category_add_meta() {

    wp_nonce_field( 'category_meta_nonce_action', 'category_meta_nonce' );

    echo '<div class="form-field date-visible">
      <label for="date_visible">Should the Date be Visible(in post):</label>
      <select name="date_visible" id="date_visible">
        <option value="yes">Yes</option>
        <option value="no">No</option>
      </select>
    </div>';
  }

  private function category_edit_meta( $term ) {
    $value1 = get_term_meta( $term->term_id, 'date_visible_value', true);

    wp_nonce_field( 'category_meta_nonce_action', 'category_meta_nonce');

    ?>
    '<tr class="form-field date-visible">
      <th>
        <label for="date_visible">Should the Date be Visible(in post):</label>
      </th>
      <td>
        <select name="date_visible" id="date_visible">
          <option <?php if($value1=="yes") echo 'selected';?> value="yes">Yes</option>
          <option <?php if($value1=="no") echo 'selected';?> value="no">No</option>
        </select>
      </td>
    </tr>
    <?php
  }

  private function category_save_meta( $term_id ) {
    if ( ! isset( $_POST['category_meta_nonce'] ) || ! wp_verify_nonce( $_POST['category_meta_nonce'], 'category_meta_nonce_action')) {
      return;
    }

    if ( !current_user_can( 'manage_categories') ) {
      return;
    }

    update_term_meta(
      $term_id,
      'date_visible_value',
      sanitize_text_field( $_POST['date_visible'],),
    );
  }
}
?>
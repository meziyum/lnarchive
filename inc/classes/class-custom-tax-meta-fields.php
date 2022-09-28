<?php
/**
 * Custom Taxonomies/Taxonomies Meta Fields
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class custom_tax_meta_fields{ //Custom Taxonomies Meta Fields Class

    use Singleton; //Using Sinlgeton

    private $meta_title_length; //Define the private Meta-title max length
    private $meta_desc_length; //Define the private Meta-Desc Max length

    protected function __construct(){ //Constructor

      //Load Class
      $this->set_hooks(); //Loading the hooks

      $this->meta_title_length=60; //Assign the values to the max meta-title length
      $this->meta_desc_length=155; //Assign the values to the max meta-desc length
    }

    protected function set_hooks() { //Hooks function

      //Array for which to include the seo terms
      $taxs = array(
        'publisher',
        'translator',
        'writer',
        'artist',
        'illustrator',
      );

      /**
      * Actions
      */

      //Adding functions to the hooks

      //Add Cateogry Meta Fields
      add_action( 'category_add_form_fields', [$this, 'category_add_meta'] );
      add_action( 'category_edit_form_fields', [$this, 'category_edit_meta'] );
      add_action( 'created_category', [$this,'category_save_meta'] );
      add_action( 'edited_category', [$this, 'category_save_meta'] );


      foreach ( $taxs as $tax ) { //Loop through all the taxonomies with the seo terms
        add_action( $tax.'_add_form_fields', [ $this, 'seo_meta_add_terms'] );
        add_action( $tax.'_edit_form_fields', [ $this, 'seo_meta_edit_terms']);
        add_action( 'created_'.$tax, [$this,'seo_meta_save_terms'] );
        add_action( 'edited_'.$tax, [$this, 'seo_meta_save_terms'] );
      }
    }

    function category_add_meta() {

      //Register Nonce
      wp_nonce_field( 'category_meta_nonce_action', 'category_meta_nonce' );

      //Date Visibility
      echo '<div class="form-field date-visible">
        <label for="date_visible">Should the Date be Visible(in post):</label>
        <select name="date_visible" id="date_visible">
          <option value="yes">Yes</option>
          <option value="no">No</option>
        </select>
      </div>';
    }

    function category_edit_meta( $term ) { //Edit Meta terms in Categories

      //Get the Meta Term values for the Categories
      $value1 = get_term_meta( $term->term_id, 'date_visible_value', true);

      //Nonce Register
      wp_nonce_field( 'category_meta_nonce_action', 'category_meta_nonce');

      //Date Visibility
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

    function category_save_meta( $term_id ){ //Save Meta terms to Categories

      // Nonce Verification
      if ( ! isset( $_POST['category_meta_nonce'] ) || ! wp_verify_nonce( $_POST['category_meta_nonce'], 'category_meta_nonce_action')) {
        return;
      }

      //If the user doesnt have manage_categories capability
      if ( !current_user_can( 'manage_categories') ) {
        return;
      }

      update_term_meta( //Update the Date Visibility Value
        $term_id, //ID of the object
        'date_visible_value', //The key
        sanitize_text_field( $_POST['date_visible'],), //Get the Value
      );

    }

    function seo_meta_add_terms() { //Add SEO Meta terms to Custom Taxonomies

      //Nonce Register
      wp_nonce_field( 'seo_nonce_action', 'seo_nonce' );

      //Meta Title Input
      echo '<div class="form-field meta-title">
        <label for="tax_meta_title">Meta Title</label>
        <input type="text" name="tax_meta_title" id="tax_meta_title" maxlength="'.$this->meta_title_length.'"/>
        <p>The meta title for SEO purposes. Max Characters('.$this->meta_title_length.')</p>
      </div>';

      //Meta Desc Input
      echo '<div class="form-field meta-desc">
        <label for="tax_meta_desc">Meta Description</label>
        <textarea name="tax_meta_desc" id="tax_meta_desc" rows="4" maxlength="'.$this->meta_desc_length.'"></textarea>
        <p>The description for SEO purposes. Max Characters('.$this->meta_desc_length.')</p>
      </div>';
    }

    function seo_meta_edit_terms( $term ) { //Edit SEO Meta terms of Custom Taxonomies

      //Get the current stored values in the meta-title and meta-desc from the database
      $value1 = get_term_meta( $term->term_id, 'tax_meta_title_val', true);
      $value2 = get_term_meta( $term->term_id, 'tax_meta_desc_val', true);

      //Nonce Register
      wp_nonce_field( 'seo_nonce_action', 'seo_nonce' );

      //Meta Title Edit
      echo '<tr class="form-field meta-title">
        <th>
          <label for="tax_meta_title">Meta Title</label>
        </th>
        <td>
          <input name="tax_meta_title" id="tax_meta_title" maxlength="'.$this->meta_title_length.'" type="text" value="'.$value1.'" />
          <p>The meta title for SEO purposes. Max Characters('.$this->meta_title_length.')</p>
        </td>
      </tr>';

      //Meta Desc Edit
      echo '<tr class="form-field meta-desc">
        <th>
          <label for="tax_meta_desc">Meta Description</label>
        </th>
        <td>
          <textarea name="tax_meta_desc" id="tax_meta_desc" rows="4" maxlength="'.$this->meta_desc_length.'">'.$value2.'</textarea>
          <p>The description for SEO purposes. Max Characters('.$this->meta_desc_length.')</p>
        </td>
      </tr>';
    }

    function seo_meta_save_terms( $term_id ) { //Save SEO Meta terms

      // Nonce Verification
      if ( ! isset( $_POST['seo_nonce'] ) || ! wp_verify_nonce( $_POST['seo_nonce'], 'seo_nonce_action')) {
        return;
      }

      //If the user doesnt have manage_categories capability
      if ( ! current_user_can( 'manage_categories') ) {
        return;
      }

      //Meta Title
      if( empty($_POST[ 'tax_meta_title'])){ //IF the meta-title empty
        update_term_meta( //Add title as meta-title by default
          $term_id, //ID of the object
            'tax_meta_title_val', //The key
            sanitize_text_field($this->get_title_value()), //Get the title value from the forms
        );
      } else { //If the meta-title is set
        update_term_meta( //Update the value
          $term_id, //ID of the object
          'tax_meta_title_val', //The key
          sanitize_text_field( $_POST[ 'tax_meta_title'],), //Getting the input from textbox
        );
      }

      //Meta-Desc
      if( empty($_POST[ 'tax_meta_desc'])){ //IF the meta-desc empty
        update_term_meta( //Add title as meta-desc by default
          $term_id, //ID of the object
          'tax_meta_desc_val', //The key
          sanitize_text_field( $_POST['description'],), //The feault value fetched from the taxonomy description
        );
      } else {
        update_term_meta(
          $term_id, //The ID of the object
          'tax_meta_desc_val', //The key
          sanitize_text_field( $_POST[ 'tax_meta_desc'],) //Getting the input from the textarea
        );
      }
    }

    function get_title_value() { //Function to get the title value from the form
      if( empty($_POST['tag-name']) ) //If the post-name form is empty
        return sanitize_text_field( $_POST['name'],); //return name form value
      else if( empty($_POST['name']) ) //If the name form is empty
        return sanitize_text_field( $_POST['tag-name'],); //return the post-name value
    }

}//End of Class
?>
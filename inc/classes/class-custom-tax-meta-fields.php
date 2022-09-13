<?php
/**
 * Custom Taxonomies Meta Fields
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
          'series',
        );

        /**
          * Actions
          */

        //Adding functions to the hooks

        foreach ( $taxs as $tax ) { //Loop through all the taxonomies with the seo termss
          add_action( $tax.'_add_form_fields', [ $this, 'seo_meta_add_terms'] );
          add_action( $tax.'_edit_form_fields', [ $this, 'seo_meta_edit_terms']);
          add_action( 'created_'.$tax, [$this,'seo_meta_save_terms'] );
          add_action( 'edited_'.$tax, [$this, 'seo_meta_save_terms'] );
        }
    }

    function seo_meta_add_terms() { //Add SEO Meta terms to Custom Taxonomies

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
        echo '<tr class="form-field meta-title">
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

        //Meta Title
        if( empty($_POST[ 'tax_meta_title'])){ //IF the meta-title empty
            update_term_meta( //Add title as meta-title by default
                $term_id, //ID of the object
                'tax_meta_title_val', //The key
                $this->get_title_value(), //Get the title value from the forms
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
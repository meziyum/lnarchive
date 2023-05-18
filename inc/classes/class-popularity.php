<?php
/**
 * Popularity Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
use lnarchive\inc\ratings;

class popularity {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('init', [$this, 'register_popularity']);
        add_action('comment_post', [$this, 'popularity_update_on_comment'], 10, 3);
        add_action('before_user_rating_created', [$this, 'popularity_update_on_new_rating'], 10, 1);
    }

    public function register_popularity() {
        register_meta('post', 'popularity', array(
            'object_subtype'  => 'novel',
            'type'   => 'number',
            'single' => true,
            'default' => 0,
            'sanitize_callback' => function($value) {
                return sanitize_number_positive($value);
            },
            'show_in_rest' => true,
        ));
    }

    public function popularity_update_on_new_rating($args) {
        $post_id = $args['object_id'];
        $user_id = $args['user_id'];
        $user_rating = ratings::get_instance()->get_user_rating(array( 'post'=> $post_id, 'author' => $user_id));
        if($user_rating) {
            return;
        }
        $this->update_popularity($post_id, 5);
    }

    public function popularity_update_on_comment($comment_ID, $comment_approved, $commentdata) {
        $comment = get_comment($comment_ID);
        $post_id = $comment->comment_post_ID;
        $this->update_popularity($post_id, 5);
    }

    public function update_popularity($post_id, $gain) {
        if(get_post_type($post_id) != 'novel') {
            return;
        }

        $value = get_post_meta($post_id, 'popularity', true);
        if($value == '') {
            $value=0;
        }
        update_post_meta($post_id, 'popularity', $value+$gain);
    }
}
?>
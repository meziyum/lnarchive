<?php
/**
 * Contribution Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class contribution {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('after_switch_theme', [$this, 'create_datbases']);
        add_action( 'init', [$this, 'register_contribution']);
        add_action('wp_insert_comment', [$this, 'contribution_update_on_comment_add'], 10, 2);
        add_action('delete_comment', [$this, 'contribution_update_on_comment_delete'], 10, 2);
        add_action('user_rating_submitted', [$this, 'contribution_points_on_rating'], 1);
        add_action('wp_insert_post', [$this, 'contribution_points_on_post_type_create'], 10, 3);
        add_action('before_delete_post', [$this, 'contribution_points_on_post_type_delete'], 10, 2);
    }

    function register_contribution($user_id){
        register_meta('user', 'contribution_points', array(
            'type' => 'number',
            'description' => 'Total Contribution Points of a User',
            'single' => true,
            'sanitize_callback' => function ($value) {
                return sanitize_number_positive($value);
            },
            'show_in_rest' => true,
        ));
    }

    public function contribution_update_on_comment_add($id, $comment) {
        $user_id = $comment->user_id ;
        $args = array(
            'object_id' => $id,
            'user_id' => $user_id,
        );
        $this->update_contribution($args, 'comment', 10);
    }

    public function contribution_update_on_comment_delete($comment_id, $comment) {
        $user_id = $comment->user_id ;
        $args = array(
            'object_id' => $comment_id,
            'user_id' => $user_id,
        );
        $this->update_contribution($args, 'comment', -10);
    }

    function contribution_points_on_rating($args) {
        $this->update_contribution($args, 'rating', 5);
    }
    
    function contribution_points_on_post_type_create($post_id, $post, $update) {
        if ($update) {
            return;
        }
        
        if ($post->post_type == 'revision') {
            return;
        }
        $args = array(
            'object_id' => $post_id,
            'user_id' => $post->post_author,
        );
        $this->update_contribution($args, 'create', 100);
    }

    function contribution_points_on_post_type_delete($postid, $post) {
        $args = array(
            'object_id' => $postid,
            'user_id' => $post->post_author,
        );
        $this->update_contribution($args, 'create', -100);
    }

    function update_contribution($args, $type, $change_value) {
        $object_id = $args['object_id'];
        $user_id = $args['user_id'];
        $args = array(
            'object_id' => $object_id,
            'user_id' => $user_id,
            'type' => $type,
        );
        $contribution_exists = $this->contribution_exists($args);

        if ($contribution_exists && $change_value>0) {
            return;
        }

        if (!$contribution_exists && $change_value<0) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'user_contributions';

        if ($contribution_exists && $change_value<0) {
            $wpdb->delete($table_name, array('object_id' => $object_id, 'user_id' => $user_id, 'contribution_type' => $type));
        } else if (!$contribution_exists && $change_value>0) {
            $wpdb->insert($table_name, array('object_id' => $object_id, 'user_id' => $user_id, 'contribution_type' => $type));
        }
        
        $value = get_user_meta($user_id, 'contribution_points', true);
        if ($value == '') {
            $value=0;
        }
        $new_value = $value+$change_value;

        if ($new_value<=0) {
            delete_user_meta($user_id, 'contribution_points');
        } else {
            update_user_meta($user_id, 'contribution_points', $new_value);
        }
    }

    function contribution_exists($contribution) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_contributions';
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE object_id = %d AND user_id = %d AND contribution_type = %s",
                $contribution['object_id'],
                $contribution['user_id'],
                $contribution['type']
            )
        );

        if($exists) {
            return true;
        }
        return false;
    }

    function create_datbases() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $contribution_table_name = $wpdb->prefix . 'user_contributions';

        if ($wpdb->get_var("SHOW TABLES LIKE '$contribution_table_name'") !== $contribution_table_name) {
            $contribution_query = "CREATE TABLE " . $contribution_table_name . " (
            contribution_id bigint(20) NOT NULL AUTO_INCREMENT,
            object_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            contribution_type VARCHAR(10) NOT NULL,
            PRIMARY KEY (contribution_id),
            FOREIGN KEY (object_id) REFERENCES {$wpdb->prefix}posts(ID),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
            ) $charset_collate;";
            
            dbDelta([$contribution_query], true);
        }
    }
}
?>
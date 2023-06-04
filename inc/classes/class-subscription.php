<?php
/**
 * Subscription Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class subscription {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('after_switch_theme', [$this, 'create_datbases']);
        add_action('publish_post', [$this, 'on_post_publish'], 10, 3);
    }

    function on_post_publish($post_id, $post, $old_status) {
        $post_title = $post->post_title;
        $novel_id = get_post_meta($post_id, 'series_value', true);
        $users = $this->get_subscribed_users($novel_id);
        $user_emails = array();

        foreach($users as $user) {
            global $wpdb;
            $user_email = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT user_email FROM wp_users WHERE ID = %d",
                    $user->user_id,
                )
            );
            array_push($user_emails);
            error_log($user_email);
        }
        $this->send_email_notification($user_emails, $post_title);
    }

    function send_email_notification($subscriber_emails, $title) {
        $subject = $title;
        $message = 'This is a test email sent from WordPress.';
        $response = wp_mail($subscriber_emails, $subject, $message);
        error_log($response);
    }

    function get_subscribed_users($object_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_subscriptions';
        $users = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT user_id FROM $table_name WHERE object_id = %d",
                $object_id
            )
        );
        return $users;
    }

    function create_datbases() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $subscription_table_name = $wpdb->prefix . 'user_subscriptions';

        if ($wpdb->get_var("SHOW TABLES LIKE '$subscription_table_name'") !== $subscription_table_name) {
            $subscription_query = "CREATE TABLE " . $subscription_table_name . " (
            subscription_id bigint(20) NOT NULL AUTO_INCREMENT,
            object_id bigint(20) UNSIGNED NOT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            PRIMARY KEY (subscription_id),
            FOREIGN KEY (object_id) REFERENCES {$wpdb->prefix}posts(ID),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->prefix}users(ID)
            ) $charset_collate;";
            
            dbDelta([$subscription_query], true);
        }
    }
}
?>
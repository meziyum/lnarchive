<?php
/**
 * Template Name: Profile
 * 
 * @package LNarchive
 */
get_header();

$user = wp_get_current_user();
$user_id = $user->ID;
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr(get_the_ID());?>" class="main-row">
        <div class="profile-wrap">
            <?php
                if( have_posts(  ) ) {
                    while( have_posts(  )) : the_post();
                        printf(
                            '<h1 id="page-title">%1$s</h1>',
                            'User Profile',
                        );
                    endwhile;
                }
            ?>
            <div id="header">
                <div id="avatar-div">
                    <img id="avatar" alt="Avatar" src="<?php echo esc_attr(get_avatar_url($user_id));?>"></img>
                    <h2><?php echo esc_html($user->display_name);?></h2>
                </div>
            </div>
            <div id="profile-section"></div>
        </div>
    </div>
</main>

<?php get_footer();?>
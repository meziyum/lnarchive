<?php
/**
 * Header Navigation Template
 * 
 * @package LNarchive
 */

use lnarchive\inc\menus;
$menu_class = menus::get_instance();

$header_menu_id = $menu_class->get_menu_id('lnarchive_primary');
$header_menus = wp_get_nav_menu_items( $header_menu_id );
?>

<nav class="header-nav navbar navbar-expand-lg">
  <div class="container">
 
    <div class="navbar-brand">
    <?php
      if( function_exists( 'the_custom_logo')) {
          the_custom_logo();
      }
    ?>
    </div>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse row" id="navbarSupportedContent">
      <div id="profile-menu" class="col-lg-1 order-lg-3">
        <?php
          if( is_user_logged_in() ) {
            $page_args = array(
              'post_type' => 'page',
              'fields' => 'ids',
              'meta_query' => array(
                  array(
                      'key' => '_wp_page_template',
                      'value' => 'page-templates/profile.php',
                  ),
              ),
            );
            $pages = get_posts($page_args);
            $page_slug = '';

            if(!empty($pages)) {
              $page_slug = get_post_field('post_name', $pages[0]);
            }
            
            $avatar_args = array(
              'height' => 96,
              'width' => 96,
              'force_default' => false,
              'rating' => 'X',
              'class' => 'profile-pic',
              'force_display' => true,
              'loading' => 'eager',
              'extra_attr' => '',
            );
            ?>
              <a href="<?php echo home_url().'/'.$page_slug;?>">
                <?php
                  echo get_avatar( 
                  get_current_user_id(), 
                  96,
                  '',
                  'Profile Pic',
                  $avatar_args,
                  );
                ?>
              </a>
            <?php
          }
          else {
            ?>
              <a id="login-button" href="<?php echo esc_url(wp_login_url());?>">Log In</a>
            <?php
          }
        ?>
      </div>

      <div id="main-search" class="col-lg-4 col-md-12 order-lg-2"></div>

      <div class="page-list col-lg-7 col-md-12 order-lg-1">
        <?php
          if( ! empty( $header_menus) && is_array( $header_menus )){
            ?>
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php
                  foreach ( $header_menus as $menu_item){
                    if( ! $menu_item->menu_item_parent) {

                      $child_menu_items = $menu_class->get_child_menu_items( $header_menus, $menu_item->ID);
                      $has_children = !empty( $child_menu_items) && is_array( $child_menu_items);

                      if( !$has_children) {
                        ?>
                          <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="<?php echo esc_url( $menu_item->url );?>">
                              <?php echo esc_html( $menu_item->title); ?>
                            </a>
                          </li>
                        <?php
                      } else {
                        ?>
                        <li class="nav-item dropdown">
                          <a class="nav-link dropdown-toggle" href="<?php echo esc_url( $menu_item->url );?>" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo esc_html( $menu_item->title); ?>
                          </a>
                          
                          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <?php
                                foreach( $child_menu_items as $child_menu_item) {
                                  ?>
                                    <li><a class="dropdown-item" href="<?php echo esc_url( $child_menu_item->url)?>">
                                      <?php echo esc_html( $child_menu_item->title); ?>
                                    </a></li>
                                    <?php
                                }
                                ?>
                          </ul>
                        </li><?php
                      }
                    }
                  }?>
              </ul><?php
          }
        ?>
      </div>
    </div>
  </div>
</nav>
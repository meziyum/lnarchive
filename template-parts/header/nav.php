<?php
/**
 * Header Navigation Template
 * 
 * @package LNarchive
 */

use lnarchive\inc\menus;
$menu_class = menus::get_instance(); //Intiate the Class

$header_menu_id = $menu_class->get_menu_id('fusfan_primary'); //Get the menu id of the primary menu
$header_menus = wp_get_nav_menu_items( $header_menu_id ); //Get all the menu_items of the primary menu
?>

<nav class="header-nav navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
 
    <a class="navbar-brand">
    <?php //The Custom Logo
      if( function_exists( 'the_custom_logo')) { //If there is a custom logo
          the_custom_logo(); //Display custom logo
      }
    ?></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse row" id="navbarSupportedContent">

    <div class="profile d-flex col-lg-1 order-lg-3 d-flex justify-content-center">

      <?php
        if( is_user_logged_in() ) {

          $args = array(
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
              
          <div class="dropdown">
            <a href="#" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
              <?php
                echo get_avatar( 
                get_current_user_id(), 
                96,
                '',
                'Profile Pic',
                $args,
                );
              ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item" href="#">Action</a></li>
                <li><a class="dropdown-item" href="#">Another action</a></li>
            </ul>
          </div>
          <?php
        }
      ?>
      </div>

    <div class="page-list col-lg-8 col-md-12 order-lg-1">
      <?php
        if( ! empty( $header_menus) && is_array( $header_menus )){
          ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <?php
                foreach ( $header_menus as $menu_item){
                  if( ! $menu_item->menu_item_parent) {

                    $child_menu_items = $menu_class->get_child_menu_items( $header_menus, $menu_item->ID);
                    $has_children = !empty( $child_menu_items) && is_array( $child_menu_items); //Boolean Value if menu has submenus

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
      
      <div class="d-flex col-lg-3 col-md-12 order-lg-2"> <!--Search Form -->
        <?php get_search_form();//Get the Search Form?> 
      </div>

    </div>
  </div>
</nav>
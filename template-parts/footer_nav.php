<?php
/**
 * Footer Navigation Template
 * 
 * @package LNarchive
 */

use lnarchive\inc\menus; //Menus Namespace
$menu_class = menus::get_instance(); //Intiate the Class
?>

<nav class="footer-nav text-center text-lg-start text-white"> <!-- Site Footer -->

  <section class="footer-social"> <!-- Social Media Section -->

    <div class="container d-flex justify-content-between p-4">
      
      <div class="me-5"> <!-- Social Text -->
        <span>Get connected: </span>
      </div>

      <div> <!-- Social List Div -->
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-discord"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-reddit"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-quora"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-pinterest"></i>
        </a>
        <a href="" class="social-icon text-white me-4">
          <i class="fa-brands fa-linkedin"></i>
        </a>
      </div>
    </div>
  </section>
  
  <section class="main-footer container text-center text-md-start mt-4"> <!-- Main Footer Section-->

    <div class="row mt-3"> <!-- Main Row-->

      <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-2"> <!-- Site Info Area -->

        <div class="footer-logo d-flex justify-content-center"> <!-- Logo -->
          <?php //The Custom Logo
            if( function_exists( 'the_custom_logo')) { //If there is a custom logo
              the_custom_logo(); //Display custom logo
            }
          ?>
        </div>

        <address> <!-- Addresss Element -->
          <h6 class="d-flex justify-content-center text-uppercase navbar-text fw-bold m-0 d-inline-block mx-auto">Contact Us</h6> <!-- Text -->
          <h6 class="d-flex justify-content-center align-items-center navbar-text m-0"><i class="fas fa-envelope me-2"></i><a href = "mailto: <?php echo esc_html(get_option('admin_email'))?>"><?php echo esc_html(get_option('admin_email'));?></a></h6> <!-- Mail -->
        </address>

      </div>

      <?php

        $footers=array('footer_primary','footer_secondary','footer_tertiary'); //ALl Footer ids

        foreach( $footers as $footer) { //Loop through all the footers
        ?>
          <nav class="col-md-3 col-lg-3 col-xl-3 mx-auto mb-2">

            <h6 class="footer-menu-name navbar-text text-uppercase fw-bold mb-0"><?php echo esc_html(wp_get_nav_menu_name($footer));?></h6> <!-- Menu Name -->
            <hr class="footer-separator d-inline-block mx-auto mb-2 mt-0"/> <!-- Separator -->
            
            <ul class="navbar-nav flex-column "> <!-- Footer Menu -->
            <?php

              $menus = wp_get_nav_menu_items( $menu_class->get_menu_id($footer)); //Get the menu items

              if( ! empty( $menus ) && is_array( $menus )){ //Check if there are items in menu
                foreach( $menus as $menu_item) { //Loop through all menu items
                  ?>
                    <li class="nav-item"> <!-- Nav Item -->
                    <a class="nav-link active" aria-current="page" href="<?php echo esc_url($menu_item->url);?>">
                      <?php echo esc_html($menu_item->title);?>
                    </a>
                    </li>
                  <?php
                }
              }
            ?>
            </ul> 
          </nav>
        <?php
        }
      ?> 
    </div>
  </section>

  <!-- Copyright Section-->
  <section class="footer-copyright text-center p-3">
      © 2022 Copyright: <?php echo esc_html(get_bloginfo('name'));?>
  </section>

</nav>
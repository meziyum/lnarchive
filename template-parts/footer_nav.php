<?php
/**
 * Footer Navigation Template
 * 
 * @package LNarchive
 */

use lnarchive\inc\menus;
$menu_class = menus::get_instance();
?>

<nav class="footer-nav">
  <section class="footer-social">
    <div id="social-icons-div">
      <?php
        $socials = array(
          'discord',
          'reddit',
          'twitter',
          'instagram',
        );

        foreach($socials as $social) {
          if(get_option($social.'-link') && get_option($social.'-display')) {
            ?>
              <a href="<?php echo esc_url(get_option($social.'-link'));?>" class="social-icon text-white">
                <i class="fa-brands fa-<?php echo $social?>"></i>
              </a>
            <?php
          }
        }
      ?>
    </div>
  </section>
  
  <section class="main-footer container">
    <div class="row">

      <div class="col-md-3 col-lg-3 col-xl-3 menu-nav">

        <div class="footer-logo d-flex justify-content-center">
          <?php if( function_exists( 'the_custom_logo'))the_custom_logo();?>
        </div>

        <address>
          <h6 class="contact-us">Contact Us</h6>
          <h6 class="email"><i class="fas fa-envelope me-2"></i><a href = "mailto: <?php echo esc_html(get_option('admin_email'))?>"><?php echo esc_html(get_option('admin_email'));?></a></h6>
        </address>

      </div>

      <?php

        $footers=array('footer_primary','footer_secondary','footer_tertiary');

        foreach( $footers as $footer) {
        ?>
          <nav class="col-md-3 col-lg-3 col-xl-3 menu-nav">
            <h4><?php echo esc_html(wp_get_nav_menu_name($footer));?></h4>
            <hr class="footer-separator"/>
            <ul>
              <?php

                $menus = wp_get_nav_menu_items( $menu_class->get_menu_id($footer));

                if( ! empty( $menus ) && is_array( $menus )){
                  foreach( $menus as $menu_item) {
                    ?>
                      <li>
                        <a class="nav-link" href="<?php echo esc_url($menu_item->url);?>">
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

  <section class="footer-copyright">
      <small>© 2022 Copyright: <?php echo esc_html(get_bloginfo('name'));?></small>
  </section>
</nav>
<?php
/**
 * Search Form Template
 * 
 * @package LNarchive
 */
 ?>
 
<form class="main-search" role="search" method="get" action="<?php echo esc_url(home_url('/'));?>">
    <span class="screen-reader-text"><?php echo 'Search for';?></span>
    <input class="form-control me-1" type="search" placeholder="<?php echo 'Search'?>" aria-label="Search" name="s" value="<?php echo esc_attr(the_search_query());?>">
</form>
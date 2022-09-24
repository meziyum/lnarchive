<?php
/**
 * Search Form Template
 * 
 * @package LNarchive
 */
 ?>
 
<form role="search" method="get" action="<?php esc_url(home_url('/'));?>"> <!--Search Form -->
    <span class="screen-reader-text"><?php echo 'Search for';?></span> <!-- Screen Reader Text -->
    <input class="form-control me-1" type="search" placeholder="<?php echo 'Search'?>" aria-label="Search" name="s" value="<?php esc_attr(the_search_query());?>"> <!-- Input Field -->
    <button class="btn btn-primary " type="submit"> <!-- Search Form Submit Button -->
        <i class="fa-solid fa-magnifying-glass"></i> <!--Font awesome icon -->
    </button>
</form>
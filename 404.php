<?php
/**
 * 404 Page Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function
?>

<main id="main" role="main" class="main-content error-page row"> <!-- 404 Main -->
	<div class="col-lg-6 offset-lg-3 col-12 error-inner"> <!--Msg Column -->
		<h1>404<span>We are sorry, it seems the page which you have been looking for was not found!</span></h1> <!-- Msg -->
        <button onclick="window.location.href='<?php echo esc_url(get_home_url());?>'" class="error-back" type="button">Back to Home</button> <!-- Back to Home Button -->
	</div>
</main>

<?php get_footer();?> <!-- Get the Footer -->
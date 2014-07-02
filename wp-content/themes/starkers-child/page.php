<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>


<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<h2 class="page-title"><?php the_title(); ?></h2>
<?php endwhile; ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div class="grid">
	<div class="col-">
		<div class="module">
			<?php the_content(); ?>
		</div>
	</div>
</div>
<?php endwhile; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
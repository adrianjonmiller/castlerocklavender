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
	<h2 class="page-title"><span><?php the_title(); ?></span>
		<?php wp_nav_menu(array(
	    'container'=> 'nav',
	    'menu_id' =>'shopping-cart-menu',
	    'menu_class' =>'menu',
	    'theme_location' => 'shopping-cart',
	    'items_wrap'      => '<ul id="%1$s" class="%2$s" data-behavior="">%3$s</ul>'
		)); ?>
	</h2>
	<?php endwhile; ?>
	<div class="grid flexslider" data-behavior="flexslider">
		<ul class="slides" id="banner">
			<?php
			$args = array( 'post_type' => 'banner', 'order' => 'ASC', 'orderby' => 'menu_order' );
			$loop = new WP_Query( $args );?>
			<?php while ( $loop->have_posts() ) : $loop->the_post();?>
			<li>
				<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail('full');
					} 
				?>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div class="grid">
	<div class="col-">
		<article class="module">
			<?php the_content(); ?>
		</article>
	</div>
</div>
<?php endwhile; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
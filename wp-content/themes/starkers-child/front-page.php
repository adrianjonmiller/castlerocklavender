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
	<h2 class="page-title"><span>Organic Lavender Farm In Washington</span>
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
			<li class="slide">
				<?php echo get_the_content(); ?>
				<?php if( has_excerpt() ) { ?>
					<div class="banner-excerpt">
						<?php echo get_the_excerpt(); ?>
					</div>
				<?php	} ?>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
<div class="grid">
	<div class="col-2-3">
		<article class="module">
			<h3><?php the_title(); ?></h3>
			<?php the_content(); ?>
		</article>
	</div>
	<div class="col-1-3">
		<div class="module">
			<?php if ( is_active_sidebar( 'right-sidebar' ) ) : ?>
					<?php dynamic_sidebar( 'right-sidebar' ); ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endwhile; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
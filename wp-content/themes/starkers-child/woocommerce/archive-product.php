<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>




<h2 class="page-title"><span>Lavender Products</span>
		<?php wp_nav_menu(array(
	    'container'=> 'nav',
	    'menu_id' =>'shopping-cart-menu',
	    'menu_class' =>'menu',
	    'theme_location' => 'shopping-cart',
	    'items_wrap'      => '<ul id="%1$s" class="%2$s" data-behavior="">%3$s</ul>'
		)); ?>
</h2>



		<?php if ( have_posts() ) : ?>
<div class="sorting">
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
</div>
			
			<div class="masonry" data-behavior="masonry">
			<?php woocommerce_product_loop_start(); ?>

				

				<?php while ( have_posts() ) : the_post(); ?>

				

					<?php wc_get_template_part( 'content', 'product' ); ?>

				

				<?php endwhile; // end of the loop. ?>
				
			<?php woocommerce_product_loop_end(); ?>

			</div>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>
		

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
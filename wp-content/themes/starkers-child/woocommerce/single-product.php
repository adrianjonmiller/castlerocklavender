<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

<h2 class="page-title"><span><?php the_title(); ?></span>
		<?php wp_nav_menu(array(
	    'container'=> 'nav',
	    'menu_id' =>'shopping-cart-menu',
	    'menu_class' =>'menu',
	    'theme_location' => 'shopping-cart',
	    'items_wrap'      => '<ul id="%1$s" class="%2$s" data-behavior="">%3$s</ul>'
		)); ?>
</h2>

<div class="grid">
	<div class="col-">
		<article class="module">

				

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );
			?>
		</article>
	</div>
</div>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer' ) ); ?>
<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file 
 *
 * Please see /external/starkers-utilities.php for info on Starkers_Utilities::get_template_parts()
 *
 * @package 	WordPress
 * @subpackage 	Starkers
 * @since 		Starkers 4.0
 */
?>
<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/html-header', 'parts/shared/header' ) ); ?>

	<h2 class="page-title"><span>Lavender Recipes</span>
		<?php wp_nav_menu(array(
	    'container'=> 'nav',
	    'menu_id' =>'shopping-cart-menu',
	    'menu_class' =>'menu',
	    'theme_location' => 'shopping-cart',
	    'items_wrap'      => '<ul id="%1$s" class="%2$s" data-behavior="">%3$s</ul>'
		)); ?>
	</h2>
<?php if ( have_posts() ): ?>
	<div class="grid">
		<div class="col-1">
			<ol>
			<?php while ( have_posts() ) : the_post(); ?>
				<li>
					<h3 class="article-title"><a href="<?php esc_url( the_permalink() ); ?>" title="Permalink to <?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
					<article class="module">
						<time datetime="<?php the_time( 'Y-m-d' ); ?>" pubdate><?php the_date(); ?> <?php the_time(); ?></time> <?php comments_popup_link('Leave a Comment', '1 Comment', '% Comments'); ?>
						<?php the_content(); ?>
					</article>
				</li>
			<?php endwhile; ?>
			</ol>
		</div>
	</div>
<?php else: ?>
<h2>No posts to display</h2>
<?php endif; ?>

<?php Starkers_Utilities::get_template_parts( array( 'parts/shared/footer','parts/shared/html-footer') ); ?>
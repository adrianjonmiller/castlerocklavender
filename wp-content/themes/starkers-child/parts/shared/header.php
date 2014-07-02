<header id="header">
	<h1 id="logo"><a href="<?php echo home_url(); ?>"></a><img src="<?php echo get_stylesheet_directory_uri() ?>/images/lavender-logo.png" alt="<?php bloginfo( 'name' ); ?>"></h1>
	

	<!-- <?php bloginfo( 'description' ); ?> -->
	<!-- <?php get_search_form(); ?> -->

	<?php wp_nav_menu(array(
	    'container'=> 'nav',
	    'menu_id' =>'main-menu',
	    'menu_class' =>'menu',
	    'theme_location' => 'primary',
	    'items_wrap'      => '<ul id="%1$s" class="%2$s" data-behavior="">%3$s</ul>'
	)); ?>

	<?php dynamic_sidebar( 'primary' ); ?>

</header>
<div role="main">
<?php 

	// Woocomerce declaration
	add_theme_support( 'woocommerce' );
	
	// Modify Jquery 

	wp_deregister_script('jquery');
	wp_register_script('jquery', ("//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"), false, '1.9.0', true);
	wp_enqueue_script('jquery');

	// Register Scripts

	function starkers_script_enqueuer() {
		wp_register_script( 'jsbehave', get_stylesheet_directory_uri().'/bower_components/jsbehave/application.js', array( 'jquery' ) );
		wp_enqueue_script( 'jsbehave' );

		wp_register_script( 'masonry', get_stylesheet_directory_uri().'/bower_components/masonry/maonsry.js', array( 'jquery' ) );
		wp_enqueue_script( 'masonry' );

		wp_register_script( 'flexslider', get_stylesheet_directory_uri().'/bower_components/flexslider/jquery.flexslider-min.js', array( 'jquery' ) );
		wp_enqueue_script( 'flexslider' );

		wp_register_script( 'behaviors', get_stylesheet_directory_uri().'/js/behaviors/behaviors.js', array( 'jquery' ) );
		wp_enqueue_script( 'behaviors' );

		wp_register_style( 'dont-over-think-it', get_stylesheet_directory_uri().'/bower_components/dont_over_think_it/css/grid.css', '', '', 'screen' );
    wp_enqueue_style( 'dont-over-think-it' );

    wp_register_style( 'ionicons', get_stylesheet_directory_uri().'/bower_components/ionicons/css/ionicons.min.css', '', '', 'screen' );
    wp_enqueue_style( 'ionicons' );

    wp_register_style( 'normalize', get_stylesheet_directory_uri().'/bower_components/normalize-css/normalize.css', '', '', 'screen' );
    wp_enqueue_style( 'normalize' );

    wp_register_style( 'flexslider-css', get_stylesheet_directory_uri().'/bower_components/flexslider/flexslider.css', '', '', 'screen' );
    wp_enqueue_style( 'flexslider-css' );

    wp_register_style( 'main', get_stylesheet_directory_uri().'/styles/main.css', '', '', 'screen' );
    wp_enqueue_style( 'main' );

		wp_register_style( 'screen', get_stylesheet_directory_uri().'/style.css', '', '', 'screen' );
    wp_enqueue_style( 'screen' );
	}

	// Register Menus

	register_nav_menu( 'primary', 'Primary Menu' );
	register_nav_menu( 'secondary', 'Secondary Menu' );

	function my_register_sidebars() {
		/**
	 * Register our sidebars and widgetized areas.
	 *
	 */
		register_sidebar(
			array(
				'id' => 'primary',
				'name' => __( 'Primary' ),
				'description' => __( 'Primary sidebar.' ),
				'before_widget' => '<div id="left-widget-area" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);
	}

	add_action( 'widgets_init', 'my_register_sidebars' );

	add_action( 'widgets_init', 'create_post_type' );
	function create_post_type() {
		register_post_type( 'banner',
			array(
				'labels' => array(
					'name' => __( 'Banners' ),
					'singular_name' => __( 'Banner' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'banner'),
			)
		);
	}

	add_action('init', 'my_custom_init');
	function my_custom_init() {
		add_post_type_support( 'banner', 'thumbnail' );
		add_post_type_support( 'banner', 'excerpt' );
	}

	add_action('init', 'my_custom_init');

?>
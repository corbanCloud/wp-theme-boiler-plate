<?php	
/**
 * @package WordPress
 * @subpackage Nimbus-Boiler-Plate
 */
global $Theme;
global $Tools;
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
	<meta charset="utf-8">

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
	   Remove this if you use the .htaccess -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php the_title(); ?> | <?php bloginfo('name'); ?></title>
	
	<meta name="description" content="">

	<meta name="author" content="">
	
	<meta name="viewport" content="width=device-width">

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php wp_head(); ?>

</head>
<body <?php body_class(); ?>>
  <!--[if lt IE 7]>
	<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
  <![endif]-->

	<header role="banner" class="main-header">
		
		<div class="color-bar">
			<div class="color-bar__block color-bar__block--red"></div>
			<div class="color-bar__block color-bar__block--green"></div>
			<div class="color-bar__block color-bar__block--orange"></div>
			<div class="color-bar__block color-bar__block--blue"></div>
		</div>

		<h1 class="logo">
			<a class="" href="/">
				ShopBox 
			</a>
		</h1>

		<?php   
			/**
			* Displays a navigation menu
			* @param array $args Arguments
			*/
			$args = array(
				'theme_location' => '',
				'menu' => '',
				'container' => 'nav',
				'container_class' => 'menu-{menu-slug}-container',
				'container_id' => '',
				'menu_class' => 'menu',
				'menu_id' => '2',
				'echo' => true,
				'fallback_cb' => 'wp_page_menu',
				'before' => '',
				'after' => '',
				'link_before' => '',
				'link_after' => '',
				'items_wrap' => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
				'depth' => 0,
				'walker' => ''
			);
		
			wp_nav_menu( $args ); ?>

	</header>
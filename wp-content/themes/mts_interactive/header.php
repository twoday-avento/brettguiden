<!DOCTYPE html>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<html class="no-js" <?php language_attributes(); ?>>
<head itemscope itemtype="http://schema.org/WebSite">
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body id="blog" <?php body_class('main'); ?> itemscope itemtype="http://schema.org/WebPage">
<div class="main-container">
	<header id="site-header" role="banner" itemscope itemtype="http://schema.org/WPHeader">
		<?php if( $mts_options['mts_top_bar_show_hide'] == 1 ) { ?>
			<div class="upper-navigation">
				<div class="container clearfix">
					<?php if(!empty($mts_options['mts_breaking'] )) { ?>
						<div class="breadcrumb">
							<div class="root"><a href="<?php echo esc_url( trailingslashit( home_url() ) ); ?>"><i class="fa fa-home"></i></a></div>
							<div class="breaking">
								<?php if( $mts_options['mts_breaking'] == 1 && !empty($mts_options['mts_breaking_cat']) ) {
									$breaking_cat = implode( ",", $mts_options['mts_breaking_cat'] );
									$cat_query = new WP_Query('cat='.$breaking_cat.'&posts_per_page=10'); ?>
									<ul class="trending-slider">
										<?php if ($cat_query->have_posts()) : while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
											<li><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></li>
										<?php endwhile; endif; ?>
									</ul>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php if ( !empty($mts_options['mts_header_social']) && is_array($mts_options['mts_header_social']) && !empty($mts_options['mts_social_icon_head'])) { ?>
						<div class="header-social">
							<?php foreach( $mts_options['mts_header_social'] as $header_icons ) : ?>
								<?php if( ! empty( $header_icons['mts_header_icon'] ) && isset( $header_icons['mts_header_icon'] ) ) : ?>
									<a href="<?php print $header_icons['mts_header_icon_link'] ?>" class="header-<?php print $header_icons['mts_header_icon'] ?>"><span class="fa fa-<?php print $header_icons['mts_header_icon'] ?>"></span></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php } ?>
				</div>
			</div><!-- END #upper-navigation -->
		<?php } ?>
		<?php if ( $mts_options['mts_sticky_nav'] == '1' ) { ?>
		<div class="clear" id="catcher"></div>
		<div id="header" class="sticky-navigation">
			<?php } else { ?>
			<div id="header">
				<?php } ?>
				<div class="container">
					<div class="logo-wrap">
						<?php if ($mts_options['mts_logo'] != '') { ?>
							<?php if( is_front_page() || is_home() || is_404() ) { ?>
								<h1 id="logo" class="image-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>></a>
								</h1><!-- END #logo -->
							<?php } else { ?>
								<h1 id="logo" class="image-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_attr( $mts_options['mts_logo'] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>></a>
								</h1><!-- END #logo -->
							<?php } ?>
						<?php } else { ?>
							<?php if( is_front_page() || is_home() || is_404() ) { ?>
								<h1 id="logo" class="text-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
								</h1><!-- END #logo -->
							<?php } else { ?>
								<h1 id="logo" class="text-logo" itemprop="headline">
									<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
								</h1><!-- END #logo -->
							<?php } ?>
						<?php } ?>
					</div>

					<?php if(!empty($mts_options['mts_header_search'])) { ?>
						<div id="search-6" class="widget widget_search">
							<?php get_search_form(); ?>
						</div><!-- END #search-6 -->
					<?php } ?>

					<?php if ( $mts_options['mts_show_primary_nav'] == '1' ) { ?>
						<div id="primary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
							<a href="#" id="pull" class="toggle-mobile-menu"><?php _e('Menu','mythemeshop'); ?></a>
							<nav class="navigation clearfix mobile-menu-wrapper">
								<?php if ( has_nav_menu( 'primary-menu' ) ) { ?>
									<?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
								<?php } else { ?>
									<ul class="menu clearfix">
										<?php wp_list_pages('title_li='); ?>
									</ul>
								<?php } ?>
							</nav>
						</div>
					<?php } ?>
				</div><!--.container-->
			</div><!--#header-->
	</header>
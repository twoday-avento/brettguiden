<?php
	$sidebar = mts_custom_sidebar();
    if ( $sidebar != 'mts_nosidebar' ) {
?>
<aside id="sidebar" class="sidebar c-4-12 <?php if( is_home() ) { echo "sidebar-right"; } ?>" role="complementary" itemscope itemtype="http://schema.org/WPSideBar">
	<?php if (!dynamic_sidebar($sidebar)) : ?>
		<div id="sidebar-search" class="widget">
			<h3 class="widget-title"><?php _e('Search', 'mythemeshop'); ?></h3>
			<?php get_search_form(); ?>
		</div>
		<div id="sidebar-archives" class="widget">
			<h3 class="widget-title"><?php _e('Archives', 'mythemeshop') ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>
		<div id="sidebar-meta" class="widget">
			<h3 class="widget-title"><?php _e('Meta', 'mythemeshop') ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>
	<?php endif; ?>
</aside><!--#sidebar-->
<?php } ?>
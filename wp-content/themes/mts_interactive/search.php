<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page">
	<div class="sidebar-with-content">
		<div class="article">
			<div id="content_box">
				<h1 class="postsby">
					<span><?php _e("Søkresultat for:"); ?></span> <?php the_search_query(); ?>
				</h1>
				<?php $j = 0; if (have_posts()) : while (have_posts()) : the_post(); ?>
					<article class="latestPost excerpt  <?php echo (++$j % 3 == 0) ? 'last' : ''; ?>" itemscope itemtype="http://schema.org/BlogPosting">
						<?php mts_archive_post(); ?>
					</article><!--.post excerpt-->
				<?php endwhile; else: ?>
					<div class="no-results">
						<h2><?php _e('Ingen treff, vennligst prøv på nytt.', 'mythemeshop'); ?></h2>
						<?php get_search_form(); ?>
					</div><!--noResults-->
				<?php endif; ?>

				<?php if ( $j !== 0 ) { // No pagination if there is no posts ?>
					<?php mts_pagination(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
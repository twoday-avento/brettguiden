<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<div id="page" class="game-page">
    <div class="sidebar-with-content">
        <?php get_template_part('parts/block', 'game_page') ?>

    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>

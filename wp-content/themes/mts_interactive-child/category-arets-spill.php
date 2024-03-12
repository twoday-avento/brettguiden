<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
    <div id="page">
    <div class="sidebar-with-content">
        <div class="<?php mts_article_class(); ?>">
            <div id="content_box">
                <h3 class="featured-category-title">
                    <?php single_cat_title(); ?>
                </h3>
                <?php echo category_description(); ?>
                <?php $j = 0; if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article class="latestPost excerpt  <?php echo (++$j % 3 == 0) ? 'last' : ''; ?>">
                        <header>
                            <h2 class="title front-view-title"><a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a></h2>
                        </header>
                        <div class="front-view-content">
                            <?php echo apply_filters('the_content', get_the_content()); ?>
                        </div>
                        <?php mts_the_postinfo(); ?>
                    </article>
                <?php endwhile; endif; ?>

                <?php if ( $j !== 0 ) { ?>
                    <?php mts_pagination(); ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
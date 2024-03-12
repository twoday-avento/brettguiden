<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php get_header(); ?>
<?php if ( is_home() && !is_paged() && $mts_options['mts_post_cat'] == '1' && !empty($mts_options['mts_featured_post_cat']) ) { ?>
    <div class="big-posts">
        <?php 
            // prevent implode error
            if (empty($mts_options['mts_featured_post_cat']) || !is_array($mts_options['mts_featured_post_cat'])) {
                $mts_options['mts_featured_post_cat'] = array('0');
            }
            $i = 1;
            $post_cat = implode(",", $mts_options['mts_featured_post_cat']);
            $my_query = new WP_Query('cat='.$post_cat.'&posts_per_page=4&ignore_sticky_posts=1');
            while ($my_query->have_posts()) : $my_query->the_post();

            $featured_class = isset($i) ? ' class="latestPost excerpt big-'.$i.'"' : 'latestPost excerpt';
        ?>
        <?php $featured_image = array('');?>
        <?php if( $i == 1 ) { ?>
            <?php if ( has_post_thumbnail() ) { $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-post-1' ); } ?>
            <article<?php echo $featured_class; ?> style="background-image: url('<?php echo $featured_image[0]; ?>');" itemscope itemtype="http://schema.org/BlogPosting">
                <meta itemprop="image" content="<?php echo esc_attr( $featured_image[0] ); ?>">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <header>
                       <div class="post-info">
                           <span class="thecategory"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></span>
                           <meta itemprop="datePublished" content="<?php the_time( get_option( 'date_format' ) ); ?>">
                        </div>
                       <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                        <div class="front-view-content">
                          <?php echo mts_excerpt(18); ?>
                        </div> 
                    </header>
                </a>
            </article>
        <?php } ?>
        <?php if( $i == 2 ) { ?>
            <?php if ( has_post_thumbnail() ) { $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-post-2' ); } ?>
            <article<?php echo $featured_class; ?> style="background-image: url('<?php echo $featured_image[0]; ?>');" itemscope itemtype="http://schema.org/BlogPosting">
                <meta itemprop="image" content="<?php echo esc_attr( $featured_image[0] ); ?>">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <header>
                       <div class="post-info">
                           <span class="thecategory"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></span>
                           <meta itemprop="datePublished" content="<?php the_time( get_option( 'date_format' ) ); ?>">
                        </div>
                       <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                        <div class="front-view-content">
                          <?php echo mts_excerpt(11); ?>
                        </div> 
                    </header>
                </a>
            </article>
        <?php } ?>
        <?php if( $i == 3 || $i == 4 ) { ?>
            <?php if ( has_post_thumbnail() ) { $featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'featured-post-3' ); } ?>
            <article<?php echo $featured_class; ?> style="background-image: url('<?php echo $featured_image[0]; ?>');" itemscope itemtype="http://schema.org/BlogPosting">
                <meta itemprop="image" content="<?php echo esc_attr( $featured_image[0] ); ?>">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <header>
                       <div class="post-info">
                           <span class="thecategory"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></span>
                           <meta itemprop="datePublished" content="<?php the_time( get_option( 'date_format' ) ); ?>">
                        </div>
                       <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                    </header>
                </a>
            </article>
        <?php } ?>
        <?php $i++; endwhile; wp_reset_query(); ?>
    </div><!-- END #big-posts -->
<?php } ?>

<?php if ( !empty($mts_options['mts_latest_categories']) && $mts_options['mts_latest_categories_show_hide'] == 1 ) { ?>
    <div class="latestPost-categories">
        <div class="container">
            <ul>
                <?php foreach( $mts_options['mts_latest_categories'] as $cat_with_icons ) : ?>
                <li class="">
                    <a href="<?php print $cat_with_icons['mts_latest_categories_link'] ?>">
                        <i class="fa fa-<?php print $cat_with_icons['mts_latest_categories_icon'] ?>"></i>
                        <div class="name">
                            <?php print $cat_with_icons['mts_latest_categories_title'] ?>                            
                        </div>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php } ?>

<?php if ( !empty( $mts_options['mts_latest_categories2'] ) && $mts_options['mts_latest_categories2_show_hide'] == 1 ) { ?>
    <div class="latestPost-news">
      <div class="container">
        <?php $category_id = implode(",", $mts_options['mts_latest_categories2']);
        $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page=4&ignore_sticky_posts=1');
        if ($cat_query->have_posts()) : while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
            <article class="latestPost" itemscope itemtype="http://schema.org/BlogPosting">
                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image post-image-left">
                    <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featured-cat',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                    <header>
                        <div class="thecategory"><?php $category = get_the_category(); echo $category[0]->cat_name; ?></div>
                        <meta itemprop="datePublished" content="<?php the_time( get_option( 'date_format' ) ); ?>">
                        <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                    </header>
                </a>
            </article>
        <?php endwhile; endif; ?>
      </div>
    </div>
<?php } ?>

<!-- END #latestPost-categories -->
<div id="page">
    <div class="sidebar-with-content">
        <div class="<?php mts_article_class(); ?>">
    		<div id="content_box">
                <?php if ( !is_paged() ) {
                    if ( !empty( $mts_options['mts_featured_categories'] ) ) {
                        foreach ( $mts_options['mts_featured_categories'] as $section ) {
                            $category_id = $section['mts_featured_category'];
                            $featured_category_layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'listlayout';
                            $posts_num = $section['mts_featured_category_postsnum'];
                            if ( $category_id === 'latest' && $featured_category_layout === 'listlayout' ) { ?>
                                <h3 class="featured-category-title"><?php _e('Latest','mythemeshop'); ?></h3>
                                <?php $j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); $j++; ?>
                                    <article class="latestPost excerpt full-left" itemscope itemtype="http://schema.org/BlogPosting">
                    				    <?php mts_archive_post(); ?>
                                    </article>
                    			<?php endwhile; endif; ?>

                                <?php if ( $j !== 0 ) { // No pagination if there is no posts ?>
                                    <?php mts_pagination(); ?>
                                <?php } ?>
                                
                            <?php } elseif ( $category_id === 'latest' && $featured_category_layout === 'gridlayout' ) { ?>
                                <h3 class="featured-category-title"><?php _e( 'Latest', 'mythemeshop' ); ?></h3>
                                <div class="trending-stories">
                                    <?php $i = 0; ?>
                                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                                        $i ++;
                                        if( $i == 1 ) { ?>
                                            <article class="latestPost excerpt grid-<?php echo $i; ?>" itemscope itemtype="http://schema.org/BlogPosting">
                                                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image post-image-left">
                                                    <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredfull',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                                </a>    
                                                <header>
                                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
                                                        <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                                    </a>    
                                                    <div class="front-view-content">
                                                        <?php echo mts_excerpt(20); ?>
                                                    </div>
                                                    <?php mts_the_postinfo(); ?>
                                                </header>  
                                            </article>
                                        <?php } ?>
                                        <?php if( $i >= 2 ) { ?>
                                            <article class="latestPost excerpt grid-2" itemscope itemtype="http://schema.org/BlogPosting">
                                                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image">
                                                    <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredmedium',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                                </a>
                                                <header>
                                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image">
                                                        <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                                    </a>
                                                    <?php mts_the_postinfo(); ?>
                                                </header>
                                            </article>
                                        <?php } ?>
                                    <?php endwhile; endif; ?>
                                    <?php if ( $i !== 0 ) { // No pagination if there is no posts ?>
                                        <?php mts_pagination(); ?>
                                    <?php } ?>
                                </div>

                            <?php } elseif ( $category_id !== 'latest' && $featured_category_layout === 'gridlayout' ) {
                                $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num.'&ignore_sticky_posts=1'); ?>
                                <h3 class="featured-category-title"><?php echo get_cat_name($category_id); ?></h3>
                                <div class="trending-stories">
                                    <?php $i = 0; ?>
                                    <?php if ($cat_query->have_posts()) : while ($cat_query->have_posts()) : $cat_query->the_post();
                                        $i ++;
                                        if( $i == 1 ) { ?>
                                            <article class="latestPost excerpt grid-<?php echo $i; ?>" itemscope itemtype="http://schema.org/BlogPosting">
                                                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image post-image-left">
                                                    <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredfull',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                                </a>    
                                                <header>
                                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
                                                        <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                                    </a>    
                                                    <div class="front-view-content">
                                                        <?php echo mts_excerpt(20); ?>
                                                    </div>
                                                    <?php mts_the_postinfo(); ?>
                                                </header>  
                                            </article>
                                        <?php } ?>
                                        <?php if( $i >= 2 ) { ?>
                                            <article class="latestPost excerpt grid-2" itemscope itemtype="http://schema.org/BlogPosting">
                                                <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image">
                                                    <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredmedium',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                                    <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                                </a>
                                                <header>
                                                    <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image">
                                                        <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                                    </a>
                                                    <?php mts_the_postinfo(); ?>
                                                </header>
                                            </article>
                                        <?php } ?>
                                    <?php endwhile; endif; ?>
                                </div>
                            <?php } else { ?>
                                <h3 class="featured-category-title"><a href="<?php echo esc_url( get_category_link( $category_id ) ); ?>" title="<?php echo esc_attr( get_cat_name( $category_id ) ); ?>"><?php echo esc_html( get_cat_name( $category_id ) ); ?></a></h3>
                                <?php
                                $j = 0;
                                $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num);
                                if ( $cat_query->have_posts() ) : while ( $cat_query->have_posts() ) : $cat_query->the_post(); ?>
                                    <article class="latestPost excerpt <?php echo (++$j % 3 == 0) ? 'last' : ''; ?>" itemscope itemtype="http://schema.org/BlogPosting">
                    				    <?php mts_archive_post(); ?>
                                    </article>
                    			<?php endwhile; endif; wp_reset_postdata();
                            }
                        }
                    }

                } else { //Paged
                    global $mts_latest_posts_layout;
                    ?>
                    <?php if ( $mts_latest_posts_layout === 'gridlayout' ) { ?>
                        <div class="trending-stories">
                            <?php $i = 0; ?>
                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                                $i ++;
                                if( $i == 1 ) { ?>
                                    <article class="latestPost excerpt grid-<?php echo $i; ?>" itemscope itemtype="http://schema.org/BlogPosting">
                                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image post-image-left">
                                            <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredfull',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                            <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                        </a>    
                                        <header>
                                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image post-image-left">
                                                <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                            </a>    
                                            <div class="front-view-content">
                                                <?php echo mts_excerpt(20); ?>
                                            </div>
                                            <?php mts_the_postinfo(); ?>
                                        </header>  
                                    </article>
                                <?php } ?>
                                <?php if( $i >= 2 ) { ?>
                                    <article class="latestPost excerpt grid-2" itemscope itemtype="http://schema.org/BlogPosting">
                                        <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow" class="post-image">
                                            <?php echo '<div class="featured-thumbnail">'; the_post_thumbnail('featuredmedium',array('title' => '','itemprop'=>'image')); echo '</div>'; ?>
                                            <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'latestPost-review-wrapper'); ?>
                                        </a>
                                        <header>
                                            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" class="post-image">
                                                <h2 class="title front-view-title" itemprop="headline"><?php the_title(); ?></h2>
                                            </a>
                                            <?php mts_the_postinfo(); ?>
                                        </header>
                                    </article>
                                <?php } ?>
                            <?php endwhile; endif; ?>
                            <?php if ( $i !== 0 ) { // No pagination if there is no posts ?>
                                <?php mts_pagination(); ?>
                            <?php } ?>
                        </div>

                    <?php } else {

                        $j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                            <article class="latestPost excerpt <?php echo (++$j % 3 == 0) ? 'last' : ''; ?>" itemscope itemtype="http://schema.org/BlogPosting">
            				    <?php mts_archive_post(); ?>
                            </article>
                        <?php endwhile; endif; ?>

                        <?php if ( $j !== 0 ) { // No pagination if there is no posts ?>
                            <?php mts_pagination(); ?>
                        <?php } ?>

                    <?php } ?>

                <?php } ?>
    		</div>
    	</div>
        <?php get_template_part('left', 'sidebar') ?>
    </div>
    <!-- END #sidebar-with-content -->
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
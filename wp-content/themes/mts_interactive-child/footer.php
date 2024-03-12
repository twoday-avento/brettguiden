<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<?php
// default = 3
$first_footer_num  = empty($mts_options['mts_first_footer_num']) ? 3 : $mts_options['mts_first_footer_num'];
?>
	</div><!--#page-->
	<footer id="site-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container">
            <div class="footer-logo">
                <?php if ($mts_options['mts_footer_logo'] != '') { ?>
                    <?php if( is_front_page() || is_home() || is_404() ) { ?>
                            <h3 id="logo" class="image-logo" itemprop="headline">
                                <a href="<?php echo esc_url( home_url() ); ?>">
                                    <img src="<?php echo esc_url( home_url() ); ?>/wp-content/uploads/2016/11/logo2.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>>
                                </a>
                            </h3><!-- END #logo -->
                    <?php } else { ?>
                            <h3 id="logo" class="image-logo" itemprop="headline">
                                <a href="<?php echo esc_url( home_url() ); ?>">
                                    <img src="<?php echo esc_url( home_url() ); ?>/wp-content/uploads/2016/11/logo2.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>>
                                </a>
                            </h3><!-- END #logo -->
                    <?php } ?>
                <?php } else { ?>
                    <?php if( is_front_page() || is_home() || is_404() ) { ?>
                            <h3 id="logo" class="text-logo" itemprop="headline">
                                <a href="<?php echo esc_url( home_url() ); ?>">
                                    <img src="<?php echo esc_url( home_url() ); ?>/wp-content/uploads/2016/11/logo2.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>>
                                </a>
                            </h3><!-- END #logo -->
                    <?php } else { ?>
                            <h3 id="logo" class="text-logo" itemprop="headline">
                                <a href="<?php echo esc_url( home_url() ); ?>">
                                    <img src="<?php echo esc_url( home_url() ); ?>/wp-content/uploads/2016/11/logo2.png" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"<?php if (!empty($mts_options['mts_logo2x'])) { echo ' data-at2x="'.esc_attr( $mts_options['mts_logo2x'] ).'"'; } ?>>
                                </a>
                            </h3><!-- END #logo -->
                    <?php } ?>
                <?php } ?>
            </div>
            <?php if ($mts_options['mts_first_footer']) : ?>
                <div class="footer-widgets first-footer-widgets widgets-num-<?php echo $first_footer_num; ?>">
                <?php
                for ( $i = 1; $i <= $first_footer_num; $i++ ) {
                    $sidebar = ( $i == 1 ) ? 'footer-first' : 'footer-first-'.$i;
                    $class = ( $i == $first_footer_num ) ? 'f-widget last f-widget-'.$i : 'f-widget f-widget-'.$i;
                    ?>
                    <div class="<?php echo $class;?>">
                        <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $sidebar ) ) : ?><?php endif; ?>
                    </div>
                    <?php
                }
                ?>
                </div><!--.first-footer-widgets-->
            <?php endif; ?>

            <div class="copyrights">
				<?php mts_copyrights_credit(); ?>
			</div> 
		</div><!--.container-->
	</footer><!--#site-footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>
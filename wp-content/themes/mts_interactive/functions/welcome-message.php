<?php 

add_action( 'admin_enqueue_scripts', 'mts_interactive_pointer_header' );
function mts_interactive_pointer_header() {
    $enqueue = false;

    $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

    if ( ! in_array( 'mts_interactive_pointer', $dismissed ) ) {
        $enqueue = true;
        add_action( 'admin_print_footer_scripts', 'mts_interactive_pointer_footer' );
    }

    if ( $enqueue ) {
        // Enqueue pointers
        wp_enqueue_script( 'wp-pointer' );
        wp_enqueue_style( 'wp-pointer' );
    }
}

function mts_interactive_pointer_footer() {
    $pointer_content = '<h3>'.__('Awesomeness!', 'mythemeshop').'</h3>';
    $pointer_content .= '<p>'.__('You have just Installed Interactive WordPress Theme by MyThemeShop.', 'mythemeshop').'</p>';
	$pointer_content .= '<p>'.__('You can Trigger The Awesomeness using Amazing Option Panel in <b>Theme Options</b>.', 'mythemeshop').'</p>';
    $pointer_content .= '<p>'.__('If you face any problem, head over to', 'mythemeshop').' <a href="http://community.mythemeshop.com/">'.__('Community Forums', 'mythemeshop').'</a></p>';
?>
<script type="text/javascript">// <![CDATA[
jQuery(document).ready(function($) {
    $('#menu-appearance').pointer({
        content: '<?php echo $pointer_content; ?>',
        position: {
            edge: 'left',
            align: 'center'
        },
        close: function() {
            $.post( ajaxurl, {
                pointer: 'mts_interactive_pointer',
                action: 'dismiss-wp-pointer'
            });
        }
    }).pointer('open');
});
// ]]></script>
<?php
}

?>
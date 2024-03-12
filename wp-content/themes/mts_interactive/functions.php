<?php
/*-----------------------------------------------------------------------------------*/
/*	Do not remove these lines, sky will fall on your head.
/*-----------------------------------------------------------------------------------*/
define( 'MTS_THEME_NAME', 'interactive' );
define( 'MTS_THEME_VERSION', '1.0.5' );
require_once( dirname( __FILE__ ) . '/theme-options.php' );
if ( ! isset( $content_width ) ) $content_width = 1060;

/*-----------------------------------------------------------------------------------*/
/*	Load Options
/*-----------------------------------------------------------------------------------*/
$mts_options = get_option( MTS_THEME_NAME );

/*-----------------------------------------------------------------------------------*/
/*  Site Title
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'title-tag' );
if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function theme_slug_render_title() { ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
    <?php }
	add_action( 'wp_head', 'theme_slug_render_title' );
}

/*-----------------------------------------------------------------------------------*/
/*	Load Translation Text Domain
/*-----------------------------------------------------------------------------------*/
load_theme_textdomain( 'mythemeshop', get_template_directory().'/lang' );

// Custom translations
if ( !empty( $mts_options['translate'] )) {
    $mts_translations = get_option( 'mts_translations_'.MTS_THEME_NAME );//$mts_options['translations'];
    function mts_custom_translate( $translated_text, $text, $domain ) {
        if ( $domain == 'mythemeshop' || $domain == 'nhp-opts' ) {
            global $mts_translations;
            if ( !empty( $mts_translations[$text] )) {
                $translated_text = $mts_translations[$text];
            }
        }
    	return $translated_text;
        
    }
    add_filter( 'gettext', 'mts_custom_translate', 20, 3 );
}

if ( function_exists( 'add_theme_support' ) ) add_theme_support( 'automatic-feed-links' );

/*-----------------------------------------------------------------------------------*/
/*  Disable theme updates from WordPress.org theme repository
/*-----------------------------------------------------------------------------------*/
// Check if MTS Connect plugin already done this
if ( !class_exists('mts_connection') ) {
    // If wrong updates are already shown, delete transient so that we can run our workaround
    add_action('init', 'mts_hide_themes_plugins');
    function mts_hide_themes_plugins() {
        if ( !is_admin() ) return;
        if ( false === get_site_transient( 'mts_wp_org_check_disabled' ) ) { // run only once
            delete_site_transient('update_themes' );
            delete_site_transient('update_plugins' );

            add_action('current_screen', 'mts_remove_themes_plugins_from_update' );
        }
    }
    // Hide mts themes/plugins
    function mts_remove_themes_plugins_from_update( $screen ) {
        $run_on_screens = array( 'themes', 'themes-network', 'plugins', 'plugins-network', 'update-core', 'network-update-core' );
        if ( in_array( $screen->base, $run_on_screens ) ) {
            //Themes
            if ( $themes_transient = get_site_transient( 'update_themes' ) ) {
                if ( property_exists( $themes_transient, 'response' ) && is_array( $themes_transient->response ) ) {
                    foreach ( $themes_transient->response as $key => $value ) {
                        $theme = wp_get_theme( $value['theme'] );
                        $theme_uri = $theme->get( 'ThemeURI' );
                        if ( 0 !== strpos( $theme_uri, 'mythemeshop.com' ) ) {
                            unset( $themes_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_themes', $themes_transient );
                }
            }
            //Plugins
            if ( $plugins_transient = get_site_transient( 'update_plugins' ) ) {
                if ( property_exists( $plugins_transient, 'response' ) && is_array( $plugins_transient->response ) ) {
                    foreach ( $plugins_transient->response as $key => $value ) {
                        $plugin = get_plugin_data( WP_PLUGIN_DIR.'/'.$key, false, false );
                        $plugin_uri = $plugin['PluginURI'];
                        if ( 0 !== strpos( $plugin_uri, 'mythemeshop.com' ) ) {
                            unset( $plugins_transient->response[$key] );
                        }
                    }
                    set_site_transient( 'update_plugins', $plugins_transient );
                }
            }
            set_site_transient( 'mts_wp_org_check_disabled', time() );
        }
    }
    add_action( 'load-themes.php', 'mts_clear_check_transient' );
    add_action( 'load-plugins.php', 'mts_clear_check_transient' );
    add_action( 'upgrader_process_complete', 'mts_clear_check_transient' );
    function mts_clear_check_transient(){
        delete_site_transient( 'mts_wp_org_check_disabled');
    }
}
// Disable auto update
add_filter( 'auto_update_theme', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/*  Disable Google Typography plugin
/*-----------------------------------------------------------------------------------*/
add_action( 'admin_init', 'mts_deactivate_google_typography_plugin' );
function mts_deactivate_google_typography_plugin() {
    if ( in_array( 'google-typography/google-typography.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        deactivate_plugins( 'google-typography/google-typography.php' );
    }
}

/*------------------------------------------------------------------------------------------------------------*/
/*  Define MTS_ICONS constant containing all available icons for use in nav menus and icon select option
/*------------------------------------------------------------------------------------------------------------*/
$_mts_icons = array(
    'Misc Icons' => array(
        'glass', 'music', 'search', 'envelope-o', 'heart', 'star', 'star-o', 'user', 'film', 'th-large', 'th', 'th-list', 'check', 'times', 'search-plus', 'search-minus', 'power-off', 'signal', 'cog', 'trash-o', 'home', 'file-o', 'clock-o', 'road', 'download', 'arrow-circle-o-down', 'arrow-circle-o-up', 'inbox', 'play-circle-o', 'repeat', 'refresh', 'list-alt', 'lock', 'flag', 'headphones', 'volume-off', 'volume-down', 'volume-up', 'qrcode', 'barcode', 'tag', 'tags', 'book', 'bookmark', 'print', 'camera', 'font', 'bold', 'italic', 'text-height', 'text-width', 'align-left', 'align-center', 'align-right', 'align-justify', 'list', 'outdent', 'indent', 'video-camera', 'picture-o', 'pencil', 'map-marker', 'adjust', 'tint', 'pencil-square-o', 'share-square-o', 'check-square-o', 'arrows', 'step-backward', 'fast-backward', 'backward', 'play', 'pause', 'stop', 'forward', 'fast-forward', 'step-forward', 'eject', 'chevron-left', 'chevron-right', 'plus-circle', 'minus-circle', 'times-circle', 'check-circle', 'question-circle', 'info-circle', 'crosshairs', 'times-circle-o', 'check-circle-o', 'ban', 'arrow-left', 'arrow-right', 'arrow-up', 'arrow-down', 'share', 'expand', 'compress', 'plus', 'minus', 'asterisk', 'exclamation-circle', 'gift', 'leaf', 'fire', 'eye', 'eye-slash', 'exclamation-triangle', 'plane', 'calendar', 'random', 'comment', 'magnet', 'chevron-up', 'chevron-down', 'retweet', 'shopping-cart', 'folder', 'folder-open', 'arrows-v', 'arrows-h', 'bar-chart', 'twitter-square', 'facebook-square', 'camera-retro', 'key', 'cogs', 'comments', 'thumbs-o-up', 'thumbs-o-down', 'star-half', 'heart-o', 'sign-out', 'linkedin-square', 'thumb-tack', 'external-link', 'sign-in', 'trophy', 'github-square', 'upload', 'lemon-o', 'phone', 'square-o', 'bookmark-o', 'phone-square', 'twitter', 'facebook', 'github', 'unlock', 'credit-card', 'rss', 'hdd-o', 'bullhorn', 'bell', 'certificate', 'hand-o-right', 'hand-o-left', 'hand-o-up', 'hand-o-down', 'arrow-circle-left', 'arrow-circle-right', 'arrow-circle-up', 'arrow-circle-down', 'globe', 'wrench', 'tasks', 'filter', 'briefcase', 'arrows-alt', 'users', 'link', 'cloud', 'flask', 'scissors', 'files-o', 'paperclip', 'floppy-o', 'square', 'bars', 'list-ul', 'list-ol', 'strikethrough', 'underline', 'table', 'magic', 'truck', 'pinterest', 'pinterest-square', 'google-plus-square', 'google-plus', 'money', 'caret-down', 'caret-up', 'caret-left', 'caret-right', 'columns', 'sort', 'sort-desc', 'sort-asc', 'envelope', 'linkedin', 'undo', 'gavel', 'tachometer', 'comment-o', 'comments-o', 'bolt', 'sitemap', 'umbrella', 'clipboard', 'lightbulb-o', 'exchange', 'cloud-download', 'cloud-upload', 'user-md', 'stethoscope', 'suitcase', 'bell-o', 'coffee', 'cutlery', 'file-text-o', 'building-o', 'hospital-o', 'ambulance', 'medkit', 'fighter-jet', 'beer', 'h-square', 'plus-square', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-double-down', 'angle-left', 'angle-right', 'angle-up', 'angle-down', 'desktop', 'laptop', 'tablet', 'mobile', 'circle-o', 'quote-left', 'quote-right', 'spinner', 'circle', 'reply', 'github-alt', 'folder-o', 'folder-open-o', 'smile-o', 'frown-o', 'meh-o', 'gamepad', 'keyboard-o', 'flag-o', 'flag-checkered', 'terminal', 'code', 'reply-all', 'star-half-o', 'location-arrow', 'crop', 'code-fork', 'chain-broken', 'question', 'info', 'exclamation', 'superscript', 'subscript', 'eraser', 'puzzle-piece', 'microphone', 'microphone-slash', 'shield', 'calendar-o', 'fire-extinguisher', 'rocket', 'maxcdn', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-circle-down', 'html5', 'css3', 'anchor', 'unlock-alt', 'bullseye', 'ellipsis-h', 'ellipsis-v', 'rss-square', 'play-circle', 'ticket', 'minus-square', 'minus-square-o', 'level-up', 'level-down', 'check-square', 'pencil-square', 'external-link-square', 'share-square', 'compass', 'caret-square-o-down', 'caret-square-o-up', 'caret-square-o-right', 'eur', 'gbp', 'usd', 'inr', 'jpy', 'rub', 'krw', 'btc', 'file', 'file-text', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'thumbs-up', 'thumbs-down', 'youtube-square', 'youtube', 'xing', 'xing-square', 'youtube-play', 'dropbox', 'stack-overflow', 'instagram', 'flickr', 'adn', 'bitbucket', 'bitbucket-square', 'tumblr', 'tumblr-square', 'long-arrow-down', 'long-arrow-up', 'long-arrow-left', 'long-arrow-right', 'apple', 'windows', 'android', 'linux', 'dribbble', 'skype', 'foursquare', 'trello', 'female', 'male', 'gratipay', 'sun-o', 'moon-o', 'archive', 'bug', 'vk', 'weibo', 'renren', 'pagelines', 'stack-exchange', 'arrow-circle-o-right', 'arrow-circle-o-left', 'caret-square-o-left', 'dot-circle-o', 'wheelchair', 'vimeo-square', 'try', 'plus-square-o', 'space-shuttle', 'slack', 'envelope-square', 'wordpress', 'openid', 'university', 'graduation-cap', 'yahoo', 'google', 'reddit', 'reddit-square', 'stumbleupon-circle', 'stumbleupon', 'delicious', 'digg', 'pied-piper', 'pied-piper-alt', 'drupal', 'joomla', 'language', 'fax', 'building', 'child', 'paw', 'spoon', 'cube', 'cubes', 'behance', 'behance-square', 'steam', 'steam-square', 'recycle', 'car', 'taxi', 'tree', 'spotify', 'deviantart', 'soundcloud', 'database', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'file-powerpoint-o', 'file-image-o', 'file-archive-o', 'file-audio-o', 'file-video-o', 'file-code-o', 'vine', 'codepen', 'jsfiddle', 'life-ring', 'circle-o-notch', 'rebel', 'empire', 'git-square', 'git', 'hacker-news', 'tencent-weibo', 'qq', 'weixin', 'paper-plane', 'paper-plane-o', 'history', 'circle-thin', 'header', 'paragraph', 'sliders', 'share-alt', 'share-alt-square', 'bomb', 'futbol-o', 'tty', 'binoculars', 'plug', 'slideshare', 'twitch', 'yelp', 'newspaper-o', 'wifi', 'calculator', 'paypal', 'google-wallet', 'cc-visa', 'cc-mastercard', 'cc-discover', 'cc-amex', 'cc-paypal', 'cc-stripe', 'bell-slash', 'bell-slash-o', 'trash', 'copyright', 'at', 'eyedropper', 'paint-brush', 'birthday-cake', 'area-chart', 'pie-chart', 'line-chart', 'lastfm', 'lastfm-square', 'toggle-off', 'toggle-on', 'bicycle', 'bus', 'ioxhost', 'angellist', 'cc', 'ils', 'meanpath', 'buysellads', 'connectdevelop', 'dashcube', 'forumbee', 'leanpub', 'sellsy', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'cart-plus', 'cart-arrow-down', 'diamond', 'ship', 'user-secret', 'motorcycle', 'street-view', 'heartbeat', 'venus', 'mars', 'mercury', 'transgender', 'transgender-alt', 'venus-double', 'mars-double', 'venus-mars', 'mars-stroke', 'mars-stroke-v', 'mars-stroke-h', 'neuter', 'facebook-official', 'pinterest-p', 'whatsapp', 'server', 'user-plus', 'user-times', 'bed', 'viacoin', 'train', 'subway', 'medium'),
    'Web Application Icons' => array(
        'adjust', 'anchor', 'archive', 'area-chart', 'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'at', 'ban', 'bar-chart', 'barcode', 'bars', 'bed', 'beer', 'bell', 'bell-o', 'bell-slash', 'bell-slash-o', 'bicycle', 'binoculars', 'birthday-cake', 'bolt', 'bomb', 'book', 'bookmark', 'bookmark-o', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 'bullseye', 'bus', 'calculator', 'calendar', 'calendar-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'cart-arrow-down', 'cart-plus', 'cc', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'comments', 'comments-o', 'compass', 'copyright', 'credit-card', 'crop', 'crosshairs', 'cube', 'cubes', 'cutlery', 'database', 'desktop', 'diamond', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'eyedropper', 'fax', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-pdf-o', 'file-powerpoint-o', 'file-video-o', 'file-word-o', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'futbol-o', 'gamepad', 'gavel', 'gift', 'glass', 'globe', 'graduation-cap', 'hdd-o', 'headphones', 'heart', 'heart-o', 'heartbeat', 'history', 'home', 'inbox', 'info', 'info-circle', 'key', 'keyboard-o', 'language', 'laptop', 'leaf', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'line-chart', 'location-arrow', 'lock', 'magic', 'magnet', 'male', 'map-marker', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 'motorcycle', 'music', 'newspaper-o', 'paint-brush', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 'phone', 'phone-square', 'picture-o', 'pie-chart', 'plane', 'plug', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'server', 'share', 'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'ship', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'space-shuttle', 'spinner', 'spoon', 'square', 'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'street-view', 'suitcase', 'sun-o', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'toggle-off', 'toggle-on', 'trash', 'trash-o', 'tree', 'trophy', 'truck', 'tty', 'umbrella', 'university', 'unlock', 'unlock-alt', 'upload', 'user', 'user-plus', 'user-secret', 'user-times', 'users', 'video-camera', 'volume-down', 'volume-off', 'volume-up', 'wheelchair', 'wifi', 'wrench'),
    'Transportation Icons' => array(
        'ambulance', 'bicycle', 'bus', 'car', 'fighter-jet', 'motorcycle', 'plane', 'rocket', 'ship', 'space-shuttle', 'subway', 'taxi', 'train', 'truck', 'wheelchair', ),
    'Gender Icons' => array(
        'circle-thin', 'mars', 'mars-double', 'mars-stroke', 'mars-stroke-h', 'mars-stroke-v', 'mercury', 'neuter', 'transgender', 'transgender-alt', 'venus', 'venus-double', 'venus-mars', ),
    'File Type Icons' => array(
        'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 'file-o', 'file-pdf-o', 'file-powerpoint-o', 'file-text', 'file-text-o', 'file-video-o', 'file-word-o', ),
    'Spinner Icons' => array(
        'circle-o-notch', 'cog', 'refresh', 'spinner', ),
    'Form Control Icons' => array(
        'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o', ),
    'Payment Icons' => array(
        'cc-amex', 'cc-discover', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'credit-card', 'google-wallet', 'paypal', ),
    'Chart Icons' => array(
        'area-chart', 'bar-chart', 'line-chart', 'pie-chart', ),
    'Currency Icons' => array(
        'btc', 'eur', 'gbp', 'ils', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd', ),
    'Text Editor Icons' => array(
        'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'repeat', 'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo', ),
    'Directional Icons' => array(
        'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up', ),
    'Video Player Icons' => array(
        'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'play', 'play-circle', 'play-circle-o', 'step-backward', 'step-forward', 'stop', 'youtube-play', ),
    'Brand Icons' => array(
        'adn', 'android', 'angellist', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 'btc', 'buysellads', 'cc-amex', 'cc-discover', 'cc-mastercard', 'cc-paypal', 'cc-stripe', 'cc-visa', 'codepen', 'connectdevelop', 'css3', 'dashcube', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 'drupal', 'empire', 'facebook', 'facebook-official', 'facebook-square', 'flickr', 'forumbee', 'foursquare', 'git', 'git-square', 'github', 'github-alt', 'github-square', 'google', 'google-plus', 'google-plus-square', 'google-wallet', 'gratipay', 'hacker-news', 'html5', 'instagram', 'ioxhost', 'joomla', 'jsfiddle', 'lastfm', 'lastfm-square', 'leanpub', 'linkedin', 'linkedin-square', 'linux', 'maxcdn', 'meanpath', 'medium', 'openid', 'pagelines', 'paypal', 'pied-piper', 'pied-piper-alt', 'pinterest', 'pinterest-p', 'pinterest-square', 'qq', 'rebel', 'reddit', 'reddit-square', 'renren', 'sellsy', 'share-alt', 'share-alt-square', 'shirtsinbulk', 'simplybuilt', 'skyatlas', 'skype', 'slack', 'slideshare', 'soundcloud', 'spotify', 'stack-exchange', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 'trello', 'tumblr', 'tumblr-square', 'twitch', 'twitter', 'twitter-square', 'viacoin', 'vimeo-square', 'vine', 'vk', 'weibo', 'weixin', 'whatsapp', 'windows', 'wordpress', 'xing', 'xing-square', 'yahoo', 'yelp', 'youtube', 'youtube-play', 'youtube-square', ),
    'Medical Icons' => array(
        'ambulance', 'h-square', 'heart', 'heart-o', 'heartbeat', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair'),
    'Custom Icons' => array('2-mannsspill', 'bsg-anbefaler', 'kunnskapsspill', 'terningspill', 'abstrakt-strategi', 'familiespill', 'lett-strategi', 'arets-spill-vinnere', 'ferdighetsspill', 'selskapsspill', 'barnespill','kortspill','strategispill')
);

define ( 'MTS_ICONS', serialize( $_mts_icons ) ); // To use it - $mts_icons = unserialize( MTS_ICONS );

/*-----------------------------------------------------------------------------------*/
/*	Post Thumbnail Support
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 180, 180, true );
    add_image_size( 'featured', 180, 180, true ); //Latest
    add_image_size( 'featured-post-1', 475, 450, true ); //featured post 1
    add_image_size( 'featured-post-2', 475, 450, true ); //featured post 2
	add_image_size( 'featured-post-3', 475, 450, true ); //featured post 3
    add_image_size( 'featured-cat', 270, 180, true ); //featured Category
    add_image_size( 'featuredfull', 564, 300, true ); //Article full width
    add_image_size( 'featuredmedium', 265, 250, true ); //Article Grid
	add_image_size( 'widgetthumb', 60, 60, true ); //widget
    add_image_size( 'widgetfull', 300, 200, true ); //sidebar full width
}

function mts_get_thumbnail_url( $size = 'full' ) {
    global $post;
    if (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
        return $image[0];
    }
    
    // use first attached image
    $images =& get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
    if (!empty($images)) {
        $image = reset($images);
        $image_data = wp_get_attachment_image_src( $image->ID, $size );
        return $image_data[0];
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) )
        return get_template_directory_uri().'/images/nothumb-'.$size.'.png';
    else
        return '';
}

/*-----------------------------------------------------------------------------------*/
/*  CREATE AND SHOW COLUMN FOR FEATURED IN PORTFOLIO ITEMS LIST ADMIN PAGE
/*-----------------------------------------------------------------------------------*/

//Get Featured image
function mts_get_featured_image($post_ID) {  
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);  
    if ($post_thumbnail_id) {  
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'widgetfull');  
        return $post_thumbnail_img[0];  
    }  
} 
function mts_columns_head($defaults) {
    if (get_post_type() == 'post')
        $defaults['featured_image'] = __('Featured Image', 'mythemeshop');
    return $defaults;  
}  
function mts_columns_content($column_name, $post_ID) {  
    if ($column_name == 'featured_image') {  
        $post_featured_image = mts_get_featured_image($post_ID);  
        if ($post_featured_image) {  
            echo '<img width="150" height="100" src="' . esc_attr( $post_featured_image ) . '" />';  
        }  
    }  
} 
add_filter('manage_posts_columns', 'mts_columns_head');  
add_action('manage_posts_custom_column', 'mts_columns_content', 10, 2);

/*-----------------------------------------------------------------------------------*/
/*	Use first attached image as post thumbnail (fallback)
/*-----------------------------------------------------------------------------------*/
add_filter( 'post_thumbnail_html', 'mts_post_image_html', 10, 5 );
function mts_post_image_html( $html, $post_id, $post_image_id, $size, $attr ) {
    if ( has_post_thumbnail() || get_post_type( $post_id ) != 'post'  )
        return $html;
    
    // use first attached image
    $images = get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post_id );
    if (!empty($images)) {
        $image = reset($images);
        return wp_get_attachment_image( $image->ID, $size, false, $attr );
    }
        
    // use no preview fallback
    if ( file_exists( get_template_directory().'/images/nothumb-'.$size.'.png' ) ) {
        $placeholder_src = get_template_directory_uri().'/images/nothumb-'.$size.'.png';
        $placeholder_classs = 'attachment-'.$size.' wp-post-image';
        return '<img itemprop="image" src="'.esc_attr( $placeholder_src ).'" class="'.esc_attr( $placeholder_classs ).'" alt="'.esc_attr( get_the_title() ).'">';
    } else {
        return '';
    }
    
}

/*-----------------------------------------------------------------------------------*/
/*	Custom Menu Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'primary-menu' => __( 'Primary Menu', 'mythemeshop' )
		 )
	 );
}

/*-----------------------------------------------------------------------------------*/
/*	Enable Widgetized sidebar and Footer
/*-----------------------------------------------------------------------------------*/
if ( function_exists( 'register_sidebar' ) ) {   
    function mts_register_sidebars() {
        $mts_options = get_option( MTS_THEME_NAME );
        
        // Default sidebar
        register_sidebar( array(
            'name' => __('Sidebar','mythemeshop'),
            'description'   => __( 'Default sidebar.', 'mythemeshop' ),
            'id' => 'sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );

        // Left sidebar
        register_sidebar( array(
            'name' => __('Left Sidebar','mythemeshop'),
            'description'   => __( 'Left sidebar.', 'mythemeshop' ),
            'id' => 'left-sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ) );
        
        // Top level footer widget areas
        if ( !empty( $mts_options['mts_first_footer'] )) {
            if ( empty( $mts_options['mts_first_footer_num'] )) $mts_options['mts_first_footer_num'] = 4;
            register_sidebars( $mts_options['mts_first_footer_num'], array(
                'name' => __( 'First Footer %d', 'mythemeshop' ),
                'description'   => __( 'Appears at the top of the footer.', 'mythemeshop' ),
                'id' => 'footer-first',
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ) );
        }
        
        // Custom sidebars
        if ( !empty( $mts_options['mts_custom_sidebars'] ) && is_array( $mts_options['mts_custom_sidebars'] )) {
            foreach( $mts_options['mts_custom_sidebars'] as $sidebar ) {
                if ( !empty( $sidebar['mts_custom_sidebar_id'] ) && !empty( $sidebar['mts_custom_sidebar_id'] ) && $sidebar['mts_custom_sidebar_id'] != 'sidebar-' ) {
                    register_sidebar( array( 'name' => ''.$sidebar['mts_custom_sidebar_name'].'', 'id' => ''.sanitize_title( strtolower( $sidebar['mts_custom_sidebar_id'] )).'', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h3>', 'after_title' => '</h3>' ));
                }
            }
        }
    }
    
    add_action( 'widgets_init', 'mts_register_sidebars' );
}

function mts_custom_sidebar() {
    $mts_options = get_option( MTS_THEME_NAME );
    
	// Default sidebar
	$sidebar = 'Sidebar';

	if ( is_home() && !empty( $mts_options['mts_sidebar_for_home'] )) $sidebar = $mts_options['mts_sidebar_for_home'];	
    if ( is_single() && !empty( $mts_options['mts_sidebar_for_post'] )) $sidebar = $mts_options['mts_sidebar_for_post'];
    if ( is_page() && !empty( $mts_options['mts_sidebar_for_page'] )) $sidebar = $mts_options['mts_sidebar_for_page'];
    
    // Archives
	if ( is_archive() && !empty( $mts_options['mts_sidebar_for_archive'] )) $sidebar = $mts_options['mts_sidebar_for_archive'];
	if ( is_category() && !empty( $mts_options['mts_sidebar_for_category'] )) $sidebar = $mts_options['mts_sidebar_for_category'];
    if ( is_tag() && !empty( $mts_options['mts_sidebar_for_tag'] )) $sidebar = $mts_options['mts_sidebar_for_tag'];
    if ( is_date() && !empty( $mts_options['mts_sidebar_for_date'] )) $sidebar = $mts_options['mts_sidebar_for_date'];
	if ( is_author() && !empty( $mts_options['mts_sidebar_for_author'] )) $sidebar = $mts_options['mts_sidebar_for_author'];
    
    // Other
    if ( is_search() && !empty( $mts_options['mts_sidebar_for_search'] )) $sidebar = $mts_options['mts_sidebar_for_search'];
	if ( is_404() && !empty( $mts_options['mts_sidebar_for_notfound'] )) $sidebar = $mts_options['mts_sidebar_for_notfound'];
    
	// Page/post specific custom sidebar
	if ( is_page() || is_single() ) {
		wp_reset_postdata();
		global $post, $wp_registered_sidebars;
        $custom = get_post_meta( $post->ID, '_mts_custom_sidebar', true );
		if ( !empty( $custom ) && array_key_exists( $custom, $wp_registered_sidebars ) || 'mts_nosidebar' == $custom ) $sidebar = $custom;
	}

	return $sidebar;
}

/*-----------------------------------------------------------------------------------*/
/*  Load Widgets, Actions and Libraries
/*-----------------------------------------------------------------------------------*/

// Add the 125x125 Ad Block Custom Widget
include_once( "functions/widget-ad125.php" );

// Add the 300x250 Ad Block Custom Widget
include_once( "functions/widget-ad300.php" );

// Add the Latest Tweets Custom Widget
include_once( "functions/widget-tweets.php" );

// Add Recent Posts Widget
include_once( "functions/widget-recentposts.php" );

// Add Related Posts Widget
include_once( "functions/widget-relatedposts.php" );

// Add Author Posts Widget
include_once( "functions/widget-authorposts.php" );

// Add Popular Posts Widget
include_once( "functions/widget-popular.php" );

// Add Facebook Like box Widget
include_once( "functions/widget-fblikebox.php" );

// Add Social Profile Widget
include_once( "functions/widget-social.php" );

// Add Category Posts Widget
include_once( "functions/widget-catposts.php" );

// Add Category Posts Widget
include_once( "functions/widget-postslider.php" );

// Add Welcome message
include_once( "functions/welcome-message.php" );

// Template Functions
include_once( "functions/theme-actions.php" );

// Post/page editor meta boxes
include_once( "functions/metaboxes.php" );

// TGM Plugin Activation
include_once( "functions/plugin-activation.php" );

// AJAX Contact Form - mts_contact_form()
include_once( 'functions/contact-form.php' );

// Custom menu walker
include_once( 'functions/nav-menu.php' );

/*-----------------------------------------------------------------------------------*/
/*	RTL language support - also in mts_load_footer_scripts()
/*-----------------------------------------------------------------------------------*/
if ( ! empty( $mts_options['mts_rtl'] ) ) {
    function mts_rtl() {
        global $wp_locale, $wp_styles;
        $wp_locale->text_direction = 'rtl';
    	if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
    		$wp_styles = new WP_Styles();
    		$wp_styles->text_direction = 'rtl';
    	}
    }
    add_action( 'init', 'mts_rtl' );
}

/*-----------------------------------------------------------------------------------*/
/*	Javascript
/*-----------------------------------------------------------------------------------*/
function mts_nojs_js_class() {
    echo '<script type="text/javascript">document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,\'js\' );</script>';
}
add_action( 'wp_head', 'mts_nojs_js_class', 1 );

function mts_add_scripts() {
	$mts_options = get_option( MTS_THEME_NAME );

	wp_enqueue_script( 'jquery' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_register_script( 'customscript', get_template_directory_uri() . '/js/customscript.js', true );
    if ( ! empty( $mts_options['mts_show_primary_nav'] ) && ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
        $nav_menu = 'both';
    } else {
        if ( ! empty( $mts_options['mts_show_primary_nav'] ) ) {
            $nav_menu = 'primary';
        } elseif ( ! empty( $mts_options['mts_show_secondary_nav'] ) ) {
            $nav_menu = 'secondary';
        } else {
            $nav_menu = 'none';
        }
    }
    wp_localize_script(
    	'customscript',
    	'mts_customscript',
    	array(
            'responsive' => ( empty( $mts_options['mts_responsive'] ) ? false : true ),
            'nav_menu' => $nav_menu
        )
    );
    wp_enqueue_script( 'customscript' );    

    wp_register_script( 'webticker', get_template_directory_uri() . '/js/jquery.webticker.js', true );
    wp_localize_script('webticker', 'tickeropts', array('rtl_support' => $mts_options['mts_rtl']));
    if( $mts_options['mts_breaking'] == '1' ) {
	   wp_enqueue_script( 'webticker' );
    }
    
    // Slider
    wp_register_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), null, true);
    wp_localize_script('owl-carousel', 'slideropts', array('rtl_support' => $mts_options['mts_rtl']));
    
    // Animated single post/page header
    if ( is_singular() ) {
        $header_animation = mts_get_post_header_effect();
        if ( 'parallax' == $header_animation ) {
            wp_register_script ( 'jquery-parallax', get_template_directory_uri() . '/js/parallax.js' );
            wp_enqueue_script ( 'jquery-parallax' );
        } else if ( 'zoomout' == $header_animation ) {
            wp_register_script ( 'jquery-zoomout', get_template_directory_uri() . '/js/zoomout.js' );
            wp_enqueue_script ( 'jquery-zoomout' );
        }
    }

	global $is_IE;
    if ( $is_IE ) {
        wp_register_script ( 'html5shim', "http://html5shim.googlecode.com/svn/trunk/html5.js" );
        wp_enqueue_script ( 'html5shim' );
	}
    
}
add_action( 'wp_enqueue_scripts', 'mts_add_scripts' );
   
function mts_load_footer_scripts() {  
	$mts_options = get_option( MTS_THEME_NAME );
	
	//Lightbox
	if ( ! empty( $mts_options['mts_lightbox'] ) ) {
        wp_register_script( 'magnificPopup', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', true );
        wp_enqueue_script( 'magnificPopup' );
	}
	
	//Sticky Nav
	if ( ! empty( $mts_options['mts_sticky_nav'] ) ) {
		wp_register_script( 'StickyNav', get_template_directory_uri() . '/js/sticky.js', true );
		wp_enqueue_script( 'StickyNav' );
	}
    
    // RTL
    if ( ! empty( $mts_options['mts_rtl'] ) ) {
		wp_register_style( 'mts_rtl', get_template_directory_uri() . '/css/rtl.css', 'style', true );
		wp_enqueue_style( 'mts_rtl' );
	}
    
    // Ajax Load More and Search Results
    wp_register_script( 'mts_ajax', get_template_directory_uri() . '/js/ajax.js', true );
	if( ! empty( $mts_options['mts_pagenavigation_type'] ) && $mts_options['mts_pagenavigation_type'] >= 2 && !is_singular() ) {
		wp_enqueue_script( 'mts_ajax' );
        
        wp_register_script( 'historyjs', get_template_directory_uri() . '/js/history.js', true );
        wp_enqueue_script( 'historyjs' );
        
        // Add parameters for the JS
        global $wp_query;
        $max = $wp_query->max_num_pages;
        $paged = ( get_query_var( 'paged' ) > 1 ) ? get_query_var( 'paged' ) : 1;
        $autoload = ( $mts_options['mts_pagenavigation_type'] == 3 );
        wp_localize_script(
        	'mts_ajax',
        	'mts_ajax_loadposts',
        	array(
        		'startPage' => $paged,
        		'maxPages' => $max,
        		'nextLink' => next_posts( $max, false ),
                'autoLoad' => $autoload,
                'i18n_loadmore' => __( 'Load More Posts', 'mythemeshop' ),
                'i18n_loading' => __('Loading...', 'mythemeshop'),
                'i18n_nomore' => __( 'No more posts.', 'mythemeshop' )
        	 )
        );
	}
    if ( ! empty( $mts_options['mts_ajax_search'] ) ) {
        wp_enqueue_script( 'mts_ajax' );
        wp_localize_script(
        	'mts_ajax',
        	'mts_ajax_search',
        	array(
				'url' => admin_url( 'admin-ajax.php' ),
        		'ajax_search' => '1'
        	 )
        );
    }
    
}  
add_action( 'wp_footer', 'mts_load_footer_scripts' );  

if( !empty( $mts_options['mts_ajax_search'] )) {
    add_action( 'wp_ajax_mts_search', 'ajax_mts_search' );
    add_action( 'wp_ajax_nopriv_mts_search', 'ajax_mts_search' );
}

/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS
/*-----------------------------------------------------------------------------------*/
function mts_enqueue_css() {
	$mts_options = get_option( MTS_THEME_NAME );

    wp_enqueue_style( 'stylesheet', get_stylesheet_directory_uri() . '/style.css', 'style' );
    
	// Slider
    // also enqueued in slider widget
    wp_register_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), null);
    
	$handle = 'stylesheet';
	
	// Lightbox
	if ( ! empty( $mts_options['mts_lightbox'] ) ) {
        wp_register_style( 'magnificPopup', get_template_directory_uri() . '/css/magnific-popup.css', 'style' );
        wp_enqueue_style( 'magnificPopup' );
	}
	
	//Font Awesome
	wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', 'style' );
	wp_enqueue_style( 'fontawesome' );
	
	//Responsive
	if ( ! empty( $mts_options['mts_responsive'] ) ) {
        wp_enqueue_style( 'responsive', get_template_directory_uri() . '/css/responsive.css', 'style' );
	}
	
    $mts_bg = '';
	if ( $mts_options['mts_bg_pattern_upload'] != '' ) {
		$mts_bg = $mts_options['mts_bg_pattern_upload'];
	} else {
		if( !empty( $mts_options['mts_bg_pattern'] )) {
			$mts_bg = get_template_directory_uri().'/images/'.$mts_options['mts_bg_pattern'].'.png';
		}
	}
	$mts_sclayout = '';
	$mts_shareit_left = '';
	$mts_shareit_right = '';
	$mts_author = '';
	$mts_header_section = '';
    if ( is_page() || is_single() ) {
        $mts_sidebar_location = get_post_meta( get_the_ID(), '_mts_sidebar_location', true );
    } else {
        $mts_sidebar_location = '';
    }
	if ( $mts_sidebar_location != 'right' && ( $mts_options['mts_layout'] == 'sclayout' || $mts_sidebar_location == 'left' )) {
		$mts_sclayout = '.article, .single .article, .sidebar.c-4-12.sidebar-left { float: right;}
		.sidebar-with-content { float: right; }
        .sidebar.sidebar-right, .sidebar.c-4-12, .sidebar-with-content .article { float: left; }';
		if( isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) {
			$mts_shareit_right = '.shareit { margin: 0 760px 0; border-left: 0; }';
		}
	}
	if ( empty( $mts_options['mts_header_section2'] ) ) {
		$mts_header_section = '.logo-wrap, .widget-header { display: none; }
		.navigation { border-top: 0; }
		#header { min-height: 47px; }';
	}
	if ( isset( $mts_options['mts_social_button_position'] ) && $mts_options['mts_social_button_position'] == 'floating' ) {
		$mts_shareit_left = '.shareit { top: 282px; left: 0; margin: 0; width: 90px; position: fixed; padding: 5px; border:none; border-right: 0;}
		.share-item {margin: 2px;}';
	}
	if ( ! empty( $mts_options['mts_author_comment'] ) ) {
        $mts_author = '.bypostauthor { background: none; }
        .bypostauthor > div .comment-author .fn:after { content: "'.__( 'Author', 'mythemeshop' ).'"; position: relative; padding: 2px 10px; margin: 0 10px; color: #FFF; font-size: 13px; line-height: 1; font-weight: normal; border-radius: 3px; }';
    }
    $example_bg = mts_get_background_styles( 'mts_background' );
	$custom_css = "
		body {background-color:{$mts_options['mts_bg_color']}; }
		body {background-image: url( {$mts_bg} );}

        .header-social a:hover, header .breadcrumb > div i:hover, header .breadcrumb > div a:hover, .breadcrumb > div a:hover, .post-info a, .latestPost .title a:hover, #site-footer .toggle-menu .toggle-caret .fa:hover, #primary-navigation .sub-menu a:hover, .latestPost .title a:hover, ul.ajax-search-results li a:hover, .sidebar .ajax-search-meta a, .breadcrumb > div i, .single .tags a, .postauthor h5 a:hover, .sidebar a, .comment-meta a, .trending-stories .latestPost .title:hover, .postauthor h5, .copyrights a, .single_post a, .textwidget a, .pnavigation2 a, #sidebar a:hover, .copyrights a:hover, #site-footer .widget li a:hover, .related-posts a:hover, .reply a, .title a:hover, .post-info a:hover, .comm, #tabber .inside li a:hover, .readMore a:hover, .fn a, a, a:hover { color: {$mts_options['mts_color_scheme']}; }

        .latestPost-categories ul li:hover a, .footer-widgets h3, .reply a:hover, #primary-navigation .sub-menu a:hover, #primary-navigation .sub-menu li.current-menu-item a { color: {$mts_options['mts_color_scheme']} !important; }

        .latestPost-news .latestPost { border-top: 2px solid {$mts_options['mts_color_scheme']}; }

        
        .navigation.mobile-menu-wrapper { background: {$mts_options['mts_color_scheme']} !important; }

        #header, .pace .pace-progress, #mobile-menu-wrapper ul li a:hover, .widget h3:before, .featured-category-title:before, .widget .wpt_widget_content .tab_title.selected a, #move-to-top, #load-posts, .featured-videos h4:after, .related-posts h4:after, .postauthor h4:after, #comments h4:after, #commentsAdd h4:after, .contact-form input[type='submit'], #commentform input#submit:hover, .contact-form input[type='submit']:hover, #primary-navigation li a:hover, .pagination a:hover, .pagination ul, .latestPost-review-wrapper, a#pull, #commentform input#submit, .contactform #submit, #move-to-top:hover, .navigation ul .current-menu-item > a, .navigation ul li:hover, .pagination a, #tabber ul.tabs li a.selected, .tagcloud a:hover, .latestPost .review-type-circle.review-total-only, .latestPost .review-type-circle.wp-review-show-total, .single .pagination > .current, .bypostauthor .comment-author .fn:after { background-color:{$mts_options['mts_color_scheme']}; color: #fff!important; }

		.owl-prev, .owl-next { background-color:{$mts_options['mts_color_scheme']}!important; }

        #site-footer { background: {$mts_options['mts_footer_bg_scheme']}; }
		{$mts_sclayout}
		{$mts_shareit_left}
		{$mts_shareit_right}
		{$mts_author}
		{$mts_header_section}
		{$mts_options['mts_custom_css']}
			";
	wp_add_inline_style( $handle, $custom_css );
}
add_action( 'wp_enqueue_scripts', 'mts_enqueue_css', 99 );

/*-----------------------------------------------------------------------------------*/
/*  Wrap videos in .responsive-video div
/*-----------------------------------------------------------------------------------*/
function mts_responsive_video( $html, $url, $attr ) {

    // Only video embeds
    $video_providers = array(
        'youtube',
        'vimeo',
        'dailymotion',
        'wordpress.tv',
        'vine.co',
        'animoto',
        'blip.tv',
        'collegehumor.com',
        'funnyordie.com',
        'hulu.com',
        'revision3.com',
        'ted.com',
    );

    // Allow user to wrap other embeds
    $providers = apply_filters('mts_responsive_video', $video_providers );

    foreach ( $providers as $provider ) {
        if ( strstr($url, $provider) ) {
            $html = '<div class="flex-video flex-video-' . sanitize_html_class( $provider ) . '">' . $html . '</div>';
            break;// Break if video found
        }
    }

    return $html;
}
add_filter( 'embed_oembed_html', 'mts_responsive_video', 99, 3 );

/*-----------------------------------------------------------------------------------*/
/*	Filters that allow shortcodes in Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'the_content_rss', 'do_shortcode' );

/*-----------------------------------------------------------------------------------*/
/*	Custom Comments template
/*-----------------------------------------------------------------------------------*/
function mts_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; 
    $mts_options = get_option( MTS_THEME_NAME ); ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" itemscope itemtype="http://schema.org/UserComments">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment->comment_author_email, 50 ); ?>
				<?php printf( '<span class="fn" itemprop="creator" itemscope itemtype="http://schema.org/Person"><span itemprop="name">%s</span></span>', get_comment_author_link() ) ?> 
				<?php if ( ! empty( $mts_options['mts_comment_date'] ) ) { ?>
					<span class="ago"><?php comment_date( get_option( 'date_format' ) ); ?></span>
				<?php } ?>
				<span class="comment-meta">
					<?php edit_comment_link( __( '( Edit )', 'mythemeshop' ), '  ', '' ) ?>
				</span>
			</div>
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'mythemeshop' ) ?></em>
				<br />
			<?php endif; ?>
			<div class="commentmetadata">
		                <div class="commenttext" itemprop="commentText">
				    <?php comment_text() ?>
		                </div>
				<div class="reply">
                    <i class="fa fa-reply"></i>
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] )) ) ?>
				</div>
			</div>
		</div>
	<!-- WP adds </li> -->
<?php }

/*-----------------------------------------------------------------------------------*/
/*	Excerpt
/*-----------------------------------------------------------------------------------*/

// Increase max length
function mts_excerpt_length( $length ) {
	return 100;
}
add_filter( 'excerpt_length', 'mts_excerpt_length', 20 );

// Remove [...] and shortcodes
function mts_custom_excerpt( $output ) {
  return preg_replace( '/\[[^\]]*]/', '', $output );
}
add_filter( 'get_the_excerpt', 'mts_custom_excerpt' );

// Truncate string to x letters/words
function mts_truncate( $str, $length = 40, $units = 'letters', $ellipsis = '&nbsp;&hellip;' ) {
    if ( $units == 'letters' ) {
        if ( mb_strlen( $str ) > $length ) {
            return mb_substr( $str, 0, $length ) . $ellipsis;
        } else {
            return $str;
        }
    } else {
        $words = explode( ' ', $str );
        if ( count( $words ) > $length ) {
            return implode( " ", array_slice( $words, 0, $length ) ) . $ellipsis;
        } else {
            return $str;
        }
    }
}

if ( ! function_exists( 'mts_excerpt' ) ) {
    function mts_excerpt( $limit = 40 ) {
      return esc_html( mts_truncate( get_the_excerpt(), $limit, 'words' ) );
    }
}

/*-----------------------------------------------------------------------------------*/
/*	Remove more link from the_content and use custom read more
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_content_more_link', 'mts_remove_more_link', 10, 2 );
function mts_remove_more_link( $more_link, $more_link_text ) {
	return '';
}
// shorthand function to check for more tag in post
function mts_post_has_moretag() {
    global $post;
    return strpos( $post->post_content, '<!--more-->' );
}

if ( ! function_exists( 'mts_readmore' ) ) {
    function mts_readmore() {
        ?>
        <div class="readMore">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="nofollow">
                <?php _e( 'Read More', 'mythemeshop' ); ?>
            </a>
        </div>
        <?php 
    }
}

/*-----------------------------------------------------------------------------------*/
/* nofollow to next/previous links
/*-----------------------------------------------------------------------------------*/
function mts_pagination_add_nofollow( $content ) {
    return 'rel="nofollow"';
}
add_filter( 'next_posts_link_attributes', 'mts_pagination_add_nofollow' );
add_filter( 'previous_posts_link_attributes', 'mts_pagination_add_nofollow' );

/*-----------------------------------------------------------------------------------*/
/* Nofollow to category links
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_category', 'mts_add_nofollow_cat' ); 
function mts_add_nofollow_cat( $text ) {
    $text = str_replace( 'rel="category tag"', 'rel="nofollow"', $text ); return $text;
}

/*-----------------------------------------------------------------------------------*/	
/* nofollow post author link
/*-----------------------------------------------------------------------------------*/
add_filter( 'the_author_posts_link', 'mts_nofollow_the_author_posts_link' );
function mts_nofollow_the_author_posts_link ( $link ) {
    return str_replace( '<a href=', '<a rel="nofollow" href=', $link ); 
}

/*-----------------------------------------------------------------------------------*/	
/* nofollow to reply links
/*-----------------------------------------------------------------------------------*/
function mts_add_nofollow_to_reply_link( $link ) {
    return str_replace( '" )\'>', '" )\' rel=\'nofollow\'>', $link );
}
add_filter( 'comment_reply_link', 'mts_add_nofollow_to_reply_link' );

/*-----------------------------------------------------------------------------------*/
/* removes the WordPress version from your header for security
/*-----------------------------------------------------------------------------------*/
function mts_remove_wpversion() {
	return '<!--Theme by MyThemeShop.com-->';
}
add_filter( 'the_generator', 'mts_remove_wpversion' );
	
/*-----------------------------------------------------------------------------------*/
/* Removes Trackbacks from the comment count
/*-----------------------------------------------------------------------------------*/
add_filter( 'get_comments_number', 'mts_comment_count', 0 );
function mts_comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
        $comments = get_comments( 'status=approve&post_id=' . $id );
        $comments_by_type = separate_comments( $comments );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}

/*-----------------------------------------------------------------------------------*/
/* adds a class to the post if there is a thumbnail
/*-----------------------------------------------------------------------------------*/
function has_thumb_class( $classes ) {
    global $post;
    if( has_post_thumbnail( $post->ID ) ) { $classes[] = 'has_thumb'; }
        return $classes;
}
add_filter( 'post_class', 'has_thumb_class' );

/*-----------------------------------------------------------------------------------*/	
/* AJAX Search results
/*-----------------------------------------------------------------------------------*/
function ajax_mts_search() {
    $query = $_REQUEST['q']; // It goes through esc_sql() in WP_Query
    $search_query = new WP_Query( array( 's' => $query, 'posts_per_page' => 3, 'post_status' => 'publish' )); 
    $search_count = new WP_Query( array( 's' => $query, 'posts_per_page' => -1, 'post_status' => 'publish' ));
    $search_count = $search_count->post_count;
    if ( !empty( $query ) && $search_query->have_posts() ) : 
        //echo '<h5>Results for: '. $query.'</h5>';
        echo '<ul class="ajax-search-results">';
        while ( $search_query->have_posts() ) : $search_query->the_post();
            ?><li>
    			<a href="<?php echo esc_url( get_the_permalink() ); ?>">
				    <?php the_post_thumbnail( 'widgetthumb', array( 'title' => '' )); ?>
    				<?php the_title(); ?>
    			</a>
    		</li>	
    		<?php
        endwhile;
        echo '</ul>';
        echo '<div class="ajax-search-meta"><span class="results-count">'.$search_count.' '.__( 'Results', 'mythemeshop' ).'</span><a href="'.esc_url( get_search_link( $query ) ).'" class="results-link">'.__('Show all results.', 'mythemeshop').'</a></div>';
    else:
        echo '<div class="no-results">'.__( 'No results found.', 'mythemeshop' ).'</div>';
    endif;
    wp_reset_postdata();
    exit; // required for AJAX in WP
}

/*-----------------------------------------------------------------------------------*/
/* Redirect feed to feedburner
/*-----------------------------------------------------------------------------------*/
if ( $mts_options['mts_feedburner'] != '' ) {
    function mts_rss_feed_redirect() {
        $mts_options = get_option( MTS_THEME_NAME );
        global $feed;
        $new_feed = $mts_options['mts_feedburner'];
        if ( !is_feed() ) {
                return;
        }
        if ( preg_match( '/feedburner/i', $_SERVER['HTTP_USER_AGENT'] )){
                return;
        }
        if ( $feed != 'comments-rss2' ) {
                if ( function_exists( 'status_header' )) status_header( 302 );
                header( "Location:" . $new_feed );
                header( "HTTP/1.1 302 Temporary Redirect" );
                exit();
        }
    }
    add_action( 'template_redirect', 'mts_rss_feed_redirect' );
}

/*-----------------------------------------------------------------------------------*/
/* Single Post Pagination - Numbers + Previous/Next
/*-----------------------------------------------------------------------------------*/
function mts_wp_link_pages_args( $args ) {
    global $page, $numpages, $more, $pagenow;
    if ( !$args['next_or_number'] == 'next_and_number' )
        return $args; 
    $args['next_or_number'] = 'number'; 
    if ( !$more )
        return $args; 
    if( $page-1 ) 
        $args['before'] .= _wp_link_page( $page-1 )
        . $args['link_before']. $args['previouspagelink'] . $args['link_after'] . '</a>'
    ;
    if ( $page<$numpages ) 
    
        $args['after'] = _wp_link_page( $page+1 )
        . $args['link_before'] . $args['nextpagelink'] . $args['link_after'] . '</a>'
        . $args['after']
    ;
    return $args;
}
add_filter( 'wp_link_pages_args', 'mts_wp_link_pages_args' );

/*-----------------------------------------------------------------------------------*/
/* add <!-- next-page --> button to tinymce
/*-----------------------------------------------------------------------------------*/
add_filter( 'mce_buttons', 'wysiwyg_editor' );
function wysiwyg_editor( $mce_buttons ) {
   $pos = array_search( 'wp_more', $mce_buttons, true );
   if ( $pos !== false ) {
       $tmp_buttons = array_slice( $mce_buttons, 0, $pos+1 );
       $tmp_buttons[] = 'wp_page';
       $mce_buttons = array_merge( $tmp_buttons, array_slice( $mce_buttons, $pos+1 ));
   }
   return $mce_buttons;
}

/*-----------------------------------------------------------------------------------*/
/*  Get Post header animation
/*-----------------------------------------------------------------------------------*/
function mts_get_post_header_effect() {
    wp_reset_postdata();
    global $post;
    $postheader_effect = get_post_meta( $post->ID, '_mts_postheader', true );
    
    return $postheader_effect;
}

/*-----------------------------------------------------------------------------------*/
/*	Custom Gravatar Support
/*-----------------------------------------------------------------------------------*/
function mts_custom_gravatar( $avatar_defaults ) {
    $mts_avatar = get_template_directory_uri() . '/images/gravatar.png';
    $avatar_defaults[$mts_avatar] = 'Custom Gravatar ( /images/gravatar.png )';
    return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'mts_custom_gravatar' );

/*-----------------------------------------------------------------------------------*/
/*  WP Review Support
/*-----------------------------------------------------------------------------------*/

// Set default colors for new reviews
function new_default_review_colors( $colors ) {
    $colors = array(
        'color' => '#FFCA00',
        'fontcolor' => '#fff',
        'bgcolor1' => '#aa1801',
        'bgcolor2' => '#aa1801',
        'bordercolor' => '#aa1801'
    );
  return $colors;
}
add_filter( 'wp_review_default_colors', 'new_default_review_colors' );
 
// Set default location for new reviews
function new_default_review_location( $position ) {
  $position = 'top';
  return $position;
}
add_filter( 'wp_review_default_location', 'new_default_review_location' );

/*-----------------------------------------------------------------------------------*/
/*  WP Mega Menu Configuration
/*-----------------------------------------------------------------------------------*/
function megamenu_parent_element( $selector ) {
    return '#header .container';
}
add_filter( 'wpmm_container_selector', 'megamenu_parent_element' );

/* Change image size */
function megamenu_thumbnails( $thumbnail_html, $post_id ) {
    $thumbnail_html = '<div class="wpmm-thumbnail">';
    $thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
    if(has_post_thumbnail($post_id)):
        $thumbnail_html .= get_the_post_thumbnail($post_id, 'widgetfull', array('title' => ''));
    else:
        $thumbnail_html .= '<img src="'.get_template_directory().'/images/nothumb-widgetfull.png" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
    endif;
    $thumbnail_html .= '</a>';
    
    // WP Review
    $thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
    
    $thumbnail_html .= '</div>';

    return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2 );

//
add_action("wp_ajax_mts_top_post", "mts_top_post");
add_action("wp_ajax_nopriv_mts_top_post", "mts_top_post");
//

/*-----------------------------------------------------------------------------------*/
/*  Thumbnail Upscale
/*  Enables upscaling of thumbnails for small media attachments, 
/*  to make sure it fits into it's supposed location.
/*  Cannot be used in conjunction with Retina Support.
/*-----------------------------------------------------------------------------------*/
if ( empty( $mts_options['mts_retina'] ) ) {
    function mts_image_crop_dimensions( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ) {
        if( !$crop )
        	return null; // let the wordpress default function handle this
    
        $aspect_ratio = $orig_w / $orig_h;
        $size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );
    
        $crop_w = round( $new_w / $size_ratio );
        $crop_h = round( $new_h / $size_ratio );
    
        $s_x = floor( ( $orig_w - $crop_w ) / 2 );
        $s_y = floor( ( $orig_h - $crop_h ) / 2 );
    
        return array( 0, 0, ( int ) $s_x, ( int ) $s_y, ( int ) $new_w, ( int ) $new_h, ( int ) $crop_w, ( int ) $crop_h );
    }
    add_filter( 'image_resize_dimensions', 'mts_image_crop_dimensions', 10, 6 );
}


/*-----------------------------------------------------------------------------------*/
/* Post view count
/* AJAX is used to support caching plugins - it is possible to disable with filter
/* It is also possible to exclude admins with another filter
/*-----------------------------------------------------------------------------------*/
add_filter('the_content', 'mts_view_count_js'); // outputs JS for AJAX call on single
add_action('wp_ajax_mts_view_count', 'ajax_mts_view_count');
add_action('wp_ajax_nopriv_mts_view_count','ajax_mts_view_count');

function mts_view_count_js( $content ) {
    global $post;
    $id = $post->ID;
    $use_ajax = apply_filters( 'mts_view_count_cache_support', true );
    
    $exclude_admins = apply_filters( 'mts_view_count_exclude_admins', false ); // pass in true or a user capability
    if ($exclude_admins === true) $exclude_admins = 'edit_posts';
    if ($exclude_admins && current_user_can( $exclude_admins )) return $content; // do not count post views here

    if (is_single()) {
        if ($use_ajax) {
            // enqueue jquery
            wp_enqueue_script( 'jquery' );
            
            $url = admin_url( 'admin-ajax.php' );
            $content .= "
<script type=\"text/javascript\">
jQuery(document).ready(function($) {
    $.post('".esc_js($url)."', {action: 'mts_view_count', id: '".esc_js($id)."'});
});
</script>";
            
        }

        if (!$use_ajax) {
            mts_update_view_count($id);
        }
    } 

    return $content;
}

function ajax_mts_view_count() {
    // do count
    $post_id = $_POST['id'];
    mts_update_view_count( $post_id );
}
function mts_update_view_count( $post_id ) {
    $count = get_post_meta( $post_id, '_mts_view_count', true );
    update_post_meta( $post_id, '_mts_view_count', $count + 1 );
    
    do_action( 'mts_view_count_after_update', $post_id );
}

/*-----------------------------------------------------------------------------------*/
/*  Color manipulations
/*-----------------------------------------------------------------------------------*/
function mts_hex_to_hsl( $color ){

    // Sanity check
    $color = mts_check_hex_color($color);

    // Convert HEX to DEC
    $R = hexdec($color[0].$color[1]);
    $G = hexdec($color[2].$color[3]);
    $B = hexdec($color[4].$color[5]);

    $HSL = array();

    $var_R = ($R / 255);
    $var_G = ($G / 255);
    $var_B = ($B / 255);

    $var_Min = min($var_R, $var_G, $var_B);
    $var_Max = max($var_R, $var_G, $var_B);
    $del_Max = $var_Max - $var_Min;

    $L = ($var_Max + $var_Min)/2;

    if ($del_Max == 0) {
        $H = 0;
        $S = 0;
    } else {
        if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
        else            $S = $del_Max / ( 2 - $var_Max - $var_Min );

        $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
        $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

        if      ($var_R == $var_Max) $H = $del_B - $del_G;
        else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
        else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

        if ($H<0) $H++;
        if ($H>1) $H--;
    }

    $HSL['H'] = ($H*360);
    $HSL['S'] = $S;
    $HSL['L'] = $L;

    return $HSL;
}

function mts_hsl_to_hex( $hsl = array() ){

    list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

    if( $S == 0 ) {
        $r = $L * 255;
        $g = $L * 255;
        $b = $L * 255;
    } else {

        if($L<0.5) {
            $var_2 = $L*(1+$S);
        } else {
            $var_2 = ($L+$S) - ($S*$L);
        }

        $var_1 = 2 * $L - $var_2;

        $r = round(255 * mts_huetorgb( $var_1, $var_2, $H + (1/3) ));
        $g = round(255 * mts_huetorgb( $var_1, $var_2, $H ));
        $b = round(255 * mts_huetorgb( $var_1, $var_2, $H - (1/3) ));
    }

    // Convert to hex
    $r = dechex($r);
    $g = dechex($g);
    $b = dechex($b);

    // Make sure we get 2 digits for decimals
    $r = (strlen("".$r)===1) ? "0".$r:$r;
    $g = (strlen("".$g)===1) ? "0".$g:$g;
    $b = (strlen("".$b)===1) ? "0".$b:$b;

    return $r.$g.$b;
}

function mts_huetorgb( $v1,$v2,$vH ) {
    if( $vH < 0 ) {
        $vH += 1;
    }

    if( $vH > 1 ) {
        $vH -= 1;
    }

    if( (6*$vH) < 1 ) {
           return ($v1 + ($v2 - $v1) * 6 * $vH);
    }

    if( (2*$vH) < 1 ) {
        return $v2;
    }

    if( (3*$vH) < 2 ) {
        return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
    }

    return $v1;

}

function mts_check_hex_color( $hex ) {
    // Strip # sign is present
    $color = str_replace("#", "", $hex);

    // Make sure it's 6 digits
    if( strlen($color) == 3 ) {
        $color = $color[0].$color[0].$color[1].$color[1].$color[2].$color[2];
    }

    return $color;
}

// Check if color is considered light or not
function mts_is_light_color( $color ){

    $color = mts_check_hex_color( $color );

    // Calculate straight from rbg
    $r = hexdec($color[0].$color[1]);
    $g = hexdec($color[2].$color[3]);
    $b = hexdec($color[4].$color[5]);

    return ( ( $r*299 + $g*587 + $b*114 )/1000 > 130 );
}

// Darken color by given amount in %
function mts_darken_color( $color, $amount = 10 ) {

    $hsl = mts_hex_to_hsl( $color );

    // Darken
    $hsl['L'] = ( $hsl['L'] * 100 ) - $amount;
    $hsl['L'] = ( $hsl['L'] < 0 ) ? 0 : $hsl['L']/100;

    // Return as HEX
    return mts_hsl_to_hex($hsl);
}

// Lighten color by given amount in %
function mts_lighten_color( $color, $amount = 10 ) {

    $hsl = mts_hex_to_hsl( $color );

    // Lighten
    $hsl['L'] = ( $hsl['L'] * 100 ) + $amount;
    $hsl['L'] = ( $hsl['L'] > 100 ) ? 1 : $hsl['L']/100;
    
    // Return as HEX
    return mts_hsl_to_hex($hsl);
}

/*-----------------------------------------------------------------------------------*/
/*  Generate css from background option
/*-----------------------------------------------------------------------------------*/
function mts_get_background_styles( $option_id ) {

    $mts_options = get_option( MTS_THEME_NAME );

    if ( ! isset( $mts_options[ $option_id ]) ) return;

    $background_option = $mts_options[ $option_id ];

    $output = '';

    $background_image_type = isset( $background_option['use'] ) ? $background_option['use'] : '';

    if ( isset( $background_option['color'] ) && !empty( $background_option['color'] ) && 'gradient' !== $background_image_type ) {
        $output .= 'background-color:'.$background_option['color'].';';
    }

    if ( !empty( $background_image_type ) ) {

        if ( 'upload' == $background_image_type ) {

            if ( isset( $background_option['image_upload'] ) && !empty( $background_option['image_upload'] ) ) {
                $output .= 'background-image:url('.$background_option['image_upload'].');';
            }
            if ( isset( $background_option['repeat'] ) && !empty( $background_option['repeat'] ) ) {
                $output .= 'background-repeat:'.$background_option['repeat'].';';
            }
            if ( isset( $background_option['attachment'] ) && !empty( $background_option['attachment'] ) ) {
                $output .= 'background-attachment:'.$background_option['attachment'].';';
            }
            if ( isset( $background_option['position'] ) && !empty( $background_option['position'] ) ) {
                $output .= 'background-position:'.$background_option['position'].';';
            }
            if ( isset( $background_option['size'] ) && !empty( $background_option['size'] ) ) {
                $output .= 'background-size:'.$background_option['size'].';';
            }

        } else if ( 'gradient' == $background_image_type ) {

            $from      = $background_option['gradient']['from'];
            $to        = $background_option['gradient']['to'];
            $direction = $background_option['gradient']['direction'];

            if ( !empty( $from ) && !empty( $to ) ) {

                $output .= 'background: '.$background_option['color'].';';

                if ( 'horizontal' == $direction ) {

                    $output .= 'background: -moz-linear-gradient(left, '.$from.' 0%, '.$to.' 100%);';
                    $output .= 'background: -webkit-gradient(linear, left top, right top, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
                    $output .= 'background: -webkit-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -o-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -ms-linear-gradient(left, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: linear-gradient(to right, '.$from.' 0%,'.$to.' 100%);';
                    $output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=1 );";

                } else {

                    $output .= 'background: -moz-linear-gradient(top, '.$from.' 0%, '.$to.' 100%);';
                    $output .= 'background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$from.'), color-stop(100%,'.$to.'));';
                    $output .= 'background: -webkit-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -o-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: -ms-linear-gradient(top, '.$from.' 0%,'.$to.' 100%);';
                    $output .= 'background: linear-gradient(to bottom, '.$from.' 0%,'.$to.' 100%);';
                    $output .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='".$from."', endColorstr='".$to."',GradientType=0 );";
                }
            }

        } else if ( 'pattern' == $background_image_type ) {

            $output .= 'background-image:url('.get_template_directory_uri().'/images/'.$background_option['image_pattern'].'.png'.');';
        }
    }

    return $output;
}

/*-----------------------------------------------------------------------------------*/
/*  Set $mts_latest_posts_layout global for paged home pages
/*-----------------------------------------------------------------------------------*/
function mts_home_posts_num() {

    if ( is_admin() || ! ( is_home() && is_paged() ) ) {
        return;
    }

    $mts_options = get_option(MTS_THEME_NAME);
    global $mts_latest_posts_layout;
    $mts_latest_posts_layout = 'listlayout';
    if ( !empty( $mts_options['mts_featured_categories'] ) ) {
        foreach ( $mts_options['mts_featured_categories'] as $section ) {
            $category_id = $section['mts_featured_category'];
            if ( 'latest' == $category_id ) {
                $mts_latest_posts_layout = isset( $section['mts_featured_category_layout'] ) ? $section['mts_featured_category_layout'] : 'listlayout';
                break;
            }
        }
    }
}
add_filter('wp_head', 'mts_home_posts_num');
?>
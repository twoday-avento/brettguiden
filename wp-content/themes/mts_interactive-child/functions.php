<?php
$req = false;
add_image_size('admin_preview', 60, 60, true);
add_image_size('user_image', 200, 200, true);
add_image_size('game_image', 200, 200, true);
add_image_size('game_image_single', 400, 400, false);

/**
 * ADD JS & CSS FILES
 */
function theme_scripts_basic() {
    wp_enqueue_script('main_script', get_stylesheet_directory_uri() . '/js/css_browser_selector.js', array(), false, true);
    wp_enqueue_script('main_script', get_stylesheet_directory_uri() . '/js/main.js', array(), false, true);
}

add_action('wp_enqueue_scripts', 'theme_scripts_basic');

function theme_styles_basic() {
    //  get_template_directory_uri() parrent directory url
    wp_enqueue_style('fontello', get_stylesheet_directory_uri() . '/css/fontello/css/fontello.css');
    wp_enqueue_style('fontello-codes', get_stylesheet_directory_uri() . '/css/fontello/css/fontello-codes.css');
    wp_enqueue_style('fontello-embedded', get_stylesheet_directory_uri() . '/css/fontello/css/fontello-embedded.css');
    wp_enqueue_style('fontello-ie7', get_stylesheet_directory_uri() . '/css/fontello/css/fontello-ie7.css');
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    //   wp_enqueue_style('main_style', get_stylesheet_directory_uri() . '/css/main.css');
}

add_action('wp_enqueue_scripts', 'theme_styles_basic');

add_filter('mpp_avatar_override', '__return_true');
add_filter('mpp_hide_avatar_override', '__return_true');

function check_permissions($roles = array()) {

    array_push($roles, 'administrator');

    $allow = false;
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        if (!empty($current_user->roles) && is_array($current_user->roles)) {
            foreach ($current_user->roles as $role) {
                if (in_array($role, $roles)) {
                    $allow = true;
                }
            }
        }
    }
    if (!$allow) {
        wp_redirect('login');
    }
}

function check_visibility($roles = array()) {

    array_push($roles, 'administrator');

    $allow = false;
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        if (!empty($current_user->roles) && is_array($current_user->roles)) {
            foreach ($current_user->roles as $role) {
                if (in_array($role, $roles)) {
                    $allow = true;
                }
            }
        }
    }
    return $allow;
}

/**
 * Virtual controller
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['controller'])) {
    switch ($_POST['controller']) {
        case 'save_review':
            add_action('init', 'save_review');
            break;
        case 'change_password':
            add_action('init', 'change_password');
            break;
        case 'change_user_info':
            add_action('init', 'change_user_info');
            break;
        case 'change_profile_image':
            add_action('init', 'change_profile_image');
            break;
        case 'delete_kritik_review':
            add_action('init', 'delete_kritik_review');
            break;
    }
}

function save_review() {
    Theme\Review::saveReview();
}

function change_profile_image() {
    global $return_action;
    $return_action = Theme\User::changeProfileImage();
}

function change_password() {
    global $return_action;
    $return_action = Theme\User::changePassword();
}

function change_user_info() {
    global $return_action;
    $return_action = Theme\User::changeUserInfo();
}

function delete_kritik_review() {
    global $return_action;
    $return_action = Theme\Review::deleteReview();
}

function custom_pagination($numpages = '', $pagerange = '', $paged = '') {

    if (empty($pagerange)) {
        $pagerange = 2;
    }

    /**
     * This first part of our function is a fallback
     * for custom pagination inside a regular loop that
     * uses the global $paged and global $wp_query variables.
     *
     * It's good because we can now override default pagination
     * in our theme, and use this function in default quries
     * and custom queries.
     */
    global $paged;
    if (empty($paged)) {
        $paged = 1;
    }
    if ($numpages == '') {
        global $wp_query;
        $numpages = $wp_query->max_num_pages;
        if (!$numpages) {
            $numpages = 1;
        }
    }

    /**
     * We construct the pagination arguments to enter into our paginate_links
     * function.
     */
    $link = parse_url(get_pagenum_link(1));

    $link = $link['scheme'] . '://' . $link['host'] . $link['path'];
    $pagination_args = array(
        'base' => $link . '%_%',
        'format' => 'page/%#%',
        'total' => $numpages,
        'current' => $paged,
        'show_all' => False,
        'end_size' => 1,
        'mid_size' => $pagerange,
        'prev_next' => True,
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'type' => 'plain',
        'add_args' => false,
        'add_fragment' => ''
    );

    $paginate_links = paginate_links($pagination_args);

    if ($paginate_links) {
        echo "<nav class='custom-pagination clearfix'>";
        echo "<span class='page-numbers page-num'>Side " . $paged . " av " . $numpages . "</span> ";
        echo $paginate_links;
        echo "</nav>";
    }
}

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    //\PC::debug($_POST);
    if (!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy();
}

function prefix_teammembers_metaboxes_html() {
    global $post;
    $custom = get_post_custom($post->ID);
    $function = isset($custom["function"][0]) ? $custom["function"][0] : '';
    ?>
	<label>Function:</label><input name="function" value="<?php echo $function; ?>">
    <?php
}

/**
 * Add JS to admin edit post
 */
function enqueue_admin_script($hook) {

    if ('post.php' != $hook) {
        return;
    }

    wp_enqueue_script('my_admin_script', get_stylesheet_directory_uri() . '/js/main_admin.js');
    wp_localize_script('my_admin_script', 'extra_admin_data', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}

add_action('admin_enqueue_scripts', 'enqueue_admin_script');

/**
 * AJAX for games list
 */
add_action('wp_ajax_nopriv_post_games_list', 'post_games_list');
add_action('wp_ajax_post_games_list', 'post_games_list');

function post_games_list() {
    $games_list = array();
    $args = array(
        'post_type' => 'spill',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $the_query = new WP_Query($args);

    if ($the_query->have_posts()) {

        while ($the_query->have_posts()) {
            $the_query->the_post();
            $games_list[] = array(
                'title' => get_the_title(),
                'id' => get_the_ID()
            );
        }
        wp_reset_postdata();
    }

    echo json_encode($games_list);
    exit;
}

/**
 * AJAX for vote games
 */
add_action('wp_ajax_nopriv_post_vote_game', 'post_vote_game');
add_action('wp_ajax_post_vote_game', 'post_vote_game');

function post_vote_game() {
    parse_str($_POST['data'], $post_data);
    $vote_score = (int)$post_data['vote_score'];
    $game_id = (int)$post_data['game_id'];
    if ((is_numeric($vote_score) && is_numeric($game_id)) && ($vote_score > 0 && $vote_score <= 10)) {
        $game = new Theme\Game($game_id);

        echo $game->saveVoteScore($vote_score);
    }

    exit;
}

/**
 * Search in title only
 *
 * @param $search
 * @param $wp_query
 *
 * @return string
 */
function search_by_title_only($search, &$wp_query) {
    global $wpdb;
    if (empty($search))
        return $search; // skip processing - no search term in query
    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';
    $search = '';
    $searchand = '';
    foreach ((array)$q['search_terms'] as $term) {
        $term = esc_sql(like_escape($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in())
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}

add_filter('posts_search', 'search_by_title_only', 500, 2);

/**
 * Redirect non-admins to the homepage after logging into the site.
 *
 * @since    1.0
 */
function acme_login_redirect($redirect_to, $request, $user) {
    return (is_array($user->roles) && in_array('administrator', $user->roles)) ? admin_url() : site_url() . '/my-profile/';
}

add_filter('login_redirect', 'acme_login_redirect', 10, 3);

function redirect_non_admin_user() {
    if (is_user_logged_in()) {
        if (!defined('DOING_AJAX') && !current_user_can('administrator')) {
            wp_redirect(site_url() . '/my-profile/');
            exit;
        }
    }
}

add_action('admin_init', 'redirect_non_admin_user');

/* Disable WordPress Admin Bar for all users but admins. */

show_admin_bar(false);

function displayLatestCategories($mts_options) {
    if (empty($mts_options)) {
        $mts_options = get_option(MTS_THEME_NAME);
    }

    echo '<div class="container">';
    echo '<ul>';

    foreach ($mts_options['mts_latest_categories'] as $cat_with_icons) {
        echo '<li>';
        echo '<a href="' . $cat_with_icons['mts_latest_categories_link'] . '">';
        echo '<i class="icon-' . $cat_with_icons['mts_latest_categories_icon'] . '"></i>';
        echo '<div class="name">';
        echo $cat_with_icons['mts_latest_categories_title'];
        echo '</div>';
        echo '</a>';
        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
}

function add_login_link($items, $args) {
    if (!is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . get_permalink('81') . '">Logg inn</a></li>';
    }
    return $items;
}

add_filter('wp_nav_menu_items', 'add_login_link', 10, 2);

function add_loginout_link($items, $args) {
    if (is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . get_permalink('4884') . '">Logut</a></li>';
    }
    return $items;
}

add_filter('wp_nav_menu_items', 'add_loginout_link', 10, 2);

/**
 * CUSTOM META BOX
 */

function custom_users_meta_box_markup() {
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    $args = array('role' => array('Administrator'));
    $user_query = new WP_User_Query($args);

    if (!empty($user_query->results)) {
        ?>
		<h2>Administrators</h2>
        <?php
        foreach ($user_query->results as $user) {
            ?>
			<div>
				<label for=""><input type="checkbox" name="user_email[<?php echo $user->ID; ?>]" value="<?php echo $user->user_email; ?>"><?php echo $user->display_name ?>
				</label>
			</div>
            <?php
        }
    }
    ?>
    <?php
    $args = array('role' => array('Contributor'));
    $user_query = new WP_User_Query($args);

    if (!empty($user_query->results)) {
        ?>
		<h2>Contributors</h2>
        <?php
        foreach ($user_query->results as $user) {
            ?>
			<div>
				<label for=""><input type="checkbox" name="user_email[<?php echo $user->ID; ?>]" value="<?php echo $user->user_email; ?>"><?php echo $user->display_name ?>
				</label>
			</div>
            <?php
        }
    }
}

function add_custom_users_meta_box() {
    add_meta_box("demo-meta-box", "Send emails to: ", "custom_users_meta_box_markup", "spill", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_users_meta_box");

function save_custom_users_meta_box($post_id, $post, $update) {
    global $req;

    if (!$req) {
        $title = 'Nytt spill er publisert "' . get_the_title($post_id) . '"';
        $message = 'Melding: Nytt spill er publisert, og du kan nå anmelde dette: <br>' . PHP_EOL;
        $game_url = get_the_permalink($post_id);
        $message .= '<a href="' . $game_url . '">' . $game_url . '</a><br>' . PHP_EOL;
        $login_url = get_permalink('81');
        $message .= '<br> Logg inn <a href="' . $login_url . '">' . $login_url . '</a>' . PHP_EOL;
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $users_to_email = $_REQUEST['user_email'];
        if ($users_to_email) {
            foreach ($users_to_email as $user_email) {
                wp_mail($user_email, $title, $message,$headers);
            }
            $req = true;
        }
    }
}

add_action("save_post", "save_custom_users_meta_box", 10, 3);
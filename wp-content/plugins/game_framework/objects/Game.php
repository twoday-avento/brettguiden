<?php
namespace Theme;

class Game {
    const POST_TYPE = 'spill';
    const POSTS_PER_PAGE = 15;
    const MAX_GAME_COMMENTS = 4;
    const SESSION_KEY = 'kritikker';
    const GAME_VOTE_TOTAL = 'game_vote_total';
    const GAME_VOTE_COUNT = 'game_vote_count';
    private $id;
    private $game;
    private $paginate;
    private $request;

    /**
     * @return mixed
     */
    public function getGame() {

        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame() {
        $this->game = get_post($this->getId(), OBJECT, 'display');
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function __construct($id = null) {
        if ($id !== false) {
            if (!$id) {
                $id = get_the_ID();
            }
            $this->setId($id);
            $this->setGame();
        }
    }

    /**
     * @param null $id
     *
     * @return \Theme\Publisher
     */
    public function getPublisher($id = null) {
        if (!$id) {
            $id = $this->getId();
        }

        $publisher_id = $this->getMeta($id, Publisher::PUBLISHER_FIELD);
        $publisher = new Publisher($publisher_id);
        return $publisher;
    }

    /**
     * @param null $id
     *
     * @return \Theme\Author
     */
    public function getAuthor($id = null) {
        if (!$id) {
            $id = $this->getId();
        }

        $author_id = $this->getMeta($id, Author::AUTHOR_FIELD);
        $author = new Author($author_id);
        return $author;
    }

    /**
     * @param null $id
     *
     * @return \Theme\Distributor
     */
    public function getDistributor($id = null) {
        if (!$id) {
            $id = $this->getId();
        }

        $distributor_id = $this->getMeta($id, Distributor::DISTRIBUTOR_FIELD);
        $distributor = new Distributor($distributor_id);
        return $distributor;
    }

    public function getMeta($id, $meta, $single = true, $type = 'post') {
        return get_metadata($type, $id, $meta, $single);
    }

    public function getTitle() {
        return $this->getGame()->post_title;
    }

    public function getDescription() {
        return $this->getGame()->post_content;
    }

    public function getExcerpt() {
        $excerpt = trim($this->getGame()->post_excerpt);
        if (!empty($excerpt)) {
            return $excerpt;
        }
        $content = $this->getDescription();
        if (preg_match('/<!--more(.*?)?-->/', $content, $matches)) {
            $content = explode($matches[0], $content, 2);

            return $content[0];
        }

        return null;
    }

    public function getCategories() {
        $terms = get_the_terms($this->getId(), 'kategori');
        if (empty($terms) || !$terms) {
            return null;
        }
        return $terms;
    }

    public function getCategoriesToArray() {
        $return = array();
        $categories = $this->getCategories();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $return[$category->slug] = $category->name;
            }
        }

        return $return;
    }

    public function getNumberOfPlayers() {
        $data_min = $this->getMeta($this->getId(), 'wpcf-number_of_players_min');
        $data_max = $this->getMeta($this->getId(), 'wpcf-number_of_players_max');
        if (empty($data_min) || !$data_min) {
            return null;
        }
        return $data_min . ' - ' . $data_max . ' ' . __('spillere');
    }

    public function getPrice() {
        $data = $this->getMeta($this->getId(), 'wpcf-price');
        if (empty($data) || !$data) {
            return null;
        }
        return $data . __(',-');
    }

    public function getPlayTime() {
        $data_min = $this->getMeta($this->getId(), 'wpcf-time_min');
        $data_max = $this->getMeta($this->getId(), 'wpcf-time_max');

        if (empty($data_min) || !$data_min) {
            return null;
        }
        return $data_min . ' - ' . $data_max . ' ' . __('minutter');
    }

    public function getWinner() {
        $data = $this->getMeta($this->getId(), 'wpcf-winner');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getOther($apply_filers = false) {
        $data = $this->getMeta($this->getId(), 'wpcf-other');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getPurpose($apply_filers = false) {
        $data = $this->getMeta($this->getId(), 'wpcf-purpose');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getSuitableFor($apply_filers = false) {
        $data = $this->getMeta($this->getId(), 'wpcf-suitable_for');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getGameDynamics($apply_filers = false) {
        $data = $this->getMeta($this->getId(), 'wpcf-game_dynamics');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getPreparation($apply_filers = false) {
        $data = $this->getMeta($this->getId(), 'wpcf-preparation');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getYear() {
        $data = $this->getMeta($this->getId(), 'wpcf-year');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getLanguage() {
        $data = $this->getMeta($this->getId(), 'wpcf-language');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getPossibleExpansion() {
        $data = $this->getMeta($this->getId(), 'wpcf-possible_expansion');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getHelperUrl() {
        $data = $this->getMeta($this->getId(), 'wpcf-helper');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getInformationUrl() {
        $data = $this->getMeta($this->getId(), 'wpcf-information');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getComplexityPoints() {
        $data = $this->getMeta($this->getId(), 'wpcf-complexity');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getExperiencePoints() {
        $data = $this->getMeta($this->getId(), 'wpcf-experience');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getSkillsPoints() {
        $data = $this->getMeta($this->getId(), 'wpcf-skills');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getLatencyPoints() {
        $data = $this->getMeta($this->getId(), 'wpcf-latency');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getReviews($post_id = null) {
        global $wpdb;

        $post_id = (int)($post_id) ? $post_id : $this->getId();

        $meta = $wpdb->get_results("SELECT `post_id` FROM `wp_postmeta` WHERE `meta_key`= '_wpcf_belongs_spill_id' AND `meta_value` = " . $post_id);
        $return = array();
        foreach ($meta as $val) {
            $return[] = $val->post_id;
        }

        return $return;
    }

    public function isUserReviewed($user_id = null, $post_id = null) {
        global $wpdb;
        if (!$user_id) {
            return null;
        }
        $post_id = (int)($post_id) ? $post_id : $this->getId();

        $meta = $wpdb->get_results("SELECT `wp_posts`.`post_author` as `post_author` FROM `wp_postmeta`,`wp_posts` WHERE `wp_postmeta`.`post_id` = `wp_posts`.`id` AND `wp_postmeta`.`meta_key`= '_wpcf_belongs_spill_id' AND `wp_postmeta`.`meta_value` = " . $post_id);
        $return = array();
        foreach ($meta as $val) {
            $return[] = $val->post_author;
        }

        return $return;
    }

    public function getGameReviewScore($post_id = null, $recalc = false) {
        if ($recalc) {
            $score_sum = 0;
            $reviews_count = 0;
            $reviews = $this->getReviews($post_id);
            if (count($reviews) < self::MAX_GAME_COMMENTS) {
                return '?';
            }
            foreach ($reviews as $review) {
                $review = new Review($review);
                $score_sum += $review->getTotalScorePercents();
                $reviews_count++;
            }

            $score = (int)round($score_sum / $reviews_count);
        } else {
            $post_id = ($post_id) ? $post_id : $this->getId();
            $score = get_post_meta($post_id, Review::CUSTOM_GAME_SCORE, true);
        }
        if (!$score) {
            return '?';
        }
        return $score;
    }

    public function getImageUrl($size = 'game_image') {
        $thumb_id = get_post_thumbnail_id($this->getId());
        $thumb_url = wp_get_attachment_image_src($thumb_id, $size);
        if ($thumb_url) {
            return $thumb_url[0];
        }
        return get_stylesheet_directory_uri() . '/images/no_game_image.png';
    }

    public function getUrl() {
        return get_permalink($this->getId());
    }

    public function getSummary() {
        $data = $this->getMeta($this->getId(), 'wpcf-summary');
        if (empty($data) || !$data) {
            return null;
        }
        return $data;
    }

    public function getSimilarGames() {
        $games = array();
        $games_ids = $this->getMeta($this->getId(), 'wpcf-similar-games', false);
        if (empty($games_ids) || !$games_ids) {
            return null;
        }

        foreach ($games_ids as $game_id) {
            $games[] =
                array(
                    'title' => get_the_title($game_id),
                    'link' => get_the_permalink($game_id)
                );
        }

        return $games;
    }

    /**
     * @return  Game[]
     */
    public function getHomepageGames() {
        $games = array();
        $args = array(
            'post_type' => 'spill',
            'posts_per_page' => 10,
            'orderby' => 'rand',
            'date_query' => array(
                array(
                    'column' => 'post_modified_gmt',
                    'after' => '3 year ago',

                )
            )
        );
        $games_query = new \WP_Query($args);

        if ($games_query->have_posts()) {
            while ($games_query->have_posts()) {
                $games_query->the_post();
                $games[$games_query->post->ID] = new Game($games_query->post->ID);
            }
        }

        wp_reset_postdata();
        return $games;
    }

    /**
     * @return  Game[]
     */
    public function getGames() {

        $fliter = array();
        $games = array();

        $request = array_merge($_GET,$this->getRequest());
        if (!$request || !isset($request['game-sortby'])) {
            $request['game-order'] = 'DESC';
            $request['game-sortby'] = 'post_date';
            $this->setRequest($request);
        }

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        /**
         * Sort by
         */
        if (isset($request['game-sortby']) && !empty($request['game-sortby'])) {
            switch ($request['game-sortby']) {
                case 'score':
                    $fliter['meta_key'] = Review::CUSTOM_GAME_SCORE;
                    $fliter['orderby'] = 'meta_value_num';
                    break;
                default:
                    $fliter['orderby'] = $request['game-sortby'];
            }
        }
        /**
         * Order by
         */
        if (isset($request['game-order']) && !empty($request['game-order'])) {
            $fliter['order'] = $request['game-order'];
        }

        /**
         * Filter category
         */
        if (isset($request['game-category']) && !empty($request['game-category'])) {

            $fliter['tax_query'] = array(array(
                'taxonomy' => 'kategori',
                'field' => 'slug',
                'terms' => $request['game-category'],
            ));
        }
        /**
         * Search by title
         */
        if (isset($request['game-name']) && !empty($request['game-name'])) {

            $fliter['s'] = $request['game-name'];
            /* $fliter['meta_query'] = array(array(
                 'key' => 'title',
                 'value' => $request['game-name'],
                 'compare' => 'LIKE',
             ));*/
        }

        if(isset($request['utgiver_id'])){
            $fliter['meta_query'] = array(array(
                'key' => '_wpcf_belongs_utgiver_id',
                'value' => $request['utgiver_id'],

            ));
        }
        if(isset($request['distributor_id'])){
            $fliter['meta_query'] = array(array(
                'key' => '_wpcf_belongs_distributor_id',
                'value' => $request['distributor_id'],

            ));
        }
        if(isset($request['utvikleren_id'])){
            $fliter['meta_query'] = array(array(
                'key' => '_wpcf_belongs_utvikleren_id',
                'value' => $request['utvikleren_id'],

            ));
        }



        $args = array(
            'post_type' => 'spill',
            'posts_per_page' => self::POSTS_PER_PAGE,
            'paged' => $paged,
        );




        $args = array_merge($args, $fliter);

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $games[$query->post->ID] = new Game($query->post->ID);
            }
        }

        wp_reset_postdata();
        $this->setPaginate(
            array(
                'found_posts' => $query->found_posts,
                'max_num_pages' => $query->max_num_pages,
                'current_page' => $paged,
                'posts_per_page' => self::POSTS_PER_PAGE,

            )
        );
        return $games;
    }

    public function getGamesSearchForm() {
    }

    public function setRequest() {

        $session_key = self::SESSION_KEY;
        if ($_POST) {
            $this->request = $_SESSION[$session_key] = $_POST;
        } elseif (isset($_SESSION[$session_key])) {
            $this->request = $_SESSION[$session_key];
        } else {
            $this->request = null;
        }

        if (isset($_GET['kategori'])) {
            $this->request = $_SESSION[$session_key] = array('game-category' => $_GET['kategori']);
        } else if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], get_permalink(79)) === FALSE) {
            $this->request = null;
            $_SESSION[$session_key] = null;
        }
        if (!isset($this->request['game-order'])) {
            $request = array();
            $request['game-order'] = 'DESC';
            $request['game-sortby'] = 'post_date';
            if (!$this->request) {
                $this->request = $request;
            } else {
                $this->request = array_merge($this->request, $request);
            }
        }
        return $this;
    }

    public function getRequest() {

        if (!$this->request) {
            $this->setRequest();
        }
        return $this->request;
    }

    public function getPaginate() {
        return $this->paginate;
    }

    public function setPaginate($total) {
        $this->paginate = $total;
        return $this;
    }

    public function canLeaveReview($user) {
        /**
         * var User $user
         */

        if (!$user) {
            return false;
        }

        $reviews = $this->getReviews();
        if (in_array($user->getId(), $this->isUserReviewed($user->getId()))) {
            return true;
        }
        if (count($reviews) >= self::MAX_GAME_COMMENTS) {
            return false;
        };

        return true;
    }

    public function getUserVotesCount($id = null) {
        if (!$id) {
            $id = $this->getId();
        }
        $data = $this->getMeta($id, self::GAME_VOTE_COUNT);
        if (!$data) {
            return 0;
        }
        return $data;
    }

    public function getUserVotesScore($id = null) {
        if (!$id) {
            $id = $this->getId();
        }
        $data = $this->getMeta($id, self::GAME_VOTE_TOTAL);
        if (!$data) {
            return 0;
        }
        return $data;
    }

    public function saveVoteScore($score_add) {
        if (!isset($_SESSION['votes'])) {
            $_SESSION['votes'] = array();
        }
        if ($this->isVoted()) {
            return json_encode(array(
                'success' => 0,
                'message' => __('You already voted for this game')));
        }
        $count = $this->getUserVotesCount() + 1;
        $score = $this->getUserVotesScore() + $score_add;

        array_push($_SESSION['votes'], $this->getId());
        update_post_meta($this->getId(), self::GAME_VOTE_TOTAL, $score);
        update_post_meta($this->getId(), self::GAME_VOTE_COUNT, $count);

        $score_summary = round($score / $count);

        return json_encode(array(
                'success' => 1,
                'message' => __('Takk for din stemme'),
                'data' => array(
                    'score' => $score_summary,
                    'count' => $count
                )
            )
        );
    }

    public function isVoted() {
        if (!isset($_SESSION['votes'])) {
            $_SESSION['votes'] = array();
        }
        if (in_array($this->getId(), $_SESSION['votes'])) {
            return true;
        }
        return false;
    }

    public function getGamesCategories() {
        $categoreis = array();
        $taxonomy = 'kategori';
        $type = 'spill';
        $args = array(
            'taxonomy' => $taxonomy,
            'type' => $type
        );
        $taxonomies = get_categories($args);
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $categoreis[] = $taxonomy;
            }
            return $categoreis;
        }
        return null;
    }
}
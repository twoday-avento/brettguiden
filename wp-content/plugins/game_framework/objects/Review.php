<?php
namespace Theme;

class Review {
    const GAME_REVIEW_FIELD = '_wpcf_belongs_spill_id';
    const POST_TYPE = 'kritiker-evaluering';
    const POSTS_PER_PAGE = 20;
    const CUSTOM_GAME_SCORE = 'custom-game-score';
    private $review;
    private $paginate;
    private $reviews;
    public static $score_fields = array('estetikk', 'tematikk', 'regler', 'originalitet', 'spilldynamikk', 'spilleglede', 'spillverdi');
    public static $score_fields_double = array('spilldynamikk', 'spilleglede', 'spillverdi');

    /**
     * @return Review[]
     */
    public function getReviews() {
        return $this->reviews;
    }

    /**
     * @param mixed $reviews
     */
    public function setReviews($reviews) {
        $this->reviews = $reviews;
        return $this;
    }

    public function getPaginate() {
        return $this->paginate;
    }

    public function setPaginate($total) {
        $this->paginate = $total;
        return $this;
    }

    public function __construct($review = null) {
        if (is_numeric($review)) {
            $review = get_post($review, OBJECT, 'display');
            $this->setReview($review);
        } elseif ($review) {
            $this->setReview($review);
        }
    }

    public function getId() {
        return $this->review->ID;
    }

    public function getPublishDate() {
        $date = date('Y-m-d', strtotime($this->review->post_date));
        return $date;
    }

    public function getReview() {
        return $this->review;
    }

    public function setReview($review) {
        if (is_numeric($review)) {

            $this->review = get_post($review, OBJECT, 'display');
        } elseif ($review) {
            $this->review = $review;
        }

        return $this;
    }

    public function getContent() {
        $review = $this->getReview();

        return $review->post_content;
    }

    public function getScore() {

        $score = array();
        if (!$score || empty($score)) {
            foreach (Review::$score_fields as $item) {
                $score[$item] = get_post_meta($this->getId(), 'wpcf-' . $item, true);
            }
        }
        return $score;
    }

    public function getTotalScorePercents() {

        $total_score = 0;
        $get_score = $this->getScore();
        foreach ($get_score as $key => $score) {
            if (in_array($key, Review::$score_fields_double)) {
                $total_score += $score;
            }
            $total_score += $score;
        }

        //   $total_score = round((($total_score * 100) / (count(Review::$score_fields+Review::$score_fields_double) * 10)));
        return $total_score;
    }

    /**
     * @return User
     */
    public function getAuthor() {
        $review = $this->getReview();
        $author = new User($review->post_author);
        return $author;
    }

    public function getReviewsByAuthorId($author_id = null) {
        $reviews = array();
        if (!$author_id) {
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                $author_id = $current_user->ID;
            } else {
                return null;
            }
        }
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $args = array(
            'post_type' => self::POST_TYPE,
            'post_status' => 'publish',
            'posts_per_page' => self::POSTS_PER_PAGE,
            'paged' => $paged,
            'author' => $author_id,
            'order' => $this->filterAscDesc(),
            'orderby' => $this->filterOrderBy(),
            's' => $this->filterByName()
        );

        $query = new \WP_Query($args);

//        var_dump($query);

        $this->setPaginate(
            array(
                'found_posts' => $query->found_posts,
                'max_num_pages' => $query->max_num_pages,
                'current_page' => $paged,
                'posts_per_page' => self::POSTS_PER_PAGE,

            )
        );
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $reviews[get_the_ID()] = new Review($query->post);
            }
        }
        wp_reset_query();
        $this->setReviews($reviews);
        return $this;
    }

    public function getSpillReviewsByAuthorId($author_id = null) {
        $reviews = array();
        if (!$author_id) {
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();
                $author_id = $current_user->ID;
            } else {
                return null;
            }
        }
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        global $wpdb;
        $query = "
    SELECT wp_posts.ID
    FROM wp_posts
    LEFT JOIN wp_term_relationships ON(wp_posts.ID = wp_term_relationships.object_id)
    LEFT JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
    LEFT JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
    WHERE wp_posts.post_author = 1
    AND wp_term_taxonomy.taxonomy = 'kategori'
    ";

        $query = $wpdb->get_results($query, OBJECT);
        foreach ($query as $post) {
            $review_new = new Review();
            $reviews[$post->ID] = $review_new->setReview($post->ID);
        }

        $this->setReviews($reviews);
        return $this;
    }

    /**
     * @param null $author_id
     *
     * @return Game[]
     */
    public function getReviewedGamesByAuthorId($author_id = null) {
        $games = null;
        $reviews = $this->getReviewsByAuthorId($author_id);
        if ($reviews) {
            foreach ($reviews as $review) {
                $game_id = get_post_meta($review->getId(), '_wpcf_belongs_spill_id', true);

                $games[$game_id] = new Game($game_id);
            }
        }

        return $games;
    }

    /**
     * @return Game
     */
    public function getGame() {
        $game_id = get_post_meta($this->getId(), self::GAME_REVIEW_FIELD, true);
        return new Game($game_id);
    }

    /**
     * @return Review|null
     */
    static function getReviewByReviewerAndGame($game_id, $author_id) {
        $args = array(
            'post_type' => self::POST_TYPE,
            'posts_per_page' => 1,
            'author' => $author_id,
            'meta_query' => array(
                array(
                    'key' => self::GAME_REVIEW_FIELD,
                    'value' => $game_id,
                    'compare' => '='))

        );
        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $query->the_post();
            $post = $query->post;
            wp_reset_query();
            return new Review($post);
        }
        return null;
    }

    static function saveReview($game_id = null) {
        if (!$game_id) {
            if (isset($_POST['game_id'])) {
                $game_id = $_POST['game_id'];
            } else {
                return false;
            }
        }
        $game = new Game($game_id);

        $user = new User();
        $user_id = $user->getId();

        $slug = 'evaluering_' . $game_id . '_' . $user_id;
        $author_id = $user_id;
        $title = $game->getTitle() . ' [' . $user->getName() . ']';
        $post_content = $_POST['content'];

        $post_data = array(
            'post_author' => $author_id,

            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_content' => $post_content,
            'post_name' => $slug,
            'post_title' => $title,
            'post_status' => 'publish',
            'post_type' => self::POST_TYPE
        );

        $review = Review::getReviewByReviewerAndGame($game_id, $user_id);

        if ($review) {
            $post_data['ID'] = $review->getId();
            wp_update_post($post_data);

            foreach (Review::$score_fields as $field) {
                update_post_meta($post_data['ID'], 'wpcf-' . $field, (int)$_POST[$field]);
            }
        } else {
            $comment_id = wp_insert_post($post_data);
            if ($comment_id) {
                add_post_meta($comment_id, '_wpcf_relationship_new', 1);
                add_post_meta($comment_id, Review::GAME_REVIEW_FIELD, $game_id);
                foreach (Review::$score_fields as $field) {
                    add_post_meta($comment_id, 'wpcf-' . $field, (int)$_POST[$field]);
                }
            }
        }
        update_post_meta($game_id, self::CUSTOM_GAME_SCORE, $game->getGameReviewScore($game_id, true));
    }

    function filterByName() {
        $game_name = (isset($_POST['game-name']) ? filter_var($_POST['game-name'], FILTER_SANITIZE_STRING) : false);
        return $game_name;
    }

    function filterByCategory() {
        $game_category = (isset($_POST['game-category']) ? filter_var($_POST['game-category'], FILTER_SANITIZE_STRING) : false);
        return $game_category;
    }

    function filterOrderBy() {
        $game_sort = (isset($_POST['game-sortby']) ? filter_var($_POST['game-sortby'], FILTER_SANITIZE_STRING) : false);
        return $game_sort;
    }

    function filterAscDesc() {
        $game_order = (isset($_POST['game-order']) ? filter_var($_POST['game-order'], FILTER_SANITIZE_STRING) : false);
        return $game_order;
    }

    static function deleteReview() {
        global $postas;
        $postas = $_POST;
        $return['password']['error'] = null;
        $return['password']['success'] = false;
        $review = new Review($postas['review_id']);
        $user = new User;
        if ($user->isAdmin() || $user->getId() == $review->getAuthor()->getId()) {
            $review->delete();
        }
        get_delete_post_link();
        return $postas;
    }
    private function delete(){
        $game = $this->getGame();
        wp_delete_post( $this->getId(), true );
        update_post_meta($game->getId(), self::CUSTOM_GAME_SCORE, $game->getGameReviewScore($game->getId(), true));
    }
}
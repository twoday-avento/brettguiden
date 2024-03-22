<?php

/*
Plugin Name: Game categories widget
Plugin URI: http://www.brettspillguiden.no/
Description: Show all games categories in sidebar
Author: Nerijus
Version: 0.1
Author URI: http://www.brettspillguiden.no/
*/

class game_categories_list extends WP_Widget {

    function __construct() {
        parent::__construct(

            'game_categories_list',

            __('Game Categories Widget', 'game_categories_list'),

            array('description' => __('Widget to display game categories', 'game_categories_list'),)
        );
    }

    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        $taxonomy = 'kategori';
        $type = 'spill';
        $categories = $this->getGamesCategories($taxonomy, $type);
        if ($categories) {
            echo '<ul>'.PHP_EOL;
            foreach ($categories as $category) {
                echo '<li>';
                echo '<a href="/kritikker/?' . $category->taxonomy . '=' . $category->slug . '">' . $category->name . '</a>';
                echo '</li>'.PHP_EOL;
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    private function getGamesCategories($taxonomy, $type) {
        $categoreis = array();
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

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'game_categories_list');
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>"/>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

function wpb_load_widget() {
    register_widget('game_categories_list');
}

add_action('widgets_init', 'wpb_load_widget');
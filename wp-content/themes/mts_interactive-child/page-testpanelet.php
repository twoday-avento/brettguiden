<?php
$mts_options = get_option(MTS_THEME_NAME);
$users = new Theme\User();

?>
<?php get_header(); ?>
    <div id="page" class="game-page">
        <div class="sidebar-with-content">
            <?php if (isset($_GET['games_reviewed_by'])): ?>
                <?php

                $user_query = new \WP_User_Query(array(
                    'fields' => 'id',
                    'posts_per_page' => -1,
                   // 'role' => 'Contributor'
                ));
                $contributors_ids = $user_query->get_results();

                foreach ($contributors_ids as $id): ?>
                    <?php
                    $reviewer = new \Theme\Contributor($id);
                    $reviews = new Theme\Review();
                    $review = $reviews->getReviewsByAuthorId($id);
                    $name = str_replace(' ', '', $reviewer->getName());
                    ?>
                    <?php if ($name == $_GET['games_reviewed_by'] || $reviewer->getId() == $_GET['games_reviewed_by']): ?>
                        <div class="row row-no-margin">
                            <h3 class="featured-category-title"><?php echo $reviewer->getName() ?></h3>
                        </div>
                        <?php foreach ($review->getReviews() as $reviewed_game): ?>
                            <div class="row row-no-margin reviewd-game-row">
                                <div class="col-reviewd-game">
                                    <a href="<?php echo $reviewed_game->getGame()->getUrl() ?>">
                                        <h6 class="reviewd-game-title"><?php echo $reviewed_game->getGame()->getTitle() ?></h6>
                                    </a>
                                    <div class="reviewed-game-comment"><?php echo $reviewed_game->getContent() ?></div>
                                </div>
                                <div class="col-reviewd-game-score">
                                    <span><?php echo $reviewed_game->getGame()->getGameReviewScore() ?></span> /100p
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else:


                $user_query_active = new \WP_User_Query(array(
                    'fields' => 'id',
                    'posts_per_page' => -1,
                    'role' => 'Contributor',
                    'meta_key' => 'wpcf-user-not-active',
                    'meta_value' => '0',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC'
                ));
                $user_query_not_active = new \WP_User_Query(array(
                    'fields' => 'id',
                    'posts_per_page' => -1,
                    'role' => 'Contributor',
                    'meta_key' => 'wpcf-user-not-active',
                    'meta_value' => '1',
                    'orderby' => 'meta_value_num',
                    'order' => 'ASC'
                ));
                $contributors_active = $user_query_active->get_results();
                $contributors_not_active = $user_query_not_active->get_results();
                ?>
                <div class="row row-no-margin">
                    <h3 class="featured-category-title"><?php echo get_the_title() ?></h3>
                </div>
                <?php foreach ($contributors_active as $id): ?>
                    <?php $contributor = new \Theme\Contributor($id);
                    $user = new Theme\User($id);
                    ?>
                    <div class="row row-no-margin contributor-row">
                        <div class="col-photo col-no-padding">
                            <div class="contributor-photo">
                                <?php echo $contributor->getAvatar(); ?>
                            </div>
                        </div>
                        <div class="col-info">
                            <h5 class="reviewers-name">
                                <?php echo $user->getName();

                                if ($contributor->isNotActive()) {
                                    echo ' (' . __('Tidligere Paneldeltager') . ')';
                                }

                                echo ($user->getUserOccupation()) ? ': ' . $user->getUserOccupation() : '';
                                ?>
                            </h5>
                            <div class="reviewers-description">
                                <?php echo($contributor->getDescription() ? $contributor->getDescription() : '-'); ?>
                            </div>
                            <div class="reviewers-fav-game">
                                <strong><?php _e('Favorittspill'); ?>: </strong><?php echo($contributor->getFavourite_Games() ? $contributor->getFavourite_Games() : '-'); ?>
                            </div>
                            <div class="reviewers-link">
                                <?php $url = str_replace(' ', '', $contributor->getName()); ?>
                                <a href="?games_reviewed_by=<?php echo $url ?>"><strong><?php _e('Vis alle kritikker'); ?></strong></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>



                <div class="row row-no-margin">
                    <h3 class="featured-category-title">Tidligere Paneldeltager</h3>
                </div>
                <?php foreach ($contributors_not_active as $id): ?>
                <?php $contributor = new \Theme\Contributor($id);
                $user = new Theme\User($id);
                ?>
                <div class="row row-no-margin contributor-row">
                    <div class="col-photo col-no-padding">
                        <div class="contributor-photo">
                            <?php echo $contributor->getAvatar(); ?>
                        </div>
                    </div>
                    <div class="col-info">
                        <h5 class="reviewers-name">
                            <?php
                            echo $user->getName();
                            echo ($user->getUserOccupation()) ? ': ' . $user->getUserOccupation() : '';
                            ?>
                        </h5>
                        <div class="reviewers-description">
                            <?php echo($contributor->getDescription() ? $contributor->getDescription() : '-'); ?>
                        </div>
                        <div class="reviewers-fav-game">
                            <strong><?php _e('Favorittspill'); ?>: </strong><?php echo($contributor->getFavourite_Games() ? $contributor->getFavourite_Games() : '-'); ?>
                        </div>
                        <div class="reviewers-link">
                            <?php $url = str_replace(' ', '', $contributor->getName()); ?>
                            <a href="?games_reviewed_by=<?php echo $url ?>"><strong><?php _e('Vis alle kritikker'); ?></strong></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>




            <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>
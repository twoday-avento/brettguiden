<?php
global $game;
$helper = new Theme\Helper();
$game = new Theme\Game();
$curr_user = new Theme\User();
$list_url = get_permalink(79);
?>
<div class="row row-no-margin">
    <div class="game-title">
        <h1><?php echo $game->getTitle(); ?></h1>
    </div>
</div>
<div class="row row-no-margin game-main-info">
    <div class="col-sm-6">
        <div class="game-photo">
            <img src="<?php echo $game->getImageUrl('game_image_single'); ?>" alt="<?php echo $game->getTitle() ?>">
        </div>
    </div>
    <div class="col-sm-6">
        <table class="game-total-score">
            <tr>
                <td><?php _e('BSG poeng'); ?></td>
                <td>
                    <span><?php echo $game->getGameReviewScore() ?></span>/100
                </td>
            </tr>
        </table>
        <?php get_template_part('parts/block', 'game_vote') ?>

        <?php if ($game->getComplexityPoints() && $game->getExperiencePoints() && $game->getSkillsPoints() && $game->getLatencyPoints()) { ?>
            <ul class="score_summary clearfix">
                <?php $helper->showScoreLine($game->getComplexityPoints(), __('Kompleksitet'), __('Enkel'), __('Avansert')); ?>
                <?php $helper->showScoreLine($game->getExperiencePoints(), __('Erfaring'), __('Ingen'), __('Ekspert')); ?>
                <?php $helper->showScoreLine($game->getSkillsPoints(), __('Ferdigheter'), __('Hell'), __('Kløkt')); ?>
                <?php $helper->showScoreLine($game->getLatencyPoints(), __('Ventetid'), __('Ingen'), __('Lang')); ?>
            </ul>
        <?php } ?>

        <?php //echo do_shortcode('[wp-review]'); ?>
    </div>
</div>
<div class="row row-no-margin game-main-info second">
    <div class="table_wrap">
        <div class="col-sm-6">
            <dl>
                <!-- Publisher -->
                <?php if ($game->getPublisher()->getName() && $game->getPublisher()->getName() != $game->getTitle()) { ?>
                    <dt><?php _e('Utgiver') ?>:</dt>
                    <dd>
                        <a href="<?php echo $list_url; ?>?utgiver_id=<?php echo $game->getPublisher()->getId(); ?>">
                            <?php echo $game->getPublisher()->getName(); ?>
                        </a>
                    </dd>
                <?php } ?>
                <!-- Developer -->
                <?php if ($game->getAuthor()->getName() && $game->getAuthor()->getName() != $game->getTitle()) { ?>
                    <dt><?php _e('Utvikler') ?>:</dt>
                    <dd>
                        <a href="<?php echo $list_url; ?>?utvikleren_id=<?php echo $game->getAuthor()->getId(); ?>">
                            <?php echo $game->getAuthor()->getName(); ?>
                        </a>
                    </dd>
                <?php } ?>
                <!-- Distributor -->
                <?php if ($game->getDistributor()->getName() && $game->getDistributor()->getName() != $game->getTitle()) { ?>
                    <dt><?php _e('Distributør') ?>:</dt>
                    <dd>
                        <a href="<?php echo $list_url; ?>?distributor_id=<?php echo $game->getDistributor()->getId(); ?>">
                            <?php echo $game->getDistributor()->getName(); ?>
                        </a>
                    </dd>
                <?php } ?>
                <!-- Categories -->
                <?php if ($game->getCategoriesToArray()) { ?>
                    <dt><?php _e('Kategori') ?>:</dt>
                    <dd><?php foreach ($game->getCategoriesToArray() as $slug => $value) { ?>
                            <a href="<?php echo $list_url . '?kategori=' . $slug ?>"><?php echo $value ?></a>
                        <?php } ?>
                    </dd>
                <?php } ?>
                <!-- Number Of Players -->
                <?php if ($game->getNumberOfPlayers()) { ?>
                    <dt><?php _e('Antall spillere') ?>:</dt>
                    <dd><?php echo $game->getNumberOfPlayers(); ?></dd>
                <?php } ?>
                <!-- Price -->
                <?php if ($game->getPrice()) { ?>
                    <dt><?php _e('Pris') ?>:</dt>
                    <dd><?php echo $game->getPrice(); ?></dd>
                <?php } ?>
                <!-- Play Time -->
                <?php if ($game->getPlayTime()) { ?>
                    <dt><?php _e('Tidsbruk') ?>:</dt>
                    <dd><?php echo $game->getPlayTime(); ?></dd>
                <?php } ?>
                <!-- Similar games -->
                <?php if ($game->getSimilarGames()) { ?>
                    <dt><?php _e('Lignende spill') ?>:</dt>
                    <dd><?php foreach ($game->getSimilarGames() as $similarGame) { ?>
                            <a href="<?php echo $similarGame['link'] ?>"><?php echo $similarGame['title'] ?></a>
                        <?php } ?>
                    </dd>
                <?php } ?>
                <!-- Winner -->
                <?php if ($game->getWinner()) { ?>
                    <dt><?php _e('Utmerkelser') ?>:</dt>
                    <dd><?php echo $game->getWinner(); ?></dd>
                <?php } ?>
                <!-- Other -->
                <?php if ($game->getOther()) { ?>
                    <dt><?php _e('Annet') ?>:</dt>
                    <dd><?php echo $game->getOther(); ?></dd>
                <?php } ?>
            </dl>
        </div>
        <div class="col-sm-6">
            <dl>
                <!-- Purpose -->
                <?php if ($game->getPurpose()) { ?>
                    <dt><?php _e('Målsetting') ?>:</dt>
                    <dd><?php echo $game->getPurpose(); ?></dd>
                <?php } ?>
                <!-- Suitable For -->
                <?php if ($game->getSuitableFor()) { ?>
                    <dt><?php _e('Passer for') ?>:</dt>
                    <dd><?php echo $game->getSuitableFor(); ?></dd>
                <?php } ?>
                <!-- Game Dynamics -->
                <?php if ($game->getGameDynamics()) { ?>
                    <dt><?php _e('Spilldynamikk') ?>:</dt>
                    <dd><?php echo $game->getGameDynamics(); ?></dd>
                <?php } ?>
                <!-- Preparation -->
                <?php if ($game->getPreparation()) { ?>
                    <dt><?php _e('Forbredelser') ?>:</dt>
                    <dd><?php echo $game->getPreparation(); ?></dd>
                <?php } ?>
                <!-- Year -->
                <?php if ($game->getYear()) { ?>
                    <dt><?php _e('År') ?>:</dt>
                    <dd><?php echo $game->getYear(); ?></dd>
                <?php } ?>
                <!-- Language -->
                <?php if ($game->getLanguage()) { ?>
                    <dt><?php _e('Språk') ?>:</dt>
                    <dd><?php echo $game->getLanguage(); ?></dd>
                <?php } ?>
                <!-- Possible game expansion -->
                <?php if ($game->getPossibleExpansion()) { ?>
                    <dt><?php _e('Ekspansjonsmuligheter'); ?>:</dt>
                    <dd><?php echo $game->getPossibleExpansion(); ?></dd>
                <?php } ?>
                <!-- Help url -->
                <?php if ($game->getHelperUrl()) { ?>
                    <dt><?php _e('Hjelpemidler'); ?>:</dt>
                    <dd>
                        <a href="<?php echo $game->getHelperUrl(); ?>" target="_blank"><?php echo $game->getHelperUrl(); ?></a>
                    </dd>
                <?php } ?>
                <!-- Game info url -->
                <?php if ($game->getInformationUrl()) { ?>
                    <dt><?php _e('Ytterligere opplysninger'); ?>:</dt>
                    <dd>
                        <a href="<?php echo $game->getInformationUrl(); ?>" target="_blank"><?php echo $game->getInformationUrl(); ?></a>
                    </dd>
                <?php } ?>
            </dl>
        </div>
    </div>
</div>
<div class="row row-no-margin">
    <div class="col-xs-12 col-no-padding game-description">
        <p>
            <?php echo $helper->applyFilters($game->getDescription()); ?>
        </p>
    </div>
</div>
<?php $reviews = $game->getReviews();
$count_reviews = count($reviews);
?>
<?php if ($reviews): ?>
    <?php if ($count_reviews >= 4) { ?>
        <div class="row row-no-margin">
            <p class="game-reviewer-title"><?php _e('HVA MENER BSG PANELET OM SPILLET'); ?></p>
        </div>
    <?php } ?>
    <?php foreach ($reviews as $review) {
        $review = new Theme\Review($review);
        $user = $review->getAuthor();
        if ($count_reviews >= 4 || $curr_user->isAdmin() || $curr_user->getId() == $user->getId()) {
            ?>
            <div class="row  game-reviewer">
                <div class="col-xs-12  ">
                    <div class="game-reviewer-photo">
                        <img src="<?php echo $user->getImageUrl(); ?>" alt="<?php echo $user->getName() ?>">
                    </div>
                    <div class="game-reviewer-name">
                        <?php echo $user->getName();
                        echo ($user->getUserOccupation()) ? ': ' . $user->getUserOccupation() : ''; ?>
                        <?php if ($curr_user->getId() == $user->getId() || $curr_user->isAdmin()) { ?>
                            <div class="delete_btn">


                            <form action="<?php echo $game->getUrl(); ?>" method="post" class="delete_review_form">
                                <input type="hidden" name="controller" value="delete_kritik_review">
                                <input type="hidden" name="review_id" value="<?php echo $review->getId(); ?>">
                                <button type="submit"><i class="icon-trash-empty"></i></button>
                            </form>
                            </div><?php } ?>
                        <div class="game-reviewer-points">
                            GA SPILLET <?php echo $review->getTotalScorePercents() ?> POENG AV 100
                        </div>
                        <div class="game-reviewer-date">
                            <?php echo $review->getPublishDate(); ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="game-reviewer-text">
                        <?php echo $helper->applyFilters($review->getContent()) ?>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="reviewers-link">
                        <?php $url = str_replace(' ', '', $user->getName()); ?>
                        <a href="<?php echo get_permalink(129); ?>?games_reviewed_by=<?php echo $url ?>"><strong><?php _e('Vis alle kritikker'); ?></strong></a>
                    </div>
                </div>
            </div>
        <?php }
    } ?>
<?php else: ?>
    <?php //echo 'No reviews'; ?>
<?php endif; ?>
<?php get_template_part('parts/block', 'kritikker_comment'); ?>
<div class="row row-no-margin">
    <p class="game-reviewer-title"><?php _e('HVA MENER LESERNE OM SPILLET'); ?></p>
</div>
<div id="disqus_thread"></div>
<script type='text/javascript'>
    /* <![CDATA[ */
    var embedVars = {
        "disqusConfig": {"platform": "wordpress@<?php bloginfo('version'); ?>", "language": "nb"},
        "disqusIdentifier": '<?php echo $game->getId(); ?> <?php echo get_bloginfo('wpurl'); ?>?p=<?php echo $game->getId(); ?>',
        "disqusShortname": "brettspillguiden",
        "disqusTitle": '<?php echo $game->getTitle(); ?>',
        "disqusUrl": '<?php echo $game->getUrl() ?>',
        "options": {"manualSync": false},
        "postId": "<?php echo $game->getId(); ?>"
    };
    /* ]]> */
</script>
<script type='text/javascript' src='<?php echo plugins_url('disqus-comment-system/media/js/disqus.js'); ?>?ver=<?php bloginfo('version'); ?>'></script>

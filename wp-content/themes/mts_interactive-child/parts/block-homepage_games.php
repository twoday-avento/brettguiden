<?php
$game_ob = new \Theme\Game(false);

$games = $game_ob->getHomepageGames();

?>
<div class="block-homepage_games">
    <ul>
        <?php foreach ($games as $game) { ?>
            <li>
                <div class="row">
                    <div class="col-lg-3 col-sm-12">
                        <a href="<?php echo $game->getUrl(); ?>">
                            <img src="<?php echo $game->getImageUrl(); ?>" alt="<?php echo $game->getTitle(); ?>">
                        </a>
                    </div>
                    <div class="col-lg-9 col-sm-12">
                        <div class="description">
                            <a href="<?php echo $game->getUrl(); ?>">
                                <h3><?php echo $game->getTitle(); ?></h3>
                                <?php echo $game->getExcerpt(); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
        <?php } ?>
    </ul>
</div>
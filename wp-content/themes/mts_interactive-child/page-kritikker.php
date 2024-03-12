<?php
//check_permissions(array('contributor'));
get_header();

$game_ob = new Theme\Game(false);
$games = $game_ob->getGames();
$request = $game_ob->getRequest();
$list_url = get_permalink(get_the_ID());
?>


    <div id="page" class="game-list-page">
    <div class="sidebar-with-content">
        <div class="row row-no-margin">
            <h3 class="featured-category-title"><?php _e('Kritikker'); ?></h3>
        </div>
        <div class="row">
            <form class="game-list-search" method="post" action="<?php echo get_permalink(get_the_ID()); ?>">
                <div class="col-sm-10 col-no-padding">
                    <div class="col-sm-3">
                        <label for="game-name"><?php _e('Navn'); ?></label>
                        <input type="text" name="game-name" id="game-name" value="<?php echo (isset($request['game-name']))?$request['game-name']:''; ?>">
                    </div>
                    <div class="col-sm-3">
                        <label for="game-category"><?php _e('Spillkategori'); ?></label>
                        <select name="game-category" id="game-category">
                            <option value=""><?php _e('Kategori'); ?></option>
                            <?php

                            $categories = $game_ob->getGamesCategories();

                            foreach ($categories as $category): ?>
                                <option value="<?php echo $category->slug; ?>" <?php echo (isset($request['game-category']) && $request['game-category'] == $category->slug)?'selected':'' ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="game-sortby"><?php _e('Sorter etter'); ?></label>
                        <select name="game-sortby" id="game-sortby">

                            <?php
                            $sortByArray = array(
                                'title'=>__('Tittel'),
                                'score'=>__('BSG score'),
                                'post_date'=>__('Publisert')
                            );
                            foreach ($sortByArray as $key => $val){ ?>
                                <option <?php echo  (isset($request['game-sortby']) && $key == $request['game-sortby'])?'selected':''; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                           <?php  }
                            ?>


                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="game-order"><?php _e('Rekkefølge'); ?></label>
                        <select name="game-order" id="game-order">

                            <?php
                            $orderByArray = array(
                                'ASC'=>__('Økende'),
                                'DESC'=>__('Synkende')
                            );
                            foreach ($orderByArray as $key => $val){ ?>
                                <option <?php echo  (isset($request['game-order']) && $key == $request['game-order'])?'selected':''; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                            <?php  } ?>

                        </select>
                    </div>
                </div>
                <div class="col-sm-2 col-no-padding">
                    <button type="submit"><?php _e('Søk'); ?></button>
                </div>
            </form>
        </div>
        <div class="games-list">
            <?php if($games){ ?>

            <?php foreach ($games as $game): ?>
                <div class="row row-no-margin game-list-row">
                    <div class="col-sm-2 col-no-padding">
                        <div class="game-list-photo">
                            <a href="<?php echo $game->getUrl() ?>"><img src="<?php echo $game->getImageUrl(); ?>" alt=""></a>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="game-list-title">
                            <a href="<?php echo $game->getUrl() ?>"><?php echo $game->getTitle(); ?></a>
                        </div>
                        <div class="game-list-text">
                            <?php echo $game->getExcerpt(); ?>
                        </div>
                    </div>
                    <div class="col-sm-3 game-list-links">
                        <ul>
                            <li><?php _e('Utgiver'); ?>:
                                <a href="<?php echo $list_url; ?>?utgiver_id=<?php echo $game->getPublisher()->getId(); ?>">
                                    <?php echo $game->getPublisher()->getName(); ?>
                                </a>
                             </li>
                            <li><?php _e('Utvikler'); ?>:
                                <a href="<?php echo $list_url; ?>?utvikleren_id=<?php echo $game->getAuthor()->getId(); ?>">
                                    <?php echo $game->getAuthor()->getName(); ?>
                                </a>
                            </li>
                            <li><?php _e('Distributør'); ?>:
                                <a href="<?php echo $list_url; ?>?distributor_id=<?php echo $game->getDistributor()->getId(); ?>">
                                    <?php echo $game->getDistributor()->getName(); ?>
                                </a>
                            </li>
                            <li><?php _e('Kategori'); ?>:

                                <?php foreach ($game->getCategoriesToArray() as $key => $val){ ?>
                                <a href="/kritikker/?kategori=<?php echo $key; ?>">                               <?php echo $val; ?></a>
                        <?php } ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-2 col-no-padding">
                        <div class="game-list-score">
                            <span><?php echo $game->getGameReviewScore(); ?></span> /100p
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php
            $paginate = $game_ob->getPaginate();
            if (function_exists('custom_pagination')) {
                custom_pagination($paginate['max_num_pages']);
            }
            ?>
            <?php }else{ ?>
                <!-- to-do: style no games message -->
            <h1>No games found!</h1>
            <?php } ?>
        </div>
    </div>
<?php get_sidebar(); ?>
<?php get_footer() ?>
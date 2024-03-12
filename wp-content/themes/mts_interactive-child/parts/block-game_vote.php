<?php global $game;
$count = $game->getUserVotesCount();
$vote = $game->getUserVotesScore();
if ($count < 1) {
    $count_delim = 1;
} else {
    $count_delim = $count;
}
$vote_summary = round($vote / $count_delim);
?>
<div id="user_vote">
    <form id="user_vote_form" action="">
        <!-- <label for="vote_score"><?php _e('Ranger spillet selv'); ?>:</label><br>
        <select name="vote_score" id="vote_score">
            <option value="">-</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>

-->
        <input type="hidden" value="<?php echo $game->getId(); ?>" name="game_id">
        <input type="hidden" value="0" name="vote_score" id="vote_score">
        <div class="user_vote_summary">
            <?php _e('Spillets rangering'); ?>:
            <span id="total_score"><?php echo $vote_summary; ?></span>/10 <?php _e('poeng'); ?> (<span id="total_count"><?php echo $count ?></span> <?php _e('stemmer'); ?>)
        </div>
        <div class="user_vote_line clearfix">
            <?php for ($i = 1; $i <= 10; $i++) {
                if (!$game->isVoted()) {
                    ?>
                    <button type="button" name="vote_score" value="<?php echo $i; ?>" data-score="<?php echo $i; ?>" class="icon-star icon_<?php echo $i; ?> <?php echo ($i <= $vote_summary) ? 'active' : 'pasive'; ?>"></button>
                <?php } else { ?>
                    <i class="icon-star <?php echo ($i <= $vote_summary) ? 'active' : 'pasive'; ?>"></i>
                <?php }
            } ?>
        </div>
        <div class="message"></div>
    </form>
</div>

<?php
global $game;
$user = new Theme\User();
$user_id = $user->getId();

$review = Theme\Review::getReviewByReviewerAndGame($game->getId(), $user_id);

$content = '';
$score = array();

if ($review) {
    $content = $review->getContent();
    $score = $review->getScore();
}
?>
<?php
if (check_visibility(array('contributor')) && $game->canLeaveReview($user)) { ?>
    <h6 class="featured-category-title">
        <?php if ($game->isUserReviewed($user->getId())) {
            _e('Edit');
        } else {
            _e('Leave');
        } ?>
        comment
    </h6>
    <form action="" method="post" class="critic-comment-form">
        <div class="row">
            <div class="col-sm-6">
                <input type="hidden" name="controller" value="save_review">
                <input type="hidden" name="game_id" value="<?php echo $game->getId(); ?>">
                <label for="review_comment_textarea"><?php _e('Comment') ?></label>
                <textarea id="review_comment_textarea" name="content" id="" cols="30" rows="10"><?php echo $content ?></textarea>
            </div>
            <div class="col-sm-6">
                <ul class="clearfix">
                    <?php
                    foreach (Theme\Review::$score_fields as $field) { ?>
                        <li>
                            <label for="<?php echo $field ?>"><?php echo $field ?></label>
                            <select name="<?php echo $field ?>" id="<?php echo $field ?>">
                                <option value=""><?php _e('Select') ?></option>
                                <?php for ($i = 1; $i <= 10; $i++) {
                                    $selected = '';
                                    if (isset($score[$field]) && $score[$field] == $i) {
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </li>
                    <?php } ?>
                    <li>
                        <button type="submit" name="save">Save</button>
                    </li>
                </ul>
            </div>
        </div>
    </form>
<?php } ?>

<?php comments_template('', true); ?>

jQuery(document).ready(function ($) {
    "use strict";
    /**
     * Vote form
     */

    $('#user_vote_form button.icon-star').hover(function () {
        $(this).addClass('selection').prevAll().addClass('selection');

    }, function () {
        $('#user_vote_form .icon-star').removeClass('selection');

    });
    $(document).on('click', '#user_vote_form .icon-star', function (e) {
        $('#vote_score').val(($(this).val()));
        $('#user_vote_form').submit();
    });
    $(document).on('submit', '#user_vote_form', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $vote = $this.find('#vote_score');
        var $vote_score = $vote.val();
        var $post_data = $this.serialize();
        console.log($post_data);
        if ($vote_score > 0) {
            $.post(
                ajaxurl,
                {
                    action: 'post_vote_game',
                    data: $post_data
                },
                function ($data) {
                    console.log($data);
                    console.log($data.success);
                    if ($data.success == 1) {
                        $('#total_score').html($data.data.score);
                        $('#total_count').html($data.data.count);
                        $('#user_vote .message').html($data.message).slideDown();

                        var $stars = '';
                        var $curr = 0;
                        var $class;
                        for (var i = 1; i <= 10; i++) {
                            $curr++;
                            if ($curr <= $data.data.score) {
                                $class = 'active';
                            } else {
                                $class = 'pasive';
                            }

                            $stars += '<i class="icon-star ' + $class + '"></i>';
                        }

                        $('.user_vote_line').html($stars);
                    } else {
                        $('#user_vote .message').html($data.message).slideDown();

                    }
                }
                , 'json');
        }
    });
    $(document).on('submit','form.delete_review_form',function (e) {
        return confirm('Are you sure?');
    });
});
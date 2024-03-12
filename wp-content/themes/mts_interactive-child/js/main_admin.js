jQuery(document).ready(function ($) {
    "use strict";
    var $games_list = false;
    callDataChanger();

    $(document).on('click', '.js-wpt-repadd.wpt-repadd.button.button-small.button-primary-toolset', function () {
        setTimeout(function () {
            callDataChanger()
        }, 500);

    });
    function callDataChanger() {
        if ($games_list) {
            changeInputToSelect($games_list);
        } else {
            $.post(extra_admin_data.ajax_url, {action: 'post_games_list'}, function ($data) {
                $games_list = $data;
                changeInputToSelect($data);
            }, 'json');
        }


    }

    function changeInputToSelect($select_json) {
        $('input[name^="wpcf\\[similar\\-games\\]"]').each(function () {
            var $this = $(this);

            if ($this.hasClass('select_updated')) {
                return;
            }
            $this.addClass('select_updated');


            var $name = $this.attr('name');
            var $value = $this.val();
            var $parent = $this.parent('div');
            /* $this.css('width', '100px');

                         console.log($name);
             console.log($value);
             console.log($parent.html());*/

            $(this).hide();

            var s = $("<select id=\"selectId\" name=\"selectName\" />");
            $("<option />", {value: '', text: 'select'}).appendTo(s);
            $.each($select_json, function ($key, $val) {
                var $selected = false;
                if ($val.id == $value) {
                    $selected = true;
                }
                $("<option />", {value: $val.id, text: $val.title, selected: $selected}).appendTo(s);
            });
            s.appendTo($parent).on('change', function () {
                $this.val($(this).val());
            });
        });
    }
});
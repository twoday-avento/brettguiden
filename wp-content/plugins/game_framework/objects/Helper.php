<?php
namespace Theme;

class Helper {

    public function applyFilters($data) {
        return apply_filters('the_content', $data);
    }

    public function newLineToBreak($data) {
        return nl2br(trim($data));
    }

    public function showMessages($data) {

        foreach ($data as $key => $val) {
            if ($key != 'success') {
                echo '<div class="' . $key . '">';
            }

            if (is_array($val)) {
                $this->showMessages($val);
            } elseif (strlen($val) > 2) {
                echo '<div>';
                echo $val;
                echo '</div>';
            }
            if ($key != 'success') {
                echo '</div>';
            }
        }
    }

    public function showScoreLine($score = null, $title = '', $s_title = '', $e_title='') {
        if ($score > 0) {
            echo '<li class="clearfix">';
            echo '<h6>' . $title . '</h6>';
            echo '<div class="score_titles">';
            echo '<div class="left">'.$s_title.'</div>';
            echo '<div class="right">'.$e_title.'</div>';
            echo '</div>';
            echo '<div class="score_line clearfix">';
            for ($i = 1; $i <= 7; $i++) {
                $current = ($score == $i)?'current':'';
                echo '<div class="score_line_unit '.$current.' unit_no_'.$i.'">&nbsp;</div>';
            }
            echo '</div>';
            echo '</li>';
        }
    }
}
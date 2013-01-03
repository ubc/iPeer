<div id='SurveyResults'>
<?php
$ques_num = 1;
foreach ($questions as $ques) {
    if ($ques['type'] == 'M') {
        echo '<h3>'.$ques_num.') '.$ques['prompt'].'</h3>';
        // a response is given
        if (isset($answers[$ques['id']])) {
            $responses = boldSelected($ques['Response'], $answers[$ques['id']]);
            echo '<ol>';
            foreach ($responses as $response) {
                echo '<li>'.$response.'</li>';
            }
            echo '</ol>';
        // no response
        } else {
            echo '<ol>';
            foreach ($ques['Response'] as $response) {
                echo '<li>'.$response['response'].'</li>';
            }
            echo '</ol>';
        }
        $ques_num++;
    } else if ($ques['type'] == 'C') {
        echo '<h3>'.$ques_num.') '.$ques['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['id']])) {
            $responses = boldSelected($ques['Response'], $answers[$ques['id']]);
            echo '<ul>';
            foreach ($responses as $response) {
                echo '<li>'.$response.'</li>';
            }
            echo '</ul>';
        // no response
        } else {
            echo '<ul>';
            foreach ($ques['Response'] as $response) {
                echo '<li>'.$response['response'].'</li>';
            }
            echo '</ul>';
        }
        $ques_num++;
    } else if ($ques['type'] == 'S') {
        echo '<h3>'.$ques_num.') '.$ques['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['id']])) {
            echo '<p><i>'.$answers[$ques['id']]['0']['SurveyInput']['response_text'].'</i></p>';
        // no response
        } else {
            echo '<p>NO RESPONSE</p>';
        }
        $ques_num++;
    } else if ($ques['type'] == 'L') {
        echo '<h3>'.$ques_num.') '.$ques['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['id']])) {
            echo '<p><i>'.$answers[$ques['id']]['0']['SurveyInput']['response_text'].'</i></p>';
        // no response
        } else {
            echo '<p>NO RESPONSE</p>';
        }
        $ques_num++;
    }
}

function boldSelected($choices, $selected) {
    $options = array();
    $answers = array();
    $data = array();

    // grabbing all the choices
    foreach ($choices as $choice) {
        $options[] = $choice['response'];
    }
    // grabbing all the chosen values
    foreach ($selected as $select) {
        $answers[] = $select['SurveyInput']['response_text'];
    }

    foreach ($options as $option) {
        // option is chosen
        if (in_array($option, $answers)) {
            $data[] = '<strong class="green">'.$option.'</strong>';
        // option is not chosen
        } else {
            $data[] = $option;
        }
    }

    return $data;
}
?>
<div style="text-align: center;">
<input type="button" name="Back" value="Back" onclick="javascript:(history.length > 1 ? history.back() : window.close());">
</div>
</div>

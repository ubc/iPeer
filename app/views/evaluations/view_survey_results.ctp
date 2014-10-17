<h2><?php echo $name; ?></h2>
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
        if (isset($answers[$ques['id']]) &&
            !empty($answers[$ques['id']]['0']['SurveyInput']['response_text'])
        ) {
            echo '<p>'.$answers[$ques['id']]['0']['SurveyInput']['response_text'].'</p>';
        // no response
        } else {
            echo '<p class="noanswer">-- No Answer --</p>';
        }
        $ques_num++;
    } else if ($ques['type'] == 'L') {
        echo '<h3>'.$ques_num.') '.$ques['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['id']]) &&
            !empty($answers[$ques['id']]['0']['SurveyInput']['response_text'])
        ) {
            echo '<pre wrap="hard">'.$answers[$ques['id']]['0']['SurveyInput']['response_text'].'</pre>';
        // no response
        } else {
            echo '<p class="noanswer">-- No Answer --</p>';
        }
        $ques_num++;
    }
}

function boldSelected($choices, $selected) {
    $answers = array();
    $data = array();
    // grabbing all the choices
    $options = Set::combine($choices, '{n}.id', '{n}.response');

    // grabbing all the chosen values
    foreach ($selected as $select) {
        if (null != $select['SurveyInput']['response_text']) {
            // if response text is available
            $answers[] = $select['SurveyInput']['response_text'];
        } else {
            // otherwise use response id
            $answers[] = isset($select['SurveyInput']['response_id']) ? $options[$select['SurveyInput']['response_id']] : "";
        }
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

<div id='SurveyResults'>
<?php
$ques_num = 1;
foreach ($questions as $ques) {
    if ($ques['Question']['type'] == 'M') {
        echo '<h3>'.$ques_num.') '.$ques['Question']['prompt'].'</h3>';
        // a response is given
        if (isset($answers[$ques['Question']['id']])) {
            $responses = boldSelected($ques['Question']['Responses'], $answers[$ques['Question']['id']]);
            echo '<ol>';
            foreach ($responses as $response) {
                echo '<li>'.$response.'</li>';
            }
            echo '</ol>';
        // no response
        } else {
            echo '<ol>';
            foreach ($ques['Question']['Responses'] as $response) {
                echo '<li>'.$response['response'].'</li>';
            }
            echo '</ol>';
        }
        $ques_num++;
    } else if ($ques['Question']['type'] == 'C') {
        echo '<h3>'.$ques_num.') '.$ques['Question']['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['Question']['id']])) {
            $responses = boldSelected($ques['Question']['Responses'], $answers[$ques['Question']['id']]);
            echo '<ul>';
            foreach ($responses as $response) {
                echo '<li>'.$response.'</li>';
            }
            echo '</ul>';
        // no response
        } else {
            echo '<ul>';
            foreach ($ques['Question']['Responses'] as $response) {
                echo '<li>'.$response['response'].'</li>';
            }
            echo '</ul>';
        }
        $ques_num++;
    } else if ($ques['Question']['type'] == 'S') {
        echo '<h3>'.$ques_num.') '.$ques['Question']['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['Question']['id']])) {
            echo '<p><i>'.$answers[$ques['Question']['id']]['0']['SurveyInput']['response_text'].'</i></p>';
        // no response
        } else {
            echo '<p>NO RESPONSE</p>';
        }
        $ques_num++;
    } else if ($ques['Question']['type'] == 'L') {
        echo '<h3>'.$ques_num.') '.$ques['Question']['prompt'].'</h3>';
        // response is given
        if (isset($answers[$ques['Question']['id']])) {
            echo '<p><i>'.$answers[$ques['Question']['id']]['0']['SurveyInput']['response_text'].'</i></p>';
        // no response
        } else {
            echo '<p>NO RESPONSE</p>';
        }
        $ques_num++;
    }
}
echo '<br>';

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
</div>
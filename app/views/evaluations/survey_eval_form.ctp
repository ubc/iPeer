<form name="frm" id="frm" method="POST" action="<?php echo $html->url('makeEvaluation/'.$eventId) ?>">
<input type="hidden" name="event_id" value="<?php echo $eventId?>"/>
<input type="hidden" name="survey_id" id="survey_id" value="<?php if (!empty($survey_id)) echo $survey_id; ?>" />
<input type="hidden" name="course_id" value="<?php echo $courseId ?>"/>
<input type="hidden" name="data[Evaluation][surveyee_id]" value="<?php echo User::get('id')?>"/>
<input type="hidden" name="question_count" value="<?php echo count($questions)?>"/>
<table class="standardtable">
    <tr><th><?php __('Team Maker Survey')?></th></tr>
    <tr>
        <td style="text-align: left;">
        <?php if(!empty($questions)):?>
            <?php foreach ($questions as $key => $row): $question = $row['Question'];?>
                <input type="hidden" name="question_id<?php echo $question['number']?>" value="<?php echo $question['id']?>"/>
                <div class="survey-prompt">Q<?php echo $key+1?>: <?php echo $question['prompt']?></div>

                <div class="survey-response">
                <?php if( $question['type'] == 'M'):// Multiple Choice Question?>
                    <?php foreach ($question['Responses'] as $index => $value):?>
                        <input type="radio" name="answer_<?php echo $question['id']?>" value="<?php echo $value['id']?>"><?php echo $value['response']?><br>
                    <?php endforeach; ?>
                <?php elseif( $question['type'] == 'C'):// Choose Any... Question?>
                    <?php foreach ($question['Responses'] as $index => $value): ?>
                        <input type="checkbox" name="answer_<?php echo $question['id']?>[]" value="<?php echo $value['id']?>"><?php echo $value['response']?><br>
                    <?php endforeach;?>
                <?php elseif( $question['type'] == 'S'):// Short Answer Question?>
                    <input type="text" name="answer_<?php echo $question['id']?>">
                <?php elseif( $question['type'] == 'L'):// Long Answer Question?>
                    <textarea name="answer_<?php echo $question['id']?>"></textarea>
                <?php endif; ?>
                </div>
            <?php endforeach;?>
        <?php endif;?>
        </td>
    </tr>
    <tr>
      <td><div align="center"><?php echo $form->submit(__('Submit', true)) ?></div></td>
    </tr>
</table>
    </form>

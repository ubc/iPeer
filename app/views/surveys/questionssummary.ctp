<div class='surveyquestions'>
<?php
foreach ($questions as $i => $q) {
    $i++;
    $prompt = $q['Question']['prompt'];
    echo $html->div('prompt', "$i. " . $prompt);
    if ($q['Question']['type'] == 'M') {
        echo $form->input($i,
            array(
                'type' => 'radio',
                'options' => $q['ResponseOptions'],
                'separator' => '<br />'
            )
        );
    } else if ($q['Question']['type'] == 'C') {
        echo $form->input('blah',
            array(
                'options' => $q['ResponseOptions'],
                'multiple' => 'checkbox',
                'label' => false
            )
        );
    } else if ($q['Question']['type'] == 'S') {
        echo $form->text($i);
    } else if ($q['Question']['type'] == 'L') {
        echo $form->textarea($i);
    }
    // question editing links
    $qId = $q['Question']['id'];
    echo $html->div('Operators',
        $html->link(__('Edit', true), "editQuestion/$qId/$survey_id",
            array('class' => 'edit-button')).
        $html->link(__('Delete', true), "removeQuestion/$survey_id/$qId",
            array('escape' => false, 'class' => 'delete-button'),
            __('Are you sure you want to delete', true) . " &ldquo;$prompt&rdquo;?").
        $html->div('MoveQuestion',
            $html->link(__('Top', true), "moveQuestion/$survey_id/$qId/TOP",
                array('escape' => false, 'class' => 'top-button')).
            $html->link(__('Up', true), "moveQuestion/$survey_id/$qId/UP",
                array('escape' => false, 'class' => 'up-button')).
            $html->link(__('Down', true), "moveQuestion/$survey_id/$qId/DOWN",
                array('escape' => false, 'class' => 'down-button')).
            $html->link(__('Bottom', true), "moveQuestion/$survey_id/$qId/BOTTOM",
                array('escape' => false, 'class' => 'bottom-button'))
        )
    );
}
$addURL = $this->webroot.'surveys/addQuestion/'.$survey_id;
$doneURL = $this->webroot.'surveys/index';
echo $html->div('center',
    $form->button(__('Add Question', true),
       array('onclick' => "window.location='$addURL'")).
    $form->button(__('Done', true),
       array('onclick' => "window.location='$doneURL'"))
);
?>
</div>

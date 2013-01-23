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
        $html->link(_('Edit'), "editQuestion/$qId/$survey_id",
            array('class' => 'edit-button')).
        $html->link(_('Delete'), "removeQuestion/$survey_id/$qId",
            array('escape' => false, 'class' => 'delete-button'),
            _('Are you sure you want to delete') . " &ldquo;$prompt&rdquo;?").
        $html->div('MoveQuestion',
            $html->link(_('Top'), "moveQuestion/$survey_id/$qId/TOP",
                array('escape' => false, 'class' => 'top-button')).
            $html->link(_('Up'), "moveQuestion/$survey_id/$qId/UP",
                array('escape' => false, 'class' => 'up-button')).
            $html->link(_('Down'), "moveQuestion/$survey_id/$qId/DOWN",
                array('escape' => false, 'class' => 'down-button')).
            $html->link(_('Bottom'), "moveQuestion/$survey_id/$qId/BOTTOM",
                array('escape' => false, 'class' => 'bottom-button'))
        )
    );
}

echo $html->div('center', 
    $form->button(_('Add Question'), 
       array('onclick' => "window.location='/surveys/addQuestion/$survey_id'")).
    $form->button(_('Done'), 
       array('onclick' => "window.location='/surveys/index'"))
);
?>
</div>

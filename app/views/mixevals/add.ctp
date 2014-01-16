<div class="MixevalAdd">
<?php 
/* Unfortunately, if we want the nice responsive and user friendly question 
 * editing all on a single page, there's quite a bit of unwieldy complexity 
 * involved. The easier, more clean way, is how surveys implement question 
 * editing, involving multiple pages. However, since we want Mixeval to replace 
 * all the other evaluations eventually, it's probably best that we deal with 
 * some complexity in order to make it easy to use.
 *
 * Most of the complexity is hidden away in the mixeval element questions_editor
 */

/* Create the Form */
echo $form->create('Mixeval');
echo $html->tag('h3', __('Info', true));
echo $form->input('name');
echo $form->input(
    'availability', 
    array(
        'type' => 'radio',
        'default' => 'private',
        'options' => array('public' => __('Public', true), 'private' => __('Private', true))
    )
);
echo $html->div("help-text", 
    __('Public lets you share this mixed evaluation with other instructors.', true));
echo $form->input('zero_mark');
echo $html->div("help-text", 
    __('Start marks from zero for all Likert questions.', true));
$check = ($this->data['Mixeval']['self_eval'] > 0) ? true : false;
echo $form->input('self_eval', 
    array('type' => 'checkbox', 'id' => 'self_eval', 'label' => __('Self-Evaluation', true),
    'checked' => $check));
echo $html->div("help-text", 
    __('Adds a reflective questions section for evaluators.', true));

// If we're editing a previously saved mixeval, will need to have an id for
// the mixeval.
if (isset($this->data['Mixeval']['id'])) {
    echo $form->hidden('id');
}

// Questions
echo $this->element('mixevals/questions_editor', 
    array('qTypes' => $mixevalQuestionTypes));

// Submit
echo $html->div('center', 
    $form->button(__('Save', true)) .
    $form->button(__('Cancel', true), array('name' => 'cancel'))
);

echo $this->Form->end();
?>
</div>

<script type="text/javascript">
selfEval();
jQuery().ready(function() {
    jQuery('#self_eval').change(function() {
        selfEval();
    });
});

function selfEval() {
    var checked = jQuery('#self_eval').is(":checked");
    // if self eval is checked - show self eval ques section
    if (checked) {
        jQuery('#self-eval-ques').show();
    } else {
        jQuery('#self-eval-ques').hide();
    }
}
</script>

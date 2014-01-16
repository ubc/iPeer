<div class='SurveyAddQuestion'>
<?php
echo $this->Form->create('Question',
    array('url' => '/'.$this->params['url']['url']));
echo $form->input('template_id',
    array(
        'empty' => __('(Select Question to Load)', true),
        'options' => $templates,
        'after' => $form->submit(__('Load', true),
            array('div' => false, 'name' => 'load'))
    )
);
echo $html->div('help-text', __('Load a master question as your template.', true));
echo $form->input('Question.master',
    array('options' => array('no' => 'No', 'yes' => 'Yes')));
echo $html->div('help-text',
    __('Whether this question can be used as a template for new questions.', true));
echo $form->input('Question.prompt');
echo $form->input('Question.type',
    array(
        'options' => array(
            'M' => __('Multiple Choice (Single Answer)', true),
            'C' => __('Choose Any Of... (Multiple Answers)', true),
            'S' => __('Single Line Text Input', true),
            'L' => __('Long Answer Text Input', true)
        )
    )
);

// Response section, has to be custom due to no cakephp automagic for doing this
// Most of the complication is due to the need to implement form restoration
// on submit error.
function makeResp($i, $val, $html, $form) {
    // make the response text box
    $input = $form->text("Response.$i.response") . $html->link('X', '#',
                array('onclick' => "rmResponseInput($i); return false;"));
    if (isset($val['id'])) { # for editing form, need to know response id
        $input .= $form->hidden("Response.$i.id");
    }
    $ret = $html->div('input text', $input, array('id' => "responseInput$i"));
    return $ret;
}
$numResponses = 0;
$responseTemplate = makeResp(-1, array(), $html, $form);
$existingResponses = $form->label('Responses') . $html->link('Add Response',
    '#', array('onclick' => "addResponseInput(); return false;"));
// restore previously submitted responses, if any
if (isset($this->data) && isset($this->data['Response'])) {
    foreach($this->data['Response'] as $key => $val) {
        $existingResponses .= makeResp($key, $val, $html, $form);
        $numResponses = $key + 1;
    }
}
// actual response section
echo $html->div('input text', $existingResponses, array('id' => 'responseInput'));

// Submit buttons
echo $html->div('center',
    $form->submit(__('Save Question', true), array('div' => false)) .
    $form->submit(__('Cancel', true), array('div' => false, 'name' => 'cancel'))
);
echo $form->end();
?>
</div>

<script type="text/javascript">
toggleResponse();
jQuery("#QuestionType").change(toggleResponse);

// Adding/Removing responses
var numResponses = <?php echo $numResponses; ?>;
function addResponseInput() {
    var template = '<?php echo $responseTemplate; ?>';
    template = template.replace(/-1/g, numResponses);
    jQuery(template).appendTo("#responseInput");
    numResponses++;
}

function rmResponseInput(num) {
    jQuery("#responseInput" + num).remove();
}

// Show/Hide response section
function toggleResponse() {
    var type = jQuery("#QuestionType").val();
    if (type == 'M' || type == 'C') {
        jQuery('#responseInput').show();
    }
    else {
        jQuery('#responseInput').hide();
    }
}
</script>

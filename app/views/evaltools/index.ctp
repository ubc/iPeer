<?php
// data processing for simple eval, create arrays to generate the entry tables
$simpleevalheaders = array(
    __('Name', true),
    __('In Use', true),
    __('Public', true),
    __('Base Point Per Member', true)
);
$simpleevalcells = array();
foreach($simpleEvalData as $data) {
    $eval = $data['SimpleEvaluation'];
    $row = array();
    $row[] = $html->link($eval['name'], '/simpleevaluations/view/'.$eval['id']);
    $row[] = $eval['event_count'] > 0 ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $eval['availability'] == "public" ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $eval['point_per_member'];
    $simpleevalcells[] = $row;
}

// data processing for rubrics, create arrays to generate the entry tables
$rubricsheaders = array(
    __('Name', true),
    __('In Use', true),
    __('Public', true),
    __('LOM', true),
    __('Criteria', true),
    __('Total Marks', true)
);
$rubricscells = array();
foreach($rubricData as $data) {
    $rubric = $data['Rubric'];
    $row = array();
    $row[] = $html->link($rubric['name'], '/rubrics/view/'.$rubric['id']);
    $row[] = $rubric['event_count'] > 0 ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $rubric['availability'] == "public" ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif',array('alt'=>'red_x'));
    $row[] = $rubric['lom_max'];
    $row[] = $rubric['criteria'];
    $row[] = $rubric['total_marks'];
    $rubricscells[] = $row;
}

// data processing for mix evals, create arrays to generate the entry tables
$mixevalheaders = array(
    __('Name', true),
    __('In Use', true),
    __('Public', true),
    __('Self-Evaluation Questions', true),
    __('Total Marks', true)
);
$mixevalcells = array();
foreach ($mixevalData as $data) {
    $mixeval = $data['Mixeval'];
    $row = array();
    $row[] = $html->link($mixeval['name'], '/mixevals/view/'.$mixeval['id']);
    $row[] = $mixeval['event_count'] > 0 ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $mixeval['availability'] == "public" ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $mixeval['self_eval'] > 0 ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $mixeval['total_marks'];
    $mixevalcells[] = $row;
}

// data processing for surveys
$surveyheaders = array(
    __('Name', true),
    __('In Use', true),
    __('Public', true),
    __('Questions', true),
);
$surveycells = array();
foreach($surveyData as $data) {
    $survey = $data['Survey'];
    $row = array();
    $row[] = $html->link($survey['name'], '/surveys/view/'.$survey['id']);
    $row[] = $survey['event_count'] > 0 ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $survey['availability'] == "public" ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $survey['question_count'];
    $surveycells[] = $row;
}

// data processing for email templates
$templateheaders = array(
    __('Name', true),
    __('Public', true),
    __('Subject', true),
    __('Description', true)
);
$templatecells = array();
foreach($emailTemplates as $data) {
    $emailTemplate = $data['EmailTemplate'];
    $row = array();
    $row[] = $html->link($emailTemplate['name'],
        '/emailtemplates/view/'.$emailTemplate['id']);
    $row[] = $emailTemplate['availability'] == "1" ?
        $html->image('icons/green_check.gif', array('alt'=>'green_check')) :
        $html->image('icons/red_x.gif', array('alt'=>'red_x'));
    $row[] = $emailTemplate['subject'];
    $row[] = $emailTemplate['description'];
    $templatecells[] = $row;
}

// evaltools navigation
echo $this->element('evaltools/tools_menu', array());
?>

<!-- Simple Eval Table -->
<div class="evaltool-section-header">
    <h2><?php __('My Simple Evaluations')?></h2>
    <div class='evaltoolsadd'><?php echo $html->link(__('Add Simple Evaluation', true), '/simpleevaluations/add', array('class' => 'add-button')); ?><?php echo $html->link(__('All Simple Evaluations', true), '/simpleevaluations'); ?></div>
</div>
<table class='standardtable'>
<?php
echo $html->tableHeaders($simpleevalheaders);
echo $html->tableCells($simpleevalcells);
?>
</table>

<!-- Rubrics -->
<div class="evaltool-section-header">
    <h2><?php __('My Rubrics Evaluations')?></h2>
    <div class='evaltoolsadd'><?php echo $html->link(__('Add Rubric', true), '/rubrics/add', array('class' => 'add-button')); ?><?php echo $html->link(__('All Rubric Evaluations', true), '/rubrics')?></div>
</div>
<table class='standardtable'>
<?php
echo $html->tableHeaders($rubricsheaders);
echo $html->tableCells($rubricscells);
?>
</table>

<!-- Mixed Evals -->
<div class="evaltool-section-header">
    <h2><?php __('My Mixed Evaluations')?></h2>
    <div class='evaltoolsadd'><?php echo $html->link(__('Add Mixed Evaluation', true), '/mixevals/add', array('class' => 'add-button')); ?><?php echo $html->link(__('All Mixed Evaluations', true), '/mixevals')?></div>
</div>
<table class='standardtable'>
<?php
echo $html->tableHeaders($mixevalheaders);
echo $html->tableCells($mixevalcells);
?>
</table>

<!-- Surveys -->
<div class="evaltool-section-header">
    <h2><?php __('My Surveys')?></h2>
    <div class='evaltoolsadd'><?php echo $html->link(__('Add Survey', true), '/surveys/add', array('class' => 'add-button')); ?><?php echo $html->link(__('All Surveys', true), '/surveys')?></div>
</div>
<table class='standardtable'>
<?php
echo $html->tableHeaders($surveyheaders);
echo $html->tableCells($surveycells);
?>
</table>

<!-- Email Templates -->
<div class="evaltool-section-header">
    <h2><?php __('My Email Templates')?></h2>
    <div class='evaltoolsadd'><?php echo $html->link(__('Add Email Template', true), '/emailtemplates/add', array('class' => 'add-button')); ?><?php echo $html->link(__('All Email Templates', true), '/emailtemplates')?></div>
</div>
<table class='standardtable'>
<?php
echo $html->tableHeaders($templateheaders);
echo $html->tableCells($templatecells);
?>
</table>

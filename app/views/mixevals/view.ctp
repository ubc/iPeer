<div class='MixevalView'>
<h2>Info</h2>
<dl>
    <dt><?php __('Name'); ?></dt>
    <dd><?php echo $mixeval['name']; ?></dd>
    <dt><?php __('Availability'); ?></dt>
    <dd><?php echo ucfirst($mixeval['availability']); ?></dd>
    <dd>
        <span class='help'>
        <?php
        __('Public lets you share this mixed evaluation with other instructors.');
        ?>
        </span>
    </dd>
    <dt>Zero Mark</dt>
    <dd><?php echo $mixeval['zero_mark'] ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('Start marks from zero for all Likert questions.')?>
        </span>
    </dd>
    <dt>Creator</dt>
    <dd><?php echo $mixeval['creator']; ?></dd>
    <dt>Created</dt>
    <dd><?php echo $mixeval['created']; ?></dd>
    <dt>Modified</dt>
    <dd><?php echo $mixeval['modified']; ?></dd>
</dl>
<h2>Questions</h2> 
<?php
$totalMarks = array_sum(Set::extract('/MixevalQuestion/multiplier', $questions));
  
$params = array('controller'            => 'mixevals',
                'zero_mark'             => $mixeval['zero_mark'],
                'questions'             => $questions,
                'user'                  => array('id' => 0));

echo $this->element('mixevals/view_mixeval_details', $params);

$required = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
echo $html->para('marks', _t('Total Marks') . ": $totalMarks");

?>
</div>

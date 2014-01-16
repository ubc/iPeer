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
    <dt>Self-Evaluation</dt>
    <dd><?php echo $mixeval['self_eval'] > 0 ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('Reflective Questions for the evaluator are included.')?>
        </span>
    </dd>
    <dt>Creator</dt>
    <dd><?php echo $mixeval['creator']; ?></dd>
    <dt>Created</dt>
    <dd><?php echo $mixeval['created']; ?></dd>
    <dt>Modified</dt>
    <dd><?php echo $mixeval['modified']; ?></dd>
</dl>
<?php
$totalMarks = $mixeval['total_marks'];
$id = array('id' => 0);
$event = array('Event' => $id, 'GroupEvent' => $id, 'Group' => $id);
  
$params = array('controller'            => 'mixevals',
                'zero_mark'             => $mixeval['zero_mark'],
                'questions'             => $questions,
                'user'                  => $id,
                'event'                 => $event,
                'evaluatee_count'       => 1,
                'eval'                  => 'Evaluation',
                'self_eval'             => 0);
?>
<?php
if ($mixeval['peer_question'] > 0) {
    echo '<h2>Peer Evaluation Questions</h2>';
    echo $this->element('mixevals/view_mixeval_details', $params);
}
?>

<?php
$params['eval'] = 'Self-Evaluation';
$params['self_eval'] = 1;

if ($mixeval['self_eval'] > 0) {
    echo '<h2>Self-Evaluation Questions</h2>';
    echo $this->element('mixevals/view_mixeval_details', $params);
}

$required = $html->tag('span', '*', array('class' => 'required orangered'));
echo $html->para('note', $required . ' ' . _t('Indicates response required.'));
echo $html->para('marks', _t('Total Marks') . ": $totalMarks");

?>
</div>

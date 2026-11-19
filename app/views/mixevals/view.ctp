<div class='MixevalView'>
<dl>
    <dt id='mixeval-name'><?php __('Name'); ?></dt>
    <dd aria-labelledby='mixeval-name'><?php echo $mixeval['name']; ?></dd>
    <dt id='mixeval-availability'><?php __('Availability'); ?></dt>
    <dd aria-labelledby='mixeval-availability'><?php echo ucfirst($mixeval['availability']); ?></dd>
    <dd>
        <span class='help'>
        <?php
        __('Public lets you share this mixed evaluation with other instructors.');
        ?>
        </span>
    </dd>
    <dt id='mixeval-zero-mark'>Zero Mark</dt>
    <dd aria-labelledby='mixeval-zero-mark'><?php echo $mixeval['zero_mark'] ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('Start marks from zero for all Likert questions.')?>
        </span>
    </dd>
    <dt id='mixeval-self-eval'>Self-Evaluation</dt>
    <dd aria-labelledby='mixeval-self-eval'><?php echo $mixeval['self_eval'] > 0 ? 'On' : 'Off';?></dd>
    <dd>
        <span class='help'>
        <?php __('Reflective Questions for the evaluator are included.')?>
        </span>
    </dd>
    <dt id='mixeval-creator'>Creator</dt>
    <dd aria-labelledby='mixeval-creator'><?php echo $mixeval['creator']; ?></dd>
    <dt id='mixeval-created'>Created</dt>
    <dd aria-labelledby='mixeval-created'><?php echo $mixeval['created']; ?></dd>
    <dt id='mixeval-modified'>Modified</dt>
    <dd aria-labelledby='mixeval-modified'><?php echo $mixeval['modified']; ?></dd>
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
<?php if ($mixeval['peer_question'] > 0): ?>
    <h1 onclick="$('rpreview').toggle();" class="title" id="rubricPreview">
        <span class="ipeer-icon"><?php __('MixEval Preview') ?></span>
    </h1>
    <div id="rpreview" style="display: block; background: #FFF;">
        <?php echo $this->element('evaluations/mixeval_eval_form', Toolkit::getMixEvalDemoData($mixeval, $selfEval))?>
    </div>
<?php endif; ?>
</div>

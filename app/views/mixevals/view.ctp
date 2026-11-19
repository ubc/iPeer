<div class='MixevalView'>
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
<?php if ($mixeval['peer_question'] > 0): ?>
    <h1 onclick="$('rpreview').toggle();" class="title" id="rubricPreview">
        <span class="ipeer-icon"><?php __('MixEval Preview') ?></span>
    </h1>
    <div id="rpreview" style="display: block; background: #FFF;">
        <?php echo $this->element('evaluations/mixeval_eval_form', Toolkit::getMixEvalDemoData($mixeval, $selfEval))?>
    </div>
<?php endif; ?>
</div>

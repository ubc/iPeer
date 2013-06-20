<h2><?php __('Evaluation Result Detail')?></h2>
<!-- Event Details Table -->
<table class="standardtable">
<tr>
    <th><?php __('Event Name')?></th>
    <th><?php __('Evaluated By')?></th>
    <th><?php __('Due Date')?></th>
    <th><?php __('Self-Evaluation')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['title'] ?></td>
    <td><?php echo $event['Group']['group_name'] ?></td>
    <td><?php echo Toolkit::formatDate($event['Event']['due_date']) ?></td>
    <td><?php echo ($event['Event']['self_eval']) ? 'Yes' : 'No' ?></td>
</tr>
</table>

<table class="standardtable">
<tr>
    <th><?php __('Description')?></th>
</tr>
<tr>
    <td><?php echo $event['Event']['description'] ?></td>
</tr>
</table>

<h2><?php __('Summary')?></h2>
<table class="standardtable">
<tr>
    <th width=50%><?php __('Rating')?></th>
    <?php if ($event['Event']['enable_details']) { ?>
        <th width=50%><?php __('Group Average')?></th>
    <?php } ?>
</tr>
<tr>
    <td>
        <?php if ($gradeReleased) {
            $finalAvg = $studentResult['aveScore'] - $studentResult['avePenalty'];
            ($studentResult['avePenalty'] > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.number_format($studentResult['avePenalty'], 2).'</font>'.
                ')'.'<font color=\'red\'>*</font>'.' = '.number_format($finalAvg, 2)) : $stringAddOn = '';

            echo number_format($studentResult['aveScore'], 2).$stringAddOn;
            $studentResult['penalty'] > 0 ? $penaltyNote = '&nbsp &nbsp &nbsp &nbsp &nbsp ( )'.'<font color=\'red\'>*</font>'.' : '.$studentResult['penalty'].
                '% late penalty.' : $penaltyNote = '';
            echo $penaltyNote;
        } else {
            echo __('Not Released', true);
        }
        ?>
    </td>
    <?php if ($event['Event']['enable_details']) { ?>
    <td>
        <?php
            if ($gradeReleased) {
                $groupAve = isset($studentResult['groupAve']) ? $studentResult['groupAve']: 0;
                echo number_format($groupAve, 2);
            } else {
                echo __('Not Released', true);
            }
        ?>
    </td>
    <?php } ?>
</tr>
</table>
<?php if ($event['Event']['enable_details'] && $commentReleased) { ?>
<table class="standardtable">
    <tr>
        <th><?php __('Comments')?> (<?php __('Randomly Ordered')?>)</th>
    </tr>
    <?php if (isset($studentResult['comments'])) {
        foreach ($studentResult['comments'] as $row) {
            $evalMarkSimple = $row['EvaluationSimple'];
            if (!empty($evalMarkSimple['comment'])) {
                echo '<tr><td>'.$evalMarkSimple['comment'].'</td></tr>';
            } else {
                echo '<tr><td>n/a</td></tr>';
            }
        }
    } else if ($event['Event']['auto_release']) {
        echo '<tr><td>n/a</td></tr>';
    } else { ?>
        <tr><td><?php echo __('Not Released.', true); ?> </td></tr>
    <?php } ?>
</table>
<?php } ?>
<div style="text-align: center;">
<input type="button" name="Back" value="Back" onclick="javascript:(history.length > 1 ? history.back() : window.close());">
</div>

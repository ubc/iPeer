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
    <td><?php echo $event['group_name'] ?></td>
    <td><?php echo Toolkit::formatDate(date("Y-m-d H:i:s", strtotime($event['Event']['due_date']))) ?></td>
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
    <th width=50%><?php __('Group Average')?></th>
</tr>
<tr>
    <td>
        <?php if ($studentResult['gradeReleaseStatus']) {
            $finalAvg = $studentResult['aveScore'] - $studentResult['avePenalty'];
            ($studentResult['avePenalty'] > 0) ? ($stringAddOn = ' - '.'('.'<font color=\'red\'>'.$studentResult['avePenalty'].'</font>'.
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
    <td>
        <?php
            if ($studentResult['gradeReleaseStatus']) {
                isset($studentResult['groupAve'])? $groupAve = $studentResult['groupAve']: $groupAve = 0;
                echo number_format($groupAve, 2);
            } else {
                echo __('Not Released', true);
            }
        ?>
    </td>
</tr>
</table>
<table class="standardtable">
    <tr>
        <th><?php __('Comments&nbsp;(Randomly Ordered)')?></th>
    </tr>
    <?php if (isset($studentResult['comments'])) {
        foreach ($studentResult['comments'] as $row) {
            $evalMarkSimple = $row['EvaluationSimple'];
            if (!empty($evalMarkSimple['eval_comment'])) {
                echo '<tr><td>'.$evalMarkSimple['eval_comment'].'</td></tr>';
            } else {
                echo '<tr><td>n/a</td></tr>';
            }
        }
    } else { ?>
        <tr><td><?php echo __('Not Released.', true); ?> </td></tr>
    <?php } ?>
</table>
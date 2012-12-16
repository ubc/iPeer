<table class="standardtable">
    <tr><th><?php __('Team Maker Survey Summary')?></th></tr>
    <tr>
        <td>
    <?php if( !empty($questions)):?>
    <?php foreach ($questions as $question): $question = $question['Question'];?>
        <div style="text-align: left;">Q<?php echo $question['number']?>: <?php echo $question['prompt']?></div>
        <div>
            <table border="0">
            <?php if($question['type'] == 'M' || $question['type'] == 'C'): ?>
                <?php if( !empty($question['Responses'])):?>
                    <?php foreach ($question['Responses'] as $index => $value):?>
                        <?php $percent = $question['total_response'] != 0 ? round(($value['count']/$question['total_response'])*100): 0;?>
                        <tr><td width="250"><?php echo $value['response']?></td><td width="30"><?php echo $value['count']?></td><td> <?php echo $percent?>% </td><td><?php echo $html->image("evaluations/bar.php?per=".$percent,array('alt'=>$percent))?></td></tr>
                    <?php endforeach;?>
                <?php endif; ?>
            <?php elseif( $question['type'] == 'S' || $question['type'] == 'L'): ?>
                <?php if( !empty($question['Responses'])):?>
                    <?php foreach ($question['Responses'] as $index => $value):?>
                        <tr valign="top"><td width="250"><?php echo $value['user_name']?></td><td width="15"></td><td><i><?php echo $value['response_text']?></i><td></tr>
                    <?php endforeach;?>
                <?php endif; ?>
            <?php endif; ?>
            </table>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
        </td>
    </tr>
</table>

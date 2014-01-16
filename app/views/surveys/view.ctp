<h2>Survey Info</h2>
<table class="standardtable">
    <tr>
      <th><?php __('Survey Title')?>:</th>
      <td><?php echo $survey['Survey']['name']; ?></td>
    </tr>
    <tr>
      <th><?php __('Creator')?>:</th>
      <td><?php echo $this->Html->link($survey['Survey']['creator'], '/users/view/'.$survey['Survey']['creator_id'])?></td>
    </tr>
    <tr>
      <th><?php __('Created')?>:</th>
      <td><?php echo Toolkit::formatDate($survey['Survey']['created']); ?></td>
    </tr>
    <tr>
      <th><?php __('Modified')?>:</th>
      <td><?php echo Toolkit::formatDate($survey['Survey']['modified']); ?></td>
    </tr>
</table>

<h2>Survey Questions</h2>

<div class='surveyquestions'>
<?php
foreach ($questions as $i => $q) {
    $i++;
    echo $html->div('prompt', "$i. " . $q['Question']['prompt']);
    if ($q['Question']['type'] == 'M') {
        echo $form->input($i, 
            array(
                'type' => 'radio',
                'options' => $q['ResponseOptions'], 
                'separator' => '<br />'
            )
        );
    } else if ($q['Question']['type'] == 'C') {
        echo $form->input('blah', 
            array(
                'options' => $q['ResponseOptions'], 
                'multiple' => 'checkbox',
                'label' => false
            )
        );
    } else if ($q['Question']['type'] == 'S') {
        echo $form->text($i);
    } else if ($q['Question']['type'] == 'L') {
        echo $form->textarea($i);
    }
}
?>
</div>

<div class='submit'>
<input class='center' type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
</div>

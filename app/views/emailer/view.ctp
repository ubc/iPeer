<table class="standardtable">
  <tr>
    <th colspan="2"><?php __('View Email')?></th>
  </tr>
  <tr>
    <td width="15%"><?php __('To')?></td>
    <td style="text-align: left;"><?php
      $last_item = end($data['User']);
      foreach($data['User'] as $user){
        $user = $user['User'];
        echo $html->link($user['full_name'], '/users/view/'.$user['id']);
        if($user != $last_item['User'])
          echo ', ';
      }
    ?>
    </td>
  </tr>
  <tr>
    <td><?php __('Subject')?></td>
    <td><?php echo $data['EmailSchedule']['subject'];?>
    </td>
  </tr>
  <tr>
    <td><?php __('Scheduled on')?></td>
    <td><?php echo Toolkit::formatDate($data['EmailSchedule']['date']);?>
    </td>
  </tr>
  <tr>
    <td><?php __('Sent')?>?</td>
    <td><?php echo $data['EmailSchedule']['sent'] == 1 ?
             $html->image('icons/green_check.gif',array('border'=>'0','alt'=>'green_check')) :
             $html->image('icons/red_x.gif',array('border'=>'0','alt'=>'red_x'));?>
    </td>
  </tr>
  <tr>
    <td><?php __('Content')?></td><td><div id='emailContent'>
    <?php echo $data['EmailSchedule']['content'];?>
    </div></td>
  </tr>
  <tr>
    <td><?php __('Created')?></td>
    <td><?php echo Toolkit::formatDate($data['EmailSchedule']['created']);?>
    </td>
  </tr>
</table>

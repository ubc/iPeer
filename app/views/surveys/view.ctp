<table class="standardtable">
    <tr><th colspan="2"><?php __('View Survey')?></th></tr>
    <tr>
      <td width="265"><?php __('Survey Title')?>:</td>
      <td><?php echo $data['Survey']['name']; ?></td>
    </tr>
    <tr>
      <td><?php __('Assigned Course')?>:&nbsp;</td>
      <td><?php echo $this->Html->link($data['Course']['course'], '/courses/view/'.$data['Survey']['course_id'])?></td>
    </tr>
    <tr>
      <td><?php __('Creator')?>:</td>
      <td><?php echo $this->Html->link($data['Survey']['creator'], '/users/view/'.$data['Survey']['creator_id'])?></td>
    </tr>
    <tr>
      <td><?php __('Due Date')?>:</td>
      <td><?php echo $data['Survey']['due_date']; ?></td>
    </tr>
    <tr>
      <td valign="top"><?php __('Release Date')?>:</td>
      <td>
      <table width="294" border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td width="48" valign="top"><?php __('From')?>:</td>
        <td width="224"><?php echo $data['Survey']['release_date_begin'] ?></td>
      </tr>
      <tr>
        <td valign="top"><?php __('To')?>:</td>
        <td><?php echo $data['Survey']['release_date_end'] ?></td>
      </tr>
    </table>
      </td>
    </tr>
    <tr>
      <td><?php __('Created')?>:</td>
      <td><?php echo $data['Survey']['created']; ?></td>
    </tr>
    <tr>
      <td><?php __('Modified')?>:</td>
      <td><?php echo $data['Survey']['modified']; ?></td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <input type="button" name="Back" value="<?php __('Back')?>" onClick="javascript:(history.length > 1) ? history.back() : window.close();">
      </td>
    </tr>
</table>

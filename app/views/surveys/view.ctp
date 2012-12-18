<table class="standardtable">
    <tr><th colspan="2"><?php __('View Survey')?></th></tr>
    <tr>
      <td width="265"><?php __('Survey Title')?>:</td>
      <td><?php echo $data['Survey']['name']; ?></td>
    </tr>
    <tr>
      <td><?php __('Creator')?>:</td>
      <td><?php echo $this->Html->link($data['Survey']['creator'], '/users/view/'.$data['Survey']['creator_id'])?></td>
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

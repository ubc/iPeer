<?php
$maillToAll = join(';', Set::extract($data['Instructor'], '/email'));
?>
<table class="standardtable">
    <tr>
        <th><?php __('Course')?></th>
        <td><?php echo $data['Course']['course']; ?></td>
        <th><?php __('Instructor(s)')?></th>
        <td rowspan="3">
            <?php foreach($data['Instructor'] as $i): ?>
                <a href="mailto:<?php echo $i['email']?>"><?php echo $html->image('icons/email_icon.gif',array('border'=>'0','alt'=>__('Email', true)))?></a>
                <?php echo $this->element('users/user_info', array('data' => $i))?>
            <?php endforeach;?>
            <br />
            <?php if (!empty($maillToAll)): ?>
                <a href="mailto:<?php echo $maillToAll?>"><?php echo $html->image('icons/email.gif',array('border'=>'0','alt'=>__(' Email To All', true)))?><?php __(' Email To All Instructors')?></a>
            <?php endif;?>
        </td>
    </tr>
    <tr>
        <th><?php __('Title')?></th>
        <td colspan="2"><?php echo $data['Course']['title']; ?></td>
    </tr>
    <tr>
        <th><?php __('Term')?></th>
        <td colspan="2"><?php echo $data['Course']['term']; ?></td>
    </tr>
    <tr>
        <th><?php __('Status')?></th>
        <td><?php $data['Course']['record_status'] == "A" ? __("Active") : __("Inactive");?></td>
        <th><?php __('Homepage')?></th>
        <td>
            <a href="<?php echo !empty($data['Course']['homepage'])? $data['Course']['homepage']:'#'; ?>" target="_blank"><?php echo $data['Course']['homepage']; ?></a>
        </td>
    </tr>
    <tr>
      <th><?php __('Created By')?>:</th>
      <td><?php echo $data['Course']['creator']?></td>
      <th><?php __('Updated By')?>:</th>
      <td><?php echo $data['Course']['updater']?></td>
    </tr>
    <tr>
      <th><?php __('Created At')?>:</th>
      <td><?php echo $data['Course']['created']; ?></td>
      <th><?php __('Updated At')?>:</th>
      <td><?php echo $data['Course']['modified']; ?></td>
    </tr>
    <tr>
      <td colspan="4" align="center">
      <input type="button" name="Back" value=<?php __('Back')?> onClick="javascript:(history.length > 1) ? history.back() : window.close();">
      </td>
    </tr>
</table>

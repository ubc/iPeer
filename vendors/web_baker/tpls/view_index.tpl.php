<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" align=center>
<tr>
<td align="center">
<p>
  <br/>
  <?php echo $html->linkTo('Add new %MODEL_NAME%', '/%CONTROLLER_NAME_LOWER%/add'); ?>
  <br/>
</p>
</center>
<center>
  <table width="90%" border="0" cellspacing="2" cellpadding="4">
    <tr class="tableheader">
      <th>ID</th>
      <th>Actions</th>
      <th>Created</th>
      <th>Modified</th>
    </tr>
    <?php foreach ($data as $row): $%MODEL_NAME_LOWER% = $row['%MODEL_NAME%']; ?>
    <tr class="tablecell">
      <td align=center>
        <?php echo $%MODEL_NAME_LOWER%['id'] ?>
      </td>
      <td align=center>
        <a href="<?php echo '/%CONTROLLER_NAME_LOWER%/view/'.$%MODEL_NAME_LOWER%['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
        <a href="<?php echo '/%CONTROLLER_NAME_LOWER%/edit/'.$%MODEL_NAME_LOWER%['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
        <a href="<?php echo '/%CONTROLLER_NAME_LOWER%/delete/'.$%MODEL_NAME_LOWER%['id']?>"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>
      </td>
      <td>
        <?php echo $html->link($%MODEL_NAME_LOWER%['title'], '/%CONTROLLER_NAME_LOWER%/view/'.$%MODEL_NAME_LOWER%['id']) ?>
      </td>
      <td>
        <?php echo $%MODEL_NAME_LOWER%['created'] ?>
      </td>
      <td>
        <?php
            if (!empty($%MODEL_NAME_LOWER%['modified'])) echo $%MODEL_NAME_LOWER%['modified'];
        ?>
      </td>
    </tr>
    <?php endforeach; ?>
</table>
</td>
</tr>
</table>

<form method="post" action="<?php echo $html->url('/emailer/write/'); ?>" name="emailer" id="emailer" class="emailer">
  <table width="100%"  border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
        <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tableheader">
            <td colspan="2" align="center">Write Email</td>
          </tr>
          <tr class="tablecell2">
            <td>From:&nbsp;</td>
            <td><?php echo $form->input('Email.from', array('size' => '80%', 'label'=>false, 'value' => $from));?></td>
          </tr>
          <tr class="tablecell2">
            <td>To:&nbsp;</td>
            <td><?php echo $form->input('Email.to', array('size' => '80%','label' => false,'value' => $to));?></td>
          </tr>
          <tr class="tablecell2">
            <td>Message:</td>
            <td><?php
                echo $form->textarea('Email.message', array(
                  'lable' => "Content: ",
                  'cols' => '60',
                  'rows' => '15',
                  'escape' => false
                ));
            ?></td>
          </tr>
        </table>
        <div><?php echo $form->submit('Send'); ?></div>
    </td>
  </tr>
</table>
</form>
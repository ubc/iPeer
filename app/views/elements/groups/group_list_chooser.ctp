<!-- This element renders 2 list of groups, selected and unselected.-->
<?php $listStyle = 'min-width:13em; height:' . (empty($listSize) ? "20" : $listSize) . 'em;'; ?>

<table><tr align="center">
    <td align="center">
      <h3><?php echo isset($allName) ? $allName : ''?></h3>
      <?php echo $this->Form->input('source', array('options' => $all,
                                                    'multiple' => true,
                                                    'div' => false,
                                                    'label' => false,
                                                    'before' => '',
                                                    'after' => '',
                                                    'separator' => '',
                                                    'between' => '',
                                                    'id' => 'all_groups',
                                                    'style' => $listStyle));?>
    </td>
    <td width="100" align="center">
      <input type="button" style="width:100px;" onClick="itemMove($('all_groups'), $('selected_groups'))" value="Assign >>" />
      <br><br>
      <input type="button" style="width:100px;" onClick="itemMove($('selected_groups'), $('all_groups'))" value="<< Remove " />
    </td>
    <td align="center">
      <h3><?php echo isset($selectedName) ? $selectedName : ''?></h3>
      <?php echo $this->Form->input('Member.Member', array('style' => $listStyle,
                                                           'div' => false,
                                                           'label' => false,
                                                           'before' => '',
                                                           'after' => '',
                                                           'separator' => '',
                                                           'between' => '',
                                                           'id' => 'selected_groups' ))?>
    </td>
</tr></table>

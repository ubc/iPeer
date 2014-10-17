<!-- This element renders 2 list of groups, selected and unselected.-->
<?php $listStyle = 'min-width:13em; height:' . (empty($listSize) ? "20" : $listSize) . 'em;';
$status = isset($status) ? $status : '';
?>

<table class="group-list-chooser"><tr align="center">
    <td>
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

    <td class='middle-column'>
        <input type="button" onClick="itemMove($('all_groups'), $('selected_groups'))" value="<?php __('Assign')?> >>"/>
        <input type="button" onClick="itemMove($('selected_groups'), $('all_groups'))" value="<< <?php __('Remove')?> "/>
    </td>

    <td>
      <h3><?php echo isset($selectedName) ? $selectedName : ''?></h3>
      <?php
        $options = array(
            'style' => $listStyle,
            'div' => false,
            'label' => false,
            'before' => '',
            'after' => '',
            'separator' => '',
            'between' => '',
            'id' => 'selected_groups' );
        if (isset($assigned)) {
          $options['options'] = $assigned;
        }

        echo $this->Form->input('Member.Member', $options);
      ?>
    </td>
</tr>
</table>

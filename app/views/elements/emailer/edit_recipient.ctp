<?php $displayField = ClassRegistry::init('User')->displayField;?>

<?php echo $this->Js->link($html->image('icons/x_small.gif',array('border'=>'0','alt'=>__('Delete', true), 'recipient_id' => $recipient['id'], 'recipient_name' => $recipient[$displayField])),
                           array('action' => 'deleteRecipient',
                                 'recipient_id' => $recipient['id']),
                           array('confirm' => __('Are you sure to remove recipient ', true).$recipient[$displayField].__(' from this field?', true),
                                 'escape' => false,
                                 'success' => '{event.target.up(1).fade();var recipient_id = event.target.readAttribute("recipient_id");var recipient_name = event.target.readAttribute("recipient_name");$("recipients").insert({bottom: "<option value=\""+recipient_id+"\">"+recipient_name+"</option>"});}',
                                 'error' => 'alert('.__("Communication error!", true).')',
                                 'buffer' => false,
                                ))?>
<?php echo $this->element('users/user_info', array('data' => $recipient))?>

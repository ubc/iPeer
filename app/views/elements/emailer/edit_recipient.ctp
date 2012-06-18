<?php
    $displayField = ClassRegistry::init('User')->displayField;
    echo $this->Js->link($html->image('icons/x_small.gif',array('border'=>'0','alt'=>__('Delete', true),'recipient_id' => $recipient['id'],'recipient_name' => $recipient[$displayField])),
        array('action' => 'deleteRecipient',
            'recipient_id' => $recipient['id']
        ),
        array('confirm' => $recipient[$displayField].__(' will be removed from the To: field...', true),
            'escape' => false,
            'success' => '{
                event.target.up(1).fade();
                var recipient_id = event.target.readAttribute("recipient_id");
                var recipient_name = event.target.readAttribute("recipient_name");
                $("recipients").insert({bottom: "<option value=\""+recipient_id+"\">"+recipient_name+"</option>"});
            }',
            'error' => 'alert("'.__("Removal error!", true).'")',
            'buffer' => false
        )
    );
    echo $this->element('users/user_info', array('data' => $recipient));
?>

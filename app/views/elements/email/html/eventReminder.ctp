<p>
<?php echo __('Hello ', true).$user['User']['full_name'].','; ?>
</p>
<p>
<?php echo __('A ', true).$type.__(' for ', true).$course['Course']['course'].__(' is made available to ', true). 
__('you in iPeer, which has yet to be completed.', true); ?>
</p>
<ul>
    <li><?php echo __('Name', true).': '.$event['Event']['title']; ?></li>
    <li><?php echo __('Due Date', true).': '.date('l, F j, Y g:i a', strtotime($event['Event']['due_date'])); ?></li>
    <li><?php echo __('Close Date', true).': '.date('l, F j, Y g:i a', strtotime($event['Event']['release_date_end'])); ?></li>
</ul>
<?php 
echo __('Please complete the ', true).$type.__(' before it closes.', true);
if (!empty($penalty)) {
    echo __(' There is a penalty for submitting after the due date.', true);
}
?>
<p>
<?php echo __('Thank you', true); ?>
</p>
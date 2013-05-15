<?php
echo __('Hello ', true).$user['User']['full_name'].",\n\n";
echo __('A ', true).$type.__(' for ', true).$course['Course']['course'].__(' is made available to ', true). 
__("you in iPeer, which has yet to be completed.\n\n", true);
echo __('Name', true).': '.$event['Event']['title']."\n";
echo __('Due Date', true).': '.date('l, F j, Y g:i a', strtotime($event['Event']['due_date']))."\n";
echo __('Close Date', true).': '.date('l, F j, Y g:i a', strtotime($event['Event']['release_date_end']))."\n\n";
echo __('Please complete the ', true).$type.__(' before it closes.', true);
if (!empty($penalty)) {
    echo __(' There is a penalty for submitting after the due date.', true);
}
echo "\n\n".__('Thank you', true);

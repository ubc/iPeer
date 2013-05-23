<?php
echo __('Hello ', true).$user_data['User']['full_name'].",\n\n";
echo __("Your iPeer password has been reset to the password below.", true)."\n\n";
echo __('Username', true).': '.$user_data['User']['username']."\n";
echo __('Password', true).': '.$user_data['User']['tmp_password']."\n";
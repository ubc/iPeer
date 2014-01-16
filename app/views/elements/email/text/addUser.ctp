<?php
echo __('Hello ', true).$name.",\n\n";
echo __('An instructor or admin has created an account for you in iPeer.', true)."\n\n";
echo __('Username', true).': '.$username."\n";
echo __('Password', true).': '.$password."\n\n";
echo __('You can login at ', true).Router::url('/login', true).'.';
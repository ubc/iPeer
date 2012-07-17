<?php
$ret = shell_exec('mysql -u root < clean.sql');
$ret = shell_exec('mysql -u root database_name < ../../app/config/sql/ipeer_samples_data.sql');
$ret = shell_exec('mysql -u root database_name < superadmin.sql');

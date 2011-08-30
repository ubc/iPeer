<span class="required">NOTE</span>: <br />
<ul>
  <li><strong><?php __('Username</strong> <u>must</u> the same at the <strong>Student Number')?></strong>.</li>
  <li><?php __('All fields mandatory, except email and password.')?></li> 
  <li><?php __('If email column is missing, students will be requested to fill in when they log in the first time.')?></li>
  <li><?php __('If password column is missing, system will generate random password for each student.')?></li>
  <li><?php __('If an external authentication module is enabled (e.g. CWL or Shiboleth), password column can be ignored. Students will use external authentication module to login.')?></li>
  <li><?php __('Please make sure to remove header from the CSV file.')?></li>
</ul>
<br />
<?php __('Formatting:')?>
<pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
<?php __('Username,First Name,Last Name,Student#,<i>Email(optional),Password(optional)')?></i>
</pre>

<br />
Examples:<br />
<pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
22928030, Sam,     Badhan,   22928030, sam@server.com, password123
78233046, Jamille, Borromeo, 78233046, jb@server.com,  pass5323123
39577051, Jordon,  Cheung,   39577051, jc@server.com,  psaswdrcD23
68000058, David,   Cliffe,   68000058, dc@server.com,  password123
</pre>

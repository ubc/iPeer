<div id="UserImport">
<h2><?php __('Instructions')?></h2>
<ul>
    <li><?php __('All fields mandatory, except email and password.')?></li> 
    <li><?php __('If email column is missing, students can update their profiles by clicking on their name (near the Logout link).')?></li>
    <li><?php __('If password column is missing, system will generate random password for each student.')?></li>
    <li><?php __('If the user already exists, their password will NOT be changed.')?></li>
    <li><?php __('If an external authentication module is enabled (e.g. CWL or Shibboleth), password column can be ignored. Students will use external authentication module to login.')?></li>
    <li><?php __('Please make sure to remove header from the CSV file.')?></li>
</ul>

<h3><?php __('Formatting:')?></h3>
<pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
<?php __('Username,First Name,Last Name,Student#,<i>Email(optional),Password(optional)</i>')?>
</pre>

<h3><?php __('Examples:')?></h3>
<pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
user1,first1,last1,student1,1@example.com,password1
user2,first2,last2,student2,2@example.com,password2
user3,first3,last3,student3,3@example.com,password3
user4,first4,last4,student4,4@example.com,password4
</pre>

<h2><?php __('Import')?></h2>

<?php 
echo $this->Form->create(null, array('type' => 'file'));
echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
echo $this->Form->input('Course', 
    array('multiple' => false, 'default' => $courseId));
echo $this->Form->input('update_class',
    array('type'=>'checkbox'));
?><div class="help-text"><?php __('Select, if you would like to remove students not in your new list.')?></div><?php
echo $this->Form->submit(__('Import', true));
echo $this->Form->end();
?>

</div>

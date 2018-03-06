<div id="UserImport">
    <?php if ($isFileImport): ?>
        <h2><?php __('Instructions')?></h2>
        <ul>
            <li><?php __('All fields mandatory, except email and password.')?></li> 
            <li><?php __('If email column is missing, students can update their profiles by clicking on their name (near the Logout link).')?></li>
            <li><?php __('If password column is missing, system will generate random password for each student.')?></li>
            <li><?php __('If the user already exists, their password will NOT be changed.')?></li>
            <li><?php __('If an external authentication module is enabled (e.g. CWL or Shibboleth), password column can be ignored. Students will use external authentication module to login.')?></li>
            <li><?php __('Please make sure to remove the header from the CSV file.')?></li>
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
    <?php else: ?>
        <ul>
            <li><?php __("This function will import instructors, TAs, and students from corresponding Canvas course")?></li>
            <li><?php __("TAs in Canvas will be imported as instructors for the course")?></li>
            <li><?php __("Instructors / TAs with existing iPeer accounts but not with Primary Role as Instructor will NOT be imported")?></li>
            <li><?php __("Existing students in the iPeer course will be removed and a fresh roster is imported from Canvas")?></li>
            <li><?php __("Existing instructors / TAs in the iPeer course will be kept and merged with those imported from Canvas")?></li>
        </ul>
    <?php endif; ?>

<h2><?php __('Import')?></h2>

<?php 
echo $this->Form->create(null, array('url' => $formUrl, 'type' => 'file'));
if ($isFileImport) {
    echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
}
else {
    echo $this->Form->input('canvasCourse', array('label'=>'From Canvas Course', 'multiple' => false, 'default' => isset($defaultCanvasId)? $defaultCanvasId : '', 'disabled' => isset($defaultCanvasId)? 'disabled' : ''));
    if (isset($defaultCanvasId)) {
        echo $this->Form->hidden('canvasCourse', array('value' => $defaultCanvasId));
    }
}
echo $this->Form->input('Course', array('label'=>'Into iPeer Course', 'multiple' => false, 'default' => $courseId, 'disabled' => 'disabled'));
echo $this->Form->hidden('Course', array('value' => $courseId));
if ($isFileImport) {
    echo $this->Form->input('update_class', array('label'=>'Remove old students', 'type'=>'checkbox'));
}
echo $this->Form->submit(__('Import', true));
echo $this->Form->end();
?>

</div>

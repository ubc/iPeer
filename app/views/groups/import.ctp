<div id='groupsimport'>
    <?php if ($isFileImport): ?>
        <h2><?php __('Instructions') ?></h2>
        <ul>
            <li><?php __('Please make sure to remove the header in CSV file.')?></li>
            <li><?php __('All fields are mandatory. The system will generate the group numbers.')?></li>
            <li><?php __('All group names must be unique within the class.')?></li>
            <li><?php __('The student identifiers that can be used are usernames or student numbers.')?></li>
            <li><?php __("Please make sure the column matches the username/student number in the students' profile")?></li>
        </ul>

        <h3><?php __('Formatting:')?></h3>
        <pre id='example'>
            <?php __('Student Identifier, Group Name')?>
        </pre>

        <h3><?php __('Examples:')?></h3>
        <pre id='example'>
            29978037, <?php __('Team A')?><br>
            29978063, <?php __('Team A')?><br>
            29978043, <?php __('Team B')?><br>
            29978051, <?php __('Team B')?>
        </pre>
    <?php endif; ?>

<h2><?php __('Import')?></h2>

<?php
echo $this->Form->create(null, array('type' => 'file', 'url' => $formUrl));
if ($isFileImport) {
    echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
    echo $this->Form->input('identifiers', array(
        'type' => 'radio',
        'options' => array('student_no' => 'Student No.', 'username' => 'Username'),
        'legend' => __('Student Identifier', true),
        'default' => 'username'
    ));
    ?><div class="help-text"><?php __('The student identifier used in the CSV file.')?></div><?php
}
else {
    echo $this->Form->input('canvasCourse', array('label'=>'From Canvas Course', 'multiple' => false));
}
echo $this->Form->input('Course', array('label'=>'Into iPeer Course', 'multiple'=>false, 'default' => $courseId));
echo $this->Form->input('update_groups', array('label'=>'Update group members for existing groups.', 'type'=>'checkbox'));
echo $this->Form->submit(__('Import', true));
echo $this->Form->end();
?>
</div>

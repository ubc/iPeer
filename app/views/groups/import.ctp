<div id='groupsimport'>

    <?php if ($importFrom == 'file'): ?>

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

    <h2><?php __('Import')?></h2>

    <?php
    echo $this->Form->create(null, array('type' => 'file', 'url' => $formUrl));
    echo $this->Form->input('file', array('type' => 'file', 'name' => 'file'));
    echo $this->Form->input('identifiers', array(
        'type' => 'radio',
        'options' => array('student_no' => 'Student No.', 'username' => 'Username'),
        'legend' => __('Student Identifier', true),
        'default' => 'username'
    ));
    ?><div class="help-text"><?php __('The student identifier used in the CSV file.')?></div><?php
    if (!empty($courseId)) {
        echo $this->Form->hidden('Course', array('value' => $courseId));
    }
    echo $this->Form->input("Course", array("label"=>"Into iPeer Course", "multiple"=>false, "default" => $courseId, "disabled"=>!empty($courseId)));
    echo $this->Form->input('update_groups', array('label'=>'Update group members for existing groups.', 'type'=>'checkbox'));
    echo $this->Form->submit(__('Import', true));
    echo $this->Form->end();
    ?>

    <?php elseif (!empty($importSuccess)): ?>

    <br><br>
    <p><a href="/courses/home/<?php echo $courseId; ?>">&laquo; Back to course homepage</a></p>

    <?php else: ?>

    When you press the Import button below:
    <ul>
        <li><?php __('The roster for this course will be imported from Canvas.')?></li>
        <li><?php __('All the groups from the selected group set will be imported.')?></li>
    </ul>

    <br><br>

    <?php
    echo $this->Form->create(null, array("id" => "syncCanvasForm", "class"=>"prepare", "url" => $formUrl ));

    if (!empty($courseId)) {
        echo $this->Form->hidden('Course', array('value' => $courseId));
    }
    echo $this->Form->input("Course", array("label"=>"iPeer Course", "multiple"=>false, "default" => $courseId, "disabled"=>!empty($courseId)));

    if (!empty($canvasCourseId)) {
        echo $this->Form->hidden('canvasCourse', array('value' => $canvasCourseId));
    }
    if (!empty($canvasCourses)) {
        echo $this->Form->input("canvasCourse", array("label"=>"Canvas Course", "multiple" => false, "default" => $canvasCourseId, "disabled"=>!empty($canvasCourseId)));
    }
    else {
        echo '<div class="input select">' . $this->Form->label("Canvas Course") . '</div>';
        echo $this->Form->select("canvasCourseInaccessible", array('0'=>'No accessible Canvas courses'), null, array("default" => '0', "disabled"=>true));
        echo $this->Form->hidden('canvasCourse', array('value' => $canvasCourseId));
    }

    if (!empty($courseId) && !empty($canvasCourseId)) :

        echo $this->Form->input("canvasGroupCategory", array("label"=>"Canvas Group set", "multiple" => false));

    endif;

    ?><label class="defLabel"></label><?php
    echo $this->Form->submit(__("Import", true), array("class" => "button"));
    echo $this->Form->end();

    endif; ?>

</div>

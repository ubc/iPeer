<?php if ($exportTo == 'file'): ?>

<?php echo $html->script('groups');?>
<div class="content-container">
    <form name="frm" id="frm" method="POST" action="<?php echo $html->url('export/'.$courseId) ?>">
      <table class="standardtable">
        <tr>
          <th colspan="2" align="center">Export As</th>
        </tr>
        <tr>
          <td width="30%">Export Filename:</td><td width="40%"><input type="text" name="file_name" value="<?php if(isset($file_name)) echo $file_name;?>" />.csv</td>
        </tr>
        <tr>
          <th colspan="2" align="center">Export Group Fields</th>
        </tr>
        <tr><td colspan="2" style="color:darkred; font-size:smaller"> (Please select at least one of the fields)</td></tr>
        <tr>
          <td width="60%">Include Group Number(s):</td><td><input type="checkbox" name="include_group_numbers" checked /></td>
        </tr>
        <tr>
          <td width="60%">Include Group Name(s):</td><td><input type="checkbox" name="include_group_names" checked /></td>
        </tr>
        <?php if (User::hasPermission('functions/viewusername')) { ?>
            <tr>
              <td width="60%">Include Username(s):</td><td><input type="checkbox" name="include_usernames" checked /></td>
            </tr>
        <?php } ?>
        <tr>
          <td width="60%">Include Student Id #:</td><td><input type="checkbox" name="include_student_id" checked /></td>
        </tr>
        <tr>
          <td width="60%">Include Student Name(s):</td><td><input type="checkbox" name="include_student_name" checked /></td>
        </tr>
        <?php /*if (User::hasPermission('functions/viewemailaddress')) { ?>
            <tr>
              <td>Include Student Email(s):</td><td><input type="checkbox" name="include_student_email" /></td>
            </tr>
        <?php }*/ ?>
        </table>
        <table class="standardtable">
        <tr>
          <th>Group Selection</th>
        </tr>
        <tr>
          <td>
<?php
echo $this->element("groups/group_list_chooser",
    array('all' => $unassignedGroups, 'assigned'=>'',
    'allName' =>  __('Available Groups', true), 'selectedName' => __('Participating Groups', true),
    'itemName' => 'Group', 'listStrings' => array("Group #", "group_num"," - ","group_name")));
?>
          </td>
        </tr>
        <tr>
          <td>
<?php echo $this->Form->submit(ucfirst($this->action).__(' Group', true), array('div' => false,
    'onClick' => "processSubmit(document.getElementById('selected_groups'));")) ?>
          </td>
        </tr>
      </table>
    </form>
</div>

<?php elseif (!empty($exportSuccess)): ?>

    <br><br>
    <p><a href="/courses/home/<?php echo $courseId; ?>">&laquo; Back to course homepage</a></p>

<?php else: ?>

<div class="instructions">

When you press the Export button below:
    <ul class="bulleted-list">
        <li><?php __('All the groups in the selected Canvas group set will be deleted.')?></li>
        <li><?php __('The groups in this iPeer course will be exported to the selected group set in Canvas, along with their members.')?></li>
        <li><?php __('Any students in iPeer that are not in Canvas will not be exported.')?></li>
    </ul>

</div>

    <br><br>

Export groups to: <br><br>
    <?php
    echo $this->Form->create(null, array("id" => "syncCanvasForm", "class"=>"prepare", "url" => $formUrl ));

    echo $this->Form->hidden('Course', array('value' => $courseId));

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
    echo $this->Form->submit(__("Export", true), array("class" => "button"));
    echo $this->Form->end(); ?>

<?php endif; ?>
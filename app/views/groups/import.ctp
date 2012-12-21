<table class='standardtable'>
    <tr>
        <th width="50%"><?php __('Instructions')?></th>
        <th width="50%"><?php __('Import')?></th>
    </tr>
    <tr>
        <td style="text-align: left;">
          <?php __('NOTE:')?>
          <ul class="instructions">
            <li><?php __('Please make sure the username column matches the username column in student import file.')?></li>
            <li><?php __('Please make sure to remove the header in CSV file.')?></li>
            <li><?php __('All columns are required.')?></li>
          </ul>
          <br />
          <?php __('Format')?>:
          <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
            <?php __('Username, Group#, and Group Name')?>
          </pre>

          <?php __('Example')?>:
          <pre style='background-color: white; border:1px solid black; padding:5px; margin:5px'>
            29978037, 1, <?php __('Team A')?><br>
            29978063, 1, <?php __('Team A')?><br>
            29978043, 2, <?php __('Team B')?><br>
            29978051, 2, <?php __('Team B')?>
          </pre>
        </td>
        <td style="text-align: left;">
    <form name="importfrm" id="importfrm" method="POST" action="<?php echo $html->url('import/'.$courseId) ?>" enctype="multipart/form-data" >
        <h3>1) <?php __('Please select a CSV file to import')?>:</h3>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="file" name="file" value="<?php __('Browse')?>" /><br>
        <?php
            $params = array('controller'=>'users', 'courseList'=>$coursesList, "defaultOpt" => $courseId);
        ?>
        <br /><h3>2) <?php __('Select the course to import into')?>:</h3>
        &nbsp;&nbsp;&nbsp;&nbsp;
            <?php echo $this->element('courses/course_selection_box', $params); ?>
        <br /><br /><h3>3) <?php __('Click the button below to Create the Groups')?>:</h3>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" value="<?php __('Import Group CSV')?> "/>
    </form>
    </td>
    </tr>
</table>

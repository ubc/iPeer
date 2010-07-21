<!-- elements::ajax_user_list end -->
<div id="ajax_update">
<?php $pagination->loadingId = 'loading';?>
<?php if($pagination->set($paging)):?>
<?php endif;?>
<table width="95%"  border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td></td>
      </tr>
  </table>
  <table width="95%" border="0" cellspacing="2" cellpadding="4">
	<tr class="tableheader">
	    <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
	    <th width="10%">Actions</th>
	    <?php endif;?>
	    <th width="16%">Course</th>
	    <th width="35%">Title</th>
	    <th>Status</th>
	    <th width="18%">Created By</th>
	    <th width="18%">Last Updated By</th>
  </tr>
  <?php $i = '0';
  ?>
	<?php
	if (isset($data)) {
  	foreach ($data as $row): $course = $row['courses']; ?>
      <tr class="tablecell">
        <td align="center">
    	  <a href="<?php echo $this->webroot.$this->themeWeb.'courses/view/'.$course['id']?>"><?php echo $html->image('icons/view.gif',array('border'=>'0','alt'=>'View'))?></a>
    	  <?php if($rdAuth->role == 'A' || $rdAuth->role == 'I'):?>
    	  <a href="<?php echo $this->webroot.$this->themeWeb.'courses/edit/'.$course['id']?>"><?php echo $html->image('icons/edit.gif',array('border'=>'0','alt'=>'Edit'))?></a>
    	  <a href="<?php echo $this->webroot.$this->themeWeb.'courses/delete/'.$course['id']?>" onclick="return confirm('Are you sure you want to delete course &ldquo;<?php echo $course['course']?>&rdquo;? \n All related data such as students and events will be deleted as well.')"><?php echo $html->image('icons/delete.gif',array('border'=>'0','alt'=>'Delete'))?></a>

    	  <?php endif;?>
    	  </td>
        <td align="left">
    	  <?php
            if (!empty($course['homepage'])) {
                $homepage = $course['homepage'];
                if (strpos(strtolower($homepage), "http://") === false) {
                    $homepage = "http://" . $homepage;
                }
            } else {
                $homepage = "";
            }
    		echo !empty($homepage) ? "<a href='$homepage'>" : "";
    		echo $html->image('icons/home.gif',array('border'=>'0', 'align'=>'middle','alt'=>'home'));
    		echo !empty($homepage) ? "</a>":'';
    		?>
    	  <?php echo $html->link($course['course'], '/courses/home/'.$course['id']) ?>
    	  </td>
        <td align="left">
          <?php echo $html->link($course['title'], '/courses/home/'.$course['id']) ?>
        </td>
        <td align="left"> <?php
          if ($course['record_status'] == 'A') {
            echo 'Active';
          } else if ($course['record_status'] == 'I'){
            echo 'Inactive';
          }
          ?>
        </td>
        <td align="center">
          <?php
          $params = array('controller'=>'courses', 'userId'=>$course['creator_id']);
          echo $this->renderElement('users/user_info', $params);
          ?><br/>
          <?php echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($course['created']))) ?>
        </td>
        <td align="center">
          <?php
          $params = array('controller'=>'courses', 'userId'=>$course['updater_id']);
          echo $this->renderElement('users/user_info', $params);
          ?><br/>
          <?php
              if (!empty($course['modified'])) echo $this->controller->Output->formatDate(date('Y-m-d H:i:s', strtotime($course['modified'])));
          ?>
        </td>
      </tr>
  	<?php $i++;?>
    <?php endforeach;
  }?>
  <?php if ($i == 0) :?>
	<tr class="tablecell" align="center">
	    <td colspan="6">Record Not Found</td>
  </tr>
  <?php endif;?>
  </table>
    <table width="95%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#E5E5E5">
      <tr>
        <td align="left"><?php echo $html->image('layout/corner_bot_left.gif',array('align'=>'left','alt'=>'left'))?></td>
        <td align="right"><?php echo $html->image('layout/corner_bot_right.gif',array('align'=>'right','alt'=>'right'))?></td>
      </tr>
    </table>
	<?php $pagination->loadingId = 'loading2';?>
	<div id="page-numbers">
    <table width="95%"  border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="33%" align="left"><?php echo $pagination->result('Results: ')?></td>
        <td width="33%"></td>
        <td width="33%" align="right">
        </td>
      </tr>
    </table>
</div>
</div>
<!-- elements::ajax_user_list end -->

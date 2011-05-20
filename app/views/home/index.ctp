<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
      <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>My Course<?php echo count($course_list) > 1 ? 's': ''; ?></td>
          <td><div align="right"><!--a href="#evaldue" onClick="showhide('evaldue'); toggle(this);">[-]</a--></div></td>
        </tr>
      </table>
      <div id="activeCourse" style="background: #FFF;"> <br>
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
      <?php foreach($course_list['A'] as $row):?>

		  <tr class="tableheader">
			  <td colspan="2">
			    <?php //echo $row['Course']['course'] ;
                                echo $html->link(
                                    $html->image("icons/home.gif", array("border"=>"0","alt"=>$row['Course']['course'])).$row['Course']['course'],
                                    "/courses/home/".$row['Course']['id'],
                                    array('escape'=>false)
                                );
                            ?>&nbsp;
			  </td>
		  </tr>

      <tr class="tablecell2">
        <td width="15"></td>
        <td>
          <b>Instructor<?php echo count($row['Instructor']) > 2 ? 's':''; ?>: </b>&nbsp;
          <?php echo $this->element('courses/course_instructors',
                                    array('instructors' => $row['Instructor']));?>

        </td>
      </tr>

      <tr class="tablecell2">
        <td width="15"></td>
    		<td>
  			  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
          <tr class="tablecell2">
            <td width="405"><b>Events: </b>&nbsp;</td>
            <td width="254"><b>Completion Ratio</b></td>
            <td width="243"><b>Due Date </b></td>
          </tr>

          <tr>
          <?php if (count($row['Event']) == 0):?>
            <td colspan='3'>None</td>
          <?php else:?>
            <?php foreach($row['Event'] as $event):?>
        		<td>
        			      <?php echo $this->Html->link($event['title'],
                                                 ($event['event_template_type_id'] == 3) ? 'surveygroups/viewresult/'.$event['id'] : 'evaluations/view/'.$event['id']);?>
                    <br />
        			      <?php if ($event['to_review_count']>0): ?>
                      <font color='darkred' style='padding-left:2em'>
                        <?php echo $event['to_review_count'] . ' new group evaluation' . ($event['to_review_count'] > 1) ? 's' : '' . ' to review.'?>
                      </font>
          			     <?php endif;?>       
        			        </a></td>
        			        <td><?php echo $event['completed_count'] . ' of ' . $event['student_sum'] . ' Students</td>';?>
        			        <td><?php echo Toolkit::formatDate($event['due_date'])?></td>
        			      <?php endforeach;?>
        			    <?php endif;?>
                 </tr>
      			    </table>
      			</td>
      		</tr>
	  <tr>
	    <td>
	<?php endforeach; ?>
  <?php if (0 == count($course_list['A'])):?>
    <tr class="tablecell"><td colspan="4">
        <b>No courses at this time</b>
    </td></tr>
  <?php	endif;?>
    <tr><td>
    </td>
    </tr>
  </table>
    
  <div id="short_help" <?php echo ($course_list['A'] == 0) ? '':'style="display:none"'?>>
  <h5>To use iPeer you have to add a course.</h5>
        <ul>
            <li>Please <u>add a course</u> from the yellow "Courses" tab above</li>
            <li>Then <U>register students</u> into that course from that courses summary display. This display will avaliable (once the course is created) by clicking on the courses name from most menus .</li>
            <li>Put your students into <u>groups</u> manually, (or, if you have the students complete a survey, iPeer can do it for you, using TeamMaker).
            <li>To create evaluations, check out the orange wizard link the top right hand corner "iPeer Tutorial Wizard".
                It has detailed movies on how to create evaluations.</li>
    </div>
    <div style="text-align:right"><a href="#" onClick="javascript:$('short_help').toggle();return false;">( Show/Hide short help )</a></div>

		<br>
      </div>
	  </td>
  </tr>
<?php if (isset($course_list['I'])):?>
  <tr>
    <td>
      <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>Inactive Course(s)</td>
          <td><div align="right"><!--a href="#evaldue" onClick="showhide('evaldue'); toggle(this);">[-]</a--></div></td>
        </tr>
      </table>
      <div id="activeCourse" style="background: #FFF;"> <br>
      <table width="95%"  border="0" align="center" cellpadding="4" cellspacing="2">
	  <?php foreach($course_list['I'] as $row):?>
		  <tr class="tableheader">
			<td colspan="2">
			    <?php echo $row['Course']['course'] ?>&nbsp;
			</td>
		  </tr>
      <tr class="tablecell2">
	      <td width="15"></td>
  			<td>
  			    <b>Instructor(s): </b>&nbsp;
            <?php echo $this->element('courses/course_instructors',
                                  array('instructors' => $row['Instructor']));?>
  			</td>
  		</tr>
      <?php endforeach; ?>
		</table>
		<br>
      </div>
	  </td>
  </tr>
<?php endif;?>

</table>

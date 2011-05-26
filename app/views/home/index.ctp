<div class="title">My Course<?php echo (isset($course_list['A']) && count($course_list['A']) > 1) ? 's': ''; ?></div>

<div class="content-container">

  <?php foreach($course_list as $row):?>
      <div class="course">
	  	  <div class="course-header">
          <?php echo $html->link($html->image("icons/home.gif", array("border"=>"0","alt"=>$row['Course']['course'])).$row['Course']['course'], 
                                 "/courses/home/".$row['Course']['id'],
                                 array('escape'=>false)
                                );?>
		    </div>

        <div class="instructor">
            <b>Instructor<?php echo count($row['Instructor']) > 2 ? 's':''; ?>: </b>&nbsp;
            <?php echo $this->element('courses/course_instructors',
                                      array('instructors' => $row['Instructor']));?>

        </div>

        <div class="event">
  		    <table width="100%" border="0" align="center" cellpadding="4" cellspacing="2">
            <tr class="tablecell2">
              <td width="405"><b>Events: </b>&nbsp;</td>
              <td width="254"><b>Completion Ratio</b></td>
              <td width="243"><b>Due Date </b></td>
            </tr>

            <?php if (count($row['Event']) == 0):?>
            <tr><td colspan='3'>None</td></tr>
            <?php else:?>
              <?php foreach($row['Event'] as $event):?>
              <tr><td>
 			          <?php echo $this->Html->link($event['title'],
                                             ($event['event_template_type_id'] == 3) ? 'surveygroups/viewresult/'.$event['id'] : 'evaluations/view/'.$event['id']);?>
                <br />
        		  	<?php if ($event['to_review_count']>0): ?>
                  <font color='darkred' style='padding-left:2em'>
                    <?php echo $event['to_review_count'] . ' new group evaluation' . (($event['to_review_count'] > 1) ? 's' : '') . ' to review.'?>
                  </font>
            		<?php endif;?>       
          		</td>
          		<td><?php echo $event['completed_count'] . ' of ' . $event['student_count'] . ' Students</td>';?>
          		<td><?php echo Toolkit::formatDate($event['due_date'])?></td>
              </tr>
        	  	<?php endforeach;?>
          	<?php endif;?>
            </table>

          </div>
        </div>
	      <?php endforeach; ?>

        <?php if (0 == count($course_list)):?>
          <div class="course"><b>No courses at this time</b></div>
        <?php	endif;?>
   </div> 

    <?php if (isset($course_list['I'])):?>
      <div class="title">Inactive Course(s)</div>

      <div class="content-container">
    	  <?php foreach($course_list['I'] as $row):?>
        <div class="course">
          <div class="course-header"><?php echo $row['Course']['course'] ?></div>
          <div class="instructor">
  			    <b>Instructor(s): </b>&nbsp;
            <?php echo $this->element('courses/course_instructors',
                                  array('instructors' => $row['Instructor']));?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif;?>
  <div class="content-container">
  <div id="short_help" <?php echo ($course_list == 0) ? '':'style="display:none"'?>
  <h5>To use iPeer you have to add a course.</h5>
        <ul>
            <li>Please <u>add a course</u> from the yellow "Courses" tab above</li>
            <li>Then <U>register students</u> into that course from that courses summary display. This display will avaliable (once the course is created) by clicking on the courses name from most menus .</li>
            <li>Put your students into <u>groups</u> manually, (or, if you have the students complete a survey, iPeer can do it for you, using TeamMaker).
            <li>To create evaluations, check out the orange wizard link the top right hand corner "iPeer Tutorial Wizard".
                It has detailed movies on how to create evaluations.</li>
    </div>
    <div style="text-align:right"><a href="#" onClick="javascript:$('short_help').toggle();return false;">( Show/Hide short help )</a></div>
    </div>

</div>

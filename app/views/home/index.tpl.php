<?php
        //$b=print_r($rdAuth,true);
        //echo "<pre>$b</pre>";
?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td>
      <table class="title" width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>My Course<?php echo count($activeCourseDetail) > 1 ? 's': ''; ?></td>
          <td><div align="right"><!--a href="#evaldue" onClick="showhide('evaldue'); toggle(this);">[-]</a--></div></td>
        </tr>
      </table>
<?php
//$a=print_r($activeCourseDetail,true);
//echo "<pre>$a</pre>";
?>

      <div id="activeCourse" style="background: #FFF;"> <br>
      <table width="95%" border="0" align="center" cellpadding="4" cellspacing="2">
  	<?php $i = '0';?>
	  <?php

	  foreach($activeCourseDetail as $row):

	    isset($row['active_course']['Course'])? $activeCourse = $row['active_course']['Course']: $activeCourse = null;
	    isset($row['active_course']['Course']['instructors'])? $courseInstructors = $row['active_course']['Course']['instructors']: $courseInstructors = null;
	    isset($row['active_course']['Course']['events'])? $courseEvents = $row['active_course']['Course']['events']: $courseEvents = null;

	    if (isset($activeCourse['id']) && $activeCourse['record_status'] == 'A') {?>
		  <tr class="tableheader">
			<td colspan="2">
			    <?php echo $activeCourse['course'] ?>&nbsp;
			</td>
		  </tr>
			    <tr class="tablecell2">
			      <td width="15"></td>
      			<td>
      			    <b>Instructor<?php echo  count($courseInstructors) > 2 ? 's':''; ?>: </b>&nbsp;
              <?php $params = array('controller'=>'home', 'courseInstructors'=>$courseInstructors);
              echo $this->renderElement('courses/course_instructors', $params);?>

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

      			    <?php //echo "<ul>";
      			          if (count($courseEvents)==0) {
      			            echo "<tr><td colspan='3'>None</td></tr>";
      			          }
      			          else {
        			          foreach($courseEvents as $row):
        			            echo '<tr>';
        			            $event = $row['Event'];
        			            echo "<td>";?>
        			            <a href="<?php
        			            if ($event['event_template_type_id'] == 3)
                            echo $this->webroot.$this->themeWeb.'surveygroups/viewresult/'.$event['id'];
        			            else echo $this->webroot.$this->themeWeb.'evaluations/view/'.$event['id']?>"><?php echo $event['title']?><br />
        			            <?php //$event['title'];
        			            if ($event['to_review_count']>0) {
                                    $plural = ($event['to_review_count'] > 1) ? "s" : "";
                                    echo "<font color='darkred' style='padding-left:2em'>$event[to_review_count] new group ";
                                    echo "evaluation$plural to review.</font>";
          			            }

        			            echo "</a></td>";
                                              //Quick fix of a bug
                                              $temp=$event['completed_count'];
        			            echo "<td>".$temp." of ".$event['student_sum']." Students</td>";
        			            echo "<td>".$this->controller->Output->formatDate($event['due_date'])."</td>";
        			            echo '</tr>';
        			          endforeach;
        			        }
      			          //echo "</ul>";
      			     ?>
      			    </table>
      			</td>
      		</tr>
	  <?php }
	  $i++;?>
	  <tr>
	    <td>
	<?php endforeach; ?>
  <?php if ($i ==0 ) {  ?>
    <tr class="tablecell"><td colspan="4">
        <b>No courses at this time</b>
    </td></tr>
  <?php	} // if ($i ==0 )?>
    <tr><td>
    <?php
     $showShortHelpNow = ($i == 0);
    ?>
    </td></tr>
  </table>
    <div id="short_help" style="display:none">
         <h5>To use iPeer you have to add a course.</h5>
        <ul>
            <li>Please <u>add a course</u> from the yellow "Courses" tab above</li>
            <li>Then <U>register students</u> into that course from that courses summary display. This display will avaliable (once the course is created) by clicking on the courses name from most menus .</li>
            <li>Put your students into <u>groups</u> manually, (or, if you have the students complete a survey, iPeer can do it for you, using TeamMaker).
            <li>To create evaluations, check out the orange wizard link the top right hand corner "iPeer Tutorial Wirard".
                It has detailed movies on how to create evaluations.</li>
    </div>
    <script>
        function showShortHelp() {
            var shortHelpDiv = $("short_help");
            shortHelpDiv.style.display = "block";
        }

    </script>

    <?php
        if ($showShortHelpNow) {
            echo "<script>javascript:showShortHelp();</script>";
        }
    ?>

    <div style="text-align:right"><a href="javascript:showShortHelp();">( Show short help )</a></div>

		<br>
      </div>
	  </td>
  </tr>
<?php
if ($inactiveCourseDetail!=null) {?>
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
	  <?php
	  //print_r($data);
	  foreach($inactiveCourseDetail as $row):
	    isset($row['inactive_course']['Course'])? $inactiveCourse = $row['inactive_course']['Course']: $inactiveCourse = null;
	    isset($row['inactive_course']['Course']['instructors'])? $incourseInstructors = $row['inactive_course']['Course']['instructors']: $incourseInstructors = null;
	    isset($row['inactive_course']['Course']['events'])? $incourseEvents = $row['inactive_course']['Course']['events']: $incourseEvents = null;
	    if (isset($inactiveCourse['Course']['id'])) {?>
		  <tr class="tableheader">
			<td colspan="2">
			    <?php echo $inactiveCourse['Course']['course'] ?>&nbsp;
			</td>
		  </tr>
      <tr class="tablecell2">
	      <td width="15"></td>
  			<td>
  			    <b>Instructor(s): </b>&nbsp;
              <?php $params = array('controller'=>'home', 'courseInstructors'=>$incourseInstructors);
              echo $this->renderElement('courses/course_instructors', $params);?>
  			</td>
  		</tr>

	  <?php } $i++;?>
	<?php endforeach; ?>
		</table>
		<br>
      </div>
	  </td>
  </tr>
<?php } ?>


</table>

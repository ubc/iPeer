<?php $displayField = ClassRegistry::init('User')->displayField;?>

<?php echo $this->Js->link($html->image('icons/x_small.gif',array('border'=>'0','alt'=>'Delete', 'instructor_id' => $instructor['id'], 'instructor_name' => $instructor[$displayField])),
                           array('action' => 'deleteInstructor', 
                                 'instructor_id' => $instructor['id'], 
                                 'course_id' => $course_id),
                           array('confirm' => __('Are you sure to remove instructor ', true).$instructor[$displayField].__(' from this course?', true),
                                 'escape' => false,
                                 'success' => 'if(response.responseText == "") {event.target.up(1).fade();var instructor_id = event.target.readAttribute("instructor_id");var instructor_name = event.target.readAttribute("instructor_name");$("CourseInstructors").insert({bottom: "<option value=\""+instructor_id+"\">"+instructor_name+"</option>"});} else {console.log(response);alert("Failed to remove the instructor form this course.\nReason: "+response.responseText);}',
                                 'error' => 'alert('.__("Communication error!", true).')',
                                 'buffer' => false,
                                ))?>
<?php echo $this->element('users/user_info', array('data' => $instructor))?>

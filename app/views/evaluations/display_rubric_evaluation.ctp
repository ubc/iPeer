 <?php
$params = array('controller'=>'evaluations', 'allMembersCompleted'=>$allMembersCompleted, 'rubric'=>$rubric, 'groupMembers'=>$groupMembers, 'memberScoreSummary'=>$memberScoreSummary);
if ($displayFormat == 'Basic') {
  echo $this->element('evaluations/view_rubric_result_basic', $params);

} else {
  echo $this->element('evaluations/view_rubric_result_detail', $params);  
}
?>

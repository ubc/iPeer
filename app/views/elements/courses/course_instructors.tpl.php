<?php
  $insPos=1;
  //print_r($courseInstructors);die;
  if (!empty($courseInstructors)) {
    foreach($courseInstructors as $row){
      $instructorId = null;
      if (isset($row['User'])) {
         $instructorId = $row['User']['id'];
      } else if (isset($row['user_id'])) {
         $instructorId = $row['user_id'];
      }
      if (isset($instructorId) && $instructorId!=1) {
        $params = array('controller'=>$controller, 'userId'=>$instructorId);
        echo $this->renderElement('users/user_info', $params);

        if ($insPos > 0 && $insPos < count($courseInstructors)-1) {
          echo "; ";
        }
        $insPos++;
      }
    }
  }
  if ($insPos == 1) {
    echo 'N/A';
  }
?>
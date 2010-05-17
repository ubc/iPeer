<?php


  header('Content-type: application/csv');

  header('Content-Disposition: attachment; filename="classList.csv"');
$query = "select distinct users.first_name,users.last_name,users.student_no,questions.prompt,responses.response from survey_inputs join users join user_enrols on user_enrols.course_id=21 join responses on survey_inputs.response_id=responses.id join questions on survey_inputs.question_id=questions.id  where survey_inputs.survey_id=6 and survey_inputs.user_id=users.id";

$handler = mysql_connect('localhost','usr-ipeer-admin','c4e40b5a');

mysql_select_db('db_ipeer2_prod');

// Performing SQL query
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
   foreach ($line as $col_value) {
       echo "$col_value,";
   }
   echo "\n";
}
?>
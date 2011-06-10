<?php
/* SVN FILE: $Id$ */
/*
 * InstallHelper Component 
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class InstallHelperComponent
{
	function runInsertDataStructure($dbConfig, $params) 
	{
		$this->params = $params;
		$basicSQLFile = "../config/sql/ipeer.sql";
		$samplesFile = "../config/sql/ipeer_samples_data.sql";
		$xml_file = $this->params['form']['data_file']['tmp_name'];
    $to_import = $this->params['form']['to_import'];
		
		//Install database with sample data
		$dataOption = $dbConfig['data_setup_option'];
    if ($dataOption == 'A') {
  		//Install database with sample data structure
		  $dbConfig['filename'] = $samplesFile;
  		$runQuery = $this->dbSource($dbConfig);		  
		}		 
		else if ($dataOption == 'B') {
  		//Install database with basic structure
		  $dbConfig['filename'] = $basicSQLFile;
  		$runQuery = $this->dbSource($dbConfig);
		}
    else if($dataOption == 'C') {
    	// Install database with basic structure
      $dbConfig['filename'] = $basicSQLFile;
      $runQuery = $this->dbSource($dbConfig);
      // Import data from 1.6 
      set_time_limit(1200);
      $this->importData($xml_file, $to_import, $dbConfig);
    }
		
		return $runQuery; 
	}

	#
	# Read and execute SQL commands from a file
	#
	function dbSource($dbConfig) {

		$executeStaus = false;
	  $fname = $dbConfig['filename'];
	  
    //connect to the server
    $mysql = mysql_connect($dbConfig['host'], $dbConfig['login'], $dbConfig['password']);
    if(!$mysql) {
      die('Could not connect: ' . mysql_error());
      return($error);
    } 
    else {
      //Open the database
      $mysqldb = mysql_select_db($dbConfig['database']);
      if (!$mysqldb) {
        //TODO create database
        //return($error);
      }	  
  		
  		$fp = fopen( $fname, "r" );
  		if ( false === $fp ) {
  			//print "Could not open \"{$fname}\".\n";
  			return false;
  		}
  	
      mysql_query('BEGIN');

  		$cmd = "";
  		$done = false;
  	
  		while ( ! feof( $fp ) ) {
  			$line = trim( fgets( $fp, 1024 ) );
  			$sl = strlen( $line ) - 1;
  	
  			if ( $sl < 0 ) { continue; }
  			if ( "-" == $line{0} && "-" == $line{1} ) { continue; }
  	
  			if ( ";" == $line{$sl} ) {
  				$done = true;
  				$line = substr( $line, 0, $sl );
  			}
  	
  			if ( "" != $cmd ) { $cmd .= " "; }
  			$cmd .= $line;
  	
  			if ( $done ) {
  				//echo $cmd . ";<br /><br /><br />";
  				$result = mysql_query($cmd, $mysql);
          if (!$result)
          {
            $error = "Cannot run query";
            mysql_query('ROLLBACK');
            mysql_close($mysql);
            return $error;
          }
  				//if ($this->execute($cmd)) {
  				//	return false;
  				//}
  				$cmd = "";
  				$done = false;
  			}
  		}
  		fclose( $fp );
  		mysql_query("COMMIT");
  		mysql_close($mysql);
  	}
		return true;
		
	}    	
	
	function updateSystemParameters($data) 
  {
  	$this->SysParameter = new SysParameter;
  	$superAdmin = null;
  	
		if (!empty($data)) {
			foreach($data['SysParameter'] as $key => $value){
				$tmpSysParam = $this->SysParameter->findParameter($key);
				$tmpSysParam['SysParameter']['parameter_value'] = $value;
				$this->SysParameter->save($tmpSysParam);
				
				if ($key == 'system.super_admin') {
					$superAdmin = $value;
				}
			}
    }  
   	return $superAdmin;
  }
  
  function importData($xml_file, $to_import, $dbConfig)
  {
  	if(phpversion() < 5) {
  		$this->_importDataPhp4($xml_file, $to_import);
  	}
    else {
    	$this->_importDataPhp5($xml_file, $to_import);
    }
  }
  
  function _importDataPhp4($xml_file, $to_import)
  {
  	$this->User = new User;
    $this->Course = new Course;
    $this->Rubric = new Rubric;
    $this->RubricsLom = new RubricsLom;
    $this->RubricsCriteria = new RubricsCriteria;
    $this->RubricsCriteriaComment = new RubricsCriteriaComment;
    $this->SimpleEvaluation = new SimpleEvaluation;
    $this->Event = new Event;
    $this->UserCourse = new UserCourse;
    $this->UserEnrol = new UserEnrol;
    $this->Survey = new Survey;
    $this->SurveyQuestion = new SurveyQuestion;
    $this->Question = new Question;

    // get the xml file

    $dom = domxml_open_file($xml_file);
       
    foreach($to_import as $import)
    {
    	switch($import)
      {
      	case "administrators":
        case "students":
        case "instructors":
          $users = $dom->get_elements_by_tagname("users");
          foreach($users as $user)
          {
            if($user->has_child_nodes()) 
            {
            	// find fields
              $user_id = $this->_getValueByTagNamePhp4($user, "user_id");
              $username = $this->_getValueByTagNamePhp4($user, "username");
              $password = $this->_getValueByTagNamePhp4($user, "password");
              $first_name = $this->_getValueByTagNamePhp4($user, "first_name");
              $last_name = $this->_getValueByTagNamePhp4($user, "last_name");
              $student_number = $this->_getValueByTagNamePhp4($user, "student_number");
              $user_type = $this->_getValueByTagNamePhp4($user, "user_type");
              $last_login = $this->_getValueByTagNamePhp4($user, "last_login");
              $email = $this->_getValueByTagNamePhp4($user, "email");
              $title = $this->_getValueByTagNamePhp4($user, "title");
             
              switch($user_type)
              {
                case "instructor":
                  $role = "I";
                  break;
                case "student":
                  $role = "S";
                  break;
                case "administrator":
                  $role = "A";
                  break;
              }
            
              $to_save['User']['id'] = $user_id;
              $to_save['User']['username'] = $username;
              $to_save['User']['password'] = $password;
              $to_save['User']['first_name'] = $first_name;
              $to_save['User']['last_name'] = $last_name;
              $to_save['User']['student_no'] = $student_number; //student_number
              $to_save['User']['last_login'] = $last_login;
              $to_save['User']['email'] = $email;
              $to_save['User']['title'] = $title;
              $to_save['User']['role'] = $role; //user_type
              $to_save['User']['record_status'] = 'A';
              $to_save['User']['creator_id'] = 1;
              
              // save to database
              // special case for "root" user
              if($username == 'root') {
              	$to_save['User']['id'] = 1;
              	$this->User->save();
              }
              else {
                $this->User->directSave($to_save);
              }

              $this->User->id = null;
              $to_save = null;
            }
          }
          break;
        case "courses":
          $courses = $dom->get_elements_by_tagname("courses");
          foreach($courses as $course)
          {
          	if($course->has_child_nodes())
            {
            	// find fields
             
              $course_id = $this->_getValueByTagNamePhp4($course, "course_id");
              $course_no = $this->_getValueByTagNamePhp4($course, "course");
              $title = $this->_getValueByTagNamePhp4($course, "title");
              $homepage = $this->_getValueByTagNamePhp4($course, "homepage");
              $status = $this->_getValueByTagNamePhp4($course, "status");
              $owner = $this->_getValueByTagNamePhp4($course, "user_id"); // UserCourses...
              
              $user_course['UserCourse']['user_id'] = $owner;
              $user_course['UserCourse']['course_id'] = $course_id;
              
              if($status == 'active')
              {
                $record_status = 'A';
              }
              else 
              {
                $record_status = 'I';
              }
              
              $course_data['Course']['id'] = $course_id;
              $course_data['Course']['course'] = $course_no;
              $course_data['Course']['title'] = $title;
              $course_data['Course']['homepage'] = $homepage;
              $course_data['Course']['record_status'] = $record_status;
              $course_date['Course']['creator_id'] = $owner;
              
              $this->Course->directSave($course_data);
              $this->Course->id = null;
              $course_data = null;
              
              $this->UserCourse->save($user_course);
              $this->UserCourse->id = null;
              $user_course = null;
              
            }
          }
          $courses = null;
          break;
        case "rubrics":
          // get everything from the rubrics table
          // put all rows into the new rubrics table
          $rubrics = $dom->get_elements_by_tagname("rubrics");
          foreach($rubrics as $rubric)
          {
          	if($rubric->has_child_nodes())
            {
            	// get fields associated with this table
              $rubric_id = $this->_getValueByTagNamePhp4($rubric, "rubric_id");
              $rubric_name = $this->_getValueByTagNamePhp4($rubric, "name");
              $rubric_creator = $this->_getValueByTagNamePhp4($rubric, "user_id");
              $rubric_total_mark = $this->_getValueByTagNamePhp4($rubric, "total_mark");
              $rubric_zero = $this->_getValueByTagNamePhp4($rubric, "zero_mark");
              $rubric_lom_max = $this->_getValueByTagNamePhp4($rubric, "lom_max");
              $rubric_criteria = $this->_getValueByTagNamePhp4($rubric, "criteria");
              $rubric_availability = $this->_getValueByTagNamePhp4($rubric, "availability");
              $rubric_template = $this->_getValueByTagNamePhp4($rubric, "template");
              
              
              // do some translation here...
              if($rubric_zero) {
                $rubric_zero_mark = 'ON';
              }
              else {
                $rubric_zero_mark = 'OFF';
              }
              
              // put data into the format that CakePHP likes...
              $rubric_data['Rubric']['id'] = $rubric_id;
              $rubric_data['Rubric']['name'] = $rubric_name;
              $rubric_data['Rubric']['total_marks'] = $rubric_total_mark;
              $rubric_data['Rubric']['zero_mark'] = $rubric_zero_mark;
              $rubric_data['Rubric']['lom_max'] = $rubric_lom_max;
              $rubric_data['Rubric']['criteria'] = $rubric_criteria;
              $rubric_data['Rubric']['availability'] = $rubric_availability;
              $rubric_data['Rubric']['template'] = $rubric_template;
              $rubric_data['Rubric']['creator_id'] = $rubric_creator;
              
              // save!
              $this->Rubric->directSave($rubric_data);
              $this->Rubric->id =  null;
              $rubric_data = null;
            }
          }
          $rubrics = null;
          
          // get everything from the rubrics_lom table
          // put all the rows into the new rubrics_loms table
          $rubrics_lom = $dom->get_elements_by_tagname("rubrics_lom");
          foreach($rubrics_lom as $lom)
          {
          	// get fields associated with this table
            $lom_rubric_id = $this->_getValueByTagNamePhp4($lom, "rubric_id");
            $lom_number = $this->_getValueByTagNamePhp4($lom, "lom_number");
            $lom_comment = $this->_getValueByTagNamePhp4($lom, "lom_comment");
            
            // no translation needed here...
            
            // put data into the format that CakePHP likes...
            $rubrics_lom_data['RubricsLom']['rubric_id'] = $lom_rubric_id;
            $rubrics_lom_data['RubricsLom']['lom_num'] = $lom_number;
            $rubrics_lom_data['RubricsLom']['lom_comment'] = $lom_comment;
            
            // save!
            $this->RubricsLom->directSave($rubrics_lom_data);
            $this->RubricsLom->id = null;
            $rubrics_lom_data = null;
          }
          $rubrics_lom = null;
          
          // get everything from the rubrics_criteria_comments table
          // put all the rows into the new rubrics_criteria_comments table
          $rubrics_criteria_comments = $dom->get_elements_by_tagname("rubrics_criteria_comments");
          foreach($rubrics_criteria_comments as $rcc)
          {
            // get fields associated with this table
            $rcc_rubric_id = $this->_getValueByTagNamePhp4($rcc, "rubric_id");
            $rcc_criteria_num = $this->_getValueByTagNamePhp4($rcc, "criteria_number");
            $rcc_lom_num = $this->_getValueByTagNamePhp4($rcc, "lom_number");
            $rcc_criteria_comment = $this->_getValueByTagNamePhp4($rcc, "criteria_comment");
            
            // no translation needed here...
            
            // put data into the format that CakePHP likes...
            $rcc_data['RubricsCriteriaComment']['rubric_id'] = $rcc_rubric_id;
            $rcc_data['RubricsCriteriaComment']['criteria_num'] = $rcc_criteria_num;
            $rcc_data['RubricsCriteriaComment']['lom_num'] = $rcc_lom_num;
            $rcc_data['RubricsCriteriaComment']['criteria_comment'] = $rcc_criteria_comment;
            
            // save!
            $this->RubricsCriteriaComment->directSave($rcc_data);
            $this->RubricsCriteriaComment->id = null;
            $rcc_data = null;
          }
          $rubrics_criteria_comments = null;
          
          // get everything from the rubrics_criteria table
          // put all the rows into the new rubrics_criterias table
          $rubrics_criteria = $dom->get_elements_by_tagname("rubrics_criteria");
          foreach($rubrics_criteria as $criterium)
          {
            // get fields associated with this table
            $rc_rubric_id = $this->_getValueByTagNamePhp4($criterium, "rubric_id");
            $rc_criteria_number = $this->_getValueByTagNamePhp4($criterium, "criteria_number");
            $rc_criteria = $this->_getValueByTagNamePhp4($criterium, "criteria");
            $rc_multiplier = $this->_getValueByTagNamePhp4($criterium, "multiplier");
            
            // no translation needed here
            
            // put data into the format that CakePHP likes...
            $criteria_data['RubricsCriteria']['rubric_id'] = $rc_rubric_id;
            $criteria_data['RubricsCriteria']['criteria_num'] = $rc_criteria_number;
            $criteria_data['RubricsCriteria']['criteria'] = $rc_criteria;
            $criteria_data['RubricsCriteria']['multiplier'] = $rc_multiplier;

            // save
            $this->RubricsCriteria->directSave($criteria_data);
            $this->RubricsCriteria->id = null;
            $criteria_data = null;
          }
          $rubrics_criteria = null;
          break;
        case "simple_evals":
          // get everything form the simple_evaluations table...this really should be in a function call...
          $simple_evals = $dom->get_elements_by_tagname('simple_evaluations');
          foreach($simple_evals as $simple)
          {
          	$se_id = $this->_getValueByTagNamePhp4($simple, 'se_id');
            $se_name = $this->_getValueByTagNamePhp4($simple, 'name');
            $se_description = $this->_getValueByTagNamePhp4($simple, 'description');
            $se_points_member = $this->_getValueByTagNamePhp4($simple, 'points_member');
            $se_user_id = $this->_getValueByTagNamePhp4($simple, 'user_id');
            
            $se_data['SimpleEvaluation']['id'] = $se_id;
            $se_data['SimpleEvaluation']['name'] = $se_name;
            $se_data['SimpleEvaluation']['description'] = $se_description;
            $se_data['SimpleEvaluation']['point_per_member'] = $se_points_member;
            $se_data['SimpleEvaluation']['creator_id'] = $se_user_id;
            
            $this->SimpleEvaluation->directSave($se_data);
            $this->SimpleEvaluation->id = null;
            $se_data = null;
          }
          $simple_evals = null;
          break;
        case "assignments":
          $assignments = $dom->get_elements_by_tagname('assignments');
          foreach($assignments as $assign)
          {
          	$asn_id = $this->_getValueByTagNamePhp4($assign, 'asn_id');
            $asn_title = $this->_getValueByTagNamePhp4($assign, 'title');
            $asn_course_id = $this->_getValueByTagNamePhp4($assign, 'course_id');
            $asn_description = $this->_getValueByTagNamePhp4($assign, 'description');
            $asn_due = $this->_getValueByTagNamePhp4($assign, 'due_date');
            $asn_release_begin = $this->_getValueByTagNamePhp4($assign, 'release_date_begin');
            $asn_release_end = $this->_getValueByTagNamePhp4($assign, 'release_date_end');
            $asn_comment_req = $this->_getValueByTagNamePhp4($assign, 'com_req');
            $asn_selfeval_allowed = $this->_getValueByTagNamePhp4($assign, 'self_eval');
            $asn_eval_type = $this->_getValueByTagNamePhp4($assign, 'eval_type');
            
            if($asn_eval_type == 2) // simple evaluation
            {
              $event_template_type_id = 1;
            }
            else if($asn_eval_type == 1) // rubric evaluation
            {
            	$event_template_type_id = 2;
            }
            
            $event_data['Event']['id'] = $asn_id;
            $event_data['Event']['course_id'] = $asn_course_id;
            $event_data['Event']['title'] = $asn_title;
            $event_data['Event']['description'] = $asn_description;
            $event_data['Event']['due_date'] = $asn_due;
            $event_data['Event']['release_date_begin'] = $asn_release_begin;
            $event_data['Event']['release_date_end'] = $asn_release_end;
            $event_data['Event']['creator_id'] = 1; // root user
            $event_data['Event']['com_req'] = $asn_comment_req;
            $event_data['Event']['self_eval'] = $asn_selfeval_allowed;
            $event_data['Event']['event_template_type_id'] = $event_template_type_id;
            
            $this->Event->directSave($event_data);
            $this->Event->id = null;
          }
          break;
        case "enrols":
          $enrols = $dom->get_elements_by_tagname('enrols');
          
          foreach($enrols as $enrol)
          {
          	$user_id = $this->_getValueByTagNamePhp4($enrol, 'user_id');
            $course_id = $this->_getValueByTagNamePhp4($enrol, 'course_id');
            
            $enrol_data['UserEnrol']['user_id'] = $user_id;
            $enrol_data['UserEnrol']['course_id'] = $course_id;
            $enrol_data['UserEnrol']['creator_id'] = 1; // root
            
            $this->UserEnrol->save($enrol_data);
            $this->UserEnrol->id = null;
          }
          
          break;
        case "surveys":
          $surveys = $dom->get_elements_by_tagname('surveys');
          
          foreach($surveys as $survey)
          {
            $svy_id = $this->_getValueByTagNamePhp4($survey, 'survey_id');
            $svy_user_id = $this->_getValueByTagNamePhp4($survey, 'user_id');
            $svy_course_id = $this->_getValueByTagNamePhp4($survey, 'course_id');
            $svy_title = $this->_getValueByTagNamePhp4($survey, 'title');
            $svy_due_date = $this->_getValueByTagNamePhp4($survey, 'due_date');
            $svy_release_date_begin = $this->_getValueByTagNamePhp4($survey, 'release_date_begin');
            $svy_release_date_end = $this->_getValueByTagNamePhp4($survey, 'release_date_end');
            $svy_released = $this->_getValueByTagNamePhp4($survey, 'released');     
            
            $svy_data['Survey']['id'] = $svy_id;
            $svy_data['Survey']['user_id'] = $svy_user_id;
            $svy_data['Survey']['course_id'] = $svy_course_id;
            $svy_data['Survey']['name'] = $svy_title;
            $svy_data['Survey']['due_date'] = $svy_due_date;
            $svy_data['Survey']['release_date_begin'] = $svy_release_date_begin;
            $svy_data['Survey']['release_date_end'] = $svy_release_date_end;
            $svy_data['Survey']['released'] = $svy_released;
            $svy_data['Survey']['creator_id'] = $svy_user_id;
            
            $this->Survey->directSave($svy_data);
            $this->Survey->id = null;
          }
          
          $questions = $dom->get_elements_by_tagname('questions');
          foreach($questions as $question)
          {
            
            $question_m = $this->_getValueByTagNamePhp4($question, 'multiple_choice');
            $question_c = $this->_getValueByTagNamePhp4($question, 'choose_any_of');
            $question_s = $this->_getValueByTagNamePhp4($question, 'short_input');
            $question_l = $this->_getValueByTagNamePhp4($question, 'long_input');
            
            $question_data['Question']['id'] = $this->_getValueByTagNamePhp4($question, 'question_id');
            $question_data['Question']['prompt'] = $this->_getValueByTagNamePhp4($question, 'prompt');
            $question_data['Question']['master'] = 'yes';
            
            if($question_m) 
            {
              $question_data['Question']['type'] = 'M'; 
            }
            else if ($question_c)
            {
              $question_data['Question']['type'] = 'C';
            }
            else if ($question_s)
            {
              $question_data['Question']['type'] = 'S';
            }
            else if($question_l)
            {
              $question_data['Question']['type'] = 'L';
            }
            
            $this->Question->directSave($question_data);
            $this->Question->id = null;
          }
          
          $svy_questions = $dom->get_elements_by_tagname('survey_questions');
          foreach($svy_questions as $svy_q)
          {
            
            $svy_q_data['SurveyQuestion']['survey_id'] = $this->_getValueByTagNamePhp4($svy_q, 'survey_id');
            $svy_q_data['SurveyQuestion']['number'] = $this->_getValueByTagNamePhp4($svy_q, 'number');
            $svy_q_data['SurveyQuestion']['question_id'] = $this->_getValueByTagNamePhp4($svy_q, 'question_id');
            $this->SurveyQuestion->save($svy_q_data);
            $this->SurveyQuestion->id = null;
          }
          
        break;
      }
    }
  }
  
  function _importDataPhp5($xml_file, $to_import)
  {
  	$this->User = new User;
    $this->Course = new Course;
    $this->Rubric = new Rubric;
    $this->RubricsLom = new RubricsLom;
    $this->RubricsCriteria = new RubricsCriteria;
    $this->RubricsCriteriaComment = new RubricsCriteriaComment;
    $this->SimpleEvaluation = new SimpleEvaluation;
    $this->Event = new Event;
    $this->UserCourse = new UserCourse;
    $this->UserEnrol = new UserEnrol;
    $this->Survey = new Survey;
    $this->SurveyQuestion = new SurveyQuestion;
    $this->Question = new Question;
        
    $dom = new DomDocument();
    $dom->load($xml_file);
    
    foreach($to_import as $import)
    {
      switch($import)
      {
        case "administrators":
        case "students":
        case "instructors":
          $users = $dom->getElementsByTagName("users");
          foreach($users as $user)
          {
            if($user->hasChildNodes()) 
            {
              // find fields
              $user_id = $this->_getValueByTagNamePhp5($user, "user_id");
              $username = $this->_getValueByTagNamePhp5($user, "username");
              $password = $this->_getValueByTagNamePhp5($user, "password");
              $first_name = $this->_getValueByTagNamePhp5($user, "first_name");
              $last_name = $this->_getValueByTagNamePhp5($user, "last_name");
              $student_number = $this->_getValueByTagNamePhp5($user, "student_number");
              $user_type = $this->_getValueByTagNamePhp5($user, "user_type");
              $last_login = $this->_getValueByTagNamePhp5($user, "last_login");
              $email = $this->_getValueByTagNamePhp5($user, "email");
              $title = $this->_getValueByTagNamePhp5($user, "title");
             
              switch($user_type)
              {
                case "instructor":
                  $role = "I";
                  break;
                case "student":
                  $role = "S";
                  break;
                case "administrator":
                  $role = "A";
                  break;
              }
            
              $to_save['User']['id'] = $user_id;
              $to_save['User']['username'] = $username;
              $to_save['User']['password'] = $password;
              $to_save['User']['first_name'] = $first_name;
              $to_save['User']['last_name'] = $last_name;
              $to_save['User']['student_no'] = $student_number; //student_number
              $to_save['User']['last_login'] = $last_login;
              $to_save['User']['email'] = $email;
              $to_save['User']['title'] = $title;
              $to_save['User']['role'] = $role; //user_type
              $to_save['User']['record_status'] = 'A';
              $to_save['User']['creator_id'] = 1;
              
              // save to database
              // special case for "root" user
              if($username == 'root') {
                $to_save['User']['id'] = 1;
                $this->User->save();
              }
              else {
                $this->User->directSave($to_save);
              }

              $this->User->id = null;
              $to_save = null;
            }
          }
          break;
        case "courses":
          $courses = $dom->getElementsByTagName("courses");
          foreach($courses as $course)
          {
            if($course->hasChildNodes())
            {
              // find fields
             
              $course_id = $this->_getValueByTagNamePhp5($course, "course_id");
              $course_no = $this->_getValueByTagNamePhp5($course, "course");
              $title = $this->_getValueByTagNamePhp5($course, "title");
              $homepage = $this->_getValueByTagNamePhp5($course, "homepage");
              $status = $this->_getValueByTagNamePhp5($course, "status");
              $owner = $this->_getValueByTagNamePhp5($course, "user_id"); // UserCourses...
              
              $user_course['UserCourse']['user_id'] = $owner_id;
              $user_course['UserCourse']['course_id'] = $course_id;
              
              if($status == 'active')
              {
                $record_status = 'A';
              }
              else 
              {
                $record_status = 'I';
              }
              
              $course_data['Course']['id'] = $course_id;
              $course_data['Course']['course'] = $course_no;
              $course_data['Course']['title'] = $title;
              $course_data['Course']['homepage'] = $homepage;
              $course_data['Course']['record_status'] = $record_status;
              $course_date['Course']['creator_id'] = $owner;
              
              $this->Course->directSave($course_data);
              $this->Course->id = null;
              $course_data = null;
              
              $this->UserCourse->save($user_course);
              $user_course = null;
              
            }
          }
          $courses = null;
          break;
        case "rubrics":
          // get everything from the rubrics table
          // put all rows into the new rubrics table
          $rubrics = $dom->getElementsByTagName("rubrics");
          foreach($rubrics as $rubric)
          {
            if($rubric->hasChildNodes())
            {
              // get fields associated with this table
              $rubric_id = $this->_getValueByTagNamePhp5($rubric, "rubric_id");
              $rubric_name = $this->_getValueByTagNamePhp5($rubric, "name");
              $rubric_creator = $this->_getValueByTagNamePhp5($rubric, "user_id");
              $rubric_total_mark = $this->_getValueByTagNamePhp5($rubric, "total_mark");
              $rubric_zero = $this->_getValueByTagNamePhp5($rubric, "zero_mark");
              $rubric_lom_max = $this->_getValueByTagNamePhp5($rubric, "lom_max");
              $rubric_criteria = $this->_getValueByTagNamePhp5($rubric, "criteria");
              $rubric_availability = $this->_getValueByTagNamePhp5($rubric, "availability");
              $rubric_template = $this->_getValueByTagNamePhp5($rubric, "template");
              
              
              // do some translation here...
              if($rubric_zero) {
                $rubric_zero_mark = 'ON';
              }
              else {
                $rubric_zero_mark = 'OFF';
              }
              
              // put data into the format that CakePHP likes...
              $rubric_data['Rubric']['id'] = $rubric_id;
              $rubric_data['Rubric']['name'] = $rubric_name;
              $rubric_data['Rubric']['total_marks'] = $rubric_total_mark;
              $rubric_data['Rubric']['zero_mark'] = $rubric_zero_mark;
              $rubric_data['Rubric']['lom_max'] = $rubric_lom_max;
              $rubric_data['Rubric']['criteria'] = $rubric_criteria;
              $rubric_data['Rubric']['availability'] = $rubric_availability;
              $rubric_data['Rubric']['template'] = $rubric_template;
              $rubric_data['Rubric']['creator_id'] = $rubric_creator;
              
              // save!
              $this->Rubric->directSave($rubric_data);
              $this->Rubric->id =  null;
              $rubric_data = null;
            }
          }
          $rubrics = null;
          
          // get everything from the rubrics_lom table
          // put all the rows into the new rubrics_loms table
          $rubrics_lom = $dom->getElementsByTagName("rubrics_lom");
          foreach($rubrics_lom as $lom)
          {
            // get fields associated with this table
            $lom_rubric_id = $this->_getValueByTagNamePhp5($lom, "rubric_id");
            $lom_number = $this->_getValueByTagNamePhp5($lom, "lom_number");
            $lom_comment = $this->_getValueByTagNamePhp5($lom, "lom_comment");
            
            // no translation needed here...
            
            // put data into the format that CakePHP likes...
            $rubrics_lom_data['RubricsLom']['rubric_id'] = $lom_rubric_id;
            $rubrics_lom_data['RubricsLom']['lom_num'] = $lom_number;
            $rubrics_lom_data['RubricsLom']['lom_comment'] = $lom_comment;
            
            // save!
            $this->RubricsLom->directSave($rubrics_lom_data);
            $this->RubricsLom->id = null;
            $rubrics_lom_data = null;
          }
          $rubrics_lom = null;
          
          // get everything from the rubrics_criteria_comments table
          // put all the rows into the new rubrics_criteria_comments table
          $rubrics_criteria_comments = $dom->getElementsByTagName("rubrics_criteria_comments");
          foreach($rubrics_criteria_comments as $rcc)
          {
            // get fields associated with this table
            $rcc_rubric_id = $this->_getValueByTagNamePhp5($rcc, "rubric_id");
            $rcc_criteria_num = $this->_getValueByTagNamePhp5($rcc, "criteria_number");
            $rcc_lom_num = $this->_getValueByTagNamePhp5($rcc, "lom_number");
            $rcc_criteria_comment = $this->_getValueByTagNamePhp5($rcc, "criteria_comment");
            
            // no translation needed here...
            
            // put data into the format that CakePHP likes...
            $rcc_data['RubricsCriteriaComment']['rubric_id'] = $rcc_rubric_id;
            $rcc_data['RubricsCriteriaComment']['criteria_num'] = $rcc_criteria_num;
            $rcc_data['RubricsCriteriaComment']['lom_num'] = $rcc_lom_num;
            $rcc_data['RubricsCriteriaComment']['criteria_comment'] = $rcc_criteria_comment;
            
            // save!
            $this->RubricsCriteriaComment->directSave($rcc_data);
            $this->RubricsCriteriaComment->id = null;
            $rcc_data = null;
          }
          $rubrics_criteria_comments = null;
          
          // get everything from the rubrics_criteria table
          // put all the rows into the new rubrics_criterias table
          $rubrics_criteria = $dom->getElementsByTagName("rubrics_criteria");
          foreach($rubrics_criteria as $criterium)
          {
            // get fields associated with this table
            $rc_rubric_id = $this->_getValueByTagNamePhp5($criterium, "rubric_id");
            $rc_criteria_number = $this->_getValueByTagNamePhp5($criterium, "criteria_number");
            $rc_criteria = $this->_getValueByTagNamePhp5($criterium, "criteria");
            $rc_multiplier = $this->_getValueByTagNamePhp5($criterium, "multiplier");
            
            // no translation needed here
            
            // put data into the format that CakePHP likes...
            $criteria_data['RubricsCriteria']['rubric_id'] = $rc_rubric_id;
            $criteria_data['RubricsCriteria']['criteria_num'] = $rc_criteria_number;
            $criteria_data['RubricsCriteria']['criteria'] = $rc_criteria;
            $criteria_data['RubricsCriteria']['multiplier'] = $rc_multiplier;

            // save
            $this->RubricsCriteria->directSave($criteria_data);
            $this->RubricsCriteria->id = null;
            $criteria_data = null;
          }
          $rubrics_criteria = null;
          break;
        case "simple_evals":
          // get everything form the simple_evaluations table...this really should be in a function call...
          $simple_evals = $dom->getElementsByTagName('simple_evaluations');
          foreach($simple_evals as $simple)
          {
            $se_id = $this->_getValueByTagNamePhp5($simple, 'se_id');
            $se_name = $this->_getValueByTagNamePhp5($simple, 'name');
            $se_description = $this->_getValueByTagNamePhp5($simple, 'description');
            $se_points_member = $this->_getValueByTagNamePhp5($simple, 'points_member');
            $se_user_id = $this->_getValueByTagNamePhp5($simple, 'user_id');
            
            $se_data['SimpleEvaluation']['id'] = $se_id;
            $se_data['SimpleEvaluation']['name'] = $se_name;
            $se_data['SimpleEvaluation']['description'] = $se_description;
            $se_data['SimpleEvaluation']['point_per_member'] = $se_points_member;
            $se_data['SimpleEvaluation']['user_id'] = $se_user_id;
            
            $this->SimpleEvaluation->directSave($se_data);
            $this->SimpleEvaluation->id = null;
            $se_data = null;
          }
          $simple_evals = null;
          break;
        case "assignments":
          $assignments = $dom->getElementsByTagName('assignments');
          foreach($assignments as $assign)
          {
            $asn_id = $this->_getValueByTagNamePhp5($assign, 'asn_id');
            $asn_title = $this->_getValueByTagNamePhp5($assign, 'title');
            $asn_course_id = $this->_getValueByTagNamePhp5($assign, 'course_id');
            $asn_description = $this->_getValueByTagNamePhp5($assign, 'description');
            $asn_due = $this->_getValueByTagNamePhp5($assign, 'due_date');
            $asn_release_begin = $this->_getValueByTagNamePhp5($assign, 'release_date_begin');
            $asn_release_end = $this->_getValueByTagNamePhp5($assign, 'release_date_end');
            $asn_comment_req = $this->_getValueByTagNamePhp5($assign, 'com_req');
            $asn_selfeval_allowed = $this->_getValueByTagNamePhp5($assign, 'self_eval');
            $asn_eval_type = $this->_getValueByTagNamePhp5($assign, 'eval_type');
            
            if($asn_eval_type == 2) // simple evaluation
            {
              $event_template_type_id = 1;
            }
            else if($asn_eval_type == 1) // rubric evaluation
            {
              $event_template_type_id = 2;
            }
            
            $event_data['Event']['id'] = $asn_id;
            $event_data['Event']['course_id'] = $asn_course_id;
            $event_data['Event']['title'] = $asn_title;
            $event_data['Event']['description'] = $asn_description;
            $event_data['Event']['due_date'] = $asn_due;
            $event_data['Event']['release_date_begin'] = $asn_release_begin;
            $event_data['Event']['release_date_end'] = $asn_release_end;
            $event_data['Event']['creator_id'] = 1; // root user
            $event_data['Event']['com_req'] = $asn_comment_req;
            $event_data['Event']['self_eval'] = $asn_selfeval_allowed;
            $event_data['Event']['event_template_type_id'] = $event_template_type_id;
            
            $this->Event->directSave($event_data);
            $this->Event->id = null;
          }
          break;
        case "enrols":
          $enrols = $dom->getElementsByTagName('enrols');
          
          foreach($enrols as $enrol)
          {
            $user_id = $this->_getValueByTagNamePhp5($enrol, 'user_id');
            $course_id = $this->_getValueByTagNamePhp5($enrol, 'course_id');
            
            $enrol_data['UserEnrol']['user_id'] = $user_id;
            $enrol_data['UserEnrol']['course_id'] = $course_id;
            
            $this->UserEnrol->save($enrol_data);
            $this->UserEnrol->id = null;
          }
          
          break;
        case "surveys":
          $surveys = $dom->getElementsByTagName('surveys');
          
          foreach($surveys as $survey)
          {
          	$svy_id = $this->_getValueByTagNamePhp5($survey, 'survey_id');
            $svy_user_id = $this->_getValueByTagNamePhp5($survey, 'user_id');
            $svy_course_id = $this->_getValueByTagNamePhp5($survey, 'course_id');
            $svy_title = $this->_getValueByTagNamePhp5($survey, 'title');
            $svy_due_date = $this->_getValueByTagNamePhp5($survey, 'due_date');
            $svy_release_date_begin = $this->_getValueByTagNamePhp5($survey, 'release_date_begin');
            $svy_release_date_end = $this->_getValueByTagNamePhp5($survey, 'release_date_end');
            $svy_released = $this->_getValueByTagNamePhp5($survey, 'released');     
            
            $svy_data['Survey']['id'] = $svy_id;
            $svy_data['Survey']['user_id'] = $svy_user_id;
            $svy_data['Survey']['course_id'] = $svy_course_id;
            $svy_data['Survey']['name'] = $svy_title;
            $svy_data['Survey']['due_date'] = $svy_due_date;
            $svy_data['Survey']['release_date_begin'] = $svy_release_date_begin;
            $svy_data['Survey']['release_date_end'] = $svy_release_date_end;
            $svy_data['Survey']['released'] = $svy_released;
            $svy_data['Survey']['creator_id'] - $svy_user_id;
            
            $this->Survey->directSave($svy_data);
            $this->Survey->id = null;
          }
          
          $questions = $dom->getElementsByTagName('questions');
          foreach($questions as $question)
          {
          	
            $question_m = $this->_getValueByTagNamePhp5($question, 'multiple_choice');
            $question_c = $this->_getValueByTagNamePhp5($question, 'choose_any_of');
            $question_s = $this->_getValueByTagNamePhp5($question, 'short_input');
            $question_l = $this->_getValueByTagNamePhp5($question, 'long_input');
            
            $question_data['Question']['id'] = $this->_getValueByTagNamePhp5($question, 'question_id');
            $question_data['Question']['prompt'] = $this->_getValueByTagNamePhp5($question, 'prompt');
            $question_data['Question']['master'] = 'yes';
            
            if($question_m) 
            {
              $question_data['Question']['type'] = 'M'; 
            }
            else if ($question_c)
            {
              $question_data['Question']['type'] = 'C';
            }
            else if ($question_s)
            {
              $question_data['Question']['type'] = 'S';
            }
            else if($question_l)
            {
              $question_data['Question']['type'] = 'L';
            }
            
            $this->Question->directSave($question_data);
            $this->Question->id = null;
          }
          
          $svy_questions = $dom->getElementsByTagName('survey_questions');
          foreach($svy_questions as $svy_q)
          {
          	
            $svy_q_data['SurveyQuestion']['survey_id'] = $this->_getValueByTagNamePhp5($svy_q, 'survey_id');
            $svy_q_data['SurveyQuestion']['number'] = $this->_getValueByTagNamePhp5($svy_q, 'number');
            $svy_q_data['SurveyQuestion']['question_id'] = $this->_getValueByTagNamePhp5($svy_q, 'question_id');
            $this->SurveyQuestion->save($svy_q_data);
            $this->SurveyQuestion->id = null;
          }
          
        break;
      }
    }
    
  }
  /**
   * _getValueByTagNamePhp5($root, $name)
   * retrieves a single node's value by tag name. If there are multiple elements with the associated tag name, then
   * only the first element's value is returned.
   */
  function _getValueByTagNamePhp5($root, $name)
  {
  	if(phpversion() >= 5)
    {
      $elem = $root->getElementsByTagName($name);
      //pr($element);
      if(isset($elem) && !empty($elem))
      {
      	$retval = $elem->item(0);
        if(is_object($retval))
        {
          return $retval->nodeValue;
        }
      }
    }
  }
  
  /**
   * _getValueByTagNamePhp4($root, $name)
   * retrieves a single node's value by tag name. If there are multiple elements with the associated tag name, then
   * only the first element's value is returned.
   */
  function _getValueByTagNamePhp4($root, $name)
  {
  	$element = $root->get_elements_by_tagname($name);
    if($element)
    {
      $retval = $element[0]->get_content();
      return $retval;
    }
  }
}

<?php
App::import('Model', 'rubric');
App::import('Controller', 'Rubrics');
class RubricTestCase extends CakeTestCase {

	function TestCopy(){
		
		$this->flushDatabase();
		$this->Course= & ClassRegistry::init('Rubric');
		$empty=null;
		
		//Test function for valid rubric
		##Set up test data
		$this->createRubricHelper(1, 'FirstRubric');
		##Run test
		$copy =  $this->Rubric->copy(1);
		$this->assertNotNull($copy);
		$this->assertEqual($copy['Rubric']['name'], "Copy of FirstRubric");
		
		//Test function for an invalid rubric
		$invalidRubricCopy = $this->Rubric->copy(9999);
		$this->assertFalse($invalidRubricCopy);
		$this->flushDatabase();
		
	}

	
	function TestAfterFind(){
	
		$this->Rubric= & ClassRegistry::init('Rubric');
		$empty=null;
		$this->flushDatabase();
		
		/**
		 * Set up a 2-by-2 rubric with 4 comments as test data
		 * The rubric has the fallowing structure:
		 * 				
		 * 				Lom 1	Lom 2
		 * Criteria1	(1,1)   (2,1)
		 * Criteria2	(1,2)	(2,2)
		 */
		$this->setUpTestAfterFindData();

		//swap columns "Lom 1" with "Lom 2"
		$this->Rubric->query( "UPDATE `IpeerTest`.`rubrics_loms` SET `lom_num` = '2' WHERE `rubrics_loms`.`id` =1");
		$this->Rubric->query( "UPDATE `IpeerTest`.`rubrics_loms` SET `lom_comment` = 'Lom2' WHERE `rubrics_loms`.`id` =1;");
		$this->Rubric->query("UPDATE `IpeerTest`.`rubrics_loms` SET `lom_num` = '1',`lom_comment` = 'Lom1' WHERE `rubrics_loms`.`id` =2");
		
		//Calling findTester() implicitly calls afterFind(); ie afterFind() has been called.
		$rubric = $this->Rubric->findTester();
		
		/**
		 * Run some tests; after calling afterFind(), the swapped column's comments should
		 * also be swapped; ie the resulting Rubric structure shoud look like this:
		 * 
		 * 				Lom 2	Lom 1
		 * Criteria1	(2,1)   (1,1)
		 * Criteria2	(2,2)	(1,2)
		 */
		$firstRowRubricsCriteria = $rubric[0]['RubricsCriteria'][0]['RubricsCriteriaComment'];
		
		//At position (1,1), the result comment should be swapped with value "(2,1)"
		$comment = $firstRowRubricsCriteria[0]['criteria_comment'];
		$this->assertEqual($comment, "(2,1)");
		//At position (2,1), the result comment should be swapped with value "(1,1)" after perform afterfind();
		$comment = $firstRowRubricsCriteria[1]['criteria_comment'];
		$this->assertEqual($comment, "(1,1)");
		
		
		$secondRowRubricCriteria = $rubric[0]['RubricsCriteria'][1]['RubricsCriteriaComment'];
		//At position (1,2), the resulting comment should be swapped with value (2,2)
		$comment = $secondRowRubricCriteria[0]['criteria_comment'];
		$this->assertEqual($comment, "(2,2)");
		//At position (2,2), the resulting comment should be swapped with value (1,2)
		$comment = $secondRowRubricCriteria[1]['criteria_comment'];
		$this->assertEqual($comment, "(1,2)");		
	}
	
	function TestSaveAllWithCriteriaComment(){
		
		$this->Rubric= & ClassRegistry::init('Rubric');
		$empty=null;
		$this->flushDatabase();
		
		$data = $this->createRubricsTestData();
		$this->Rubric->printHelp($data);	
		//$boolean = $this->Rubric->saveAllWithCriteriaComment($data);
	}
	
#####################################################################################################################################################	
###############################################     HELPER FUNCTIONS     ############################################################################
#####################################################################################################################################################
	
	function setUpTestAfterFindData(){
		
		$this->Rubric= & ClassRegistry::init('Rubric');
		$this->flushDatabase();
		
		$rubricSql = "INSERT INTO `IpeerTest`.`rubrics` () VALUES ( '1', 'Test', '0', NULL , NULL , 'public', 'horizontal', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$criteria_1_Sql = "INSERT INTO `IpeerTest`.`rubrics_criterias` () VALUES ('1', '1', '1', 'Criteria1' , '0')";
		$criteria_2_Sql = "INSERT INTO `IpeerTest`.`rubrics_criterias` () VALUES ('2', '1', '2', 'Criteria2' , '0')";
		$lom_1_Sql = "INSERT INTO `IpeerTest`.`rubrics_loms` () VALUES ('1', '1', '1','Lom1')";
		$lom_2_Sql = "INSERT INTO `IpeerTest`.`rubrics_loms` () VALUES ('2', '1', '2', 'Lom2')";
		$comment1 = "INSERT INTO `IpeerTest`.`rubrics_criteria_comments` (`id`, `criteria_id`, `rubrics_loms_id`, `criteria_comment`) VALUES ('1', '1', '1', '(1,1)')";
		$comment2 = "INSERT INTO `IpeerTest`.`rubrics_criteria_comments` (`id`, `criteria_id`, `rubrics_loms_id`, `criteria_comment`) VALUES ('2', '1', '2', '(2,1)')";
		$comment3 = "INSERT INTO `IpeerTest`.`rubrics_criteria_comments` (`id`, `criteria_id`, `rubrics_loms_id`, `criteria_comment`) VALUES ('3', '2', '1', '(1,2)')";
		$comment4 = "INSERT INTO `IpeerTest`.`rubrics_criteria_comments` (`id`, `criteria_id`, `rubrics_loms_id`, `criteria_comment`) VALUES ('4', '2', '2', '(2,2)')";
		
		$this->Rubric->query($rubricSql);
		$this->Rubric->query($criteria_1_Sql);
		$this->Rubric->query($criteria_2_Sql);
		$this->Rubric->query($lom_1_Sql);
		$this->Rubric->query($lom_2_Sql);
		$this->Rubric->query($comment1);
		$this->Rubric->query($comment2);
		$this->Rubric->query($comment3);
		$this->Rubric->query($comment4);
	}

	function createRubricsTestData(){
		
	$data = array( 
		'Rubric' => array
        (
            'id' => '',
            'template' => 'horizontal',
            'name' => 'Test3',
            'lom_max' => 2,
            'criteria' => 1,
            'availability' => 'public',
            'zero_mark' => 0,
            'criteria_mark_0_0' => 0.5,
            'criteria_mark_0_1' => 1
        ),

    'RubricsLom' => array
        (
            '0' => array
                (
                    'lom_comment' => 'LOM 1',
                    'id' => '',
                    'lom_num' => 1
                ),

            '1' => array
                (
                    'lom_comment' => 'LOM 2',
                    'id' =>'' ,
                    'lom_num' => 2
                )

        ),

    'RubricsCriteria' => array
        (
            '0' => array
                (
                    'criteria' => 'Criteria 1',
                    'id' => '',
                    'criteria_num' => 1,
                    'RubricsCriteriaComment' => array
                        (
                            '0' => array
                                (
                                    'criteria_comment' => 'Sites 1',
                                    'id' => '' 
                                ),

                            '1' => array
                                (
                                    'criteria_comment' => 'Sites 2',
                                    'id' => ''
                                )
                        ),
                    'multiplier' => 1
                )

        ));
		
       	return $data;
	}
	
	function flushDatabase(){
		$this->Rubric= & ClassRegistry::init('Rubric');
		
		$this->Rubric->deleteAllTuples('rubrics');
		$this->Rubric->deleteAllTuples('rubrics_criterias');
		$this->Rubric->deleteAllTuples('rubrics_loms');
		$this->Rubric->deleteAllTuples('rubrics_criteria_comments');
	}
	
	function createRubrics_criteriasHelper($rubric_id='', $criteria_num='', $comment=''){
		$this->Rubric= & ClassRegistry::init('Rubric');
		
		$sql = "INSERT INTO rubrics_criterias VALUES (NULL, '$rubric_id', '$criteria_num', '$comment' , '0')";
		$this->Rubric->query($sql);
	}
	
	function createRubrics_lomsHelper($rubric_id='', $lomNo='', $comment){
		$this->Rubric= & ClassRegistry::init('Rubric');
		
		$sql = "INSERT INTO rubrics_loms VALUES (NULL, '$rubric_id', '$lomNo', '$comment')";
		$this->Rubric->query($sql);
	} 
	
	function createRubric_criteria_commentHelper($rubric_id='', $criteria_num='', $lom_num='', $comment = ''){
		
		$this->Rubric= & ClassRegistry::init('Rubric');
		
		$sql = "INSERT INTO rubrics_criteria_comments VALUES (NULL, '$rubric_id', '$criteria_num', '$lom_num', '$comment')";
		$this->Rubric->query($sql);
	}
	
	function createRubricHelper($id='', $title=''){
		
		$this->Rubric= & ClassRegistry::init('Rubric');
		
		$sql = "INSERT INTO rubrics VALUES ('$id', '$title', '0', NULL , NULL , 'public', 'horizontal', '0', '0000-00-00 00:00:00', NULL , NULL)";
		$this->Rubric->query($sql);				
	}
}
?>
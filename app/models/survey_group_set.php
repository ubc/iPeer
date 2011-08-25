<?php
/* SVN FILE: $Id$ */

/**
 * Enter description here ....
 *
 * @filesource
 * @copyright    Copyright (c) 2006, .
 * @link
 * @package
 * @subpackage
 * @since
 * @version      $Revision$
 * @modifiedby   $LastChangedBy$
 * @lastmodified $Date: 2006/06/20 18:44:19 $
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Survey
 *
 * Enter description here...
 *
 * @package
 * @subpackage
 * @since
 */

class SurveyGroupSet extends AppModel
{
    var $name = 'SurveyGroupSet';
    var $belongsTo = array('Survey' => 
                           array('className'    => 'Survey',
                                 'condition'    => '',
                                 'order'        => '',
                                 'foreignKey'   => 'survey_id'
                                )
                          );
    var $hasMany = array('SurveyGroup' =>
                         array('className'   => 'SurveyGroup',
                               'conditions'  => '',
                               'order'       => '',
                               'foreignKey'  => 'group_set_id',
                               'dependent'   => true,
                               'exclusive'   => true,
                               'finderSql'   => ''
                              ));

    var $actsAs = array('ExtendAssociations', 'Containable');

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->virtualFields['group_count'] = sprintf('SELECT count(*) as count FROM survey_groups as sg WHERE sg.group_set_id = %s.id', $this->alias);
  }

  /*function date($surveyId=null,$setDescription=null) {
    $tmp = $this->find('first', array('conditions' => array('survey_id' => $surveyId,
                                                            'set_description' => $setDescription)));
    return $tmp['SurveyGroupSet']['id'];
  }*/

  /*function getSurveyIdById($id) {
    $tmp = $this->read(null, $id);
    return $tmp['SurveyGroupSet']['survey_id'];
  }*/

  /**
   * Saves data to surveyGroupSet model and all of its associates.
   * @param tpye_ARRAY $data : array consisting of survey_group_set,
   * 						   survey_groups, survey_group_members tables entries
   * @param type_BOOLEAN $validate : 
   * @param type_ARRAY $fieldList :
   */
  function save($data = null, $validate = true, $fieldList = array()) {
    if(empty($data)) return false;
    // begin transaction
    $dataSource = $this->getDataSource();
    $dataSource->begin($this);
    if($result = parent::save($data, $validate, $fieldList)){
      if(isset($data['SurveyGroup'])){
        $SurveyGroup = ClassRegistry::init('SurveyGroup');
        foreach($data['SurveyGroup'] as $survey_group){
          $survey_group['SurveyGroup']['group_set_id'] = $this->id;
          if(isset($survey_group['SurveyGroupMember'])){
            foreach($survey_group['SurveyGroupMember'] as $key => $m){
              $survey_group['SurveyGroupMember'][$key]['group_set_id'] = $this->id;
            }
          }
          $result = $SurveyGroup->saveAll($survey_group, array('validate' => $validate,
                                                               'atomic' => false));
        }
      }
    }
    if($result){
      $dataSource->commit($this);
    } else{
      $dataSource->rollback($this);
    }
    return $result;
  }

  /**
   * Sets SurveyGroupSet.release == 1 and creates a entry in Group based on the 
   * corresponding groupSet.  Note, SurveyGroupSet.release is set to 1 if and only if a
   * correspond group entry is successfully added to the database.
   * 
   * @param type_INT $group_set_id : SurveyGroupSet.id
   */
  function release($group_set_id) {
    $group_set = $this->find('first', array('conditions' => array('SurveyGroupSet.id' => $group_set_id),
                                            'contain' => array('Survey', 'SurveyGroup' => array('Member.id')),
                                           ));
    if($group_set['SurveyGroupSet']['released']) return false;

    $Group = ClassRegistry::init('Group');
    $result = true;
    $groups = array();

    //get last group number if exists
    $max_group_num = $Group->getLastGroupNumByCourseId($group_set['Survey']['course_id']);

    // begin transaction
    $dataSource = $this->getDataSource();
    $dataSource->begin($this);
    foreach ($group_set['SurveyGroup'] as $surveyGroup) {
      $group = array();
      $groupNum = $surveyGroup['group_number'];
      $group['Group']['group_num'] = $groupNum + $max_group_num;
      $group['Group']['group_name'] = $group_set['SurveyGroupSet']['set_description'].' Team #'.$surveyGroup['group_number'];
      $group['Group']['course_id'] = $group_set['Survey']['course_id'];

      //add group members
      foreach ($surveyGroup['Member'] as $surveyGroupMember) {
        $groupMember = array();
        $groupMember['user_id'] = $surveyGroupMember['id'];
        $group['Member'][] = $groupMember;
      }
      $groups[] = $group;
    }

    if($result = $Group->saveAll($groups)) {
      //change status of survey set to released
      $this->id = $group_set['SurveyGroupSet']['id'];
      $result = $this->saveField('released', 1);
    }

    if($result) {
      $dataSource->commit($this);
    } else {
      $dataSource->rollback($this);
    }

    return $result;
  }
  
  function beforeDelete($cascade) {
    $time = $this->field('date', array('id' => $this->id));
    //delete teammaker crums
    unlink(TMP.$time.'.txt');
    unlink(TMP.$time.'.xml');
    unlink(TMP.$time.'.txt.scores');

    return true;
  }
}
?>
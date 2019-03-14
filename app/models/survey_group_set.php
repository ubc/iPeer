<?php
/**
 * SurveyGroupSet
 *
 * @uses AppModel
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SurveyGroupSet extends AppModel
{
    public $name = 'SurveyGroupSet';
    public $belongsTo = array(
        'Survey' => array(
            // deprecated assocation!
            'className'    => 'Survey',
            'condition'    => '',
            'order'        => '',
            'foreignKey'   => 'survey_id'
        ),
        'Event' => array(
            'className'    => 'Event',
            'condition'    => '',
            'order'        => '',
            'foreignKey'   => 'survey_id'
        )
    );
    public $hasMany = array(
        'SurveyGroup' => array(
            'className'   => 'SurveyGroup',
            'conditions'  => '',
            'order'       => '',
            'foreignKey'  => 'group_set_id',
            'dependent'   => true,
            'exclusive'   => true,
            'finderSql'   => ''
        )
    );

    public $actsAs = array('ExtendAssociations', 'Containable');

    /**
     * __construct
     *
     * @param bool $id    id
     * @param bool $table table
     * @param bool $ds    data source
     *
     * @access protected
     * @return void
     */
    function __construct($id = false, $table = null, $ds = null)
    {
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
     *
     * @param tpye_ARRAY   $data      array consisting of survey_group_set,
     * 						          survey_groups, survey_group_members tables entries
     * @param type_BOOLEAN $validate  if validate
     * @param type_ARRAY   $fieldList field list
     *
     * @access public
     * @return array result
     */
    function save($data = null, $validate = true, $fieldList = array())
    {
        if (empty($data)) {
            return false;
        }
        // begin transaction
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        if ($result = parent::save($data, $validate, $fieldList)) {
            if (isset($data['SurveyGroup'])) {
                $SurveyGroup = ClassRegistry::init('SurveyGroup');
                $groupNum = 1;
                foreach ($data['SurveyGroup'] as $survey_group) {
                    $survey_group['SurveyGroup']['group_set_id'] = $this->id;
                    $survey_group['SurveyGroup']['group_number'] = $groupNum;
                    if (isset($survey_group['SurveyGroupMember'])) {
                        foreach ($survey_group['SurveyGroupMember'] as $key => $member) {
                            $survey_group['Member'][$key]['SurveyGroupMember']['group_set_id'] = $this->id;
                            $survey_group['Member'][$key]['SurveyGroupMember']['user_id'] = $member;
                            $survey_group['Member'][$key]['SurveyGroupMember']['group_id'] = $groupNum;
                        }
                        unset($survey_group['SurveyGroupMember']);
                    }

                    if (!$result = $SurveyGroup->saveAll($survey_group, array('validate' => $validate,
                        'atomic' => false))) {
                        break;
                    }
                    $groupNum++;
                }
            }
        }
        if ($result) {
            $dataSource->commit($this);
        } else {
            $dataSource->rollback($this);
        }
        return $result;
    }


    /**
     * Sets SurveyGroupSet.release == 1 and creates a entry in Group based on the
     * corresponding groupSet.
     * Note: SurveyGroupSet.release is set to 1 if and only if a
     * correspond group entry is successfully added to the database.
     *
     * @param type_INT $group_set_id : SurveyGroupSet.id
     *
     * @access public
     * @return bool   released or not
     */
    function release($group_set_id)
    {
        $group_set = $this->find('first', array(
            'conditions' => array('SurveyGroupSet.id' => $group_set_id),
            'contain' => array('SurveyGroup' => array('Member')),
        ));
        if (empty($group_set) || $group_set['SurveyGroupSet']['released']) {
            return false;
        }

        $event = $this->Event->find('first', array(
            'conditions' => array('id' => $group_set['SurveyGroupSet']['survey_id']),
            'contain' => false,
        ));
        $courseId = $event['Event']['course_id'];

        $Group = ClassRegistry::init('Group');
        $result = true;
        $groups = array();

        //get last group number if exists
        $max_group_num = $Group->getLastGroupNumByCourseId($courseId);

        // begin transaction for saving the records
        $dataSource = $this->getDataSource();
        $dataSource->begin($this);
        foreach ($group_set['SurveyGroup'] as $surveyGroup) {
            $group = array();
            $groupNum = $surveyGroup['group_number'];
            $group['Group']['group_num'] = $groupNum + $max_group_num;
            $group['Group']['group_name'] = $group_set['SurveyGroupSet']['set_description'].' Team #'.$surveyGroup['group_number'];
            $group['Group']['course_id'] = $courseId;

            //add group members
            foreach ($surveyGroup['Member'] as $surveyGroupMember) {
                $groupMember = array();
                $groupMember['user_id'] = $surveyGroupMember['id'];
                $group['Member'][] = $groupMember;
            }
            $groups[] = $group;
        }

        if ($result = $Group->saveAll($groups)) {
            //change status of survey set to released
            $this->id = $group_set['SurveyGroupSet']['id'];
            $result = $this->saveField('released', 1);
        }

        if ($result) {
            $dataSource->commit($this);
        } else {
            $dataSource->rollback($this);
        }

        return $result;
    }

    /**
     * beforeDelete
     *
     * @param mixed $cascade cascade
     *
     * @access public
     * @return void
     */
    function beforeDelete($cascade = true)
    {
        $time = $this->field('date', array('id' => $this->id));

        if ($time && file_exists(TMP . $time . '.txt')) {
            //delete teammaker crums
            unlink(TMP.$time.'.txt');
            unlink(TMP.$time.'.xml');
            unlink(TMP.$time.'.txt.scores');
        }

        return true;
    }
}

<?php
/**
 * MixevalsController
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class MixevalsController extends AppController
{
    public $uses =  array('Event', 'Mixeval','MixevalQuestion', 
        'MixevalQuestionDesc', 'Personalize', 'UserCourse', 
        'MixevalQuestionType', 'EvaluationSubmission');
    public $name = 'Mixevals';
    public $helpers = array('Html','Ajax','Javascript','Time');
    public $components = array('AjaxList','Auth','Output', 'userPersonalize', 'framework');


    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->set('title_for_layout', __('Mixed Evaluations', true));
        $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
        parent::__construct();
    }


    /**
     * _postProcess
     *
     * @param mixed $data
     *
     * @access public
     * @return void
     */
    function _postProcess($data)
    {
        // Creates the custom in use column
        if ($data) {
            foreach ($data as $key => $entry) {
                $entry['Mixeval']['event_count'] = $this->Event->find('count', 
                    array('conditions' => array(
                        'event_template_type_id' => $entry['Mixeval']['id'])
                    )
                );

                // is it in use?
                $inUse = (0 != $entry['Mixeval']['event_count']);

                // Put in the custom column
                $data[$key]['!Custom']['inUse'] = $inUse ? __("Yes", true) : __("No", true);
            }
        }
        // Return the processed data back
        return $data;
    }

    /**
     * setUpAjaxList
     *
     * @access public
     * @return void
     */
    function setUpAjaxList()
    {
        // Set up Columns
        $columns = array(
            array("Mixeval.id",            "",              "",  "hidden"),
            array("Mixeval.name",          __("Name", true),         "auto", "action", "View Evaluation"),
            array("!Custom.inUse",         __("In Use", true),       "4em",  "number"),
            array("Mixeval.availability",  __("Availability", true), "6em",  "string"),
            array("Mixeval.total_question",  __("Questions", true),    "4em", "number"),
            array("Mixeval.total_marks",  __("Total Marks", true),    "4em", "number"),
            array("Mixeval.creator_id",           "",               "",    "hidden"),
            array("Mixeval.creator",     __("Creator", true),        "8em", "action", "View Creator"),
            array("Mixeval.created",      __("Creation Date", true),  "10em", "date"));

        // Just list all and my evaluations for selections
        $userList = array(User::get('id') => "My Evaluations");

        // Join with Users
        $jointTableCreator =
            array("id"         => "Creator_id",
                "localKey"   => "creator_id",
                "description" => __("Evaluations to show:", true),
                "default" => User::get('id'),
                "list" => $userList,
                "joinTable"  => "users",
                "joinModel"  => "Creator");
        // put all the joins together
        $joinTables = array($jointTableCreator);

        if (User::hasPermission('functions/superadmin')) {
            $extraFilters = "";
        } else {
            $creators = array();
            // grab course ids of the courses admin/instructor has access to
            $courseIds = array();
            if (User::hasPermission('functions/user/admin')) {
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
            } else {
                $courseIds = Set::extract(
                    $this->UserCourse->findAllByUserId(User::get('id')), 
                    '/UserCourse/course_id'
                );
            }
            // grab all instructors that have access to the courses above
            $instructors = $this->UserCourse->findAllByCourseId($courseIds);

            $extraFilters = "(";
            // only admins will go through this loop
            foreach ($instructors as $instructor) {
                $id = $instructor['UserCourse']['user_id'];
                $creators[] = $id;
                $extraFilters .= "creator_id = $id or ";
            }
            // allow instructors/admins to see their own simple eval templates
            $extraFilters .= "creator_id = ".User::get('id')." or ";
            // can see all public simple evaluation templates
            $extraFilters .= "availability = 'public')";
        }

        // Instructors can only edit their own mixeval templates
        $restrictions = "";
        // instructors
        $basicRestrictions = array(
            User::get('id') => true,
            "!default" => false);
        // super admins
        if (User::hasPermission('functions/superadmin')) {
            $basicRestrictions = "";
        // faculty admins
        } else if (User::hasPermission('controllers/departments')) {
            foreach ($creators as $creator) {
                $basicRestrictions = $basicRestrictions + array($creator => true);
            }
        }

        empty($basicRestrictions) ? $restrictions = $basicRestrictions :
            $restrictions['Mixeval.creator_id'] = $basicRestrictions;

        // Set up actions
        $warning = __("Are you sure you want to delete this evaluation permanently?", true);
        $actions = array(
            array(__("View Evaluation", true), "", "", "", "view", "Mixeval.id"),
            array(__("Edit Evaluation", true), "", $restrictions, "", "edit", "Mixeval.id"),
            array(__("Copy Evaluation", true), "", "", "", "copy", "Mixeval.id"),
            array(__("Delete Evaluation", true), $warning, $restrictions, "", "delete", "Mixeval.id"),
            array(__("View Creator", true), "",    "", "users", "view", "Mixeval.creator_id"));

        // No recursion in results
        $recursive = 0;

        // Set up the list itself
        $this->AjaxList->setUp($this->Mixeval, $columns, $actions,
            "Mixeval.name", "Mixeval.name", $joinTables, $extraFilters, $recursive, "_postProcess");
    }

    /**
     * index
     *
     * @access public
     * @return void
     */
    function index()
    {
        // Set up the basic static ajax list variables
        $this->setUpAjaxList();
        // Set the display list
        $this->set('paramsForList', $this->AjaxList->getParamsForList());
    }

    /**
     * ajaxList
     *
     * @access public
     * @return void
     */
    function ajaxList()
    {
        // Set up the list
        $this->setUpAjaxList();
        // Process the request for data
        $this->AjaxList->asyncGet();
    }


    /**
     * view
     *
     * @param mixed $id id
     *
     * @access public
     * @return void
     */
    function view($id)
    {
        $eval = $this->Mixeval->find('first', array(
            'conditions' => array('id' => $id),
            'contain' => 'Question.Description',
        ));

        // check to see if $id is a valid mixed evaluation
        if (empty($eval)) {
            $this->Session->setFlash(_t('Error: Invalid Mixeval ID.'));
            $this->redirect('index');
            return;
        }

        // Make sure the user has access if the eval is not public,
        // - a user has access if they're superadmin.
        // - if they're an instructor, they have access to evals made by 
        // themselves and by instructors who teach the saame courses.
        // - if they're a faculty admin, they have access to all evals made by
        // instructors in their faculty. 
        if ($eval['Mixeval']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins & super admin
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id must be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to view this evaluation', true));
                $this->redirect('index');
                return;
            }
        }
        $questions = $this->MixevalQuestion->findAllByMixevalId($id);
        $mixeval = $this->Mixeval->find('first',
            array('conditions' => array('id' => $id), 'contain' => false));

        $this->set('mixeval', $mixeval['Mixeval']);
        $this->set('questions', $questions);
        $this->set('breadcrumb', 
            $this->breadcrumb->push('mixevals')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($mixeval['Mixeval']['name'])
        );
    }

    /**
     * add
     *
     * @access public
     * @return void
     */
    function add()
    {
        // Check that the user has permission to access this page
        if (!User::hasPermission('controllers/Mixevals')) {
            $this->Session->setFlash(_t('Error: You do not have permission to edit this evaluation'));
            $this->redirect('index');
            return;
        }

        // Process form submit
        if (!empty($this->data)) {
            $this->_dataSavePrep();
            $this->_transactionalSave();
        }

        // Load data for the view
        $mixeval_question_types = $this->MixevalQuestionType->find('list');
        $this->set('mixevalQuestionTypes', $mixeval_question_types);
        $this->set('breadcrumb', 
            $this->breadcrumb->push('mixevals')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
    }

    /**
     * Helper method to correct inconsistencies resulting from user manipulation
     * on the forms so that data can be saved correctly
     */
    public function _dataSavePrep() {
        // Add a creation date
        $this->data['Mixeval']['created'] = date('Y-m-d H:i:s');

        // Reorder the questions so that they're contiguous starting from 0.
        // This is necessary because the question indexes can be missing
        // questions due to users moving them around, and form persistence
        // on save failure requires 0 indexed contiguous arrays.
        // What's helpful is that question_num has been kept contiguous by
        // javascript, unfortunately, it's indexed from 1 instead of 0 for
        // user friendliness.
        $newOrder = array(); // maps old index to new index, this is for
        // fixing the QuestionDesc indexes, which
        // is still referring to the old index
        if (isset($this->data['MixevalQuestion'])) {
            $contiguousQs = array();
            foreach ($this->data['MixevalQuestion'] as $oldIndex => $q) {
                $newIndex = $q['question_num'] - 1;
                $contiguousQs[$newIndex] = $q; 
                $newOrder[$oldIndex] = $newIndex;
            }
            $this->data['MixevalQuestion'] = $contiguousQs;
        }

        // Question desc has the same problem with needing to be contiguous.
        // We also need to edit the question desc data: 
        // - Update question_index to the new question indexes.
        // - Determine each desc's scale level.
        //
        // Determining each desc's scale level depends on the order of the 
        // question desc always being sequential. E.g. If Q1 has descs # 3, 
        // 8, 5534, 23323, then we assume that the lowest desc # (3 in this 
        // case) is the lowest scale (1) and 23323 is the desc for the 
        // highest scale (4 in this case) 
        if (isset($this->data['MixevalQuestionDesc'])) {
            $contiguousDescs = array();
            // map old question index to scale, this keeps track of how many
            // descriptors for each question we've seen so far (and hence,
            // the scale level)
            $descScale = array(); 
            foreach ($this->data['MixevalQuestionDesc'] as $desc) {
                $oldIndex = $desc['question_index'];
                // fix the index
                $desc['question_index'] = $newOrder[$oldIndex];
                // assign the appropriate scale
                if (!isset($descScale[$oldIndex])) {
                    $descScale[$oldIndex] = 1;
                }
                else {
                    $descScale[$oldIndex]++;
                }
                $desc['scale_level'] = $descScale[$oldIndex];
                // make contiguous
                $contiguousDescs[] = $desc;
            }
            $this->data['MixevalQuestionDesc'] = $contiguousDescs;
        }
    }

    /** 
     * Helper method to split out all the complication involved in saving
     * mixeval data.
     *
     * Can't figure out how to get cakephp to nicely save 
     * MixevalQuestionDesc with just a Mixeval->save call, so it'll have to be 
     * split up into a multi-model save transaction. Note that since CakePHP 
     * doesn't have nested transaction support, we're going to avoid saveAll as 
     * it issues another transaction by default. This should be simpler than 
     * having to properly deal with the return statuses in a non-transactional 
     * saveAll call. 
     */
    public function _transactionalSave() {
        // Don't actually do a save if user pressed cancelled button
        if (isset($this->params['form']['cancel'])) {
            $this->redirect('index');
        }

        // First, we have to validate the forms. The automagic validation errors
        // won't show up with the multiple save calls we're going to be using.
        // Note that this will validate Mixeval and MixevalQuestions, but not
        // MixevalQuestionDesc.
        if (!$this->Mixeval->saveAll($this->data, array('validate' => 'only'))){
            $this->Session->setFlash(
                _t('Unable to save, please check below for error messages.'));
            return;
        }
        
        $continue = true;
        $this->Mixeval->begin();

        if ($continue) {
            $ret = $this->Mixeval->save($this->data); 
            if (!$ret) {
                $this->Session->setFlash(
                    _t('Unable to save the mixed evaluation.'));
                $continue = false;
            }
        }

        // Have to save each question individually due to previously noted 
        // problem with saveAll and transactions.
        if ($continue) {
            $questions = array('MixevalQuestion' => 
                $this->data['MixevalQuestion']);
            foreach ($questions['MixevalQuestion'] as $q) {
                $q['mixeval_id'] = $this->Mixeval->id;
                $saveQ = array('MixevalQuestion' => $q);
                $this->MixevalQuestion->create();
                if (!$this->MixevalQuestion->save($saveQ)) {
                    $this->Session->setFlash(
                        _t("Unable to save this mixed eval's questions."));
                    $continue = false;
                    break;
                }
            }
        }

        // Save the MixevalQuestionDesc
        // Have to give a question_id to each question desc
        if ($continue && isset($this->data['MixevalQuestionDesc'])) {
            // first, need to map question number to question id
            $questions = $this->MixevalQuestion->findAllByMixevalId(
                $this->Mixeval->id);
            $qIndexToId = array(); 
            foreach ($questions as $q) {
                $q = $q['MixevalQuestion'];
                $qIndexToId[$q['question_num'] - 1] = $q['id'];
            }
            // try to save each question desc
            foreach ($this->data['MixevalQuestionDesc'] as $d) {
                $d['question_id'] = $qIndexToId[$d['question_index']];
                $saveDesc = array('MixevalQuestionDesc' => $d);
                $this->MixevalQuestionDesc->create();
                if (!$this->MixevalQuestionDesc->save($saveDesc)) {
                    $this->Session->setFlash(
                        _t('Unable to save the mixed eval question descs.'));
                    $continue = false;
                    break;
                }
            }
        }

        // commit the transaction if everything looks good
        if ($continue) {
            $this->Mixeval->commit();
            $this->Session->setFlash(
                _t('The mixed evaluation was saved successfully.'), 'good');
            $this->redirect('index');
            return;
        }
        else {
            $this->Mixeval->rollback();
        }
    }

    /**
     * edit
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function edit($id)
    {
        // Check that the user has permission to access this page
        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        // Check that there's actually a mixeval with the given ID
        if (!is_numeric($id) && 
            !$this->Mixeval->field('id', array('id' => $id))
        ){
            $this->Session->setFlash(_t('Error: Invalid ID.'));
            $this->redirect('index');
            return;
        }

        // Check for submissions, can't edit if there are submissions
        $events = $this->Event->find(
            'list',
            array(
                'conditions' => array(
                    'event_template_type_id' => 4,
                    'template_id' => $id
                ),
                'fields' => array('id'),
            )
        );
        if (!empty($events)) {
            $subs = $this->EvaluationSubmission->find('list',
                array('conditions' => array('event_id' => $events)));
            if (!empty($subs)) {
                $this->Session->setFlash(sprintf(
                    _t('%s cannot be edited now that submissions have been made. Please make a copy.'), 
                    $this->Mixeval->field('name', array('id' => $id))
                ));
                $this->redirect('index');
                return;
            }
        }

        if (empty($this->data)) {
            // Load existing data
            $this->data = $this->Mixeval->find(
                'first', 
                array(
                    'conditions' => array('id' => $id),
                    'contain' => array('MixevalQuestion.MixevalQuestionDesc')
                )
            );
            $qIds = $this->MixevalQuestion->find(
                'list',
                array(
                    'conditions' => array('mixeval_id' => $id),
                    'fields' => array('id')
                )
            );
            $descs = $this->MixevalQuestionDesc->find(
                'all',
                array(
                    'conditions' => array('question_id' => $qIds),
                    'contain' => false,
                )
            );
            // Find all returns data in a different format than form submit, and
            // since the question editor expects data in form submit format, we
            // need to change the $descs data to the expected format.
            //
            // Also need to add a question_index to each question descriptor.

            // get a mapping of question id to question index.
            $qIdToIndex = array();
            foreach ($this->data['MixevalQuestion'] as $index => $q) {
                $qIdToIndex[$q['id']] = $index;
            }
            $tmpDescs = array();
            foreach ($descs as $d) {
                $d = $d['MixevalQuestionDesc'];
                $d['question_index'] = $qIdToIndex[$d['question_id']];
                $tmpDescs[] = $d;
            }
            $this->data['MixevalQuestionDesc'] = $tmpDescs;
        } else {
            // Save changes
            $this->_dataSavePrep();
            $this->_transactionalSave();
        }

        // Load data for the view
        $mixeval_question_types = $this->MixevalQuestionType->find('list');
        $this->set('mixevalQuestionTypes', $mixeval_question_types);
        $this->set('breadcrumb', 
            $this->breadcrumb->push('mixevals')->
            push(Inflector::humanize(Inflector::underscore($this->action)))->
            push($this->data['Mixeval']['name'])
        );
        $this->render('add');
    }

    /**
     * copy
     *
     * @param bool $id
     *
     * @access public
     * @return void
     */
    function copy($id=null)
    {
        $eval = $this->Mixeval->getEventSub($id);

        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if ($eval['Mixeval']['availability'] != 'public' && !User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to copy this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        $this->data = $this->Mixeval->copy($id);
        $this->set('data', $this->data);
        $this->set('action', __('Copy Mixed Evaluation', true));
        $this->render('edit');
    }


    /**
     * delete
     *
     * @param mixed $id
     *
     * @access public
     * @return void
     */
    function delete($id)
    {
        if (!User::hasPermission('controllers/mixevals')) {
            $this->Session->setFlash(__('You do not have permission to delete mixed evaluations', true));
            $this->redirect('/home');
            return;
        }

        // retrieving the requested mixed evaluation
        $eval = $this->Mixeval->getEventSub($id);

        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Invalid ID.', true));
            $this->redirect('index');
            return;
        }

        if (!User::hasPermission('functions/superadmin')) {
            // instructor
            if (!User::hasPermission('controllers/departments')) {
                $instructorIds = array($this->Auth->user('id'));
            // admins
            } else {
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to delete this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        // Deny Deleting evaluations in use:
        $this->Mixeval->id = $id;
        $data = $this->Mixeval->read();
        $inUse = (0 <= $data['Mixeval']['event_count']);

        if ($inUse) {
            $message = "<span style='color:red'>";
            $message.= __("This evaluation is now in use, and can NOT be deleted.<br />", true);
            $message.= __("Please remove all the events assosiated with this evaluation first.", true);
            $message.= "</span>";
            $this->Session->setFlash($message);
            $this->redirect('index');
            //	exit;
        } else {
            if ($this->Mixeval->delete($id)) {
                $this->Session->setFlash(__('The Mixed Evaluation was removed successfully.', true), 'good');
                $this->redirect('index');
            } else {
                $this->Session->setFlash($this->Mixeval->errorMessage, 'error');
            }
        }
    }
}

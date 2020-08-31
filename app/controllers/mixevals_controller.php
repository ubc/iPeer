<?php
App::import('Lib', 'caliper');
use caliper\CaliperHooks;

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
        $this->mine_only = (!empty($_REQUEST['show_my_tool']) && ('on' == $_REQUEST['show_my_tool'] || 1 == $_REQUEST['show_my_tool'])) ? true : false;
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {
        parent::beforeFilter();

        $this->set('title_for_layout', __('Mixed Evaluations', true));
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
                        'event_template_type_id' => 4,
                        'template_id' => $entry['Mixeval']['id'])
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
            array("Mixeval.availability",  __("Availability", true), "6em",  "map",
                array('private' => 'private', 'public' => 'public')),
            array("Mixeval.peer_question", __("Peer Evaluation Questions", true), "6em", "number"),
            array("Mixeval.self_eval", __("Self Evaluation Questions", true), "6em", "number"),
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
        $restrictions = array();
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
    function view($id, $showSelfEval = 1)
    {
        $eval = $this->Mixeval->find('first', array(
            'conditions' => array('id' => $id),
            'contain' => 'Question.Description',
        ));

        // check to see if $id is a valid mixed evaluation
        if (empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid Mixeval ID.', true));
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
        // the questions may be saved in a slightly different order
        // this causes the order to be different between edit/copy and view.
        $questions = $this->MixevalQuestion->find('all', array(
            'conditions' => array('mixeval_id' => $id),
            'order' => 'question_num'
        ));
        $mixeval = $this->Mixeval->find('first',
            array('conditions' => array('id' => $id), 'contain' => false));

        $this->set('mixeval', $mixeval['Mixeval']);
        $this->set('questions', $questions);
        $this->set('selfEval', $showSelfEval);
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
            $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
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
        $selfEval = $this->data['Mixeval']['self_eval'];

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
        $deleteSelfEval = array();
        if (isset($this->data['MixevalQuestion'])) {
            $contiguousQs = array();
            foreach ($this->data['MixevalQuestion'] as $oldIndex => $q) {
                $newIndex = $q['question_num'] - 1;
                if (!$selfEval && $q['self_eval']) {
                    // skip self evaluation when they are "deleted"
                    $deleteSelfEval[$oldIndex] = 1;
                    continue;
                }
                $scaleLevel = 0;
                if (isset($this->data['MixevalQuestionDesc'])) {
                    foreach ($this->data['MixevalQuestionDesc'] as $desc) {
                        $scaleLevel++;
                    }
                }
                $q['scale_level'] = $scaleLevel;
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
                if (isset($deleteSelfEval[$oldIndex])) {
                    // skip desc for deleted self evaluation questions
                    continue;
                }
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
     *
     * @access public
     * @return void
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
                __('Unable to save, please check below for error messages.', true));
            return;
        }

        $continue = true;
        $this->Mixeval->begin();

        // events is used for caliper
        $events = array();
        $isNewMixeval = !isset($this->data['Mixeval']['id']);
        $existingQIds = array();

        // Only try deleting stuff if we're editing
        if (isset($this->data['Mixeval']['id'])) {
            // Delete removed questions
            $existingQs = $this->MixevalQuestion->findAllByMixevalId(
                $this->data['Mixeval']['id']);

            // Declare all the functions used by array_map. Note that
            // we check if the function_exists only because the test cases
            // keep complaining about these functions being redeclared.
            if (!function_exists('getExistingQId')) {
                /**
                 * getExistingQId
                 *
                 * @param mixed $x
                 *
                 * @access public
                 * @return void
                 */
                function getExistingQId($x)
                {
                    return $x['MixevalQuestion']['id'];
                }
                /**
                 * getId
                 *
                 * @param mixed $x
                 *
                 * @access public
                 * @return void
                 */
                function getId($x)
                {
                    return isset($x['id']) ? $x['id'] : null;
                }
                /**
                 * getExistingDescId
                 *
                 * @param mixed $x
                 *
                 * @access public
                 * @return void
                 */
                function getExistingDescId($x)
                {
                    return array_map('getId', $x['MixevalQuestionDesc']);
                }
            }
            $existingQIds = array_map('getExistingQId', $existingQs);

            $submittedQIds = isset($this->data['MixevalQuestion']) ?
                array_map('getId', $this->data['MixevalQuestion']) : array();

            $deletedQIds = array_diff($existingQIds, $submittedQIds);

            $events = CaliperHooks::mixeval_save_deleted_question_partial($this->data['Mixeval']['id'], $deletedQIds);
            foreach ($deletedQIds as $id) {
                $this->MixevalQuestion->delete($id);
            }

            // Delete removed question descs
            $existingQDescIds = array_map('getExistingDescId', $existingQs);
            $existingQDescIds = array_reduce($existingQDescIds,
                'array_merge', array());

            $submittedQDescIds = isset($this->data['MixevalQuestionDesc']) ?
                array_map('getId', $this->data['MixevalQuestionDesc']) : array();

            $deletedDescIds = array_diff($existingQDescIds, $submittedQDescIds);

            foreach ($deletedDescIds as $id) {
                $this->MixevalQuestionDesc->delete($id);
            }
        }

        // Save the Mixeval info
        if ($continue) {
            $ret = $this->Mixeval->save($this->data);
            if (!$ret) {
                $this->Session->setFlash(
                    __('Unable to save the mixed evaluation.', true));
                $continue = false;
            }
        }

        // Have to save each question individually due to previously noted
        // problem with saveAll and transactions.
        if ($continue && isset($this->data['MixevalQuestion'])) {
            $questions = array('MixevalQuestion' =>
                $this->data['MixevalQuestion']);
            foreach ($questions['MixevalQuestion'] as $q) {
                $q['mixeval_id'] = $this->Mixeval->id;
                $saveQ = array('MixevalQuestion' => $q);
                $this->MixevalQuestion->create();
                if (!$this->MixevalQuestion->save($saveQ)) {
                    $this->Session->setFlash(
                        __("Unable to save this mixed eval's questions.", true));
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
                        __('Unable to save the mixed eval question descs.', true));
                    $continue = false;
                    break;
                }
            }
        }

        // commit the transaction if everything looks good
        if ($continue) {
            $this->Mixeval->commit();
            CaliperHooks::mixeval_save_with_questions($events, $this->Mixeval->id, $existingQIds, $isNewMixeval);

            $this->Session->setFlash(
                __('The mixed evaluation was saved successfully.', true), 'good');
            // TODO Maybe redirect to view evaluation instead?
            // And put an Edit button in the view evaluation too
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
            // check people who are not superadmins
            if (!User::hasPermission('controllers/departments')) {
            // instructor
                $instructorIds = array($this->Auth->user('id'));
            } else {
            // admins
                // course ids
                $courseIds = array_keys(User::getMyDepartmentsCourseList('list'));
                // instructors
                $instructors = $this->UserCourse->findAllByCourseId($courseIds);
                $instructorIds = Set::extract($instructors, '/UserCourse/user_id');
                // add the user's id
                array_push($instructorIds, $this->Auth->user('id'));
            }

            $eval = $this->Mixeval->findById($id);
            // creator's id be in the array of accessible user ids
            if (!(in_array($eval['Mixeval']['creator_id'], $instructorIds))) {
                $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        // Check that there's actually a mixeval with the given ID
        if (!is_numeric($id) ||
            !$this->Mixeval->field('id', array('id' => $id))
        ){
            $this->Session->setFlash(__('Error: Invalid ID.', true));
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
                    __('%s cannot be edited now that submissions have been made. Please make a copy.', true),
                    $this->Mixeval->field('name', array('id' => $id))
                ));
                $this->redirect('index');
                return;
            }
        }

        // Save changes if there are any
        if (!empty($this->data)) {
            $this->_dataSavePrep();
            // This is kind of a hack. Inside _transactionSave, it will call saveAll with 'validate' option
            // equals to 'only' to perform validation.  In turn, the cake library Model's __save function
            // will call create() to reset the model with default values, including the 'creator_id'.
            // Setting the created date here will let TraceableBehavior to update the creator_id properly and
            // avoid saving the creator_id as 0.
            $eval = $this->Mixeval->findById($id);
            $this->data['Mixeval']['created'] = $eval['Mixeval']['created'];
            $this->_transactionalSave();
        } else {

            // Load existing mix evaluation data for the view
            // Needs to be here since we need to reload the data after a save, or
            // the js question tracking will get confused due to the differing
            // question indexes.
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
            // find all returns data in a different format than form submit, and
            // since the question editor expects data in form submit format, we
            // need to change the $descs data to the expected format.

            // also need to add a question_index to each question descriptor, we do
            // this with a mapping of question id to question index.
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
        }

        // Load other stuff for the view
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
        // Process form submit
        if (!empty($this->data)) {
            $this->_dataSavePrep();
            $this->_transactionalSave();
        } else {
            $eval = $this->Mixeval->find('first', array(
                'conditions' => array('id' => $id),
                'contain' => array('Event' => 'EvaluationSubmission')
            ));

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

            $mixeval = $this->Mixeval->find('first', array(
                'conditions' => array('id' => $id),
                'recursive' => 2
            ));

            $title = __('Copy of ', true).$mixeval['Mixeval']['name'];
            $copy['Mixeval'] = array('name' => $title, 'availability' => $mixeval['Mixeval']['availability'],
                'zero_mark' => $mixeval['Mixeval']['zero_mark'], 'self_eval' => $mixeval['Mixeval']['self_eval']);
            foreach ($mixeval['MixevalQuestion'] as $index => $ques) {
                $desc = $ques['MixevalQuestionDesc'];
                unset($ques['id'], $ques['MixevalQuestionType'], $ques['mixeval_id'],
                    $ques['scale_level'], $ques['MixevalQuestionDesc']);
                $copy['MixevalQuestion'][] = $ques;
                foreach ($desc as $d) {
                    $descriptor = array('descriptor' => $d['descriptor'], 'question_index' => $index);
                    $copy['MixevalQuestionDesc'][] = $descriptor;
                }
            }
            $this->data = $copy;
        }
        $this->autoRender = false;
        $mixeval_question_types = $this->MixevalQuestionType->find('list');
        $this->set('mixevalQuestionTypes', $mixeval_question_types);
        $this->set('breadcrumb',
            $this->breadcrumb->push('mixevals')->
            push(Inflector::humanize(Inflector::underscore($this->action)))
        );
        $this->render('add');
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
        $eval = $this->Mixeval->find('first', array(
            'conditions' => array('id' => $id),
            'contain' => array('Event')
        ));

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

        $inUse = (0 < count($data['Event']));

        if ($inUse) {
            $message = __("This evaluation is now in use, and can NOT be deleted.<br />", true);
            $message.= __("Please remove all the events associated with this evaluation first.", true);
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

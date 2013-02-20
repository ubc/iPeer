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
        'MixevalQuestionType');
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
                $courseIds = Set::extract($this->UserCourse->findAllByUserId($myID), '/UserCourse/course_id');
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
            $this->Session->setFlash(_('Error: Invalid Mixeval ID.'));
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
    }

    /**
     * add
     *
     * @access public
     * @return void
     */
    function add()
    {
        $mixeval_question_types = $this->MixevalQuestionType->find('list');
        $this->set('mixevalQuestionTypes', $mixeval_question_types);
        if (!empty($this->data)) {
            // Since users can move questions around, we have two different
            // question orderings that needs to be dealth with: The order
            // in which the questions were inserted and the order in which
            // the user wants to questions to be.
            
            // Reorder the questions so that they match the numbering that
            // the user wanted, also create a mapping from the insertion
            // order to the user order.
            $userOrder = array();
            if (isset($this->data['MixevalQuestion'])) {
                $orderedQs = array();
                foreach ($this->data['MixevalQuestion'] as $key => $q) {
                   $orderedQs[$q['question_num']] = $q; 
                   $userOrder[$key] = $q['question_num'];
                }
                $this->data['MixevalQuestion'] = $orderedQs;
            }

            // also give a scale_level to each question desc. Note that this
            // implemention depends on the order of the question desc always
            // being sequential. E.g. If Q1 has descs # 3, 8, 5534, 23323, then
            // we assume that the lowest desc # (3 in this case) is the lowest
            // scale (1) and 23323 is the desc for the highest scale (4 in this
            // case) 
            if (isset($this->data['MixevalQuestionDesc'])) {
                // we also want to make sure that the descriptors have an index
                // that monotonically increases by 1 each entry to make our 
                // life easier when restoring form state in the view. Also 
                // needs to be indexed from 1 and not 0, so we'll put in a fake 
                // 0 element and remove it later. 
                $orderedDescs = array(0 => 'blah');
                // map question num to scale, this keeps track of how many
                // descriptors for each question we've seen so far (and hence,
                // the scale level)
                $descScale = array(); 
                foreach ($this->data['MixevalQuestionDesc'] as $desc) {
                    $qNum = $desc['question_id']; // this is the insertion order
                    // change to user order
                    $desc['question_id'] = $userOrder[$qNum];
                    // assign the appropriate scale
                    if (!isset($descScale[$qNum])) {
                        $descScale[$qNum] = 1;
                    }
                    else {
                        $descScale[$qNum]++;
                    }
                    $desc['scale_level'] = $descScale[$qNum];

                    $orderedDescs[] = $desc;
                }
                unset($orderedDescs[0]);
                $this->data['MixevalQuestionDesc'] = $orderedDescs;
            }

            $this->_transactionalSave();
        }
    }

    /* Helper method to split out all the complication involved in saving
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
        $continue = true;
        $this->Mixeval->begin();

        $ret = $this->Mixeval->save($this->data); 
        if (!$ret) {
            $this->Session->setFlash(
                _('Unable to save the mixed evaluation.'));
            $continue = false;
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
                        _("Unable to save this mixed eval's questions."));
                    $continue = false;
                    break;
                }
            }
        }

        // Save the MixevalQuestionDesc
        // Have to remap question_id to the correct question
        if ($continue && isset($this->data['MixevalQuestionDesc'])) {
            // first, need to map question number to question id
            $questions = $this->MixevalQuestion->findAllByMixevalId(
                $this->Mixeval->id);
            $qNumToId = array(); 
            foreach ($questions as $q) {
                $q = $q['MixevalQuestion'];
                $qNumToId[$q['question_num']] = $q['id'];
            }
            // try to save each question desc
            foreach ($this->data['MixevalQuestionDesc'] as $d) {
                $d['question_id'] = $qNumToId[$d['question_id']];
                $saveDesc = array('MixevalQuestionDesc' => $d);
                $this->MixevalQuestionDesc->create();
                if (!$this->MixevalQuestionDesc->save($saveDesc)) {
                    $this->Session->setFlash(
                        _('Unable to save the mixed eval question descs.'));
                    $continue = false;
                    break;
                }
            }
        }

        // commit the transaction if everything looks good
        if ($continue) {
            $this->Mixeval->commit();
            $this->Session->setFlash(
                _('The mixed evaluation was added successfully.'), 'good');
            $this->redirect('index');
            return;
        }
        else {
            $this->Mixeval->rollback();
        }
    }

    /**
     * deleteQuestion
     *
     * @param mixed $question_id
     *
     * @access public
     * @return void
     */
    function deleteQuestion($question_id)
    {
        $this->autoRender = false;
        $this->MixevalQuestion->deleteAll(array('id' => $question_id), true);
    }


    /**
     * deleteDescriptor
     *
     * @param mixed $descriptor_id
     *
     * @access public
     * @return void
     */
    function deleteDescriptor($descriptor_id)
    {
        $this->autoRender = false;
        $this->MixevalQuestionDesc->delete(array('id' => $descriptor_id));
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
        // retrieving the requested mixed evaluation
        $eval = $this->Mixeval->getEventSub($id);

        // check to see if $id is valid - numeric & is a mixed evaluation
        if (!is_numeric($id) || empty($eval)) {
            $this->Session->setFlash(__('Error: Invalid ID.', true));
            $this->redirect('index');
            return;
        }
        
        // check to see if submissions had been made - if yes - mixeval can't be edited
        foreach ($eval['Event'] as $event) {
            if (!empty($event['EvaluationSubmission'])) {
                $this->Session->setFlash(sprintf(__('Submissions had been made. %s cannot be edited. Please make a copy.', true), $eval['Mixeval']['name']));
                $this->redirect('index');
                return;
            }
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
                $this->Session->setFlash(__('Error: You do not have permission to edit this evaluation', true));
                $this->redirect('index');
                return;
            }
        }

        if (empty($this->data)) {
            $this->data = $this->Mixeval->find('first', array('conditions' => array('id' => $id),
                'contain' => array('Question.Description',
            )));

        } else {
            $data = $this->data;

            if ($this->Mixeval->save($data)) {
                $this->MixevalQuestion->insertQuestion($this->Mixeval->id, $this->data['Question']);
                $id = $this->Mixeval->id;
                $question_ids= $this->MixevalQuestion->find('all', array('conditions' => array('mixeval_id'=> $id), 'fields'=>'id, question_num'));
                $this->MixevalQuestionDesc->insertQuestionDescriptor($this->data['Question'], $question_ids);
                $this->Session->setFlash(__('The Mixed Evaluation was edited successfully.', true), 'good');
                $this->redirect('index');
                return;
            } else {
                $this->set('data', $this->data);
                $this->Session->setFlash(__("The evaluation was not added successfully.", true));
                $error = $this->Mixeval->getErrorMessage();
                if (!is_array($error)) {
                    $this->Session->setFlash($error);
                }
            }
        }
        $this->set('data', $this->data);
        $this->set('action', __('Edit Mixed Evaluation', true));
    }


    /**
     * __processForm
     *
     * @access protected
     * @return void
     */
    function __processForm()
    {
        if (!empty($this->data)) {
            $this->Output->filter($this->data);//always filter

            //Save Data
            if ($this->Mixeval->saveAllWithDescription($this->data)) {
                $this->data['Mixeval']['id'] = $this->Mixeval->id;
                return true;
            }
        }
        return false;
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

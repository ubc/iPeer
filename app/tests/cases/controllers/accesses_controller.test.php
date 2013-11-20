<?php
App::import('Lib', 'ExtendedAuthTestCase');
App::import('Controller', 'Accesses');

// mock instead of needing to create a new controller for every test
Mock::generatePartial('AccessesController',
    'MockAccessesController',
    array('isAuthorized', 'render', 'redirect', '_stop', 'header'));
    
/**
 * AccessesControllerTest Accesses controller test case
 *
 * @uses $ExtendedAuthTestCase
 * @package Tests
 */
class AccessesControllerTest extends ExtendedAuthTestCase
{
    public $controller = null;
    
    public $fixtures = array(
        'app.access', 'app.oauth_token', 'app.sys_parameter',
        'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type',
        'app.course', 'app.group', 'app.group_event', 'app.evaluation_simple',
        'app.survey_input', 'app.survey_group_member', 'app.survey_group_set',
        'app.survey', 'app.question', 'app.response', 'app.survey_question',
        'app.survey_group', 'app.faculty', 'app.user_faculty', 'app.role', 'app.roles_user',
        'app.user_course', 'app.user_tutor', 'app.user_enrol','app.groups_member',
        'app.department', 'app.course_department', 'app.penalty', 'app.evaluation_rubric',
        'app.evaluation_rubric_detail', 'app.evaluation_mixeval', 'app.evaluation_mixeval_detail'
    );
    
    /**
     * startCase case startup
     *
     * @access public
     * @return void
     */
    public function startCase()
    {
        echo "Start Access controller test.\n";
        $this->defaultLogin = array(
            'User' => array(
                'username' => 'root',
                'password' => md5('ipeeripeer')
            )
        );
    }
    
    /**
     * endCase case ending
     *
     * @access public
     * @return void
     */
    public function endCase()
    {
    }
    
    /**
     * startTest prepare tests
     * @access public
     * @return void
     */
    public function startTest($method)
    {
        echo $method.TEST_LB;
        $this->controller = new MockAccessesController();
    }
    
    /**
     * @access public
     * @return void
     */
    public function endTest($method)
    {
        if (isset($this->controller->Auth)) {
            $this->controller->Auth->logout();
        }
        unset($this->controller);
        ClassRegistry::flush();
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    function testView()
    {
    	//id should not be used since it can change
        $allow = array('create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1);
        $deny = array('create' => -1, 'read' => -1, 'update' => -1, 'delete' => -1);

        $superadmin = $this->testAction('/accesses/view/1', array('return' => 'vars'));
        unset($superadmin['permissions']['controllers/courses/add']["id"]);
        $this->assertEqual($superadmin['permissions']['controllers/courses/add'], $allow);
        $this->assertEqual($superadmin['roleId'], 1);
        $this->assertEqual($superadmin['title_for_layout'], 'Permissions Editor > superadmin');
        
        $admin = $this->testAction('/accesses/view/2', array('return' => 'vars'));
        unset($admin['permissions']['controllers/courses/add']["id"]);
        $this->assertEqual($admin['permissions']['controllers/courses/add'], $allow);
        $this->assertEqual($admin['roleId'], 2);
        $this->assertEqual($admin['title_for_layout'], 'Permissions Editor > admin');
        
        $instructor = $this->testAction('/accesses/view/3', array('return' => 'vars'));
        unset($instructor['permissions']['controllers/courses/add']["id"]);
        $this->assertEqual($instructor['permissions']['controllers/courses/add'], $allow);
        $this->assertEqual($instructor['roleId'], 3);
        $this->assertEqual($instructor['title_for_layout'], 'Permissions Editor > instructor');
        
        $tutor = $this->testAction('/accesses/view/4', array('return' => 'vars'));
        unset($tutor['permissions']['controllers/courses/add']["id"]);
        $this->assertEqual($tutor['permissions']['controllers/courses/add'], $deny);
        $this->assertEqual($tutor['roleId'], 4);
        $this->assertEqual($tutor['title_for_layout'], 'Permissions Editor > tutor');
        
        $student = $this->testAction('/accesses/view/5', array('return' => 'vars'));
        unset($student['permissions']['controllers/courses/add']["id"]);
        $this->assertEqual($student['permissions']['controllers/courses/add'], $deny);
        $this->assertEqual($student['roleId'], 5);
        $this->assertEqual($student['title_for_layout'], 'Permissions Editor > student');
    }
    
    function testEdit()
    {
        $this->Access = ClassRegistry::init('Access');
        
        // no entry existed before the next test
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 11)
        ));
        $this->assertEqual($result, array());
        
        // testing allowing access to ALL actions that has no entry in the aros_acos table       
        $this->testAction('/accesses/edit/allow/11/2');
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 11)
        ));
        $this->assertEqual($result['Access']['_create'], 1);
        $this->assertEqual($result['Access']['_read'], 1);
        $this->assertEqual($result['Access']['_update'], 1);
        $this->assertEqual($result['Access']['_delete'], 1);
        
        // testing denying access to ALL actions that has an entry in the aros_acos table
        $this->testAction('/accesses/edit/deny/11/2');
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 11)
        ));
        $this->assertEqual($result['Access']['_create'], -1);
        $this->assertEqual($result['Access']['_read'], -1);
        $this->assertEqual($result['Access']['_update'], -1);
        $this->assertEqual($result['Access']['_delete'], -1);
        
        $this->Access->delete($result['Access']['id']);
        
        // no entry existed before the next test
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 12)
        ));
        $this->assertEqual($result, array());
        
        // testing allowing access to ONE action that has no entry in the aros_acos table
        $this->testAction('/accesses/edit/allow/12/2/create');
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 12)
        ));
        $this->assertEqual($result['Access']['_create'], 1);
        $this->assertEqual($result['Access']['_read'], -1);
        $this->assertEqual($result['Access']['_update'], -1);
        $this->assertEqual($result['Access']['_delete'], -1);
        
        // testing denying access to ONE action that has an entry in the aros_acos table
        $this->testAction('/accesses/edit/deny/12/2/create');
        $result = $this->Access->find('first', array(
            'conditions' => array('aro_id' => 2, 'aco_id' => 12)
        ));
        $this->assertEqual($result['Access']['_create'], -1);
        $this->assertEqual($result['Access']['_read'], -1);
        $this->assertEqual($result['Access']['_update'], -1);
        $this->assertEqual($result['Access']['_delete'], -1);
        
        $this->Access->delete($result['Access']['id']);
    }
}
?>
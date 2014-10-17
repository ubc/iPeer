<?php
App::import('Model', 'Access');

class AccessTestCase extends CakeTestCase {
    public $name = 'Access';
    public $fixtures = array('app.access', 'app.oauth_token', 'app.sys_parameter',
        'app.user', 'app.evaluation_submission', 'app.event', 'app.event_template_type',
        'app.course', 'app.group', 'app.group_event', 'app.evaluation_simple',
        'app.survey_input', 'app.survey_group_member', 'app.survey_group_set',
        'app.survey', 'app.question', 'app.response', 'app.survey_question',
        'app.survey_group', 'app.faculty', 'app.user_faculty', 'app.role', 'app.roles_user',
        'app.user_course', 'app.user_tutor', 'app.user_enrol','app.groups_member',
        'app.department', 'app.course_department', 'app.penalty');
    public $Access = null;
    
    function startCase()
    {
        echo "Start Access model test.\n";
        $this->Access = ClassRegistry::init('Access');
    }
    
    function endCase()
    {
    }
    
    function startTest($method)
    {
    }
    
    function endTest($method)
    {
    }
    
    function testAccessInstance()
    {
        $this->assertTrue(is_a($this->Access, 'Access'));
    }
    
    function testLoadPermissions()
    {
        $this->Aco = ClassRegistry::init('Aco');
        $this->Aro = ClassRegistry::init('Aro');
        
        //id should not be used since it can change
        $allow = array('create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1);
        $deny = array('create' => -1, 'read' => -1, 'update' => -1, 'delete' => -1);
        $acos = $this->Aco->find('threaded');

        // Testing for super admin role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>1, 'Aro.model'=>'Role')));
        $superadmin = $this->Access->loadPermissions($acos, $group_aro);
        unset($superadmin["controllers/users/add"]["id"]);
        $this->assertEqual($superadmin['controllers/users/add'], $allow);
        
        // Testing for admin role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>2, 'Aro.model'=>'Role')));
        $admin = $this->Access->loadPermissions($acos, $group_aro);
        unset($admin["controllers/users/add"]["id"]);
        $this->assertEqual($admin['controllers/users/add'], $allow);
        
        // Testing for instructor role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>3, 'Aro.model'=>'Role')));
        $instructor = $this->Access->loadPermissions($acos, $group_aro);
        unset($instructor["controllers/users/add"]["id"]);
        $this->assertEqual($instructor['controllers/users/add'], $allow);
        
        // Testing for tutor role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>4, 'Aro.model'=>'Role')));
        $tutor = $this->Access->loadPermissions($acos, $group_aro);
        unset($tutor["controllers/users/add"]["id"]);
        $this->assertEqual($tutor['controllers/users/add'], $deny);
        
        // Testing for student role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>5, 'Aro.model'=>'Role')));
        $student = $this->Access->loadPermissions($acos, $group_aro);
        unset($student["controllers/users/add"]["id"]);
        $this->assertEqual($student['controllers/users/add'], $deny);
        
        // Testing for invalid role
        $group_aro = $this->Aro->find('threaded', array('conditions'=>array('Aro.foreign_key'=>999, 'Aro.model'=>'Role')));
        $invalid = $this->Access->loadPermissions($acos, $group_aro);
        $this->assertEqual($invalid, array());
    }
}
?>
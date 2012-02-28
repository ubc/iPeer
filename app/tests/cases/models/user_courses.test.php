<?php
App::import('Model', 'UserCourse');

class UserCourseTestCase extends CakeTestCase
{
    public $name = 'UserEnrol';
    public $fixtures = array(
        'app.course', 'app.role', 'app.user', 'app.group',
        'app.roles_user', 'app.event', 'app.event_template_type',
        'app.group_event', 'app.evaluation_submission',
        'app.survey_group_set', 'app.survey_group',
        'app.survey_group_member', 'app.question',
        'app.response', 'app.survey_question', 'app.user_course',
        'app.user_enrol', 'app.groups_member', 'app.survey'
    );
    public $UserCourse = null;

    function startCase()
    {
        $this->UserCourse = ClassRegistry::init('UserCourse');
    }

    function endCase()
    {
    }

    function startTest($method)
    {
    }

    function endTest($method)
    {
        $this->flushDatabase();
    }

    function testUserCourseInstance()
    {
        $this->assertTrue(is_a($this->UserCourse, 'UserCourse'));
    }

    function testGetInstructors()
    {

        //Test course with instructors
        $instructors = $this->UserCourse->getInstructors(1);
        $instructors = $this->toArray($instructors);
        $this->assertEqual($instructors, array('slade', 'peterson'));

        //Test course with no instructors
        $instructors = $this->UserCourse->getInstructors(4);
        $this->assertEqual($instructors, null);

        //Test invalid course
        $instructors = $this->UserCourse->getInstructors(999);
        $this->assertEqual($instructors, null);

    }

    function testGetInstructorsId()
    {

        //Test course with instructors
        $instructors = $this->UserCourse->getInstructorsId(1);
        $instructors = $this->toIdArray($instructors);
        $this->assertEqual($instructors, array(1,2));

        //Test course with no instructors
        $instructors = $this->UserCourse->getInstructorsId(4);
        $this->assertEqual($instructors, null);

        //Test invalid course
        $instructors = $this->UserCourse->getInstructorsId(999);
        $this->assertEqual($instructors, null);

    }

    /*
     *Function is not used anywhere
     *
     */

    function testInsertAdmin()
    {
    }

    ##########################################################################################################
    ##################   HELPER FUNCTION USED FOR UNIT TESTING PURPOSES   ####################################
    ##########################################################################################################


    function deleteAllTuples($table)
    {

        $this->UserCourse= & ClassRegistry::init('UserCourse');
        $sql = "DELETE FROM $table";
        $this->UserCourse->query($sql);
    }

    function flushDatabase()
    {

        $this->deleteAllTuples('courses');
        $this->deleteAllTuples('users');
        $this->deleteAllTuples('user_courses');
        $this->deleteAllTuples('user_enrols');
        $this->deleteAllTuples('roles_users');
        $this->deleteAllTuples('groups');
        $this->deleteAllTuples('groups_members');
    }

    function toArray($events)
    {
        $courseNameArray = array();
        foreach ($events as $event){
            array_push($courseNameArray, $event['User']['last_name']);
        }
        return $courseNameArray;
    }

    function toIdArray($events)
    {
        $courseNameArray = array();
        foreach ($events as $event){
            array_push($courseNameArray, $event);
        }
        return $courseNameArray;
    }
}

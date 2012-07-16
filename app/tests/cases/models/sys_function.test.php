<?php
App::import('Model', 'SysFunction');

class SysFunctionTestCase extends CakeTestCase
{
    public $name = 'SysFunction';
    public $fixtures = array('app.sys_function'
    );
    public $SysFunction = null;

    function startCase()
    {
        $this->SysFunction = ClassRegistry::init('SysFunction');
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


    function testBeforeSave()
    {
        // Set up test input
        $validSaveinput = array(
            'SysFunction' => array(
                'id' => 1,
                'function_code' => 'code1',
                'function_name' => 'name1',
                'parent_id' => 1,
                'controller_name' => 'controller1',
                'url_link' => 'link1',
                'permission_type' => 'I',
                'record_status' => 'A',
                'creator_id' => 1,
                'created' => null,
                'updater_id' => null,
                'modified' => null
            )
        );
        $result = $this->SysFunction->save($validSaveinput);
        $this->assertTrue($result, 'Saving valid record. Error message: ' . $this->SysFunction->getErrorMessage());

        $falseInputType1 = array(
            'SysFunction' => array(
                'id' => 'fd',
                'function_code' => 'code1',
                'function_name' => 'name1',
                'parent_id' => 0,
                'controller_name' => 'controller1',
                'url_link' => 'link1',
                'permission_type' => 'I',
                'record_status' => 'A',
                'creator_id' => 0,
                'created' => 0,
                'updater_id' => null,
                'modified' => null
            )
        );
        $result = $this->SysFunction->save($falseInputType1);
        $this->assertFalse($result);

        $falseInputType2 = array(
            'SysFunction' => array(
                'id' => 1,
                'function_code' => 'code1',
                'function_name' => 'name1',
                'parent_id' => 0,
                'controller_name' => '',
                'url_link' => 'link1',
                'permission_type' => 'I',
                'record_status' => 'A',
                'creator_id' => 0,
                'created' => 0,
                'updater_id' => null,
                'modified' => null
            )
        );
        $result = $this->SysFunction->save($falseInputType2);
        $this->assertFalse($result);
    }
}

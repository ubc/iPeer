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

    function testSysFunctionInstance()
    {
        $this->assertTrue(is_a($this->SysFunction, 'SysFunction'));
    }

    function testGetTopAccessibleFunction()
    {
        // Set up test data
        $resultNotAccessible = $this->SysFunction->getTopAccessibleFunction('I');
        $resultAccessible = $this->SysFunction->getTopAccessibleFunction('A');
        $notAccessible1 = $resultNotAccessible[0]['SysFunction'];
        $notAccessible2 = $resultNotAccessible[1]['SysFunction'];
        $Accessible1 = $resultAccessible[0]['SysFunction'];
        $expectNotAccessible1 = array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
            'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1');
        $expectNotAccessible2 = array('id' => 2, 'function_code' => 'code2', 'function_name' => 'name2',
            'parent_id' => 0, 'controller_name' => 'controller2', 'url_link' => 'link2');
        $expectAccessible1 = array('id' => 3, 'function_code' => 'code3', 'function_name' => 'name3',
            'parent_id' => 0, 'controller_name' => 'controller3', 'url_link' => 'link3');
        // Assert the queried data results are as expected
        $this->assertEqual($notAccessible1, $expectNotAccessible1);
        $this->assertEqual($notAccessible2, $expectNotAccessible2);
        $this->assertEqual($Accessible1, $expectAccessible1);

        // Assert function only queries tuples with parent_id==0
        $this->assertEqual(count($resultAccessible), 1);
    }

    function testGetAllAccessibleFunction()
    {
        // Set up test data
        $resultNotAccessible = $this->SysFunction->getAllAccessibleFunction('I');
        $resultAccessible = $this->SysFunction->getAllAccessibleFunction('A');
        $notAccessible1 = $resultNotAccessible[0]['SysFunction'];
        $notAccessible2 = $resultNotAccessible[1]['SysFunction'];
        $Accessible1 = $resultAccessible[0]['SysFunction'];
        $Accessible2 = $resultAccessible[1]['SysFunction'];
        $expectNotAccessible1 = array('id' => 1, 'function_code' => 'code1', 'function_name' => 'name1',
            'parent_id' => 0, 'controller_name' => 'controller1', 'url_link' => 'link1');
        $expectNotAccessible2 = array('id' => 2, 'function_code' => 'code2', 'function_name' => 'name2',
            'parent_id' => 0, 'controller_name' => 'controller2', 'url_link' => 'link2');
        $expectAccessible1 = array('id' => 3, 'function_code' => 'code3', 'function_name' => 'name3',
            'parent_id' => 0, 'controller_name' => 'controller3', 'url_link' => 'link3');
        $expectAccessible2 = array('id' => 4, 'function_code' => 'code4', 'function_name' => 'name4',
            'parent_id' => 1, 'controller_name' => 'controller4', 'url_link' => 'link4');
        // Assert the queried data results are as expected
        $this->assertEqual($notAccessible1, $expectNotAccessible1);
        $this->assertEqual($notAccessible2, $expectNotAccessible2);
        $this->assertEqual($Accessible1, $expectAccessible1);
        $this->assertEqual($Accessible2, $expectAccessible2);
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

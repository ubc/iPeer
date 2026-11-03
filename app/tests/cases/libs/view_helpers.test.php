<?php
App::import('Core', array('Controller', 'View', 'Helper'));

/**
 * Tests for how Cake's View loads helpers and how Helper::value() reads
 * form data. Both index arrays with a value that is null for top-level
 * helpers and for fields without a suffix, which PHP treats as ''.
 *
 * @package app.tests.cases.libs
 */
class ViewHelpersTestCase extends CakeTestCase
{
    function startTest($method)
    {
        $this->Controller = new Controller();
        // View registers itself as 'view' but won't replace an existing entry,
        // so drop any left over from an earlier test case
        ClassRegistry::removeObject('view');
        $this->View = new View($this->Controller);
    }

    function endTest($method)
    {
        unset($this->View, $this->Controller);
        ClassRegistry::removeObject('view');
    }

    function testLoadHelpersAttachesNestedHelperToParent()
    {
        // FormHelper declares $helpers = array('Html'), so Html is loaded
        // with Form as its parent and attached to it as $Form->Html
        $loaded = array();
        $loaded = $this->View->_loadHelpers($loaded, array('Form'));

        $this->assertTrue(isset($loaded['Form']));
        $this->assertTrue(isset($loaded['Html']));
        $this->assertTrue($loaded['Form']->Html === $loaded['Html']);
    }

    function testLoadHelpersTopLevelHelpers()
    {
        $loaded = array();
        $loaded = $this->View->_loadHelpers($loaded, array('Html', 'Time'));

        $this->assertEqual(array('Html', 'Time'), array_keys($loaded));
        $this->assertIsA($loaded['Html'], 'HtmlHelper');
        $this->assertIsA($loaded['Time'], 'TimeHelper');
    }

    function testValueReturnsArrayFieldWithoutSuffix()
    {
        $Helper = new Helper();
        $Helper->data = array('Post' => array('tags' => array('1', '2')));

        $this->assertIdentical(array('1', '2'), $Helper->value('Post.tags'));
    }

    function testValueWithoutSuffixReadsEmptyStringKey()
    {
        // with no field suffix, Helper::value() looks the (null) suffix up in
        // an array value, which matches a '' key. This pins current behaviour
        // (an accident of PHP turning a null key into '') so the PHP 8.5 fix
        // can't change it; it isn't intended behaviour, so a deliberate fix may
        // update this test.
        $Helper = new Helper();
        $Helper->data = array('Post' => array('tags' => array('' => 'blank', 'a' => 'b')));

        $this->assertIdentical('blank', $Helper->value('Post.tags'));
    }

    function testValueScalarField()
    {
        $Helper = new Helper();
        $Helper->data = array('Post' => array('title' => 'Hello'));

        $this->assertIdentical('Hello', $Helper->value('Post.title'));
    }
}

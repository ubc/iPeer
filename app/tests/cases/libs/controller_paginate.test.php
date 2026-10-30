<?php

/**
 * Bare model over the faculties fixture, so no associations get loaded.
 */
class PaginateFaculty extends Model
{
    public $name = 'PaginateFaculty';
    public $useTable = 'faculties';
}

/**
 * Tests for how Controller::paginate() normalises the requested page.
 *
 * @package app.tests.cases.libs
 */
class ControllerPaginateTestCase extends CakeTestCase
{
    public $fixtures = array('app.faculty');

    function startTest($method)
    {
        $this->Controller = new Controller();
        $this->Controller->uses = array('PaginateFaculty');
        $this->Controller->params['url'] = array();
        $this->Controller->constructClasses();
        $this->Controller->paginate = array('limit' => 1);
    }

    function endTest($method)
    {
        unset($this->Controller);
        ClassRegistry::flush();
    }

    private function pageFor($page, $conditions = array())
    {
        $this->Controller->passedArgs = array('page' => $page);
        $this->Controller->paginate('PaginateFaculty', $conditions);
        return $this->Controller->params['paging']['PaginateFaculty'];
    }

    function testFixtureHasSeveralPages()
    {
        $paging = $this->pageFor('1');
        $this->assertTrue($paging['pageCount'] >= 2);
    }

    function testNumericPage()
    {
        $this->assertIdentical(2, $this->pageFor('2')['page']);
    }

    function testLastPage()
    {
        $paging = $this->pageFor('last');
        $this->assertIdentical($paging['pageCount'], $paging['page']);
    }

    function testPageBeyondLastClampsToLast()
    {
        $paging = $this->pageFor('999');
        $this->assertIdentical($paging['pageCount'], $paging['page']);
    }

    function testPageBelowOneClampsToFirst()
    {
        $this->assertIdentical(1, $this->pageFor('0')['page']);
        $this->assertIdentical(1, $this->pageFor('-1')['page']);
    }

    function testNonNumericPageGoesToFirst()
    {
        $this->assertIdentical(1, $this->pageFor('abc')['page']);
    }

    function testNoResultsIsPageOne()
    {
        $paging = $this->pageFor('1', array('PaginateFaculty.id' => -1));
        $this->assertIdentical(0, $paging['count']);
        $this->assertIdentical(1, $paging['page']);
    }
}

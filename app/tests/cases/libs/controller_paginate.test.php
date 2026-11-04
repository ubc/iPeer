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
 * Model whose paginate() override finds nothing and returns false.
 */
class PaginateNothing extends Model
{
    public $name = 'PaginateNothing';
    public $useTable = 'faculties';

    function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array())
    {
        return false;
    }

    function paginateCount($conditions = null, $recursive = 0, $extra = array())
    {
        return 0;
    }
}

/**
 * Tests for how Controller::paginate() normalises the requested page,
 * sort and direction.
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
        // page 1 is before the last page, so it must be left as is
        $this->assertIdentical(1, $this->pageFor('1')['page']);
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

    private function orderFor($args)
    {
        $this->Controller->passedArgs = $args;
        $this->Controller->paginate('PaginateFaculty');
        $paging = $this->Controller->params['paging']['PaginateFaculty'];
        return isset($paging['options']['order']) ? $paging['options']['order'] : null;
    }

    function testSortAndDirection()
    {
        $this->assertIdentical(array('PaginateFaculty.name' => 'desc'), $this->orderFor(array('sort' => 'name', 'direction' => 'desc')));
        $this->assertIdentical(array('PaginateFaculty.name' => 'asc'), $this->orderFor(array('sort' => 'name', 'direction' => 'sideways')));
    }

    function testSortThatIsNotAStringIsIgnored()
    {
        $this->assertNull($this->orderFor(array('sort' => array('name'), 'direction' => 'asc')));
    }

    function testDirectionThatIsNotAStringIsAsc()
    {
        $this->assertIdentical(array('PaginateFaculty.name' => 'asc'), $this->orderFor(array('sort' => 'name', 'direction' => array('desc'))));
    }

    function testOrderIsIgnoredWithSortThatIsNotAString()
    {
        $this->assertNull($this->orderFor(array('sort' => array('name'), 'order' => 'PaginateFaculty.name desc')));
        $this->assertNull($this->orderFor(array('sort' => array('name'), 'order' => array('name' => 'desc limit 1'))));
        $this->assertNull($this->orderFor(array('sort' => array('name'), 'order' => array('name' => array('desc')))));
    }

    function testOrderThatIsNotAStringIsIgnoredWithoutSort()
    {
        $this->assertNull($this->orderFor(array('order' => array('name' => 'desc limit 1'))));
    }

    function testOrderWithoutSortIsUsedAsSort()
    {
        $this->assertIdentical(array('PaginateFaculty.name' => 'desc'), $this->orderFor(array('order' => 'name', 'direction' => 'desc')));
    }

    function testSortWithPropertyThatIsNotAModelIsIgnored()
    {
        $this->assertIdentical(array(), $this->orderFor(array('sort' => 'name.x')));
        $this->assertIdentical(array(), $this->orderFor(array('sort' => 'validate.x')));
    }

    function testFalseResultsBecomeEmptyList()
    {
        $model = ClassRegistry::init(array('class' => 'PaginateNothing', 'ds' => 'test_suite'));
        $this->assertIdentical(array(), $this->Controller->paginate($model));
        $this->assertIdentical(0, $this->Controller->params['paging']['PaginateNothing']['current']);
    }
}

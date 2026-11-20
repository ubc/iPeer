<?php
App::import('Component', 'AjaxList');

class AjaxListComponentTestCase extends CakeTestCase
{
    public $fixtures = [];

    function startTest($method)
    {
        $this->AjaxList = new AjaxListComponent();
        $this->AjaxList->setUp(
            null,
            [
                ['Course.course', 'Course', '15em', 'string'],
                ['Course.title', 'Title', 'auto', 'string'],
                ['Course.record_status', 'Status', '5em', 'map', ['A' => 'Active', 'I' => 'Inactive']],
                ['!Custom.link', 'Link', '5em', 'action'],
            ],
            [],
            'Course.course',
            'Course.title',
            [['id' => 'Creator_id', 'list' => [5 => 'My Courses']]]
        );
    }

    // A state as the browser sends it, with the given changes.
    function _state($changes = [])
    {
        return (object) array_merge([
            'sortBy'              => 'Course.title',
            'sortAsc'             => true,
            'pageSize'            => 20,
            'pageShown'           => 1,
            'searchBy'            => 'Course.course',
            'searchValue'         => 'APSC',
            'mapFilterSelections' => (object) ['Course.record_status' => 'A'],
            'joinFilterSelections' => (object) ['Creator_id' => '5'],
        ], $changes);
    }

    function testStateWithColumnsOfTheListIsKept()
    {
        $state = $this->AjaxList->checkState($this->_state());

        $this->assertIdentical('Course.title', $state->sortBy);
        $this->assertIdentical('Course.course', $state->searchBy);
        $this->assertIdentical('APSC', $state->searchValue);
        $this->assertIdentical(['Course.record_status' => 'A'], (array) $state->mapFilterSelections);
        $this->assertIdentical(['Creator_id' => '5'], (array) $state->joinFilterSelections);
        $this->assertIdentical(true, $state->sortAsc);
    }

    function testSortAndSearchByOtherNamesUseTheDefaults()
    {
        $state = $this->AjaxList->checkState($this->_state([
            'sortBy'   => 'Course.id, Course.title',
            'searchBy' => 'Course.instructor_id',
        ]));

        $this->assertIdentical('Course.course', $state->sortBy);
        $this->assertIdentical('Course.title', $state->searchBy);
    }

    function testColumnsWithoutQueryFieldsAreNotUsed()
    {
        // Columns flagged with '!' are not part of the query.
        $state = $this->AjaxList->checkState($this->_state(['sortBy' => '!Custom.link']));

        $this->assertIdentical('Course.course', $state->sortBy);
    }

    function testMapFiltersKeepOnlyMapColumnsWithTheirValues()
    {
        // Course.title is a column of the list, but not a map column.
        $state = $this->AjaxList->checkState($this->_state([
            'mapFilterSelections' => (object) [
                'Course.record_status' => 'I',
                'Course.title'         => 'APSC',
                'Course.id'            => '1',
            ],
        ]));
        $this->assertIdentical(['Course.record_status' => 'I'], (array) $state->mapFilterSelections);

        foreach (['X', ['A'], (object) ['value' => 'A']] as $value) {
            $state = $this->AjaxList->checkState($this->_state([
                'mapFilterSelections' => (object) ['Course.record_status' => $value],
            ]));
            $this->assertIdentical([], (array) $state->mapFilterSelections);
        }
    }

    function testJoinFiltersKeepOnlyTheirListedValues()
    {
        foreach (['7', ['5'], (object) ['value' => '5']] as $value) {
            $state = $this->AjaxList->checkState($this->_state([
                'joinFilterSelections' => (object) ['Creator_id' => $value],
            ]));
            $this->assertIdentical([], (array) $state->joinFilterSelections);
        }

        $state = $this->AjaxList->checkState($this->_state([
            'joinFilterSelections' => (object) ['Course.id' => '5'],
        ]));
        $this->assertIdentical([], (array) $state->joinFilterSelections);
    }

    function testPageSizeAndPageAreWholeNumbers()
    {
        $state = $this->AjaxList->checkState($this->_state(['pageSize' => '50 rows', 'pageShown' => '-3']));

        $this->assertIdentical(20, $state->pageSize);
        $this->assertIdentical(1, $state->pageShown);
    }

    function testPageSizeAndPageHaveALimit()
    {
        $state = $this->AjaxList->checkState($this->_state(['pageSize' => 1000000, 'pageShown' => 1000000000]));

        $this->assertIdentical(AjaxListComponent::MAX_PAGE_SIZE, $state->pageSize);
        $this->assertIdentical(AjaxListComponent::MAX_PAGE, $state->pageShown);
    }

    function testValuesThatAreNotStringsUseTheDefaults()
    {
        $state = $this->AjaxList->checkState($this->_state([
            'sortBy'      => ['Course.title'],
            'searchBy'    => ['Course.course'],
            'searchValue' => ['APSC'],
        ]));

        $this->assertIdentical('Course.course', $state->sortBy);
        $this->assertIdentical('Course.title', $state->searchBy);
        $this->assertIdentical('', $state->searchValue);
    }

    function testMissingStateValuesGetDefaults()
    {
        foreach ([(object) [], [1], 'state', null] as $sent) {
            $state = $this->AjaxList->checkState($sent);

            $this->assertIdentical('Course.course', $state->sortBy);
            $this->assertIdentical(true, $state->sortAsc);
            $this->assertIdentical('Course.title', $state->searchBy);
            $this->assertIdentical('', $state->searchValue);
            $this->assertIdentical(20, $state->pageSize);
            $this->assertIdentical(1, $state->pageShown);
            $this->assertIdentical([], (array) $state->mapFilterSelections);
        }
    }
}

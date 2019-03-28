<?php

/**
 * TreeBuilderComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class TreeBuilderComponent extends CakeObject
{

    public $tree;
    public $FacultyAco;
    public $name = 'TreeBuilder';
    public $actsAs = array('Tree');

    /**
     * __construct
     *
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        $this->Sanitize = new Sanitize;
        $this->FacultyAco = ClassRegistry::init('FacultyAco');
    }


    /**
     * addChildNode
     *
     * @param bool $parent_id parent id
     * @param bool $faculty   faculty
     *
     * @access public
     * @return void
     */
    function addChildNode($parent_id=null, $faculty=null)
    {
        $data['parent_id'] = $parent_id;
        $data['faculty'] = $faculty;
        if ($this->FacultyAco->save($data)) {
            return true;
        } else {
            throw new Exception("Invalude parent_id");
        }
    }


    /**
     * modifyNode
     *
     * @param bool $nodeId     node id
     * @param bool $newFaculty new faculty
     *
     * @access public
     * @return void
     */
    function modifyNode($nodeId=null, $newFaculty=null)
    {
        $this->FacultyAco->id = $nodeId;
        if ($this->FacultyAco->save(array('faculty' => $newFaculty))) {
            return true;
        } else {
            throw new Exception("Invalide parent_id");
        }
    }
}

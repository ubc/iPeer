<?php
/////// CWL LOGIN //////////

require_once 'vendor/autoload.php'; // Load OneLogin SAML2


class HomeUBCSamlLogoutController extends AppController
{
    /**
     * This controller does not use a model
     *
     * @public $uses
     */
    public $uses =  array( 'Group', 'GroupEvent',
        'User', 'UserCourse', 'Event', 'EvaluationSubmission',
        'Course', 'Role', 'UserEnrol', 'Rubric', 'Penalty');

    /**
     * __construct
     *
     * @access protected
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * beforeFilter
     *
     * @access public
     * @return void
     */
    function beforeFilter()
    {

        $this->_afterLogout();

        $this->redirect('/public/saml/logout.php');
       

        exit;

    }


    /**
     * index
     *
     *
     * @access public
     * @return void
     */
    function index()
    {

        $this->log("HOME:UBC SAML LOGOUT Controller:");

    }
}

<?php
App::import('Vendor', 'IMSGlobal\\LTI', array('file'=>'imsglobal'.DS.'lti-1p3-tool'.DS.'src'.DS.'lti'.DS.'lti.php'));
App::import('Lib', 'LTI13Database');
App::import('Model', 'LTI13');

use IMSGlobal\LTI\LTI_OIDC_Login;
use IMSGlobal\LTI\LTI_Message_Launch;

/**
 * LTI13Controller
 *
 * @uses AppController
 * @package   CTLT.iPeer
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LTI13Controller extends AppController
{
    protected $ltidb, $model;

    public function __construct(LTI13Database $ltidb, LTI13 $model)
    {
        $this->ltidb = $ltidb;
        $this->model = $model;
    }

    public function login()
    {
        $url = Router::url('/launch');
        return LTI_OIDC_Login::new($this->ltidb)->do_oidc_login_redirect($url)->do_redirect();
    }

    public function launch()
    {
        $launch = LTI_Message_Launch::new($this->ltidb)->validate();
        $data = $this->model->get_launch_data($launch->get_launch_id(), $this->ltidb, $request);
        $this->set('data', $data);
        $this->render('launch');
    }
}

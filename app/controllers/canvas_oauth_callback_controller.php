<?php
App::import('Component', 'CanvasApi');

/**
 * Centralised Canvas OAuth callback endpoint.
 *
 * Canvas redirects here after the user authorises iPeer. This controller
 * exchanges the code for tokens and then sends the user back to wherever
 * they came from (encoded in the OAuth state parameter).
 *
 * Not added to the ACL - parent::beforeFilter() is intentionally skipped so
 * the permission-check logic in AppController never runs. Guard's startup()
 * still fires and redirects unauthenticated users to the login page.
 */
class CanvasOauthCallbackController extends AppController
{
    public $uses = array();

    public function beforeFilter()
    {
        $this->autoRender = false;
        AppController::logControllerAction($this);
    }

    public function callback()
    {
        if (isset($this->params['url']['error'])) {
            $error     = $this->params['url']['error'];
            $errorDesc = isset($this->params['url']['error_description']) ? $this->params['url']['error_description'] : '';
            CakeLog::write('warning', 'Canvas OAuth callback error for user ' . $this->Auth->user('id')
                . ' — error=' . $error . ', error_description=' . $errorDesc
                . ', url=' . (isset($this->params['url']['url']) ? $this->params['url']['url'] : '(none)'));
            if ($error === 'access_denied') {
                $this->Session->setFlash(__('Canvas authorization was cancelled. You need to authorize iPeer '
                                          .'in order to use Canvas functionalities.', true));
            } else {
                $this->Session->setFlash('Canvas authorization failed. Please try again or contact an administrator.');
            }
            $this->redirect('/');
            return;
        }
        $canvasApi   = new CanvasApiComponent($this->Auth->user('id'));
        $destination = $canvasApi->handleOauthCallback($this);
        if (!$destination) {
            CakeLog::write('warning', 'Canvas OAuth callback failed for user ' . $this->Auth->user('id') . ' - redirecting to /');
        }
        $this->redirect($destination ?: '/');
    }
}

<?php
require_once('BasePage.php');

/**
 * LoginPage
 *
 * @uses BasePage
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author Compass <pan.luo@ubc.ca>
 */
class LoginPage extends BasePage
{
    public $elements = array(
        'id' => array(
            'GuardUsername' => 'eUsername',
            'GuardPassword' => 'ePassword',
        ),
        'css selector' => array(
            'input[type=submit]' => 'submit',
        ),
    );

    public function __construct($session)
    {
        $this->session = $session;
        if ("iPeer - Guard" != $this->session->title()) {
            throw new RuntimeException('Not a login page! current page is '.$this->session->title());
        }
    }

    public function login($username, $password)
    {
       $this->eUsername->sendKeys($username);
       $this->ePassword->sendKeys($password);
       $this->submit->click();

       return PageFactory::initElements($this->session, 'Home');
    }
}

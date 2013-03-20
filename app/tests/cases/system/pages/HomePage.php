<?php
require_once('BasePage.php');

/**
 * HomePage
 *
 * @uses BasePage
 * @copyright Copyright (c) 2013 All rights reserved.
 * @author Compass <pan.luo@ubc.ca>
 */
class HomePage extends BasePage
{
    public function __construct($session)
    {
        $this->session = $session;
        if ("iPeer - Home" != $this->session->title()) {
            throw new RuntimeException('Not a home page! current page is '.$this->session->title());
        }
    }
}


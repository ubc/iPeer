<?php
/*
 * EmailComponent for CakePHP
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.5.1797
 * @license		OPPL
 *
 */
class EmailComponent extends Object
{
    var $thtml;
	var $headers = null;
    var $to = null;
    var $from = null;
    var $subject = null;
    var $cc = null;
    var $bcc = null;
    var $controller;


    function message()
    {
        ob_start();
        $this->controller->render($this->thtml);
        $mail = ob_get_clean();
        return $mail; 
    }

    function send()
    {
		
        $headers  = $this->headers
			."Content-Transfer-Encoding: quoted-printable\n"
			."From: $this->from\n"
			."Return-Path: $this->from\n"
			."CC:$this->cc\n"
			."BCC:$this->bcc\n";

       	$success = mail($this->to, $this->subject, $this->message(), $headers);
       	return $success;
    }

}

?>
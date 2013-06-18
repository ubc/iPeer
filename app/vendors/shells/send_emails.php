<?php
App::import('Component', 'Email');
require_once (CORE_PATH.'cake/libs/controller/controller.php');

/**
 * CreateAclShell
 *
 * @uses Shell
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class SendEmailsShell extends Shell
{
    public $uses = array('User', 'EmailSchedule', 'SysParameter', 'EmailMerge');
    const EMAIL_TASK_LOCK = 'tmp/email_task_lock';
    /**
     * main
     *
     *
     * @access public
     * @return void
     */
    function main()
    {
        // check if the LOCK is already there
        if (file_exists(APP.SendEmailsShell::EMAIL_TASK_LOCK)) {
            echo "Send email task is still running. exiting...\n";
            return;
        }

        if (!touch(APP.SendEmailsShell::EMAIL_TASK_LOCK)) {
            echo "Failed to create the lock file: ".APP.SendEmailsShell::EMAIL_TASK_LOCK."! Check permissions.\n";
            return;
        }

        $controller = new Controller();
        $this->Email = new EmailComponent(null);
        $this->Email->initialize($controller);

        /**
         * Goes through scheduled emails that have not yet been sent,
         * send them if they're due and mark them them as sent.
         */
        $emails = $this->EmailSchedule->getEmailsToSend();
        $defaultFrom = $this->SysParameter->get('display.contact_info');

        foreach ($emails as $e) {
            $successCount = 0;
            $failedCount = 0;
            $e = $e['EmailSchedule'];
            echo "Processing email schedule id ".$e['id']."\n";
            $from_id = $e['from'];
            $from = $this->User->getEmails($from_id);
            $from = (isset($from[$from_id]) && empty($from[$from_id])) ? $defaultFrom : $from[$from_id];

            $emailList = $this->User->getEmails(explode(';', $e['to']));
            foreach ($emailList as $to_id => $to) {
                if (empty($to)) {
                    // skip the empty ones
                    continue;
                }
                $subject = $e['subject'];
                $content = $this->doMerge($e['content'], EmailMerge::MERGE_START, EmailMerge::MERGE_END, $to_id);
                if ($this->sendEmail($content, $subject, $from, $to)) {
                    $successCount++;
                } else {
                    echo "Failed to send email to ".$to."\n";
                    $failedCount++;
                }

            }
            $tmp = array('id' => $e['id'], 'sent' => '1');
            $this->EmailSchedule->save($tmp);
            echo "Message Sent: Success = ".$successCount.", Failed = ".$failedCount."\n";
        }

        if (!unlink(APP.SendEmailsShell::EMAIL_TASK_LOCK)) {
            echo "Failed to delete the email task lock file. Check the permission!\n";
        }
    }

    /**
     * Given a user id, get the email address associated with that id, if any.
     *
     * @param int $id - the user id
     *
     * @return The user's email address, if it exists, empty string if not
     */
    private function getEmailAddress($id) {
        return $this->User->field('email', array('User.id' => $id));
    }

    /**
     * Do merge
     *
     * @param string $string  string with merge fields
     * @param int    $start   start of merge field
     * @param int    $end     end of merge field
     * @param int    $user_id user id
     *
     * @return merged string
     */
    function doMerge($string, $start, $end, $user_id = null)
    {
        //Return array $matches that contains all tags
        preg_match_all('/'.$start.'(.*?)'.$end.'/', $string, $matches, PREG_OFFSET_CAPTURE);
        $patterns = array();
        $replacements = array();
        $patterns = $matches[0];
        foreach ($matches[0] as $key => $match) {
            $patterns[$key] = '/'.$match[0].'/';

            $table = $this->EmailMerge->getFieldAndTableNameByValue($match[0]);
            $table_name = $table['table_name'];
            $field_name = $table['field_name'];
            $this->$table_name->recursive = -1;
            $value = $this->$table_name->find('first', array(
                'conditions' => array($table_name.'.id' => $user_id),
                'fields' => $field_name
            ));

            $replacements[$key] = $value[$table_name][$field_name];
        }
        return preg_replace($patterns, $replacements, $string);
    }

    /**
     * _sendEmail send email wrapper
     *
     * @param mixed $content      email body
     * @param mixed $subject      email subject
     * @param mixed $from         sender address
     * @param mixed $toAddress    receiver address
     * @param bool  $templateName email template name
     * @param bool  $ccAddress    cc field
     * @param bool  $bcc          bcc field
     *
     * @access protected
     * @return void
     */
    protected function sendEmail($content, $subject, $from, $toAddress, $templateName = 'default', $ccAddress = array(), $bcc= array())
    {
        $this->Email->reset();

        $smtpHost = $this->SysParameter->get('email.host');
        if (!empty($smtpHost)) {
            $smtp['port'] = $this->SysParameter->get('email.port');
            $smtp['host'] = $this->SysParameter->get('email.host');
            $smtp['username'] = $this->SysParameter->get('email.username');
            $smtp['password'] = $this->SysParameter->get('email.password');
            $smtp['timeout'] = 30;
            $this->Email->delivery = 'smtp';
            $this->Email->smtpOptions = $smtp;
        } else {
            $this->Email->delivery = 'mail';
        }

        $this->Email->to = $toAddress;
        $this->Email->cc = $ccAddress;
        $this->Email->bcc = $bcc;
        $this->Email->subject = $subject;
        $this->Email->from = $from;
        $this->Email->template = $templateName;
        $this->Email->sendAs = 'both';

        return $this->Email->send($content);
    }
}

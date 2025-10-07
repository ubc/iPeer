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
    public $uses = array('User', 'EmailSchedule', 'SysParameter', 'EmailMerge', 'Event',
        'Group', 'GroupsMembers', 'EvaluationSubmission', 'GroupEvent', 'Course', 'Penalty',
        'EmailTemplate');
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
        $timezone = $this->SysParameter->findByParameterCode('system.timezone');
        // default to UTC if no timezone is set
        if (!(empty($timezone) || empty($timezone['SysParameter']['parameter_value']))) {
            $timezone = $timezone['SysParameter']['parameter_value'];
        } else if (ini_get('date.timezone')) {
            $timezone = ini_get('date.timezone');
        } else {
            $timezone = 'UTC';
        }
        date_default_timezone_set($timezone);
        $emails = $this->EmailSchedule->getEmailsToSend();
        $defaultFrom = $this->SysParameter->get('display.contact_info');

        foreach ($emails as $e) {
            $successCount = 0;
            $failedCount = 0;
            $e = $e['EmailSchedule'];
            echo "Processing email schedule id ".$e['id']."\n";
            $from_id = $e['from'];
			$event_id = $e['event_id'];

            $from = $this->User->getEmails($from_id);
            $from = (isset($from[$from_id]) && empty($from[$from_id])) ? $defaultFrom : $from[$from_id];

            //Returns the modified emaillist if the list contains the 'save_reminder' param, else returns $e['to']
			$filter_email_list = $this->reminderFilter($event_id, $e['to'], $e['id'], $e['date']);
			// storing common data for all emails in email_list
			$commonData = array();
			if (isset($e['event_id'])) {
			    $event = $this->Event->getEventById($e['event_id']);
			    $commonData['Event'] = $event['Event'];
			    // fill in email template subject/content for event reminders
			    $emailTemplate = $this->EmailTemplate->findById($e['content']);
			    $e['subject'] = $emailTemplate['EmailTemplate']['subject'];
			    $e['content'] = $emailTemplate['EmailTemplate']['content'];
			}
			if (isset($e['course_id'])) {
			    $course = $this->Course->getCourseById($e['course_id']);
			    $commonData['Course'] = $course['Course'];
			}

            //Return array $matches that contains all tags
            preg_match_all('/'.EmailMerge::MERGE_START.'(.*?)'.EmailMerge::MERGE_END.'/', $e['content'], $matches, PREG_OFFSET_CAPTURE);
            $patterns = array();
            $replacements = array();
            $tables = array();
            foreach ($matches[0] as $key => $match) {
                $table = $this->EmailMerge->getFieldAndTableNameByValue($match[0]);

                if (isset($commonData[$table['table_name']])) {
                    // if in commonData, grab the field's value
                    $patterns[$key] = '/'.$match[0].'/';
                    $value = $commonData[$table['table_name']][$table['field_name']];
                    $replacements[$key] = strtotime($value) ? date('l, F j, Y g:i a',strtotime($value)) : $value;
                } else {
                    // if not in commonData, save table attributes for merging later
                    if (!isset($tables[$table['table_name']])) {
                        $tables[$table['table_name']] = array();
                    }
                    // format: array[table_name][field_name] = pattern
                    $tables[$table['table_name']][$table['field_name']] = '/'.$match[0].'/';
                }
            }
	    // email content after merging common data, common data is non-user
	    // specific data such as course name and such. User specific data
	    // is merged in each individual email in the following loop using
	    // doMerge.
            $contentWithCommonData = preg_replace($patterns, $replacements, $e['content']);

            $emailList = $this->User->getEmails(explode(';', $filter_email_list));
            foreach ($emailList as $to_id => $to) {
                if (empty($to)) {
                    // skip the empty ones
                    continue;
                }

                $subject = $e['subject'];
                $content = $contentWithCommonData;
                if (!empty($tables)) {
                    // merge data not in common data
                    $content = $this->doMerge($contentWithCommonData, EmailMerge::MERGE_START, EmailMerge::MERGE_END, $tables, $to_id);
                }
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

   /*
    * If the email is an event reminder, returns the list of users that have not submitted
    *
    * @param mixed $event_id
    * @param mixed $to
    * @param mixed $email_id
    * @param mixed $date
    *
    */
    private function reminderFilter($event_id, $to, $email_id, $date)
    {
        $to = explode(';', $to);
        if (isset($event_id) && $to[0]=='save_reminder') {
            //If the date on the reminder is past the due date, delete the corresponding reminder from the table
            $event = $this->Event->findById($event_id);
            if (strtotime($event['Event']['due_date']) < strtotime($date)) {
                //Delete the corresponding row and return an empty to[] list
                $this->EmailSchedule->delete($email_id, false);
                return array();
            } else {
                $submissions = $this->EvaluationSubmission->getEvalSubmissionsByEventId($event_id);
                $submitter_list = Set::extract('/EvaluationSubmission/submitter_id', $submissions);

                if ($event['Event']['event_template_type_id'] == 3) {
                    $members = $this->User->getEnrolledStudents($event['Event']['course_id']);
                    $members = Set::extract('/User/id', $members);
                } else {
                    $groups = $this->GroupEvent->findAllByEventId($event_id);
                    $groupIds = Set::extract('/GroupEvent/group_id', $groups);
                    $members = $this->GroupsMembers->findAllByGroupId($groupIds);
                    $members = Set::extract('/GroupsMembers/user_id', $members);
                }
                $sendTo = array_diff($members, $submitter_list);
                $sendTo = implode(';', $sendTo);

                //Save the new array in the Database table email_schedules
                $data = array('id' => $email_id, 'to'=> $sendTo);
                $this->EmailSchedule->save($data);

                return $sendTo;
            }
        } else { //Database entry does not correspond to reminders and so, return list as is.
            return implode(';', $to);
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
     * @param array  $tables  list of fields needed to be merged
     * @param int    $user_id user id
     *
     * @return merged string
     */
    function doMerge($string, $start, $end, $tables, $user_id)
    {
        foreach ($tables as $name => $attributes) {
            $fields = array_keys($attributes);
            $patterns = array_values($attributes);
            $replacements = array();
            $value = $this->$name->find('first', array(
                'conditions' => array($name.'.id' => $user_id),
                'fields' => $fields
            ));
            foreach ($fields as $key => $field) {
                $replacements[$key] = $value[$name][$field];
            }
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

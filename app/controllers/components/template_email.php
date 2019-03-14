<?php
App::import('Component', 'Email');
App::import('Model', 'EmailMerge');
/**
 * TemplateEmailComponent
 *
 * @uses Object
 * @package   CTLT.iPeer
 * @author    Pan Luo <pan.luo@ubc.ca>
 * @copyright 2012 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class TemplateEmailComponent extends EmailComponent
{
    public $contentTemplate;
    public $subjectTemplate;
    public $name = 'TemplateEmail';
    public $failed = array();
    public $controller;
    public $SysParameter = null;

    /**
     * startup
     *
     * @param mixed &$controller
     *
     * @access public
     * @return void
     */
    public function startup(&$controller)
    {
        $this->controller = $controller;
        $this->SysParameter = ClassRegistry::init('SysParameter');
    }

    /**
     * initParameters
     *
     * @access public
     * @return void
     */
    public function initParameters()
    {
        $this->Email->reset();

        $smtpHost = $this->SysParameter->get('email.host');
        if (!empty($smtpHost)) {
            $smtp['port'] = $this->SysParameter->get('email.port');
            $smtp['host'] = $this->SysParameter->get('email.host');
            $smtp['username'] = $this->SysParameter->get('email.username');
            $smtp['password'] = $this->SysParameter->get('email.password');
            $smtp['timeout'] = 30;
            $this->delivery = 'smtp';
            $this->smtpOptions = $smtp;
        } else {
            $this->delivery = 'mail';
        }
    }

    /**
     * hasMergeField test if it is a merge template
     *
     * @param mixed $string
     *
     * @access public
     * @return void
     */
    public function hasMergeField($string)
    {
        return (strpos($string, EmailMerge::MERGE_START) !== false);
    }

    /**
     * send
     *
     * @param array  $emails       email addresses with user id as index
     * @param string $templateName email template name
     * @param string $from         from address
     *
     * @access public
     * @return void
     */
    public function sendByTemplate($emails, $templateName, $from = null)
    {
        $templateObj = ClassRegistry::init('EmailTemplate');
        $template = $templateObj->findByName($templateName);
        $this->subjectTemplate = $template['EmailTemplate']['subject'];
        $this->contentTemplate = $template['EmailTemplate']['content'];

        // get from address
        if ($from == null) {
            $this->from = $this->SysParameter->get('display.contact_info');
        } else {
            $this->from = $from;
        }

        if (!$this->hasMergeField($this->contentTemplate.$this->subjectTemplate)) {
            // no merge field, send directly
            $this->initParameters();
            $this->to = implode(',', array_values($emails));
            $this->subject = $this->subjectTemplate;

            $result = parent::send($this->contentTemplate);
            if (!$result) {
                $this->log('Sending email(s) failed. '.$this->smtpError);
            }

            return $result;
        } else {
            // do merge
            $result = true;
            $subjects = $this->merge(array_keys($emails), $this->subjectTemplate);
            $contents = $this->merge(array_keys($emails), $this->contentTemplate);

            foreach ($emails as $userId => $email) {
                $this->initParameters();
                $this->to = $email;
                $this->subject = $subjects[$userId];
                if (!parent::send($contents[$userId])) {
                    $this->log('Sending email(s) failed. '.$this->smtpError);
                    $result = false;
                }
                $this->reset();
            }

            return $result;
        }
    }

    /**
     * merge
     *
     * @param mixed $userIds user id array
     * @param mixed $string  string to be merged
     *
     * @access public
     * @return void
     */
    public function merge($userIds, $string)
    {
        if (!$this->hasMergeField($string)) {
            return array_fill_keys($userIds, $string);
        }

        $result = array();
        $mergeObj = ClassRegistry::init('EmailMerge');
        $merges = $mergeObj->getAllMergesWithKey();
        $userObj = ClassRegistry::init('User');
        $users = $userObj->find('all', array('conditions' => array('id' => $userIds)));

        //Return array $matches that contains all tags
        preg_match_all('/'.EmailMerge::MERGE_START.'(.*?)'.EmailMerge::MERGE_END.'/', $string, $matches, PREG_OFFSET_CAPTURE);
        $patterns = array();
        $replacements = array();

        foreach ($users as $user) {
            foreach ($matches[0] as $key => $match) {
                $patterns[$key] = '/'.$match[0].'/';
                $tableName = $merges[$match[0]]['table_name'];
                $fieldName = $merges[$match[0]]['field_name'];
                $replacements[$key] = $user[$tableName][$fieldName];
            }
            $result[$user['User']['id']] = preg_replace($patterns, $replacements, $string);
        }

        return $result;
    }
}

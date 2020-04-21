<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Lib', 'LTI13Database', array('file'=>'lti13'.DS.'LTI13Database.php'));

use App\LTI13\LTI13Database;
use Firebase\JWT\JWT;
use IMSGlobal\LTI\LTI_Exception;
use IMSGlobal\LTI\LTI_Message_Launch;

/**
 * LTI 1.3 Model
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 * @link      https://www.imsglobal.org/spec/security/v1p0/#fig_oidcflow
 */
class Lti13 extends AppModel
{
    public $useTable = false;
    public $db, $User, $Course, $Role, $LtiToolRegistration;
    public $ltiCourse;
    public $jwtBody = array();
    public $ipeerRoster = array();
    public $ltiRoster = array();
    public $rosterUpdatesLog = array();
    public $log_path = ROOT.'/app/tmp/logs/lti13';

    public function __construct()
    {
        $this->User = ClassRegistry::init('User');
        $this->Course = ClassRegistry::init('Course');
        $this->Role = ClassRegistry::init('Role');
        $this->LtiToolRegistration = ClassRegistry::init('LtiToolRegistration');
        $issuers = $this->LtiToolRegistration->findIssuers();
        $this->db = new LTI13Database($issuers);
    }

    /**
     * Initialize LTI_Message_Launch object and validate its data.
     *
     * @return string
     */
    public function launch()
    {
        $launch = LTI_Message_Launch::new($this->db);
        $launch->validate();
        return $launch;
    }

    /**
     * Get LTI_Message_Launch object from Cache.
     *
     * @return LTI_Message_Launch
     */
    public function launchFromCache()
    {
        $launchId = $this->getLaunchIdFromCache();
        return LTI_Message_Launch::from_cache($launchId, $this->db);
    }

    /**
     * Get launch ID from LTI cache
     *
     * @see IMSGlobal\LTI\Cache
     * @return string
     */
    public function getLaunchIdFromCache()
    {
        if (!$json = file_get_contents(sys_get_temp_dir() . '/lti_cache.txt')) {
            throw new LTI_Exception("LTI cache is empty.");
            return;
        }

        $assoc = json_decode($json, true);
        if (!$keys = preg_grep("/^lti1p3_launch_/", array_keys($assoc))) {
            throw new LTI_Exception("LTI launch ID not found in LTI cache.");
            return;
        }

        $keys = array_values($keys);
        if (count($keys) > 1) {
            throw new LTI_Exception("More than one LTI launch ID found in LTI cache.");
            return;
        }

        return $keys[0];
    }

    /**
     * Get the LTI_Message_Launch data for logging.
     *
     * @return string
     */
    public function getData()
    {
        $launch = $this->launchFromCache();
        $jwtBody = $launch->get_launch_data();
        return array(
            'launch_id'    => $launch->get_launch_id(),
            'message_type' => $jwtBody["https://purl.imsglobal.org/spec/lti/claim/message_type"],
            '$_POST'       => $_POST,
            'jwt_header'   => json_decode($this->jwtHeader(), 448),
            'jwt_body'     => $jwtBody,
            'nrps_members' => $this->getNrpsMembers(),
        );
    }

    /**
     * Get course id from cached launch data.
     *
     * @return string
     */
    public function getCourseId()
    {
        $launch = $this->launchFromCache();
        $jwtBody = $launch->get_launch_data();
        $label = $jwtBody["https://purl.imsglobal.org/spec/lti/claim/context"]["label"];
        if (!$data = $this->findCourseByLabel($label)) {
            throw new LTI_Exception("LTI course label not found.");
            return;
        }
        return $data['Course']['id'];
    }

    /**
     * Get JWT header.
     *
     * @return string
     */
    private function jwtHeader()
    {
        if ($jwt = @$_REQUEST['id_token']) {
            return JWT::urlsafeB64Decode(explode('.', $jwt)[0]);
        }
    }

    /**
     * Get all members of the LTI_Names_Roles_Provisioning_Service instance.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L48
     * Obtained through Resource Link, not Deep Link.
     * @return array
     */
    public function getNrpsMembers()
    {
        $launch = $this->launchFromCache();
        if ($launch->has_nrps()) {
            $nrps = $launch->get_nrps();
            return $nrps->get_members();
        }
    }

    /**
     * Update course roster from LTI data from current LTI launch.
     *
     * @param string $courseId
     */
    public function updateRoster($courseId)
    {
        // Get JWT body after LTI launch
        $launch = $this->launchFromCache();
        $this->jwtBody = $launch->get_launch_data();

        // Get course [id, label, title] from LTI launch's JWT body
        $this->ltiCourse = $this->getLtiCourseData();

        // Check $courseId against course in database
        $data = $this->findCourseByLabel($this->ltiCourse['label']);
        if ($data['Course']['id'] != $courseId) {
            $message = sprintf(
                "Wrong course for this roster update.\n
                Canvas is currently connected to `%s`",
                $this->ltiCourse['label']
            );
            throw new LTI_Exception($message);
            return;
        }

        // Call LTI Resource Link to get LTI roster data
        if ($this->ltiRoster = $this->getNrpsMembers()) {
            // Update or create iPeer course roster from the LTI data
            $this->saveCourseRoster();
        }
    }

    /**
     * Check if course data is available in JWT body and get `label` and `title` from it.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L55
     * @return array|null
     */
    public function getLtiCourseData()
    {
        $key = "https://purl.imsglobal.org/spec/lti/claim/context";
        if (!$context = @$this->jwtBody[$key]) {
            throw new LTI_Exception(sprintf("Missing '%s'", $key));
            return;
        }

        $keys = array('id', 'label', 'title');
        foreach ($keys as $key) {
            if (!array_key_exists($key, $context)) {
                throw new LTI_Exception(sprintf("Missing 'context %s'", $key));
                return;
            }
        }

        return array_intersect_key($context, array_flip($keys));
    }

    /**
     * Save course roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L64
     */
    public function saveCourseRoster()
    {
        extract($this->ltiCourse); // => $id, $label, $title

        if ($data = $this->findCourseByLabel($label)) {
            if (empty($data['Course']['canvas_id'])) {
                $data['Course']['canvas_id'] = $id;
                $this->Course->save($data);
            }
            $this->updateCourseRoster($data);
        } else {
            $data = array(
                'canvas_id' => $id,
                'course' => $label,
                'title' => $title,
                'record_status' => Course::STATUS_ACTIVE,
            );
            $this->createCourseRoster($data);
        }
    }

    /**
     * Find course by label in database.
     *
     * @param string $label
     * @return array
     */
    public function findCourseByLabel($label)
    {
        $conditions = array('Course.course' => $label);
        return $this->Course->find('first', compact('conditions'));
    }

    /**
     * Update course roster in database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L88
     * @param array $data
     */
    public function updateCourseRoster(array $data)
    {
        if ($courseId = @$data['Course']['id']) {
            $this->ipeerRoster = $this->User->getEnrolledStudents($courseId);
            $this->rosterUpdatesLog['removeUsersFoundInBothRosters'] = $this->removeUsersFoundInBothRosters();
            $this->rosterUpdatesLog['removeRemainingUsersFromIpeerRoster'] = $this->removeRemainingUsersFromIpeerRoster($courseId);
            $this->rosterUpdatesLog['addRemainingUsersInIpeerRoster'] = $this->addRemainingUsersInIpeerRoster($courseId);
        }
    }

    /**
     * Create course roster in database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L70
     * @param array $data
     */
    public function createCourseRoster(array $data)
    {
        if ($this->Course->save($data)) {
            $this->rosterUpdatesLog['addUsersInIpeerRoster'] = $this->addUsersInIpeerRoster($this->Course->id);
        }
    }

    /**
     * Remove users in both rosters.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L92
     * @return array
     */
    public function removeUsersFoundInBothRosters()
    {
        $log = array();
        foreach ($this->ltiRoster as $ltiKey => $ltiData) {
            foreach ($this->ipeerRoster as $ipeerKey => $ipeerData) {
                if ($userLtiId = @$ipeerData['User']['lti_id']) {
                    if ($ltiData['user_id'] == $userLtiId) {
                        $log []= $ipeerData['User'];
                        unset($this->ltiRoster[$ltiKey], $this->ipeerRoster[$ipeerKey]);
                        continue;
                    }
                }
            }
        }
        return $log;
    }

    /**
     * Remove remaining users from iPeer roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L102
     * @param int $courseId
     * @return array
     */
    public function removeRemainingUsersFromIpeerRoster($courseId)
    {
        $log = array();
        foreach ($this->ipeerRoster as $data) {
            if ($userId = @$data['User']['id']) {
                $log []= $data['User'];
                $this->User->removeStudent($userId, $courseId);
            }
        }
        return $log;
    }

    /**
     * Add remaining users in iPeer roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L107
     * @param int $courseId
     * @return array
     */
    public function addRemainingUsersInIpeerRoster($courseId)
    {
        $log = array();
        foreach ($this->ltiRoster as $data) {
            if (!$this->isInstructor($data['roles'])) {
                $log []= $data;
                $this->addUser($data, $courseId);
            }
        }
        return $log;
    }

    /**
     * Add users in iPeer roster.
     *
     * @param int $courseId
     * @return array
     */
    public function addUsersInIpeerRoster($courseId)
    {
        $log = array();
        foreach ($this->ltiRoster as $data) {
            $log []= $data;
            $this->addUser($data, $courseId);
        }
        return $log;
    }

    /**
     * Add user to database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L137
     * @param array $data
     * @param int $courseId
     */
    public function addUser(array $data, $courseId)
    {
        $username = $this->getUsername($data);
        $ltiId = $data['user_id'];
        $isInstructor = $this->isInstructor($data['roles']);

        // If user exists, save existing user to course
        if ($userData = $this->User->getByUsername($username)) {
            $this->saveExistingUserToCourse($userData, $courseId, $isInstructor, $ltiId);
        }

        // If user doesn't exist, save new user to course
        $userData = array(
            'User' => array(
                'username' => $username,
                'first_name' => $data['given_name'],
                'last_name' => $data['family_name'],
                'email' => $data['email'],
                'send_email_notification' => false,
                'lti_id' => $ltiId,
                'created' => date('Y-m-d H:i:s'),
            ),
            'Role' => array(
                'RolesUser' => $this->getUserType($isInstructor),
            ),
        );
        $this->saveNewUserToCourse($userData, $courseId, $isInstructor);
    }

    /**
     * Save existing user to course in database.
     *
     * @param array $userData
     * @param int $courseId
     * @param bool $isInstructor
     * @param string $ltiId
     */
    public function saveExistingUserToCourse(array $userData, $courseId, $isInstructor, $ltiId)
    {
        if ($userId = @$userData['User']['id']) {
            if ($this->addUserToCourse($userId, $courseId, $isInstructor)) {
                // User might not have an lti_id, so save one
                $userData['User']['lti_id'] = $ltiId;
                $this->User->save($userData);
            }
        }
    }

    /**
     * Save new user to course in database.
     *
     * @param array $userData
     * @param int $courseId
     * @param bool $isInstructor
     */
    public function saveNewUserToCourse(array $userData, $courseId, $isInstructor)
    {
        $this->User->create();
        if ($this->User->save($userData)) {
            $this->addUserToCourse($this->User->id, $courseId, $isInstructor);
        }
    }

    /**
     * Add user to course in database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L194
     * @param string $userId
     * @param int $courseId
     * @param bool $isInstructor
     */
    public function addUserToCourse($userId, $courseId, $isInstructor)
    {
        if ($isInstructor) {
            if ($roleId = $this->Role->field('id', array('name' => 'instructor'))) {
                $this->User->registerRole($userId, $roleId);
                $this->Course->addInstructor($courseId, $userId);
            }
        } else {
            if ($roleId = $this->Role->field('id', array('name' => 'student'))) {
                $this->User->registerRole($userId, $roleId);
                $data = array(
                    'UserEnrol' => array(
                        'course_id' => $courseId,
                        'user_id' => $userId,
                        'record_status' => 'A',
                    ),
                );
                if ($this->User->UserEnrol->save($data)) {
                    $this->User->UserEnrol->id = null;
                }
            }
        }
    }

    /**
     * Find user by `users.lti_id` in database.
     *
     * @return array
     */
    public function findUserByLtiUserId()
    {
        $conditions = array('User.lti_id' => $this->jwtBody["sub"]);
        return $this->User->find('first', compact('conditions'));
    }

    /**
     * Get deployment_id from JWT body.
     *
     * @return string
     */
    public function getDeploymentId()
    {
        $key = "https://purl.imsglobal.org/spec/lti/claim/deployment_id";
        if (!$deployment_id = @$this->jwtBody[$key]) {
            throw new LTI_Exception(sprintf("Missing '%s'", $key));
            return;
        }
        return $deployment_id;
    }

    /**
     * Check if provided role(s) is a LTI instructor.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L219
     * @param mixed $roles Array or string
     * @return bool
     */
    public function isInstructor($roles)
    {
        return (bool)preg_grep('/Instructor/i', (array)$roles);
    }

    /**
     * Get username from LTI data.
     *
     * @param array $data
     * @return string
     */
    public function getUsername(array $data)
    {
        $keys = array('given_name', 'family_name');
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                throw new LTI_Exception(sprintf("Missing '%s'", $key));
                return;
            }
        }
        return $data['given_name'].$data['family_name'];
    }

    /**
     * Get user type.
     *
     * @param bool $isInstructor
     * @return int
     */
    public function getUserType($isInstructor)
    {
        return $isInstructor ? $this->User->USER_TYPE_INSTRUCTOR : $this->User->USER_TYPE_STUDENT;
    }

    /**
     * Delete LTI 1.3 log files.
     */
    public function resetLogs()
    {
        array_map('unlink', glob($this->log_path.'/*.log'));
    }
}

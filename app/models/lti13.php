<?php
App::import('Lib', 'Lti13Bootstrap');
App::import('Lib', 'LTI13Database', array('file'=>'lti13'.DS.'LTI13Database.php'));

use App\LTI13\LTI13Database;
use IMSGlobal\LTI\LTI_Exception;

/**
 * LTI 1.3 Model
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @since     3.4.5
 * @author    Steven Marshall <steven.marshall@ubc.ca>
 * @copyright 2019 All rights reserved.
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class Lti13 extends AppModel
{
    public $useTable = false;
    public $db, $User, $Course, $Role;
    public $launchId, $jwtPayload, $ipeerRoster, $ltiRoster, $ltiCourse;

    public function __construct()
    {
        $this->db = new LTI13Database();
        $this->User = ClassRegistry::init('User');
        $this->Course = ClassRegistry::init('Course');
        $this->Role = ClassRegistry::init('Role');
    }

    /**
     * Encode the LTI13Database::$issuers array into JSON.
     *
     * @return string
     */
    public function getRegistrationJson()
    {
        return json_encode($this->db->get_issuers(), 448);
    }

    /**
     * Initialize LTI_Message_Launch object and validate its data.
     *
     * @return string
     */
    public function launch()
    {
        $launch = LTI_Message_Launch::new($this->db);
        try {
            $launch->validate();
        } catch (\Exception $e) {
            echo "Launch validation failed.";
        }
        $this->launchId = $launch->get_launch_id();
        return $this->launchId;
    }

    /**
     * Update course roster from LTI data from current LTI launch.
     */
    public function update()
    {
        // Get JWT payload after LTI launch
        $launch = LTI_Message_Launch::from_cache($this->launchId, $this->db);
        $this->jwtPayload = json_decode($launch->get_launch_data(), true);

        // Get course label and title from LTI launch's JWT payload
        $this->ltiCourse = $this->getLtiCourseData();

        // Call LTI Resource Link to get LTI roster data
        $this->ltiRoster = $this->getLtiRoster();

        // Update or create iPeer course roster from the LTI data
        $this->saveCourseRoster();
    }

    /**
     * Check if course data is available in JWT payload and get `label` and `title` from it.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L55
     * @return array|null
     */
    public function getLtiCourseData()
    {
        $key = 'https://purl.imsglobal.org/spec/lti/claim/context';
        if (! $context = @$this->jwtPayload[$key]) {
            throw new LTI_Exception(sprintf("Missing '%s'", $key));
            return;
        }
        $keys = array('label', 'title');
        foreach ($keys as $key) {
            if (!array_key_exists($key, $context)) {
                throw new LTI_Exception(sprintf("Missing 'context %s'", $key));
                return;
            }
        }
        return array_intersect_key($context, array_flip($keys));
    }

    /**
     * Get LTI course roster from API call.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L48
     * @return array
     */
    public function getLtiRoster()
    {

    }

    /**
     * Save course roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L64
     */
    public function saveCourseRoster()
    {
        extract($this->ltiCourse); // => $label, $title

        if ($data = $this->findCourseByLabel($label)) {
            $this->updateCourseRoster($data);
        } else {
            $data = array(
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
    public function updateCourseRoster($data)
    {
        $courseId = $data['Course']['id'];
        $this->ipeerRoster = $this->User->getEnrolledStudents($courseId);
        $this->removeUsersFoundInBothRosters();
        $this->removeRemainingUsersFromIpeerRoster($courseId);
        $this->addRemainingUsersInIpeerRoster($courseId);
    }

    /**
     * Create course roster in database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L70
     * @param array $data
     */
    public function createCourseRoster($data)
    {
        if (!$this->Course->save($data)) {
            throw new LTI_Exception("Unable to add course.");
            return;
        }
        $this->addUsersInIpeerRoster($this->Course->id);
    }

    /**
     * Remove users in both rosters.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L92
     */
    public function removeUsersFoundInBothRosters()
    {
        foreach ($this->ltiRoster as $ltiKey => $ltiData) {
            foreach ($this->ipeerRoster as $ipeerKey => $ipeerData) {
                if ($ltiData['user_id'] == $ipeerData['User']['lti_id']) {
                    unset($this->ltiRoster[$ltiKey], $this->ipeerRoster[$ipeerKey]);
                    continue;
                }
            }
        }
    }

    /**
     * Remove remaining users from iPeer roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L102
     * @param int $courseId
     */
    public function removeRemainingUsersFromIpeerRoster($courseId)
    {
        foreach ($this->ipeerRoster as $data) {
            $this->User->removeStudent($data['User']['id'], $courseId);
        }
    }

    /**
     * Add remaining users in iPeer roster.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L107
     * @param $courseId
     */
    public function addRemainingUsersInIpeerRoster($courseId)
    {
        foreach ($this->ltiRoster as $data) {
            if (!$this->isInstructor($data['roles'])) {
                $this->addUser($data, $courseId);
            }
        }
    }

    /**
     * Add users in iPeer roster.
     *
     * @param int $courseId
     */
    public function addUsersInIpeerRoster($courseId)
    {
        foreach ($this->ltiRoster as $data) {
            $this->addUser($data, $courseId);
        }
    }

    /**
     * Add user to database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L137
     * @param array $data
     * @param int $courseId
     * @return bool
     */
    public function addUser($data, $courseId)
    {
        $isInstructor = $this->isInstructor($data['roles']);
        $firstName = $data['person_name_given'];
        $lastName = $data['person_name_family'];
        $username = $firstName . $lastName;
        $ltiId = $data['user_id'];

        // If user exists, save existing user to course
        if ($userData = $this->User->getByUsername($username)) {
            return $this->saveExistingUserToCourse($userData, $ltiId, $courseId, $isInstructor);
        }

        // If user doesn't exist, save new user to course
        $userData = array(
            'User' => array(
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['person_contact_email_primary'],
                'send_email_notification' => false,
                'lti_id' => $ltiId,
                'created' => date('Y-m-d H:i:s'),
            ),
            'Role' => array(
                'RolesUser' => $this->getUserType($isInstructor),
            ),
        );
        return $this->saveNewUserToCourse($userData, $courseId, $isInstructor);
    }

    /**
     * Save existing user to course in database.
     *
     * @param array $data
     * @param string $ltiId
     * @param int $courseId
     * @param bool $isInstructor
     * @return bool
     */
    public function saveExistingUserToCourse($data, $ltiId, $courseId, $isInstructor)
    {
        if ($this->addUserToCourse($data['User']['id'], $courseId, $isInstructor)) {
            // User might not have an lti_id, so save one
            $data['User']['lti_id'] = $ltiId;
            return (bool)$this->User->save($data);
        }
        return false;
    }

    /**
     * Save new user to course in database.
     *
     * @param array $data
     * @param int $courseId
     * @param bool $isInstructor
     * @return bool
     */
    public function saveNewUserToCourse($data, $courseId, $isInstructor)
    {
        $this->User->create();
        if ($this->User->save($data)) {
            return $this->addUserToCourse($this->User->id, $courseId, $isInstructor);
        }
        return false;
    }

    /**
     * Add user to course in database.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L194
     * @param string $userId
     * @param int $courseId
     * @param bool $isInstructor
     * @return bool
     */
    public function addUserToCourse($userId, $courseId, $isInstructor)
    {
        if ($isInstructor) {
            if ($roleId = $this->Role->field('id', array('name' => 'instructor'))) {
                $this->User->registerRole($userId, $roleId);
                $this->Course->addInstructor($courseId, $userId);
                return true;
            }
        } else {
            if ($roleId = $this->Role->getDefaultId()) {
                $this->User->registerRole($userId, $roleId);
                $this->User->UserEnrol->insertCourses($userid, array($courseId));
                return true;
            }
        }
        return false;
    }

    /**
     * Check if provided role is a LTI instructor.
     *
     * Previously https://github.com/ubc/iPeer/blob/3.4.4/app/controllers/lti_controller.php#L219
     * @param string $role
     * @return bool
     */
    public function isInstructor($role)
    {
        return stripos($role, "Instructor") !== false;
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
}

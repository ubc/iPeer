<?php

/**
 * LtiContext
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiContext extends AppModel
{
    public $name = 'LtiContext';
    public $belongsTo = array(
        'Course' => array(
            'className' => 'Course',
            'foreignKey' => 'course_id'
        ),
        'LtiToolRegistration' => array(
            'className' => 'LtiToolRegistration',
            'foreignKey' => 'lti_tool_registration_id'
        )
    );
    public $hasMany = array(
        'LtiResourceLink' => array(
            'className'   => 'LtiResourceLink',
            'foreignKey'  => 'lti_context_id',
            'dependent'   => true
        ),
    );
    public $validate = array(
        'lti_tool_registration_id' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'context_id' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', 255),
                'message' => 'Maximum 255 characters',
                'required' => true,
                'allowEmpty' => false,
            ),
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
    );

    public function syncFaculty($launch_data_parser) {
        $this->Faculty = ClassRegistry::init('Faculty');
        $name = $launch_data_parser->getFacultyNameValue();

        $faculty = $this->Faculty->find('first', array(
            'conditions' => array(
                'Faculty.name' => $name
            )
        ));
        if (empty($faculty)) {
            $faculty = array(
                'Faculty' => array(
                    'name' => $name,
                )
            );
            $this->Faculty->save($faculty);

            // refresh data after save
            $faculty = $this->Faculty->find('first', array(
                'conditions' => array(
                    'Faculty.name' => $name
                )
            ));
        }
        return $faculty;
    }

    public function syncLaunchContext($launch_data_parser)
    {
        $this->Course = ClassRegistry::init('Course');

        $lti_tool_registration_id = $launch_data_parser->lti_tool_registration['id'];
        $context_id = $launch_data_parser->getClaimParam('context', 'id');

        $lti_context = $this->getByRegistrationIdAndContextId($lti_tool_registration_id, $context_id);
        if (empty($lti_context)) {
            $lti_context = array(
                'LtiContext' => array(
                    'lti_tool_registration_id' => $lti_tool_registration_id,
                    'context_id' => $context_id,
                )
            );
        }
        $lti_context['LtiContext']['nrps_context_memberships_url'] = $launch_data_parser->getNrpsClaimParam('context_memberships_url');

        $course_data = $launch_data_parser->getCourseData();
        //setup course link if needed
        if (!isset($lti_context['LtiContext']['course_id'])) {
            $existing_course = $this->Course->find('first', array(
                'conditions' => array('Course.course' => $course_data['course']),
                'contain' => false
            ));
            if (!empty($existing_course)) {
                $lti_context['Course'] = $existing_course['Course'];
            } else {
                $lti_context['Course'] = $course_data;
            }
        }
        //skip updating course
        if (!empty($course_data['title'])) {
            $lti_context['Course']['title'] = $course_data['title'];
        }
        if (!empty($course_data['canvas_id'])) {
            $lti_context['Course']['canvas_id'] = $course_data['canvas_id'];
        }
        if (!empty($course_data['term'])) {
            $lti_context['Course']['term'] = $course_data['term'];
        }
        $this->saveAll($lti_context);

        // refresh data after save
        $lti_context = $this->getByRegistrationIdAndContextId($lti_tool_registration_id, $context_id);
        return $lti_context;
    }

    public function getByRegistrationIdAndContextId($lti_tool_registration_id, $context_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'LtiContext.lti_tool_registration_id' => $lti_tool_registration_id,
                'LtiContext.context_id' => $context_id,
            ),
            'contain' => array(
                'Course'
            )
        ));
    }

    public function getByCourseId($course_id)
    {
        return $this->find('all', array(
            'conditions' => array(
                'LtiContext.course_id' => $course_id,
            ),
            'contain' => array(
                'LtiResourceLink'
            )
        ));
    }
}

<?php

/**
 * LtiResourceLink
 *
 * @uses      AppModel
 * @package   CTLT.iPeer
 * @license   MIT {@link http://www.opensource.org/licenses/MIT}
 */
class LtiResourceLink extends AppModel
{
    public $name = 'LtiResourceLink';
    public $belongsTo = array(
        'LtiContext' => array(
            'className' => 'LtiContext',
            'foreignKey' => 'lti_context_id'
        ),
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id'
        )
    );
    public $validate = array(
        'lti_context_id' => array(
            'notEmpty' => array(
                'rule'     => 'notEmpty',
                'message'  => 'Cannot be blank',
                'required' => true,
            ),
        ),
        'resource_link_id' => array(
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

    public function syncLaunchResourceLink($launch_data_parser, $lti_context_id)
    {
        $resource_link_id = $launch_data_parser->getClaimParam('resource_link', 'id');
        $lti_resource_link = $this->getByLtiContextIdAndResourceLinkId($lti_context_id, $resource_link_id);

        if (empty($lti_resource_link)) {
            $lti_resource_link = array(
                'LtiResourceLink' => array(
                    'lti_context_id' => $lti_context_id,
                    'resource_link_id' => $resource_link_id,
                )
            );
        }
        $lti_resource_link['LtiResourceLink']['lineitems_url'] = $launch_data_parser->getAgsClaimParam('lineitems');
        $lti_resource_link['LtiResourceLink']['lineitem_url'] = $launch_data_parser->getAgsClaimParam('lineitem');
        $lti_resource_link['LtiResourceLink']['scope_lineitem'] = $launch_data_parser->hasAgsClaimScope('lineitem');
        $lti_resource_link['LtiResourceLink']['scope_lineitem_read_only'] = $launch_data_parser->hasAgsClaimScope('lineitem.readonly');
        $lti_resource_link['LtiResourceLink']['scope_result_readonly'] = $launch_data_parser->hasAgsClaimScope('result.readonly');
        $lti_resource_link['LtiResourceLink']['scope_result_score'] = $launch_data_parser->hasAgsClaimScope('score');

        // $event_data = $launch_data_parser->getEventData();

        $this->save($lti_resource_link);

        // refresh data after save
        $lti_resource_link = $this->getByLtiContextIdAndResourceLinkId($lti_context_id, $resource_link_id);
        return $lti_resource_link;
    }

    public function getByLtiContextIdAndResourceLinkId($lti_context_id, $resource_link_id)
    {
        return $this->find('first', array(
            'conditions' => array(
                'LtiResourceLink.lti_context_id' => $lti_context_id,
                'LtiResourceLink.resource_link_id' => $resource_link_id,
            ),
            'contain' => array(
                'Event',
            )
        ));
    }
}

<?php

namespace App\LTI13;

use IMSGlobal\LTI\LTI_Assignments_Grades_Service;
use IMSGlobal\LTI\LTI_Exception;
use IMSGlobal\LTI\LTI_Lineitem;
use IMSGlobal\LTI\LTI_Service_Connector;

class LTI_Assignments_Grades_Service_Override extends LTI_Assignments_Grades_Service {

    protected $service_connector;
    protected $service_data;

    public function __construct(LTI_Service_Connector $service_connector, $service_data) {
        parent::__construct($service_connector, $service_data);
        $this->service_connector = $service_connector;
        $this->service_data = $service_data;
    }

    /**
     * Override LTI_Assignments_Grades_Service::find_or_create_lineitem()
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Assignments_Grades_Service.php::find_or_create_lineitem()
     * @param LTI_Lineitem $new_line_item
     * @return LTI_Lineitem
     */
    public function find_or_create_lineitem(LTI_Lineitem $new_line_item) {
        if (!in_array("https://purl.imsglobal.org/spec/lti-ags/scope/lineitem", $this->service_data['scope'])) {
            throw new LTI_Exception('Missing required scope', 1);
        }
        $line_items = $this->service_connector->make_service_request(
            $this->service_data['scope'],
            'GET',
            $this->service_data['lineitems'],
            null,
            null,
            'application/vnd.ims.lis.v2.lineitemcontainer+json'
        );
        foreach ($line_items['body'] as $line_item) {
            if (empty($new_line_item->get_resource_id()) || $line_item['resourceid'] == $new_line_item->get_resource_id()) {
                if (empty($new_line_item->get_tag()) || $line_item['tag'] == $new_line_item->get_tag()) {
                    $line_item['resourceId'] = $line_item['resourceid'];
                    unset($line_item['resourceid']);
                    return new LTI_Lineitem($line_item);
                }
            }
        }
        $created_line_item = $this->service_connector->make_service_request(
            $this->service_data['scope'],
            'POST',
            $this->service_data['lineitems'],
            strval($new_line_item),
            'application/vnd.ims.lis.v2.lineitem+json',
            'application/vnd.ims.lis.v2.lineitem+json'
        );
        return new LTI_Lineitem($created_line_item['body']);
    }

    /**
     * Override LTI_Assignments_Grades_Service::get_grades()
     *
     * @see vendor/imsglobal/lti-1p3-tool/src/lti/LTI_Assignments_Grades_Service.php::get_grades()
     * @param LTI_Lineitem $lineitem
     * @return string
     */
    public function get_grades(LTI_Lineitem $lineitem) {
        return parent::get_grades($lineitem);
    }
}

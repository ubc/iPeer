<?php
class EmailTemplateFixture extends CakeTestFixture {
    public $name = 'EmailTemplate';

    public $import = 'EmailTemplate';

    public $records = array(
        array('id' => 1, 'name' => 'Test Template for 1', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '1', 'created' => '2011-06-10 01:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 01:00:00' ),
        array('id' => 2, 'name' => 'Test Template for 2', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '0', 'creator_id' => '2', 'created' => '2011-06-10 01:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 01:00:00' ),
        array('id' => 3, 'name' => 'Test Template public', 'subject' => 'Test Email w/ Template', 'description' => 'Description for Test Email Template', 'content' => 'This is Test Email Template', 'availability' => '1', 'creator_id' => '2', 'created' => '2011-06-10 01:00:00', 'updater_id' => '1', 'updated' => '2011-06-10 01:00:00' ),
    );
}

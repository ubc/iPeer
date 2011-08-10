<?php

class PenaltyFixture extends CakeTestFixture {
  var $name = 'Penalty';
  var $import = 'Penalty';

	  var $records = array(
    array('id' => 1, 'event_id' => '1', 'days_late' => '1', 'percent_penalty' =>10),
    array('id' => 2, 'event_id' => '1', 'days_late' => '2', 'percent_penalty' =>20),
    array('id' => 3, 'event_id' => '1', 'days_late' => '4', 'percent_penalty' =>40),
    array('id' => 4, 'event_id' => '1', 'days_late' => '5', 'percent_penalty' =>50),
    );
}



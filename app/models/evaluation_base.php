<?php

class EvaluationBase extends AppModel {
  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $c = get_class( $this );
    $this->virtualFields['event_count'] = sprintf('SELECT count(*) as count FROM events as event WHERE event.event_template_type_id = %d AND event.template_id = %s.id', constant($c.'::TEMPLATE_TYPE_ID'), $this->alias);
  }
}

<?php

class EvaluationBase extends AppModel {
  var $name = 'EvaluationBase';
  var $actsAs = array('ExtendAssociations', 'Containable', 'Habtamable');

  function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $c = get_class( $this );
    $this->virtualFields['event_count'] = sprintf('SELECT count(*) as count FROM events as event WHERE event.event_template_type_id = %d AND event.template_id = %s.id', constant($c.'::TEMPLATE_TYPE_ID'), $this->alias);
  }

  function beforeSave(){
    // Ensure the name is not empty
    if (empty($this->data[$this->name]['name'])) {
      $this->errorMessage = "Please enter a new name for this " . $this->name . ".";
      return false;
    }

    // Remove any signle quotes in the name, so that custom SQL queries are not confused.
    $this->data[$this->name]['name'] =
      str_replace("'", "", $this->data[$this->name]['name']);

    //check the duplicate name
    if (empty($this->data[$this->name]['id']) && !$this->__checkDuplicateName()) {
      return false;
    }
    
       //check if questions are entered
    if(empty($this->data['Question'])&&($this->name =='Mixeval')){
       $this->errorMessage = "Please add at least one question for this " . $this->name . ".";
       return false;
     }
    return parent::beforeSave();
  }
  
  //Validation check on duplication of name
	function __checkDuplicateName() {
    $result = $this->find('first', array('conditions' => array('name' => $this->data[$this->name]['name'])));
    if ($result) {
      $this->errorMessage='Duplicate name found. Please change the name.';
      return false;
    }

    return true;
  }


  /**
   * Returns the evaluations made by this user, and any other public ones.
   */
  function getBelongingOrPublic($user_id) {
    if(!is_numeric($user_id)) {
      return false;
    }

    $conditions = array('creator_id' => $user_id);
    if($this->name != 'SimpleEvaluation') {
      $conditions = array('OR' => array_merge(array('availability' => 'public'), $conditions));
    }
    return $this->find('all', array('conditions' => $conditions));
  }

  function getEventCount($evaluation_id) {
    $eval = $this->read('event_count', $evaluation_id);
    return $eval[$this->alias]['event_count'];
  }
}

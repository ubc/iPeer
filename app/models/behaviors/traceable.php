<?php
/**
 *
 * Automagically adds the logged in user's id to the specified fields when certain
 * events occur within the model.
 *
 * NB: If this behavior is not working, be sure you are not using saveField to save the
 * trigger field. If you do, no other fields (including the target field) will be saved.
 * Instead use a standard model->save call. This is a cake issue so nothing can be
 * done without altering the core.
 * 
 * This works on a system of trigger fields and target fields. When a 'trigger' field
 * is saved, and it updates a corresponding 'target' field. For example, if you have
 * a trigger field called 'deleted' with a corresponding target field of 'deleted_by',
 * when deleted is saved with an empty value (anything which causes empty() to return true)
 * then the deleted_by field is set to a specified value (by default, 0). When deleted
 * is saved with a non-empty value, deleted_by is updated with the id of the currently
 * Authed user.
 * 
 * When using find queries on a model which implements this behaviour, a
 * user model will be generated for each target field and stored under a corresponding
 * name in the data array (assuming the recursive level permits of course).
 * For example, a User model for user identified in the created_by field will 
 * be stored under the CreatedBy key in the returned data array:
 * 
 * Array (
 *     'Webpages' => Array (
 *         'id' => 1
 *         // etc
 *     ),
 *     'CreatedBy' => Array (
 *         'id' => 1,
 *         'username' => 'Karl'
 *         // etc
 *     )
 * )
 *
 * In the event that no user model can be associated with the target field (for
 * example, of the target field contains 0 or NULL), the target field data will
 * be set to an empty array:
 *
 * Array (
 *     'Webpages' => Array (
 *         'id' => 1
 *         // etc
 *     ),
 *     'CreatedBy' => Array ()
 * )
 *
 * @author Karl Rixon <karl@karlrixon.co.uk>
 * @version 1.0
 *
 * Modifed by Compass for iPeer Project
 * 
 **/
class TraceableBehavior extends ModelBehavior {

    /**
     * @var mixed An array of default configuration options
     * @access private
     */
    private $_defaults = array(
        // The name of the user model.
        'user_model' => 'User',
        // An array of fields, with a trigger field as key and a target field as value.
        // When the trigger field is set to a non empty() value, the user's id is
        // inserted into the corresponding target field. When it is set to an empty()
        // value, the target field is reset to restored_value.
        'fields'  => array(
            'created' => 'creator_id',
            'modified' => 'updater_id',
            'deleted' => 'deleted_by',
            'hidden' => 'hidden_by',
            'locked' => 'locked_by'
        ),
        'display_fields' => array(
            'created' => 'CONCAT(Creator.first_name, " ", Creator.last_name)',
            'modified' => 'CONCAT(Updater.first_name, " ", Updater.last_name)',
        ),
        // The value to which target fields will be set when toggled back.
        'restored_value' => 0,
        // If true, a user model representing each target field will be automatically bound
        // to the model which implements this behaviour (using a belongsTo association).
        // possible values: model, embed, false
        'auto_bind' => 'embed',
        'map' => array()
    );
    
    /**
     * @var int Stores the id of the currently Authed user.
     * @access private
     */
    private $_userId = 0;
    
    /**
     * @var mixed An array of values which should indicate that a field has been emptied. There
     * is no need to specify the values which evaluate true using PHP's empty() function;
     * this is intended for custom values which would not normally be considered empty().
     * @access private
     */
    private $_empty = array(
        '0000-00-00 00:00:00' // empty datetime
    );
    
    private $_noTrace = false;

    /**
     * Initialises the behaviour.
     *
     * @param Model $model A reference to the model object to which this behaviour is attached.
     * @param mixed $config An array of behaviour configuration options to be merged with the defaults.
     * @return void
     * @author Karl Rixon <karlrixon@gmail.com>
     * @version 1.0
     * @since 1.0
     */
    function setup($model, $config = array()) {
        
        if (!$model->useTable) {
            // Model is not tied to a database table.
            return;
        }
        
        $this->settings[$model->alias] = array_merge($this->_defaults, (array) $config);

        if (empty($this->_userId)) {
            $this->_userId = $this->_getAuthedUserId($model);
        }
        $this->settings[$model->alias]['map'] = $this->_buildFieldMap($model);
        
        if ('model' == $this->settings[$model->alias]['auto_bind']) {
            $this->_bindModels($model);
        } else if ('embed' == $this->settings[$model->alias]['auto_bind']) {
          // auto grab the creator and updater name for those tables that have 
          // creator_id and updater_id
          $local_alias = $model->alias.'1';
          if($model->hasField($this->settings[$model->alias]['fields']['created'])) {
            $model->virtualFields['creator'] = sprintf('SELECT %s FROM users as Creator JOIN %s as %s ON Creator.id = %s.%s WHERE %s.id = %s.id', 
                                                       $this->settings[$model->alias]['display_fields']['created'], $model->table, 
                                                       $local_alias, $local_alias, $this->settings[$model->alias]['fields']['created'],
                                                       $local_alias, $model->alias);
          }

          if($model->hasField($this->settings[$model->alias]['fields']['modified'])) {
            $model->virtualFields['updater'] = sprintf('SELECT %s FROM users as Updater JOIN %s as %s ON Updater.id = %s.%s WHERE %s.id = %s.id', 
                                                       $this->settings[$model->alias]['display_fields']['modified'], $model->table, 
                                                       $local_alias, $local_alias, $this->settings[$model->alias]['fields']['modified'],
                                                       $local_alias, $model->alias);
          }
        }
    }
    
    /**
     * Called before model data is saved.
     *
     * This method is just used as a place to attach a call to the _trace method which
     * does all the hard work of managing the trigger/target fields.
     *
     * @param Model $model A reference to the model object to which this behaviour is attached.
     * @return boolean Always returns true to allow the save to continue as normal.
     * @author Karl Rixon <karlrixon@gmail.com>
     * @version 1.0
     * @since 1.0
     */
    function beforeSave($model) {
        if (!empty($this->settings[$model->alias]['map'])) {
            $this->_trace($model);
        }
        return true;
    }
    
    /**
     * Cleans up the found data, changing empty association models to empty arrays.
     *
     * Without this, any empty association model data will contain all of the keys
     * from the User model's table. It seems neater to return an empty array for any
     * items which do not have a matching user model.
     *
     * @param Model $model A reference to the model object to which this behaviour is attached.
     * @param mixed $results An array containing the data returned from the find.
     * @return void
     * @author Karl Rixon <karlrixon@gmail.com>
     * @version 1.0
     * @since 1.0
    */
    function afterFind($model, $results, $primary) {
        
        if (empty($this->settings[$model->alias]['map'])) {
            return $results;
        }
        
        if ($model->recursive == -1) {
            return $results;
        }
        
        if (!empty($results) && $primary) {
            foreach ($results as &$result) {
                if (!isset($result[$model->alias])) {
                    continue;
                }
                foreach ($this->settings[$model->alias]['map'] as $associationName) {
                    if (empty($result[$associationName]['id'])) {
                        $result[$associationName] = array();
                    }
                }
            }
        }
        
        return $results;
        
    }
    
    /**
     * Checks for any triggered fields, and sets the corresponding target field accordingly.
     *
     * If a triggered field is found in the data, and it's value is empty (anything
     * which evaluates to true using PHP's empty() function), its target field is
     * set to the restored_value. If it is not empty, its target field is set to the
     * id of the currently Authed user.
     *
     * @param Model $model A reference to the model object to which this behaviour is attached.
     * @return bool True if succesful, false if an error occured. Note that true will be
     *  returned even if no trigger fields were found. False will only be returned if
     *  an actual error occured - the lack of trigger fields is not considered an error.
     * @author Karl Rixon <karlrixon@gmail.com>
     * @version 1.0
     * @since 1.0
     */
    private function _trace(&$model) {
        
        if (!isset($this->settings[$model->alias]['fields'])) {
            return false;
        } elseif (!$this->_userId) {
            return false;
        }
        
        foreach ($this->settings[$model->alias]['fields'] as $trigger => $target) {
            if (isset($model->data[$model->alias][$trigger])) {
                if (empty($model->data[$model->alias][$trigger]) || in_array($model->data[$model->alias][$trigger], $this->_empty)) {
                    $model->data[$model->alias][$target] = $this->settings[$model->alias]['restored_value'];
                } else {
                    $model->data[$model->alias][$target] = $this->_userId;
                }
            }
        }
        
        return true;
        
    }

    /**
     * Gets the id of the currently Authed user.
     */
    private function _getAuthedUserId($model) {
        App::import('Component', 'Session');
        $session = new SessionComponent();
        return $session->read('Auth.' . $this->settings[$model->alias]['user_model'] . '.id');
    }

    /**
     * Builds an array with field names as keys, and camelized versions of
     * the field names as values.
     */
    private function _buildFieldMap($model) {
        
        // Get an array of all fields which exist in both the model's table, and in
        // the behaviour settings for this model.
        $fields = array_values(array_intersect(
            $this->settings[$model->alias]['fields'],
            array_keys($model->_schema)
        ));
        
        $map = array();
        foreach ($fields as $field) {
            $map[$field] = Inflector::camelize($field);
        }

        return $map;
    
    }

    /**
     * Binds any models which should be bound in order to represent the
     * User who is to be traced.
     */
    private function _bindModels($model) {
        
        if (empty($this->settings[$model->alias]['map'])) {
            return false;
        }
        
        $models = array();
        foreach ($this->settings[$model->alias]['map'] as $foreignKey => $associationName) {
            $models['belongsTo'][$associationName] = array(
                'className'  => $this->settings[$model->alias]['user_model'],
                'foreignKey' => $foreignKey
            );
        }
        if (sizeof($models) > 0) {
            $model->bindModel($models, false);
        }
    }

}

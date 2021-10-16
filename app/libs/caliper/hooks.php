<?php
namespace caliper;

use IMSGlobal\Caliper\profiles\Profile;
use IMSGlobal\Caliper\actions\Action;
use IMSGlobal\Caliper\events\SessionEvent;
use IMSGlobal\Caliper\events\NavigationEvent;
use IMSGlobal\Caliper\events\QuestionnaireEvent;
use IMSGlobal\Caliper\events\QuestionnaireItemEvent;
use IMSGlobal\Caliper\events\AssessmentEvent;
use IMSGlobal\Caliper\events\AssessmentItemEvent;
use IMSGlobal\Caliper\events\FeedbackEvent;
use IMSGlobal\Caliper\events\ResourceManagementEvent;
use IMSGlobal\Caliper\events\ToolUseEvent;
use caliper\CaliperActor;
use caliper\CaliperEntity;
use caliper\CaliperSensor;

class CaliperHooks {
    public static function app_controller_before_render($app_controller) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $queryParams = $app_controller->params['url'];
        if ($queryParams['url']) {
            unset($queryParams['url']);
        }
        $absoluteUrl = \Router::url($app_controller->here, true) . \Router::queryString($queryParams);
        $absolutePath = ltrim(\Router::url($app_controller->here, true), '/');
        $relativeUrl =  \Router::url($app_controller->here, false) . \Router::queryString($queryParams);
        $relativePath = ltrim(\Router::url($app_controller->here, false), '/');
        $queryString = ltrim(\Router::queryString($queryParams), '?');
        // skip favicon views
        if ($relativePath != 'favicon.ico') {
            $event = (new NavigationEvent())
                ->setProfile( new Profile( Profile::READING ) )
                ->setAction(new Action(Action::NAVIGATED_TO))
                ->setObject(CaliperEntity::webpage($relativePath))
                ->setExtensions([
                    'relativePath' => $relativePath,
                    'queryString' => $queryString,
                    'absolutePath' => $absolutePath,
                    'absoluteUrl' => $absoluteUrl,
                ]);
            CaliperSensor::sendEvent($event);
        }
    }

    public static function app_controller_after_login($app_controller) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $app_controller->Session->write('session_start', time());
        $event = (new SessionEvent())
            ->setProfile( new Profile( Profile::SESSION ) )
            ->setAction(new Action(Action::LOGGED_IN))
            ->setObject(CaliperEntity::iPeer());
        CaliperSensor::sendEvent($event);
    }

    public static function app_controller_after_logout($app_controller) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $app_controller->Session->write('session_end', time());
        $event = (new SessionEvent())
            ->setProfile( new Profile( Profile::SESSION ) )
            ->setAction(new Action(Action::LOGGED_OUT))
            ->setObject(CaliperEntity::iPeer());
        CaliperSensor::sendEvent($event);
    }

    public static function course_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->find('first', array(
            'conditions' => array('Course.id' => $model->id),
            'recursive' => 0,
        ));
    }

    public static function course_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);

        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::course(
                $results['Course']));
        CaliperSensor::sendEvent($event, $results['Course']);
    }

    public static function course_after_save($model, $created) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $action = $created ? Action::CREATED : Action::MODIFIED;
        $results = $model->find('first', array(
            'conditions' => array('Course.id' => $model->id),
            'recursive' => 0,
        ));

        // Potential TODO: add activate/deactivate event based on record_status
        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action($action))
            ->setObject(CaliperEntity::course(
                $results['Course']));
        CaliperSensor::sendEvent($event, $results['Course']);
    }

    public static function event_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->find('first', array(
            'conditions' => array('Event.id' => $model->id),
            'contain' => array('Course', 'Penalty'),
        ));
    }

    public static function event_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $SimpleEvaluation = \ClassRegistry::init('SimpleEvaluation');
        $Mixeval = \ClassRegistry::init('Mixeval');
        $Rubric = \ClassRegistry::init('Rubric');
        $Survey = \ClassRegistry::init('Survey');

        $events = array();

        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);
        $event_obj = $results['Event'];
        $course = $results['Course'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];

        if($event_obj['event_template_type_id'] == $SimpleEvaluation::TEMPLATE_TYPE_ID) {
            $results = $SimpleEvaluation->getEvaluation($event_obj['template_id']);

            $events[]= (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::event_simple_evaluation(
                    $event_obj,
                    $results['SimpleEvaluation']));
        } elseif($event_obj['event_template_type_id'] == $Mixeval::TEMPLATE_TYPE_ID) {
            $results = $Mixeval->find('first', array(
                'conditions' => array('Mixeval.id' => $event_obj['template_id']),
                'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
                'recursive' => 2,
            ));

            $events[]= (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::event_mixeval(
                    $event_obj,
                    $results['Mixeval'],
                    $results['MixevalQuestion']));
            foreach($results['MixevalQuestion'] as $question) {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::DELETED))
                    ->setObject(CaliperEntity::mixeval_question(
                        $event_obj,
                        $results['Mixeval'],
                        $results['MixevalQuestion'],
                        $question));
            }
        } elseif($event_obj['event_template_type_id'] == $Rubric::TEMPLATE_TYPE_ID) {
            $results = $Rubric->find('first', array(
                'conditions' => array('Rubric.id' => $event_obj['template_id']),
                'contain' => array(
                    'RubricsCriteria',
                    'RubricsCriteria.RubricsCriteriaComment',
                    'RubricsLom',
                )
            ));

            $events[]= (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::event_rubric(
                    $event_obj,
                    $results['Rubric'],
                    $results['RubricsCriteria']));
            foreach($results['RubricsCriteria'] as $question) {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::DELETED))
                    ->setObject(CaliperEntity::rubric_question(
                        $event_obj,
                        $results['Rubric'],
                        $results['RubricsCriteria'],
                        $question,
                        $results['RubricsLom']));
            }
        } elseif($event_obj['event_template_type_id'] == $Survey::TEMPLATE_TYPE_ID) {
            $results = $Survey->find('first', array(
                'conditions' => array('Survey.id' => $event_obj['template_id']),
                'contain' => array(
                    'Question' => array(
                        'order' => 'SurveyQuestion.number ASC',
                        'Response'
                    )
                )
            ));

            $events[]= (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::event_survey(
                    $event_obj,
                    $results['Survey'],
                    $results['Question']));
            foreach($results['Question'] as $question) {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::DELETED))
                    ->setObject(CaliperEntity::survey_item(
                        $event_obj,
                        $results['Survey'],
                        $results['Question'],
                        $question));
            }
        }

        CaliperSensor::sendEvent($events, $course);
    }

    public static function event_after_save($model, $created) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $SimpleEvaluation = \ClassRegistry::init('SimpleEvaluation');
        $Mixeval = \ClassRegistry::init('Mixeval');
        $Rubric = \ClassRegistry::init('Rubric');
        $Survey = \ClassRegistry::init('Survey');

        $events = array();

        $results = $model->find('first', array(
            'conditions' => array('Event.id' => $model->id),
            'contain' => array('Course', 'Penalty'),
        ));
        $event_obj = $results['Event'];
        $course = $results['Course'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];

        if($event_obj['event_template_type_id'] == $SimpleEvaluation::TEMPLATE_TYPE_ID) {
            $results = $SimpleEvaluation->getEvaluation($event_obj['template_id']);

            if ($created) {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::COPIED))
                    ->setObject(CaliperEntity::simple_evaluation(
                        $results['SimpleEvaluation']))
                    ->setGenerated(CaliperEntity::event_simple_evaluation(
                        $event_obj,
                        $results['SimpleEvaluation']));
            } else {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::MODIFIED))
                    ->setObject(CaliperEntity::event_simple_evaluation(
                        $event_obj,
                        $results['SimpleEvaluation']));
            }
        } elseif($event_obj['event_template_type_id'] == $Mixeval::TEMPLATE_TYPE_ID) {
            $results = $Mixeval->find('first', array(
                'conditions' => array('Mixeval.id' => $event_obj['template_id']),
                'contain' => array(
                    'MixevalQuestion',
                    'MixevalQuestion.MixevalQuestionDesc',
                    'MixevalQuestion.MixevalQuestionType'
                ),
            ));

            if ($created) {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::COPIED))
                    ->setObject(CaliperEntity::mixeval(
                        $results['Mixeval'],
                        $results['MixevalQuestion']))
                    ->setGenerated(CaliperEntity::event_mixeval(
                        $event_obj,
                        $results['Mixeval'],
                        $results['MixevalQuestion']));
                    foreach($results['MixevalQuestion'] as $question) {
                        $events[] = (new ResourceManagementEvent())
                            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                            ->setAction(new Action(Action::COPIED))
                            ->setObject(CaliperEntity::mixeval_question(
                                NULL, //event
                                $results['Mixeval'],
                                $results['MixevalQuestion'],
                                $question))
                            ->setGenerated(CaliperEntity::mixeval_question(
                                $event_obj,
                                $results['Mixeval'],
                                $results['MixevalQuestion'],
                                $question));
                    }
            } else {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::MODIFIED))
                    ->setObject(CaliperEntity::event_mixeval(
                        $event_obj,
                        $results['Mixeval'],
                        $results['MixevalQuestion']));
            }
        } elseif($event_obj['event_template_type_id'] == $Rubric::TEMPLATE_TYPE_ID) {
            $results = $Rubric->find('first', array(
                'conditions' => array('Rubric.id' => $event_obj['template_id']),
                'contain' => array(
                    'RubricsCriteria',
                    'RubricsCriteria.RubricsCriteriaComment',
                    'RubricsLom',
                )
            ));

            if ($created) {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::COPIED))
                    ->setObject(CaliperEntity::rubric(
                        $results['Rubric'],
                        $results['RubricsCriteria']))
                    ->setGenerated(CaliperEntity::event_rubric(
                        $event_obj,
                        $results['Rubric'],
                        $results['RubricsCriteria']));
                foreach($results['RubricsCriteria'] as $question) {
                    $events[] = (new ResourceManagementEvent())
                        ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                        ->setAction(new Action(Action::COPIED))
                        ->setObject(CaliperEntity::rubric_question(
                            NULL, //event
                            $results['Rubric'],
                            $results['RubricsCriteria'],
                            $question,
                            $results['RubricsLom']))
                        ->setGenerated(CaliperEntity::rubric_question(
                            $event_obj,
                            $results['Rubric'],
                            $results['RubricsCriteria'],
                            $question,
                            $results['RubricsLom']));
                }
            } else {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::MODIFIED))
                    ->setObject(CaliperEntity::event_rubric(
                        $event_obj,
                        $results['Rubric'],
                        $results['RubricsCriteria']));
            }
        } elseif($event_obj['event_template_type_id'] == $Survey::TEMPLATE_TYPE_ID) {
            $results = $Survey->find('first', array(
                'conditions' => array('Survey.id' => $event_obj['template_id']),
                'contain' => array(
                    'Question' => array(
                        'order' => 'SurveyQuestion.number ASC',
                        'Response'
                    )
                )
            ));

            if ($created) {
                $events[]= (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::COPIED))
                    ->setObject(CaliperEntity::survey(
                        $results['Survey'],
                        $results['Question']))
                    ->setGenerated(CaliperEntity::event_survey(
                        $event_obj,
                        $results['Survey'],
                        $results['Question']));
                foreach($results['Question'] as $question) {
                    $events[]= (new ResourceManagementEvent())
                        ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                        ->setAction(new Action(Action::COPIED))
                        ->setObject(CaliperEntity::survey_item(
                            NULL, //event
                            $results['Survey'],
                            $results['Question'],
                            $question))
                        ->setGenerated(CaliperEntity::survey_item(
                            $event_obj,
                            $results['Survey'],
                            $results['Question'],
                            $question));
                }
            } else {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::MODIFIED))
                    ->setObject(CaliperEntity::event_survey(
                        $event_obj,
                        $results['Survey'],
                        $results['Question']));
            }
        }

        CaliperSensor::sendEvent($events, $course);
    }

    public static function submit_simple_evaluation($eventId, $evaluator, $groupEventId, $groupId) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $User = \ClassRegistry::init('User');
        $Event = \ClassRegistry::init('Event');
        $SimpleEvaluation = \ClassRegistry::init('SimpleEvaluation');

        $events = array();

        $rater = $User->findUserByidWithFields($evaluator);
        $results = $Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array(
                'Course',
                'Penalty',
                'EvaluationSimple' => array(
                    'conditions' => array(
                        'EvaluationSimple.evaluator =' => $evaluator,
                    )
                ),
                'GroupEvent' => array(
                    'conditions' => array(
                        'GroupEvent.id' => $groupEventId,
                    ),
                    'Group'
                )
            ),
        ));
        $event_obj = $results['Event'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];
        $course = $results['Course'];
        $group = $results['GroupEvent'][0]['Group'];
        $user_evaluation_simple = $results['EvaluationSimple'];

        $results = $SimpleEvaluation->getEvaluation($event_obj['template_id']);
        $groupMembers = $User->getEventGroupMembersNoTutors($groupId, $event_obj['self_eval'], $evaluator);
        $scale_max = $results['SimpleEvaluation']['point_per_member'] * count($groupMembers);

        foreach($user_evaluation_simple as $evaluation_simple) {
            $group_member = NULL;
            foreach($groupMembers as $groupMember) {
                if ($evaluation_simple['evaluatee'] == $groupMember['User']['id']) {
                    $group_member = $groupMember['User'];
                    break;
                }
            }
            if ($group_member) {
                $events[]= (new AssessmentItemEvent())
                    ->setProfile( new Profile( Profile::ASSESSMENT ) )
                    ->setAction(new Action(Action::COMPLETED))
                    ->setObject(CaliperEntity::simple_evaluation_group_member_question(
                        $event_obj,
                        $results['SimpleEvaluation'],
                        $scale_max,
                        $group,
                        $group_member))
                    ->setGenerated(CaliperEntity::simple_evaluation_group_member_response(
                        $event_obj,
                        $group,
                        $group_member,
                        $evaluation_simple));
                $events[]= (new FeedbackEvent())
                    ->setProfile( new Profile( Profile::FEEDBACK ) )
                    ->setAction(new Action(Action::RANKED))
                    ->setObject(CaliperActor::generateActor($group_member))
                    ->setGenerated(CaliperEntity::simple_evaluation_feedback_rating(
                        $rater,
                        $group_member,
                        $event_obj,
                        $results['SimpleEvaluation'],
                        $scale_max,
                        $evaluation_simple));
            }
        }
        $events[]= (new AssessmentEvent())
            ->setProfile( new Profile( Profile::ASSESSMENT ) )
            ->setAction(new Action(Action::SUBMITTED))
            ->setObject(CaliperEntity::event_simple_evaluation(
                $event_obj,
                $results['SimpleEvaluation']));
        $events[]= (new ToolUseEvent())
            ->setProfile( new Profile( Profile::TOOL_USE ) )
            ->setAction(new Action(Action::USED))
            ->setObject(CaliperEntity::iPeer());

        CaliperSensor::sendEvent($events, $course, $group);
    }

    public static function simple_evaluation_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->getEvaluation($model->id);
    }

    public static function simple_evaluation_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);

        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::simple_evaluation(
                $results['SimpleEvaluation']));
        CaliperSensor::sendEvent($event);
    }

    public static function simple_evaluation_after_save($model, $created) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $action = $created ? Action::CREATED : Action::MODIFIED;
        $results = $model->getEvaluation($model->id);

        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action($action))
            ->setObject(CaliperEntity::simple_evaluation(
                $results['SimpleEvaluation']));
        CaliperSensor::sendEvent($event);
    }

    public static function submit_survey($eventId, $userId) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Event = \ClassRegistry::init('Event');
        $Survey = \ClassRegistry::init('Survey');

        $events = array();

        $results = $Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array(
                'Course',
                'Penalty',
                'SurveyInput' => array(
                    'conditions' => array(
                        'SurveyInput.user_id =' => $userId,
                    ),
                )
            ),
        ));
        $event_obj = $results['Event'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];
        $course = $results['Course'];
        $user_survey_inputs = $results['SurveyInput'];

        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $event_obj['template_id']),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response'
                )
            )
        ));
        foreach($results['Question'] as $question) {
            $survey_inputs = array();
            foreach ($user_survey_inputs as $survey_input) {
                if ($survey_input['question_id'] == $question['id']) {
                    $survey_inputs[] = $survey_input;
                }
            }
            if (count($survey_inputs) > 0) {
                $events[]= (new QuestionnaireItemEvent())
                    ->setProfile( new Profile( Profile::SURVEY ) )
                    ->setAction(new Action(Action::COMPLETED))
                    ->setObject(CaliperEntity::survey_item(
                        $event_obj, //event
                        $results['Survey'],
                        $results['Question'],
                        $question))
                    ->setGenerated(CaliperEntity::survey_item_response(
                        $event_obj, //event
                        $results['Survey'],
                        $results['Question'],
                        $question,
                        $survey_inputs));
            }
        }

        $events[]= (new QuestionnaireEvent())
            ->setProfile( new Profile( Profile::SURVEY ) )
            ->setAction(new Action(Action::SUBMITTED))
            ->setObject(CaliperEntity::event_survey(
                $event_obj,
                $results['Survey'],
                $results['Question']));
        CaliperSensor::sendEvent($events, $course);
    }

    public static function survey_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->find('first', array(
            'conditions' => array('Survey.id' => $model->id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
    }

    public static function survey_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $events = array();
        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);

        foreach($results['Question'] as $question) {
            $events[]= (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::survey_item(
                    NULL, //event
                    $results['Survey'],
                    $results['Question'],
                    $question));
        }
        $events[]= (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::survey(
                $results['Survey'],
                $results['Question']));
        CaliperSensor::sendEvent($events);
    }

    public static function survey_edit($survey_id) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Survey = \ClassRegistry::init('Survey');

        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $survey_id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));

        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::MODIFIED))
            ->setObject(CaliperEntity::survey(
                $results['Survey'],
                $results['Question']));
        CaliperSensor::sendEvent($event);
    }

    public static function survey_create($survey_id) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Survey = \ClassRegistry::init('Survey');

        $events = array();
        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $survey_id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
        $events[] = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::CREATED))
            ->setObject(CaliperEntity::survey(
                $results['Survey'],
                $results['Question']));

        foreach($results['Question'] as $question) {
            $events[] = (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::CREATED))
                ->setObject(CaliperEntity::survey_item(
                    NULL, //event
                    $results['Survey'],
                    $results['Question'],
                    $question));
        }
        CaliperSensor::sendEvent($events);
    }

    public static function survey_edit_question($survey_id, $question_id) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Survey = \ClassRegistry::init('Survey');

        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $survey_id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
        $question_key = array_search($question_id, array_column($results['Question'], 'id'));
        $question = $results['Question'][$question_key];

        $event = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::MODIFIED))
            ->setObject(CaliperEntity::survey_item(
                NULL, //event
                $results['Survey'],
                $results['Question'],
                $question));
        CaliperSensor::sendEvent($event);
    }

    public static function survey_remove_question($survey_id, $question_id) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Survey = \ClassRegistry::init('Survey');

        $events = array();
        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $survey_id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
        $question_key = array_search($question_id, array_column($results['Question'], 'id'));
        $question = $results['Question'][$question_key];

        $events[]= (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::survey_item(
                NULL, //event
                $results['Survey'],
                $results['Question'],
                $question));

        $events[]= (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::MODIFIED))
            ->setObject(CaliperEntity::survey(
                $results['Survey'],
                $results['Question']));
        CaliperSensor::sendEvent($events);
    }

    public static function survey_create_question($survey_id, $question_id) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Survey = \ClassRegistry::init('Survey');

        $events = array();
        $results = $Survey->find('first', array(
            'conditions' => array('Survey.id' => $survey_id),
            'contain' => array(
                'Question' => array(
                    'order' => 'SurveyQuestion.number ASC',
                    'Response',
                ),
            ),
            'recursive' => 2,
        ));
        $question_key = array_search($question_id, array_column($results['Question'], 'id'));
        $question = $results['Question'][$question_key];

        $events[]= (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::CREATED))
            ->setObject(CaliperEntity::survey_item(
                NULL, //event
                $results['Survey'],
                $results['Question'],
                $question));
        $events[]= (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::MODIFIED))
            ->setObject(CaliperEntity::survey(
                $results['Survey'],
                $results['Question']));
        CaliperSensor::sendEvent($events);
    }

    public static function submit_rubric($eventId, $evaluator, $groupEventId, $groupId) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $User = \ClassRegistry::init('User');
        $Event = \ClassRegistry::init('Event');
        $Rubric = \ClassRegistry::init('Rubric');

        $events = array();

        $rater = $User->findUserByidWithFields($evaluator);
        $results = $Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array(
                'Course',
                'Penalty',
                'EvaluationRubric' => array(
                    'conditions' => array(
                        'EvaluationRubric.evaluator =' => $evaluator,
                    ),
                    'EvaluationRubricDetail'
                ),
                'GroupEvent' => array(
                    'conditions' => array(
                        'GroupEvent.id' => $groupEventId,
                    ),
                    'Group'
                )
            ),
        ));
        $event_obj = $results['Event'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];
        $course = $results['Course'];
        $group = $results['GroupEvent'][0]['Group'];
        $user_evaluation_rubric = $results['EvaluationRubric'];

        $results = $Rubric->find('first', array(
            'conditions' => array('Rubric.id' => $event_obj['template_id']),
            'contain' => array(
                'RubricsCriteria',
                'RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom',
            )
        ));
        $groupMembers = $User->getEventGroupMembersNoTutors($groupId, $event_obj['self_eval'], $evaluator);

        foreach($user_evaluation_rubric as $evaluation_rubric) {
            $group_member = NULL;
            foreach($groupMembers as $groupMember) {
                if ($evaluation_rubric['evaluatee'] == $groupMember['User']['id']) {
                    $group_member = $groupMember['User'];
                    break;
                }
            }
            if ($group_member) {
                foreach($evaluation_rubric['EvaluationRubricDetail'] as $evaluation_rubric_detail) {
                    $question = NULL;
                    foreach($results['RubricsCriteria'] as $q) {
                        if ($q['criteria_num'] == $evaluation_rubric_detail['criteria_number']) {
                            $question = $q;
                            break;
                        }
                    }
                    if ($question) {
                        $events[]= (new AssessmentItemEvent())
                            ->setProfile( new Profile( Profile::ASSESSMENT ) )
                            ->setAction(new Action(Action::COMPLETED))
                            ->setObject(CaliperEntity::rubric_group_member_question(
                                $event_obj,
                                $results['Rubric'],
                                $results['RubricsCriteria'],
                                $question,
                                $results['RubricsLom'],
                                $group,
                                $group_member))
                            ->setGenerated(CaliperEntity::rubric_group_member_response(
                                $event_obj,
                                $question,
                                $group,
                                $group_member,
                                $evaluation_rubric,
                                $evaluation_rubric_detail));
                        $events[]= (new FeedbackEvent())
                            ->setProfile( new Profile( Profile::FEEDBACK ) )
                            ->setAction(new Action(Action::RANKED))
                            ->setObject(CaliperActor::generateActor($group_member))
                            ->setGenerated(CaliperEntity::rubric_feedback_rating(
                                $rater,
                                $group_member,
                                $event_obj,
                                $results['Rubric'],
                                $question,
                                $results['RubricsLom'],
                                $evaluation_rubric_detail));
                    }
                }
                $events[]= (new FeedbackEvent())
                    ->setProfile( new Profile( Profile::FEEDBACK ) )
                    ->setAction(new Action(Action::RANKED))
                    ->setObject(CaliperActor::generateActor($group_member))
                    ->setGenerated(CaliperEntity::rubric_overall_feedback_rating(
                        $rater,
                        $group_member,
                        $event_obj,
                        $results['Rubric'],
                        $results['RubricsCriteria'],
                        $evaluation_rubric));
            }
        }

        $events[]= (new AssessmentEvent())
            ->setProfile( new Profile( Profile::ASSESSMENT ) )
            ->setAction(new Action(Action::SUBMITTED))
            ->setObject(CaliperEntity::event_rubric(
                $event_obj,
                $results['Rubric'],
                $results['RubricsCriteria']));
        $events[]= (new ToolUseEvent())
            ->setProfile( new Profile( Profile::TOOL_USE ) )
            ->setAction(new Action(Action::USED))
            ->setObject(CaliperEntity::iPeer());

        CaliperSensor::sendEvent($events, $course, $group);
    }

    public static function rubric_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->find('first', array(
            'conditions' => array('Rubric.id' => $model->id),
            'contain' => array(
                'RubricsCriteria',
                'RubricsCriteria.RubricsCriteriaComment',
                'RubricsLom',
            )
        ));
    }

    public static function rubric_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $events = array();
        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);

        foreach($results['RubricsCriteria'] as $question) {
            $events[] = (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::rubric_question(
                    NULL, //event
                    $results['Rubric'],
                    $results['RubricsCriteria'],
                    $question,
                    $results['RubricsLom']));
        }

        $events[] = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::rubric(
                $results['Rubric'],
                $results['RubricsCriteria']));
        CaliperSensor::sendEvent($events);
    }

    public static function rubric_delete_criteria_partial($rubric, $criteria) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        return (new ResourceManagementEvent())
            # all criteria and loms are deleted on every save
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::rubric_question(
                NULL, //event
                $rubric['Rubric'],
                $rubric['RubricsCriteria'],
                $criteria,
                $rubric['RubricsLom']));
    }


    public static function rubric_save_with_criteria($model, $events, $modified_criteria_ids, $is_new) {
        if (!CaliperSensor::caliperEnabled()) { return; }

        $results =  $model->find('first', array(
            'conditions' => array('Rubric.id' => $model->id),
            'contain' => array(
                'RubricsCriteria',
                'RubricsLom',
                'RubricsCriteria.RubricsCriteriaComment',
            )
        ));

        foreach($results['RubricsCriteria'] as $question) {
            $action = in_array($question['id'], $modified_criteria_ids) ?
                Action::MODIFIED : Action::CREATED;

            $events[] = (new ResourceManagementEvent())
                # all criteria and loms are deleted on every save
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action($action))
                ->setObject(CaliperEntity::rubric_question(
                    NULL, //event
                    $results['Rubric'],
                    $results['RubricsCriteria'],
                    $question,
                    $results['RubricsLom']));
        }

        $action = $is_new ? Action::CREATED : Action::MODIFIED;
        $events[] = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action($action))
            ->setObject(CaliperEntity::rubric(
                $results['Rubric'],
                $results['RubricsCriteria']));

        CaliperSensor::sendEvent($events);
    }


    public static function submit_mixeval($eventId, $evaluator, $groupEventId, $groupId) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $User = \ClassRegistry::init('User');
        $Event = \ClassRegistry::init('Event');
        $Mixeval = \ClassRegistry::init('Mixeval');

        $events = array();

        $rater = $User->findUserByidWithFields($evaluator);
        $results = $Event->find('first', array(
            'conditions' => array('Event.id' => $eventId),
            'contain' => array(
                'Course',
                'Penalty',
                'EvaluationMixeval' => array(
                    'conditions' => array(
                        'EvaluationMixeval.evaluator =' => $evaluator,
                    ),
                    'EvaluationMixevalDetail'
                ),
                'GroupEvent' => array(
                    'conditions' => array(
                        'GroupEvent.id' => $groupEventId,
                    ),
                    'Group'
                )
            ),
        ));
        $event_obj = $results['Event'];
        $event_obj['Course'] = $results['Course'];
        $event_obj['Penalty'] = $results['Penalty'];
        $course = $results['Course'];
        $group = $results['GroupEvent'][0]['Group'];
        $user_evaluation_mixeval = $results['EvaluationMixeval'];

        $results = $Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $event_obj['template_id']),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));
        $groupMembers = $User->getEventGroupMembersNoTutors($groupId, $event_obj['self_eval'], $evaluator);
        $dropdown_scale_max = count($groupMembers) * 10.0;

        foreach($user_evaluation_mixeval as $evaluation_mixeval) {
            $group_member = NULL;
            foreach($groupMembers as $groupMember) {
                if ($evaluation_mixeval['evaluatee'] == $groupMember['User']['id']) {
                    $group_member = $groupMember['User'];
                    break;
                }
            }
            if ($group_member) {
                foreach($evaluation_mixeval['EvaluationMixevalDetail'] as $evaluation_mixeval_detail) {
                    $question = NULL;
                    foreach($results['MixevalQuestion'] as $q) {
                        if ($q['question_num'] == $evaluation_mixeval_detail['question_number']) {
                            $question = $q;
                            break;
                        }
                    }
                    if ($question) {
                        $events[]= (new AssessmentItemEvent())
                            ->setProfile( new Profile( Profile::ASSESSMENT ) )
                            ->setAction(new Action(Action::COMPLETED))
                            ->setObject(CaliperEntity::mixeval_group_member_question(
                                $event_obj,
                                $results['Mixeval'],
                                $results['MixevalQuestion'],
                                $question,
                                $dropdown_scale_max,
                                $group,
                                $group_member))
                            ->setGenerated(CaliperEntity::mixeval_group_member_response(
                                $event_obj,
                                $question,
                                $group,
                                $group_member,
                                $evaluation_mixeval,
                                $evaluation_mixeval_detail));

                        $action = ($question['mixeval_question_type_id'] == 1 || $question['mixeval_question_type_id'] == 4) ?
                            Action::RANKED : Action::COMMENTED;

                        $events[]= (new FeedbackEvent())
                            ->setProfile( new Profile( Profile::FEEDBACK ) )
                            ->setAction(new Action($action))
                            ->setObject(CaliperActor::generateActor($group_member))
                            ->setGenerated(CaliperEntity::mixeval_feedback(
                                $rater,
                                $group_member,
                                $event_obj,
                                $results['Mixeval'],
                                $question,
                                $dropdown_scale_max,
                                $evaluation_mixeval_detail));
                    }
                }
                $events[]= (new FeedbackEvent())
                    ->setProfile( new Profile( Profile::FEEDBACK ) )
                    ->setAction(new Action(Action::RANKED))
                    ->setObject(CaliperActor::generateActor($group_member))
                    ->setGenerated(CaliperEntity::mixeval_overall_feedback_rating(
                        $rater,
                        $group_member,
                        $event_obj,
                        $results['Mixeval'],
                        $results['MixevalQuestion'],
                        $evaluation_mixeval));
            }
        }

        $events[]= (new AssessmentEvent())
            ->setProfile( new Profile( Profile::ASSESSMENT ) )
            ->setAction(new Action(Action::SUBMITTED))
            ->setObject(CaliperEntity::event_mixeval(
                $event_obj,
                $results['Mixeval'],
                $results['MixevalQuestion']));
        $events[]= (new ToolUseEvent())
            ->setProfile( new Profile( Profile::TOOL_USE ) )
            ->setAction(new Action(Action::USED))
            ->setObject(CaliperEntity::iPeer());

        CaliperSensor::sendEvent($events, $course, $group);
    }

    public static function mixeval_before_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        //save info in data before deletion
        $model->data['caliper_delete'] = $model->find('first', array(
            'conditions' => array('Mixeval.id' => $model->id),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));

        if(array_key_exists('Event', $model->data['caliper_delete'])) {
            unset($model->data['caliper_delete']['Event']);
        }
    }

    public static function mixeval_after_delete($model) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $events = array();
        $results = $model->data['caliper_delete'];
        unset($model->data['caliper_delete']);

        foreach($results['MixevalQuestion'] as $question) {
            $events[] = (new ResourceManagementEvent())
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action(Action::DELETED))
                ->setObject(CaliperEntity::mixeval_question(
                    NULL, //event
                    $results['Mixeval'],
                    $results['MixevalQuestion'],
                    $question));
        }

        $events[] = (new ResourceManagementEvent())
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action(Action::DELETED))
            ->setObject(CaliperEntity::mixeval(
                $results['Mixeval'],
                $results['MixevalQuestion']));
        CaliperSensor::sendEvent($events);
    }

    public static function mixeval_save_deleted_question_partial($mixeval_id, $deleted_question_ids) {
        if (!CaliperSensor::caliperEnabled()) { return array(); }
        $Mixeval = \ClassRegistry::init('Mixeval');

        $results = $Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $mixeval_id),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));

        $events = array();
        foreach($results['MixevalQuestion'] as $question) {
            if (in_array($question['id'], $deleted_question_ids)) {
                $events[] = (new ResourceManagementEvent())
                    ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                    ->setAction(new Action(Action::DELETED))
                    ->setObject(CaliperEntity::mixeval_question(
                        NULL, //event
                        $results['Mixeval'],
                        $results['MixevalQuestion'],
                        $question));
            }
        }
        return $events;
    }

    public static function mixeval_save_with_questions($events, $mixeval_id, $existing_question_ids, $is_new) {
        if (!CaliperSensor::caliperEnabled()) { return; }
        $Mixeval = \ClassRegistry::init('Mixeval');

        $results = $Mixeval->find('first', array(
            'conditions' => array('Mixeval.id' => $mixeval_id),
            'contain' => array('MixevalQuestion', 'MixevalQuestion.MixevalQuestionDesc'),
            'recursive' => 2,
        ));
        foreach($results['MixevalQuestion'] as $question) {
            $action = in_array($question['id'], $existing_question_ids) ?
                Action::MODIFIED : Action::CREATED;
            $events[] = (new ResourceManagementEvent())
                # all criteria and loms are deleted on every save
                ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
                ->setAction(new Action($action))
                ->setObject(CaliperEntity::mixeval_question(
                    NULL, //event
                    $results['Mixeval'],
                    $results['MixevalQuestion'],
                    $question));
        }

        $action = $is_new ? Action::CREATED : Action::MODIFIED;
        $events[] = (new ResourceManagementEvent())
            # all criteria and loms are deleted on every save
            ->setProfile( new Profile( Profile::RESOURCE_MANAGEMENT ) )
            ->setAction(new Action($action))
            ->setObject(CaliperEntity::mixeval(
                $results['Mixeval'],
                $results['MixevalQuestion']));
        CaliperSensor::sendEvent($events);
    }
}

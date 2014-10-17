<?php
/* Besides the usual trouble needed to make each question types editable, the
 * primary complexity here is that there's a lot of fragile state to keep track
 * of due to the need to keep the questions numbered sequentially as a user
 * adds and removes questions.
 *
 * The key idea here is that a question's question_num field keeps track of
 * the order that the user wants the questions in while the question's array
 * index does NOT change while in the view.
 */

/* Each question type can have unique options for the user to configure, this
 * section creates the html template needed for each question type.
 */
function makeQ($view, $qType, $i, $qTypes, $selfEval, $quesNum, $required=true)
{
    $html = $view->Html;
    $form = $view->Form;

    $qTypeId = 0;
    $qHeader = "";
    $qFields = "";
    switch ($qType) {
    case 'Likert':
        $qHeader = __('Likert Answer Question', true);
        $qTypeId = array_search($qType, $qTypes);
        $qFields = likertFields($view, $i);
        break;
    case 'Paragraph':
        $qHeader = __('Paragraph Answer Question', true);
        $qTypeId = array_search($qType, $qTypes);
        break;
    case 'Sentence':
        $qHeader = __('Sentence Answer Question', true);
        $qTypeId = array_search($qType, $qTypes);
        break;
    case 'ScoreDropdown':
        $qHeader = __('Score Dropdown Answer Question', true);
        $qTypeId = array_search($qType, $qTypes);
        $qFields = scoredropdownFields($view, $i);
        break;
    default:
       return ""; // unrecognized question type
    }

    // Build the remove, move up, move down controls
    $removeLink = $html->link('x', '#',
        array(
            'class' => 'removeQ',
            'onclick' => "removeQ($i, $selfEval); return false;",
            'escape' => false
        )
    );
    $upLink = $html->link('▲', '#',
        array(
            'class' => 'upQ',
            'onclick' => "upQ($i, $selfEval); return false;",
            'escape' => false
        )
    );
    $downLink = $html->link('▼', '#',
        array(
            'class' => 'downQ',
            'onclick' => "downQ($i, $selfEval); return false;",
            'escape' => false
        )
    );
    $controls = "$removeLink $upLink $downLink";
    // If we're editing a previously saved question, will need to have an id for
    // the question.
    $hiddenIdField = "";
    if (isset($view->data['MixevalQuestion'][$i]['id'])) {
        $hiddenIdField = $form->hidden("MixevalQuestion.$i.id");
    }

    // give an ID to the question number for easy renumbering later on
    $qNum = $html->tag('span', $quesNum . ". ", array('id' => "questionIndex$i"));
    $requiredTxt = ($qType != 'Likert') ? '' :
        $html->div("help-text", __('Unrequired Likert questions are not counted toward the total rating.', true));
    $ret = $html->div('MixevalMakeQuestion',
        $html->tag('h3', "$controls $qNum $qHeader") .
        $hiddenIdField .
        $form->input("MixevalQuestion.$i.title",
            array("type" => "text", "label" => "Question")) .
        $form->input("MixevalQuestion.$i.instructions") .
        $form->input("MixevalQuestion.$i.required", array('checked' => $required)) .
        $form->hidden("MixevalQuestion.$i.self_eval", array('value' => $selfEval)) .
        $requiredTxt .
        $form->hidden("MixevalQuestion.$i.mixeval_question_type_id",
            array('value' => $qTypeId)) .
        $form->hidden("MixevalQuestion.$i.question_num",
            array('value' => $i + 1)) .
        $qFields
        ,
        array('id' => "question$i")
    );

    return $ret;
}

// Helper for creating a template for score dropdown questions
function scoredropdownFields($view, $i) {
    $html = $view->Html;
    $form = $view->Form;
    $ret = $form->hidden("MixevalQuestion.$i.multiplier",
            array('value' => 10));
    $ret = $ret.$html->div("help-text",
        __('The increments on the drop-down will be based on 10 base points per member, the drop-down will go from 1 to (10 x No. of GroupMembers) in increments of 1 <br>', true));
    return $ret;
}

// Helper for creating a template for likert questions
function likertFields($view, $i) {
    $html = $view->Html;
    $form = $view->Form;

    $descs = '';
    if (isset($view->data['MixevalQuestionDesc'])) {
        foreach ($view->data['MixevalQuestionDesc'] as $key => $d) {
            if ($d['question_index'] == $i) {
                // note that $key is indexed from 0 while we want the more
                // user friendly indexed from 1, hence the +1
                $descs .= makeDesc($view, $i, $key);
            }
        }
    }

    $ret = $form->input("MixevalQuestion.$i.multiplier",
        array('label' => 'Marks'));
    $ret .= $html->div("help-text",
        __('This mark will be scaled according to the response. E.g.: If there are 5 scale levels and this is set at 1, the lowest scale will be worth 0.2 marks, the second lowest 0.4 marks, and so on with the highest scale being worth the full 1 mark.', true));
    $ret .= $form->input("MixevalQuestion.$i.show_marks",
        array('label' => 'Show Marks', 'type' => 'checkbox'));
    $ret .= $html->div("help-text",
        __('This setting will hide/show the mark distribution to those taking the evaluation.', true));
    $ret .= $html->div('',
        $form->label(null, 'Scale', array('class' => 'defLabel')) .
        $form->button("Add", array('type' => 'button',
            'onclick' => "addDesc($i);")) .
        $html->div('DescsDiv', $descs, array('id' => "DescsDiv$i"))
    );
    return $ret;
}

// Create a template for question descriptors
function makeDesc($view, $qNum, $descNum) {
    $html = $view->Html;
    $form = $view->Form;

    // If we're editing a previously saved question, will need to have an id for
    // the question desc
    $hiddenIdField = "";
    if (isset($view->data['MixevalQuestionDesc'][$descNum]['id'])) {
        $hiddenIdField = $form->hidden("MixevalQuestionDesc.$descNum.id");
    }

    $ret = $html->div('MixevalQuestionDesc',
        $hiddenIdField .
        $form->text("MixevalQuestionDesc.$descNum.descriptor") .
        $form->hidden("MixevalQuestionDesc.$descNum.question_index",
            array('value' => $qNum, 'class' => "MixevalQuestionDesc$qNum")) .
        $html->link('x', '#',
            array(
                'class' => 'removeQ',
                'onclick' => "removeDesc($qNum, $descNum); return false;"
            )
        ),
        array('id' => "question{$qNum}desc$descNum")
    );
    return $ret;
}

/* Now that we have all the question templates, we need to check whether the
 * user is visiting this page from a failed submit. If the user is visiting
 * from a failed submit, we need to reload all the questions they've already
 * configured, so they don't have to enter them all over again.
 */
$numQ = 0; // for initializing the javascript counter that track question
$numPeer = 1; // for initializing the question number for peer evaluation questions
$numSelf = 1; // for initializing the question number for self evaluation questions
$numQArray = ""; // for initializing the javascript array that tracks questions
$numPeerQArray = ""; // for initializing the javascript array that tracks peer questions
$numSelfQArray = ""; // for initializing the javascript array that tracks self questions
$reloadedQ = "";
$reloadedSelfQ = "";
if (isset($this->data) && isset($this->data['MixevalQuestion'])) {
    $prevQs = $this->data['MixevalQuestion'];
    foreach ($prevQs as $q) {
        $required = $q['required'] ? true : false;
        $qType = $qTypes[$q['mixeval_question_type_id']];
        if ($q['self_eval']) {
            $reloadedSelfQ .= makeQ($this, $qType, $numQ, $qTypes, $q['self_eval'], $numSelf, $required);
            $numSelfQArray .= "$numQ,";
            $numSelf++;
        } else {
            $reloadedQ .= makeQ($this, $qType, $numQ, $qTypes, $q['self_eval'], $numPeer, $required);
            $numPeerQArray .= "$numQ,";
            $numPeer++;
        }
        $numQArray .= "$numQ,";
        $numQ++;
    }
    // because IE is stupid we need to remove trailing commas
    if (!empty($numQArray)) {
        $numQArray = substr($numQArray, 0, -1);
    }
}
// initialize the javascript counter that tracks descriptors
$numDesc = 0;
if (isset($this->data['MixevalQuestionDesc'])) {
    $numDesc = count($this->data['MixevalQuestionDesc']);
}


// peer evaluation questions section
echo $html->tag('h3', __('Peer Evaluation Questions', true));
$addQButton = $form->button(__('Add', true),
    array('type' => 'button', 'onclick' => "insertQ(false);"));
echo $form->input('MixevalQuestionTypePeer', array('after' => $addQButton,
    'options' => $qTypes));
echo $html->div('', $reloadedQ, array('id' => 'questions', 'class' => 'questions'));

// self evaluation questions section
echo '<div id="self-eval-ques">';
echo $html->tag('h3', __('Self-Evaluation Questions', true));
$addQButton = $form->button(__('Add', true),
    array('type' => 'button', 'onclick' => "insertQ(true);"));
$selfQTypes = $qTypes;
unset($selfQTypes[4]); // remove score dropdown from self-evaluation
echo $form->input('MixevalQuestionTypeSelf', array('after' => $addQButton,
    'options' => $selfQTypes));
echo $html->div('', $reloadedSelfQ, array('id' => 'selfQues', 'class' => 'questions'));
echo '</div>';
?>

<script type="text/javascript">
// tracking variables that tells us what ID to give to the next question or desc
var numQ = <?php echo $numQ; ?>; // the total number of questions
var numPeer = <?php echo $numPeer ?>; // next question number for peer evaluation ques
var numSelf = <?php echo $numSelf ?>; // next question number for self evaluation ques
// the total number of descriptors
var numDesc = <?php echo $numDesc; ?>;
// keeps track of currently valid user ids, cause users can remove questions
var questionIds = [<?php echo $numQArray; ?>];
var peerQuesIds = [<?php echo $numPeerQArray; ?>]; // for peer evaluation section
var selfQuesIds = [<?php echo $numSelfQArray; ?>]; // for self-evaluation section
// templates for each question type, the negative numbers will be replaced
// with an appropriate ID (from the tracking variables)
// -1 is for numQ, -2 is for numDesc
// Note that we're using negative numbers because of problems with quote
// escaping in strings with cakephp generated onclick attributes.
var likertQ = '<?php echo makeQ($this, 'Likert', -1, $qTypes, -2, -3); ?>';
var sentenceQ = '<?php echo makeQ($this, 'Sentence', -1, $qTypes, -2, -3); ?>';
var paragraphQ = '<?php echo makeQ($this, 'Paragraph', -1, $qTypes, -2, -3); ?>';
var scoredropdownQ = '<?php echo makeQ($this, 'ScoreDropdown', -1, $qTypes, -2, -3); ?>';
var desc = '<?php echo makeDesc($this, -1, -2); ?>';


// Add a question
function insertQ(self_eval) {
    var sub = self_eval ? 'Self' : 'Peer';
    var type = jQuery('#MixevalMixevalQuestionType'+sub+' option:selected').text();
    var q = "";
    switch (type) {
    case "Likert":
        q = likertQ;
        break;
    case "Paragraph":
        q = paragraphQ;
        break;
    case "Sentence":
        q = sentenceQ;
        break;
    case "ScoreDropdown":
        q = scoredropdownQ;
        break;
    default:
        return "";
    }
    var section = self_eval ? '#selfQues' : '#questions';
    if (self_eval) {
        self = 1;
        num = numPeer;
        numPeer++;
    } else {
        self = 0;
        num = numSelf;
        numSelf++;
    }
    q = q.replace(/-1/g, numQ);
    q = q.replace(/-2/g, self);
    q = q.replace(/-3/g, num);
    jQuery(q).hide().appendTo(section).fadeIn(600);
    questionIds.push(numQ);
    if (self_eval) {
        selfQuesIds.push(numQ);
    } else {
        peerQuesIds.push(numQ);
    }
    numQ++;
    reorderQ();
}

// Remove a question
function removeQ(qNum, self_eval) {
    var target = jQuery('#question' + qNum);
    target.addClass('remove');
    target.hide('blind', 600, function() { target.remove(); });
    questions = self_eval ? selfQuesIds : peerQuesIds;
    questions.splice(questions.indexOf(qNum), 1); // remove from the section's list
    questionIds.splice(questionIds.indexOf(qNum), 1); // remove from main list
    reassignQuesArray(self_eval, questions);
    reorderQ();
}

// Move question up one, e.g.: swap position with question above it
function upQ(qNum, self_eval) {
    questions = self_eval ? selfQuesIds : peerQuesIds;
    var i = questions.indexOf(qNum);
    var j = questionIds.indexOf(qNum);
    // make sure not to move topmost question of the section
    if (i != 0) {
        aboveDiv = jQuery('#question' + questions[i - 1]);
        thisDiv = jQuery('#question' + qNum);
        // swap places with the question above us
        thisDiv.hide('drop', 300, function() {
            thisDiv.insertBefore(aboveDiv).show('drop', 500);
            reorderQ();
        });
        // make sure the change is reflected in the js arrays
        questions[i] = questions[i - 1];
        questions[i - 1] = qNum;
        questionIds[j] = questionIds[j - 1];
        questionIds[j - 1] = qNum;
    }
    reassignQuesArray(self_eval, questions);
}

// Move question down one, e.g.: swap position with question below it
function downQ(qNum, self_eval) {
    questions = self_eval ? selfQuesIds : peerQuesIds;
    var i = questions.indexOf(qNum);
    var j = questionIds.indexOf(qNum);
    // make sure not to move bottommost question
    if (i < questions.length - 1) {
        belowDiv = jQuery('#question' + questions[i + 1]);
        thisDiv = jQuery('#question' + qNum);
        // swap places with the question below us
        thisDiv.hide('drop', 300, function() {
            thisDiv.insertAfter(belowDiv).show('drop', 500);
            reorderQ();
        });
        // make sure the change is reflected in the js array
        questions[i] = questions[i + 1];
        questions[i + 1] = qNum;
        questionIds[j] = questionIds[j + 1];
        questionIds[j + 1] = qNum;
    }
    reassignQuesArray(self_eval, questions);
}

// After we've deleted a question, we should renumber the questions so that
// they're sequential again
function reorderQ() {
    // reorder peer evaluation questions
    j = 0; // overall questions order
    for (var i = 0; i < peerQuesIds.length; i++) {
        var staticId = peerQuesIds[i];
        jQuery("#questionIndex" + staticId).text(i + 1 + '. ');
        jQuery("#MixevalQuestion" + staticId + "QuestionNum").val(j + 1);
        console.log(".MixevalQuestionDesc" + staticId);
        jQuery(".MixevalQuestionDesc" + staticId).val(j + 1);
        j++;
    }
    // reorder self evaluation questions
    for (i = 0; i < selfQuesIds.length; i++) {
        var staticId = selfQuesIds[i];
        jQuery("#questionIndex" + staticId).text(i + 1 + '. ');
        jQuery("#MixevalQuestion" + staticId + "QuestionNum").val(j + 1);
        console.log(".MixevalQuestionDesc" + staticId);
        jQuery(".MixevalQuestionDesc" + staticId).val(j + 1);
        j++;
    }
}

// Add a question descriptor, this is used to configure things like scale
// levels in likert questions
function addDesc(numQ) {
    var insert = desc.replace(/-1/g, numQ);
    insert = insert.replace(/-2/g, numDesc);
    jQuery(insert).hide().appendTo('#DescsDiv' + numQ).fadeIn(350);
    numDesc++;
}

// Remove a question descriptor
function removeDesc(qNum, numDesc) {
    var target = jQuery('#question' + qNum + 'desc' + numDesc);
    target.addClass('remove');
    target.hide('blind', 350, function() { target.remove(); });
}

// update the appropriate question array
function reassignQuesArray(self_eval, questions) {
    if (self_eval) {
        selfQuesIds = questions;
    } else {
        peerQuesIds = questions;
    }
}

</script>


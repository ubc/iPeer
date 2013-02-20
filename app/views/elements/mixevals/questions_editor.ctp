<?php
/* Besides the usual trouble needed to make each question types editable, the 
 * primary complexity here is that there's a lot of fragile state to keep track 
 * of due to the need to keep the questions numbered sequentially as a user 
 * adds and removes questions.
 */

/* Each question type can have unique options for the user to configure, this 
 * section creates the html template needed for each question type. 
 */
function makeQ($view, $qType, $i, $qTypes)
{
    $html = $view->Html;
    $form = $view->Form;

    // TODO PERSISTENCE FOR mixeval question desc
    $qTypeId = 0;
    $qHeader = "";
    $qFields = "";
    switch ($qType) {
    case 'Likert':
        $qHeader = _('Likert Answer Question');
        $qTypeId = array_search($qType, $qTypes);
        $qFields = likertFields($view, $i);
        break;
    case 'Paragraph':
        $qHeader = _('Paragraph Answer Question');
        $qTypeId = array_search($qType, $qTypes);
        break;
    case 'Sentence':
        $qHeader = _('Sentence Answer Question');
        $qTypeId = array_search($qType, $qTypes);
        break;
    default:
       return ""; // unrecognized question type
    }

    $removeLink = $html->link('x', '#', 
        array(
            'class' => 'removeQ', 
            'onclick' => "removeQ($i); return false;",
            'escape' => false
        )
    );
    $upLink = $html->link('▲', '#', 
        array(
            'class' => 'upQ', 
            'onclick' => "upQ($i); return false;",
            'escape' => false
        )
    );
    $downLink = $html->link('▼', '#', 
        array(
            'class' => 'downQ', 
            'onclick' => "downQ($i); return false;",
            'escape' => false
        )
    );
    $controls = "$removeLink $upLink $downLink";
    // give an ID to the question number for easy renumbering later on
    $qNum = $html->tag('span', "$i. ", array('id' => "questionNum$i"));
    $ret = $html->div('MixevalMakeQuestion',
        $html->tag('h3', "$controls $qNum $qHeader") .
        $form->input("MixevalQuestion.$i.title", 
            array("type" => "text", "label" => "Question")) .
        $form->input("MixevalQuestion.$i.instructions") .
        $form->input("MixevalQuestion.$i.required") .
        $form->hidden("MixevalQuestion.$i.mixeval_question_type_id",
            array('value' => $qTypeId)) .
        $form->hidden("MixevalQuestion.$i.question_num", array('value' => $i)) .
        $qFields
        ,
        array('id' => "question$i")
    );

    return $ret;
}

// Helper for creating a template for likert questions
function likertFields($view, $i) {
    $html = $view->Html;
    $form = $view->Form;

    $descs = '';
    if (isset($view->data['MixevalQuestionDesc'])) {
        foreach ($view->data['MixevalQuestionDesc'] as $key => $d) {
            if ($d['question_id'] == $i) {
                // note that $key is indexed from 0 while we want the more
                // user friendly indexed from 1, hence the +1
                $descs .= makeDesc($view, $i, $key);
            }
        }
    }

    $ret = $form->input("MixevalQuestion.$i.multiplier", 
        array('label' => 'Marks'));
    $ret .= $html->div("help-text", 
        _('This mark will be scaled according to the response. E.g.: If there are 5 scale levels and this is set at 1, the lowest scale will be worth 0.2 marks, the second lowest 0.4 marks, and so on with the highest scale being worth the full 1 mark.'));
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

    $ret = $html->div('MixevalQuestionDesc',
        $form->text("MixevalQuestionDesc.$descNum.descriptor") .
        $form->hidden("MixevalQuestionDesc.$descNum.question_id", 
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
$numQ = 1; // for initializing the javascript counter that track questions
$numQArray = ""; // for initializing the javascript array that tracks questions
$reloadedQ = "";
if (isset($this->data) && isset($this->data['MixevalQuestion'])) {
    $prevQs = $this->data['MixevalQuestion'];
    foreach ($prevQs as $q) {
        $qType = $qTypes[$q['mixeval_question_type_id']];
        $reloadedQ .= makeQ($this, $qType, $numQ, $qTypes);
        $numQArray .= "$numQ,";
        $numQ++;
    }
    // because IE is stupid we need to remove trailing commas
    if (!empty($numQArray)) {
        $numQArray = substr($numQArray, 0, -1);
    }
}
// initialize the javascript counter that tracks descriptors
$numDesc = 1;
if (isset($this->data['MixevalQuestionDesc'])) {
    $numDesc = count($this->data['MixevalQuestionDesc']) + 1;
}

// Finally, we create the div that will hold all these questions
echo $html->div('', $reloadedQ, array('id' => 'questions'));
?>

<script type="text/javascript">
// tracking variables that tells us what ID to give to the next question or desc
var numQ = <?php echo $numQ; ?>; // the total number of questions + 1
// the total number of descriptors + 1
var numDesc = <?php echo $numDesc; ?>; 
// keeps track of currently valid user ids, cause users can remove questions
var questionIds = new Array(<?php echo $numQArray; ?>);
// templates for each question type, the negative numbers will be replaced
// with an appropriate ID (from the tracking variables)
// -1 is for numQ, -2 is for numDesc
// Note that we're using negative numbers because of problems with quote
// escaping in strings with cakephp generated onclick attributes.
var likertQ = '<?php echo makeQ($this, 'Likert', -1, $qTypes); ?>';
var sentenceQ = '<?php echo makeQ($this, 'Sentence', -1, $qTypes); ?>';
var paragraphQ = '<?php echo makeQ($this, 'Paragraph', -1, $qTypes); ?>';
var desc = '<?php echo makeDesc($this, -1, -2); ?>';


// Add a question
function insertQ() {
    var type = jQuery('#MixevalMixevalQuestionType option:selected').text();
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
    default:
        return "";
    }
    q = q.replace(/-1/g, numQ);
    jQuery(q).hide().appendTo('#questions').fadeIn(600);
    questionIds.push(numQ);
    numQ++;
    reorderQ();
}

// Remove a question
function removeQ(qNum) {
    var target = jQuery('#question' + qNum);
    target.addClass('remove');
    target.hide('blind', 600, function() { target.remove(); });
    questionIds.splice(questionIds.indexOf(qNum), 1);
    reorderQ();
}

// Move question up one, e.g.: swap position with question above it
function upQ(qNum) {
    var i = questionIds.indexOf(qNum);
    // make sure not to move topmost question
    if (i != 0) {
        aboveDiv = jQuery('#question' + questionIds[i - 1]);
        thisDiv = jQuery('#question' + qNum);
        // swap places with the question above us
        thisDiv.hide('drop', 300, function() {
            thisDiv.insertBefore(aboveDiv).show('drop', 500);
            reorderQ();
        });
        // make sure the change is reflected in the js array
        questionIds[i] = questionIds[i - 1];
        questionIds[i - 1] = qNum;
    }
}

// Move question down one, e.g.: swap position with question below it
function downQ(qNum) {
    var i = questionIds.indexOf(qNum);
    // make sure not to move bottommost question
    if (i < questionIds.length - 1) {
        belowDiv = jQuery('#question' + questionIds[i + 1]);
        thisDiv = jQuery('#question' + qNum);
        // swap places with the question below us
        thisDiv.hide('drop', 300, function() {
            thisDiv.insertAfter(belowDiv).show('drop', 500);
            reorderQ();
        });
        // make sure the change is reflected in the js array
        questionIds[i] = questionIds[i + 1];
        questionIds[i + 1] = qNum;
    }
}

// After we've deleted a question, we should renumber the questions so that
// they're sequential again
function reorderQ() {
    for (var i = 0; i < questionIds.length; i++) {
        var staticId = questionIds[i];
        jQuery("#questionNum" + staticId).text(i + 1 + '. ');
        jQuery("#MixevalQuestion" + staticId + "QuestionNum").val(i + 1);
        console.log(".MixevalQuestionDesc" + staticId);
        jQuery(".MixevalQuestionDesc" + staticId).val(i + 1);
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

</script>


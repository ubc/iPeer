<?php echo $html->script('calculate_marks');?>
<?php
$descriptor_des = array('1'=>'Lowest','2'=>'','3'=>'Middle','4'=>'','5'=>'Highest');
isset($data['questions'])? $questions = $data['questions'] : $questions = null;
$scale_default = $data['Mixeval']['scale_max'];
$zero_mark = $data['Mixeval']['zero_mark'];
$default_options = array('type' => 'text',
                         'cols' => 20,
                         'rows' => 3,
                         'label' => false,
                         'before' => '',
                         'after' => '',
                         'between' => '',
                         'readonly' => $readonly,
                        );
?>

<div class="section" id="section">
  <?php for($i = 0; $i < $data['Mixeval']["total_question"]; $i++):?>
  <div class="question">
    <div class="question-tab"><span>Q<?php echo ($i+1)?></span></div>
    <div class="remove-button">X</div>
    <div class="question-content">
      <?php echo $this->Form->input('Question.'.$i.'.id', array('type' => 'hidden'))?>
      <?php echo $this->Form->input('Question.'.$i.'.question_type', array('type' => 'hidden'))?>
      <?php echo $this->Form->input('Question.'.$i.'.question_num', array( 'type' => 'hidden', 'value' => ($i+1), 'order' => 'true'))?>
      <?php switch($data['Question'][$i]['question_type']) {
        case 'T':
          echo $this->element('mixevals/text_question', array('i' => $i, 'default_options' => $default_options));
          break;
        case 'S':
          echo $this->element('mixevals/scale_question', array('i' => $i, 
                                                              'q' => $data['Question'][$i],
                                                              'scale_default' => $scale_default,
                                                              'zero_mark' => $zero_mark,
                                                              'readonly' => $readonly,
                                                              'default_options' => $default_options));
          break;
      }?>
    </div>
  </div>
  <?php endfor;?>
</div>


<div align="right" style="background-color: #EAEAEA;">
  	Total Marks: <input type="text" name="total_marks" id="total" class="input" size="5" readonly>
</div>

<div align="right">
  <button id="add-lickert-button" type="button">Add Lickert Question</button>
  <button id="add-text-button" type="button">Add Comment Question</button>
</div>

<script type="text/javascript">
function updateOrder() {
  $$('div.question-tab span').each(function(s, index) {
    s.update('Q'+(index+1));
  });

  $$('input[order]').each(function(s, index) {
    s.value = index+1;
  });
}

function addRemoveHandler(e) {
  $(e).observe('click', function(event) {
    var id = Event.element(event).next().down().value;
    if( id != "") {
      new Ajax.Request('<?php echo $this->Html->url('deleteQuestion')?>/'+id, {
        onFailure: function(response) {
          alert('Failed to remove question.');
          return false;
        }
      });
    }

    Effect.DropOut(Event.element(event).up(), {afterFinish: function(effect) {
      $(effect.effects[0].element).remove();
      calculateTotalMarks($$('.multiplier'));
      updateOrder();
    }});
  });
}

function observeMultiplierChange(e) {
  $(e).observe('change', function() {
    calculateTotalMarks($$('.multiplier'));
    calculateWeight(e, zero_mark.checked);
  });
}

document.observe('dom:loaded', function() { 
  var max_order = $$('input[order]').length;

  Sortable.create('section', { elements: $$('div.question'), constraint:'vertical', handles: $$('div.question-tab'),
    onChange: function(item) {  
      updateOrder();
    },  
                  
    onUpdate: function(list) {  
    },
  });

  $$('.multiplier').each(function (e) {
    observeMultiplierChange(e);
    calculateWeight(e, zero_mark.checked);
  });

  calculateTotalMarks($$('.multiplier'));

  $$('.remove-button').each(function (e) {
    addRemoveHandler(e);
  });

  $('zero_mark').observe('change', function() {
    $$('.multiplier').each(function (e) {
      calculateWeight(e, zero_mark.checked);
    });
  });


  $w('a div p span img table thead td th tr tbody tfoot input textarea select option').each(function(e) {
    window['$' + e] = function() {
      return new Element(e, arguments[0]);
    }
  });

  $('add-text-button').observe('click', function() {
    max_order++;
    var newElement = $div({class: "question"})
      .insert($div({class: "question-tab"})
              .insert("<span>Q"+($$('div.question-tab span').length+1)+"</span>"))
      .insert($div({class: "remove-button"}).insert("X"))
      .insert($div({class: "question-content"})
              .insert($input({type: "hidden",
                             name: "data[Question]["+max_order+"][question_type]",
                             value: "T"}))
              .insert($input({type: "hidden",
                             name: "data[Question]["+max_order+"][question_num]",
                             order: "true",
                             value: max_order}))
              .insert($table()
                      .insert($tr()
                              .insert($td({width: "180px"}).insert('Question Prompt:'))
                              .insert($td().insert($textarea({name: "data[Question]["+max_order+"][title]",
                                                             class: "question-title"}))))
                      .insert($tr()
                              .insert($td().insert('Mandatory?'))
                              .insert($td().insert($input({name: "data[Question]["+max_order+"][required]",
                                                   type: "checkbox"}))))
                      .insert($tr()
                              .insert($td().insert('Instruction:'))
                              .insert($td().insert($textarea({name: "data[Question]["+max_order+"][instructions]",
                                                      class: "question-instruction"}))))
                      .insert($tr()
                              .insert($td().insert("Student's Answer Option:"))
                              .insert($td().insert($input({name: "data[Question]["+max_order+"][response_type]",
                                                          type: "radio",
                                                          checked: "checked",
                                                          value: "S"}))
                                      .insert("Single line of text input box ")
                                      .insert($input({name: "data[Question]["+max_order+"][response_type]",
                                                     type: "radio",
                                                     value: "T"}))
                                      .insert("Multiple lines of text input box (Maximum 65535 characters)"))))
              );
    $("section").appendChild(newElement);
    Sortable.create("section", { elements: $$('div.question'), constraint:'vertical', handles: $$('div.question-tab'),
      onChange: function(item) {  
        updateOrder();
      },  
    });
    addRemoveHandler($$(".question").last().down(".remove-button"));
  });

  $('add-lickert-button').observe('click', function() {
    max_order++;
    var descriptors;
    var descriptor_text = $tr();
    var descriptor_input = $tr();
    var descriptor_mark = $tr();
    var options = $select({name: "data[Question]["+max_order+"][multiplier]",
                          class: "multiplier"});

    for(var i = 0; i < <?php echo $scale_default?>; i++) {
      descriptor_text.insert($td().insert("Descriptor:"));
      descriptor_input.insert($td().insert($textarea({name: "data[Question]["+max_order+"][Description]["+i+"][descriptor]",
                                                     class: "question-descriptor"})));
      descriptor_mark.insert($td().insert("Mark:").insert($input({name: "marks",
                                                                 readonly: "readonly",
                                                                 class: "criteria-mark",
                                                                 type: "text"})));
    }

    for(var i = 1; i <= 15; i ++) {
      options.insert($option({value: i}).insert(i));
    }

    descriptor_text.insert($td().insert("&nbsp;"));
    descriptor_input.insert($td().insert("&nbsp;"));
    descriptor_mark.insert($td().insert("Scale Weight: ").insert(options));

    var newElement = $div({class: "question"})
      .insert($div({class: "question-tab"})
              .insert("<span>Q"+($$('div.question-tab span').length+1)+"</span>"))
      .insert($div({class: "remove-button"}).insert("X"))
      .insert($div({class: "question-content"})
              .insert($input({type: "hidden",
                             name: "data[Question]["+max_order+"][question_type]",
                             value: "T"}))
              .insert($input({type: "hidden",
                             name: "data[Question]["+max_order+"][question_num]",
                             order: "true",
                             value: max_order}))
              .insert($table()
                      .insert($tr()
                              .insert($td({colspan: 6}).insert($textarea({name: "data[Question]["+max_order+"][title]",
                                                             class: "question-title"}))))
                      .insert(descriptor_text)
                      .insert(descriptor_input)
                      .insert(descriptor_mark))
              );

    $("section").appendChild(newElement);
    Sortable.create("section", { elements: $$('div.question'), constraint:'vertical', handles: $$('div.question-tab'),
      onChange: function(item) {  
        updateOrder();
      },  
    });
    addRemoveHandler($$(".question").last().down(".remove-button"));
    observeMultiplierChange($$(".question").last().down(".multiplier"));
    calculateWeight($$(".question").last().down(".multiplier"), zero_mark.checked);
    calculateTotalMarks($$('.multiplier'));
  });
});  
</script>

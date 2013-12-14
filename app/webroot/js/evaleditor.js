/********************************
 * Evalution Editor
 * Create evaluation form using javascript to enable rich interactive editor
 * By Compass
 *
 * Parameters:
 *   container: container element to generate the evaluation editor
 *   questions: question data array
 *   options:   the setup options
 *     zero_mark: the zero mark element, default: "zero_mark"
 *     delete_question_url: the URL to delete question for ajax call
 *     delete_descriptor_url: the URL to delete descriptor for ajax call
 *
 * Example:
 *
 * new EvalEditor("evaleditor",
 *                <?php echo json_encode($data['Question'])?>,
 *                {delete_question_url: "<?php echo $this->Html->url('deleteQuestion')?>/",
 *                 delete_descriptor_url: "<?php echo $this->Html->url('deleteDescriptor')?>/",
 *                });
 ********************************/

var EvalEditor = Class.create({
  initialize: function(container, questions) {
    var defaults = {
      zero_mark: "zero_mark",
      delete_question_url: "",
      delete_descriptor_url: "",
    };

    this.options = Object.extend(defaults, arguments[2] || { });

    this.container = $(container);
    this.max_order = 1;
    this.descriptor_indexes = new Array();

    // set the zero mark control
    if(this.options.zero_mark != "") {
      this.options.zero_mark = $('zero_mark');
      this.options.zero_mark.observe("change", this._onChangeZeroMark.bindAsEventListener(this));
    }

    this.generate(questions);
  },

  num_questions: function() {
    return this.container.select('.question').length;
  },

  generate: function(questions) {
    this.section = Builder.node('div', {className: "section"});
    this.summary = Builder.node('div', {id: "summary"}, [
      "Total Marks: ",
      Builder.node('input', {type: "text", name: "total_marks", id: "total", className: "input", readonly: "true"})
    ]);
    this.controls = Builder.node('div', {id: "controls"}, [
      Builder.node('button', {id: "add-lickert-button", type: "button"}, "Add Lickert Question"),
      Builder.node('button', {id: "add-text-button", type: "button"}, "Add Comment Question"),
    ]);

    // hook up the observers
    Element.observe($(this.controls).down('#add-lickert-button'), 'click', function() {this.addQuestion({mixeval_question_type_id: "1"})}.bind(this));
    Element.observe($(this.controls).down('#add-text-button'), 'click', function() {this.addQuestion({mixeval_question_type_id: "2"})}.bind(this));

    // add new elements to the container
    this.container.appendChild(this.section);
    this.container.appendChild(this.summary);
    this.container.appendChild(this.controls);

    // add questions

    if(questions) {
      for(var i = 0; i < questions.length; i++) {
    	this.addQuestion(Object.extend(questions[i], {index: i}));
      }
    }
  },

  addQuestion: function() {
    var defaults = {
      mixeval_question_type_id: "2",
      title: "",
      id: "",
    };
    var options = Object.extend(defaults, arguments[0] || { });

    var question_body;

    if(options.mixeval_question_type_id == "1") {
      question_body = this._generateLickertQuestion(Object.extend(options, {index: this.max_order}));
    } else {
      question_body = this._generateTextQuestion(Object.extend(options, {index: this.max_order}));
    }

    var questionElement = Builder.node("div", {className: "question", index: this.max_order}, [
      Builder.node("div", {className: "question-tab"}, [
        Builder.node("span", "Q"+(this.container.select('div.question-tab span').length+1))
      ]),
      Builder.node("div", {className: "question-content"}, [
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][id]",
                     className: "question-id",
                     value: options.id}),
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][mixeval_question_type_id]",
                     value: options.mixeval_question_type_id}),
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][question_num]",
                     order: "true",
                     value: this.max_order}),
        Builder.node("div", [
          Builder.node("div", {className: "question-title"}, [
            Builder.node("textarea", {name: "data[Question]["+this.max_order+"][title]",
                         className: "question-title-textarea",
                         cols: "20",
                         rows: "3"}, options.title)
            ]),
          question_body
        ])
      ])
    ]);

    var elem = Builder.node("div", {"class": "remove-button"}, "X");
    $(elem).observe('click', this.onRemoveQuestion.bindAsEventListener(this));

    questionElement.down(".question-tab").insert({after: elem});

    this.section.appendChild(questionElement);
    this.max_order++;
    this._calculateTotalMarks();

    Sortable.create(this.section, {
      elements: this.section.select('div.question'),
      constraint:'vertical',
      handles: this.section.select('div.question-tab'),
      onChange: this._updateOrder.bindAsEventListener(this),
    });

  },

  onRemoveQuestion: function(event) {
    var question = event.element().up();
    this.removeQuestion(question);
  },

  removeQuestion: function(question) {
    // remove from server if exists
    var id = $(question).down('.question-id').value;
    if( id != "") {
      new Ajax.Request(this.options.delete_question_url+id, {
        onFailure: function(response) {
          alert('Failed to remove question.');
          return false;
        }
      });
    }

    // remove from page
    Effect.DropOut(question, {afterFinish: function(effect) {
      $(effect.effects[0].element).remove();
      this._calculateTotalMarks();
      this._updateOrder();
    }.bind(this)});
  },

  _generateDescriptor: function(options) {
    var defaults = {
      descriptor: "",
      index: 0,
      question_index: 0,
      id: "",
    };

    options = Object.extend(defaults, options || { });

    var descriptor = Builder.node("div", {"class": "descriptor"}, [
      Builder.node("input", {type: "hidden",
                             name: "data[Question]["+options.question_index+"][Description]["+options.index+"][id]",
                             className: "descriptor-id",
                             value: options.id}),
      Builder.node("div", Builder.node("textarea", {name:"data[Question]["+options.question_index+"][Description]["+options.index+"][descriptor]",
                                                    cols: 20,
                                                    rows: 3,
                                                    className:"question-descriptor"}, options.descriptor)),
      Builder.node("button", {"class": "remove-descriptor-button", "type": "button"}, "X"),
      "Mark:",
      Builder.node("input", {name: "marks",
                             readonly: "readonly",
                             className: "criteria-mark",
                             size: 3,
                             type: "text"})
    ]);

    descriptor.down('.remove-descriptor-button').observe('click', this._onRemoveDescriptor.bindAsEventListener(this));
    return descriptor;
  },

  _generateLickertQuestion: function(options) {
    var defaults = {
      required: false,
      instructions: "",
      response_type: "S",
      index: 0,
      multiplier: 1,
      Description: [],
    };

    var options = Object.extend(defaults, options || { });

    var descriptors = Builder.node("div", {className: "descriptors"});

    // generate multiplier element
    var multiplier  = Builder.node("input", {type: "text", name: "data[Question]["+options.index+"][multiplier]", className: "multiplier", value: options.multiplier, size: "10"});
    /*
    var multiplier  = Builder.node("select", {name: "data[Question]["+options.index+"][multiplier]",
                                              className: "multiplier"});
    for(var i = 1; i <= 15; i ++) {
      if(options.multiplier == i) {
        $(multiplier).appendChild(Builder.node("option", {value: i, selected: "selected"}, i));
      } else {
        $(multiplier).appendChild(Builder.node("option", {value: i}, i));
      }
    }
    */

    // generate question body
    var question_body = Builder.node("div", {className: "question-body"}, [
      Builder.node("div", {"class": "descriptor-title"}, [
        "Descriptors: ",
        Builder.node("button", {className: "add-descriptor-button", "type": "button"}, "Add")
      ]),
      Builder.node("div", {"class": "scale-weight"}, ["Scale Weight", multiplier]),
      descriptors
    ]);

    // generate descriptors, this has to be after append multiplier as we need
    // multiplier to calcuate marks
    for(var i = 0; i < options.Description.length; i++) {
      this.addDescriptor(descriptors, Object.extend(options.Description[i], {question_index: options.index}));
    }

    $(question_body).down(".add-descriptor-button").observe('click', this._onAddDescriptor.bindAsEventListener(this, descriptors));
    $(multiplier).observe('change', this.onChangeMultiplier.bindAsEventListener(this, multiplier));
    return question_body;
  },

  _generateTextQuestion: function(options) {
    var defaults = {
      required: false,
      instructions: "",
      response_type: "L",
      index: 0,
    };

    var options = Object.extend(defaults, options || { });

    var question_body = Builder.node("div", {className: "question-body"} , Builder.node("table", [
      Builder.node("tr", [
        Builder.node("td","Mandatory"),
        Builder.node("td", Builder.node("input", {name: "data[Question]["+options.index+"][required]",
                                                  id: "Question"+options.index+"Mandatory",
                                                  type: "checkbox"}))
      ]),
      Builder.node("tr", [
        Builder.node("td", "Instruction:"),
        Builder.node("td", Builder.node("textarea", {name: "data[Question]["+options.index+"][instructions]",
                                                     className: "question-instruction-textarea",
                                                     cols: "50",
                                                     rows: "3"}, options.instructions))
      ]),
      Builder.node("tr", [
        Builder.node("td", "Student's Answer Option:"),
        Builder.node("td", [ Builder.node("input", {name: "data[Question]["+options.index+"][response_type]",
                                                    id: "Question"+options.index+"ResponseTypeS",
                                                    type: "radio",
                                                    value: "S"}),
                             "Single line of text input box ",
                             Builder.node("input", {name: "data[Question]["+options.index+"][response_type]",
                                                    id: "Question"+options.index+"ResponseTypeL",
                                                    type: "radio",
                                                    value: "L"}),
                             "Multiple lines of text input box (Maximum 65535 characters)"
        ])
      ])
    ]));

    // set the values for checkboxes
    if(options.required) {
      $(question_body).down("#Question"+options.index+"Mandatory").checked = true;
    }
    $(question_body).down("#Question"+options.index+"ResponseType"+options.response_type).checked = true;

    return question_body;
  },

  _updateOrder: function() {
    this.container.select('div.question-tab span').each(function(s, index) {
      s.update('Q'+(index+1));
    });

    this.container.select('input[order]').each(function(s, index) {
      s.value = index+1;
    });
  },

  _onAddDescriptor: function(event) {
    var container = event.element().up(1).down(".descriptors");
    this.addDescriptor(container);
  },

  addDescriptor: function(container) {
    var options = arguments[1] || {};

    if(options.question_index == undefined) {
      options.question_index = $(container).up(3).readAttribute("index");
    }

    // initialize indexes if not defined
    if(this.descriptor_indexes[options.question_index] == undefined) {
      this.descriptor_indexes[options.question_index] = 0;
    }

    // generate descriptor
    $(container).appendChild(this._generateDescriptor(Object.extend({index: this.descriptor_indexes[options.question_index]}, options)));

    // clean up and recalculate
    this.descriptor_indexes[options.question_index]++;
    this._calculateWeight(container.up().down(".multiplier"));
  },

  // a multiplier just changed
  onChangeMultiplier: function(e) {
    var elem = event.element();
    this._calculateTotalMarks();
    this._calculateWeight(elem);
  },

  _calculateTotalMarks: function() {
  	var totalMark = 0;

    $(this.container).select(".multiplier").each(function(s) {
      totalMark += parseInt(eval(s.value));
    });

    $(this.container).down('#total').value = totalMark;
  },

  _calculateWeight: function(e) {
    var zero_mark_value = this.options.zero_mark.checked ? 1 : 0;
    var weight = $(e).value / ($(e).up(1).select('.criteria-mark').length - zero_mark_value);
    $(e).up(1).select('.criteria-mark').each(function(s, index) {
      $(s).value = Math.round(weight * (index+1-zero_mark_value) * 100) / 100;
    });
  },

  _onChangeZeroMark: function() {
    this.container.select(".multiplier").each(function(e) {
      this._calculateWeight(e);
    }.bind(this));
  },

  _onRemoveDescriptor: function(event) {
    var descriptor = event.element().up();
    this.removeDescriptor(descriptor);
  },

  removeDescriptor: function(descriptor) {
    var container = descriptor.up(1);

    // remove from server if exists
    var id = $(descriptor).down('.descriptor-id').value;
    if( id != "") {
      new Ajax.Request(this.options.delete_descriptor_url+id, {
        onFailure: function(response) {
          alert('Failed to remove descriptor.');
          return false;
        }
      });
    }

    // remove from page
    Effect.Fade(descriptor, {afterFinish: function(effect) {
      $(effect.element).remove();
      this._calculateWeight(container.down(".multiplier"));
    }.bind(this)});
  },
});


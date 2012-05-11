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
 *
 *
 *THIS IS A VERSION OF EVALEDITOR, MODIFIED FOR VIEWING ONLY
 *
 *
 * Example:
 *
 * new EvalEditor("evalviewer",
 *                <?php echo json_encode($data['Question'])?>); 
 ********************************/

var EvalViewer = Class.create({
  initialize: function(container, questions) {
    var defaults = {
      zero_mark: "zero_mark"
    };

    this.options = Object.extend(defaults, arguments[2] || { });

    this.container = $(container);
    this.max_order = 0;
    this.descriptor_indexes = new Array();
    
    // set the zero mark control
    if(this.options.zero_mark != "") {
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
      Builder.node('div', {type: "text", name: "total_marks", id: "total", className: "input", readonly: "true", style: "float:right; width:20px; padding-right:15px"})
    ]);
  

   
    // add new elements to the container 
    this.container.appendChild(this.section);
    this.container.appendChild(this.summary);


    // add questions
    if(questions) {
      for(var i = 0; i < questions.length; i++) {
        this.addQuestion(Object.extend(questions[i], {index: i}));
      }
    }
  },

  addQuestion: function() {
    var defaults = {
      question_type: "T",
      title: "",
      id: "",
    };

    var options = Object.extend(defaults, arguments[0] || { });

    var question_body;
    if(options.question_type == "S") {
      question_body = this._generateLickertQuestion(Object.extend(options, {index: this.max_order}));
    } else {
      question_body = this._generateTextQuestion(Object.extend(options, {index: this.max_order}));
    }

    var questionElement = Builder.node("div", {className: "question", index: this.max_order}, [
      Builder.node("div", {className: "question-tab", style:"cursor: auto"}, [
        Builder.node("span", "Q"+(this.container.select('div.question-tab span').length+1))
      ]),
      Builder.node("div", {className: "question-content"}, [
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][id]",
                     className: "question-id",
                     value: options.id}),
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][question_type]",
                     value: options.question_type}),
        Builder.node("input", {type: "hidden",
                     name: "data[Question]["+this.max_order+"][question_num]",
                     order: "true",
                     value: this.max_order}),
        Builder.node("div", [
          Builder.node("div", {className: "question-title"}, [
            Builder.node("div", {name: "data[Question]["+this.max_order+"][title]",
                         className: "question-title-textarea",
                         style: "width: 100%; height: 4.6em; background-color: #f5f5f5; border: 1px solid #bfbfbf; padding: 0.2em;",
                         cols: "20",
                         rows: "3"}, options.title)
            ]),
          question_body
        ])
      ])
    ]);

    this.section.appendChild(questionElement);
    this.max_order++;
    this._calculateTotalMarks();

  },



  _generateDescriptor: function(options) {
    var defaults = {
      descriptor: "",
      index: 0,
      question_index: 0,
      id: "",
    };

    options = Object.extend(defaults, options || { });

    var descriptor = Builder.node("div", {'class': "descriptor"}, [
      Builder.node("input", {type: "hidden",
                             name: "data[Question]["+options.question_index+"][Description]["+options.index+"][id]",
                             className: "descriptor-id",
                             value: options.id}),
      Builder.node("div", Builder.node("div", {name:"data[Question]["+options.question_index+"][Description]["+options.index+"][descriptor]",
    	  											style: "width: 11.6em; height: 3.6em; background-color: #f5f5f5; border: 1px solid #bfbfbf; padding: 0.2em;",
                                                    className:"question-descriptor"}, options.descriptor)),
      "Mark:",
      Builder.node("input", {name: "marks",
                             readonly: "readonly",
                             className: "criteria-mark",
                             size: 3,
                             type: "text"})
    ]);

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
    var multiplier  = Builder.node("span", {name: "data[Question]["+options.index+"][multiplier]",
                                              className: "multiplier", style:""}, options.multiplier);


    // generate question body
    var question_body = Builder.node("div", {className: "question-body"}, [
      Builder.node("div", {'class': "descriptor-title"}, [
        "Descriptors: "
          ]),
         
      Builder.node("div", {'class': "scale-weight", style:""}, ["Scale Weight:", multiplier]),
      descriptors
    ]);

      // generate descriptors, this has to be after append multiplier as we need
    // multiplier to calcuate marks
    for(var i = 0; i < options.Description.length; i++) {
      this.addDescriptor(descriptors, Object.extend(options.Description[i], {question_index: options.index}));
    }


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
                                                  type: "checkbox",
                                                  disabled: "true"}))
      ]),
      Builder.node("tr", [
        Builder.node("td", "Instruction:"),
        Builder.node("td", Builder.node("div", {name: "data[Question]["+options.index+"][instructions]",
                                                     className: "question-instruction-textarea",
                                                     cols: "50",
                                                     rows: "3",style: "width: 96.5%; height: 4.2em; background-color: #f5f5f5; border: 1px solid #bfbfbf; padding: 0.2em;",
                                                     }, options.instructions))
      ]),
      Builder.node("tr", [
        Builder.node("td", "Student's Answer Option:"),
        Builder.node("td", [ Builder.node("input", {name: "data[Question]["+options.index+"][response_type]",
                                                    id: "Question"+options.index+"ResponseTypeS",
                                                    type: "radio",
                                                    value: "S",
                                                    disabled: "true"}),
                             "Single line of text input box ",
                             Builder.node("input", {name: "data[Question]["+options.index+"][response_type]",
                                                    id: "Question"+options.index+"ResponseTypeL",
                                                    type: "radio",
                                                    value: "L",
                                                    disabled: "true"}),
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


  _calculateTotalMarks: function() {
  	var totalMark = 0;

    $(this.container).select(".multiplier").each(function(s) {
      totalMark += parseInt(eval(s.innerHTML));
    });

    $(this.container).down('#total').innerHTML = totalMark;
  },

  _calculateWeight: function(e) {
    var zero_mark_value = this.options.zero_mark.checked ? 1 : 0;
    
  //  alert $("data[Question][0][multiplier]").value);
    var weight = $(e).innerHTML / ($(e).up(1).select('.criteria-mark').length - zero_mark_value);
    $(e).up(1).select('.criteria-mark').each(function(s, index) {
      $(s).value = Math.round(weight * (index+1-zero_mark_value) * 100) / 100;
    });
  },


});


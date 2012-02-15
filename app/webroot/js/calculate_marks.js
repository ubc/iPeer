function calculateMarks(lom_num, criteria, zero_mark, model) {
	// calculate total marks
	var totalMark = 0;

	for(var j = 0; j < criteria; j++) {
		var field2 = model+j+"Multiplier";
		var c_weight = parseInt(eval("document.getElementById('" + field2 + "').value"));
		totalMark = parseInt(totalMark) + c_weight;
	}
	document.getElementById('total').value = totalMark;

	// calculate each mark field
  for(var i = 0; i < criteria; i++) {
    for(var j = 0; j < lom_num; j++) {
      var field = model+"Mark"+i+""+j;
      var field2 = model+i+"Multiplier";
      var c_weight = parseInt(eval("document.getElementById('" + field2 + "').value"));

      if(zero_mark=="1")
        var mark = (c_weight/(lom_num-1))*j;
      else
        var mark = (c_weight/lom_num)*(j+1);

      mark = Math.round(mark * 100) / 100;

      eval("document.getElementById('" + field + "').value="+mark) ;
    }
  }
	/*
	for(i=0; i<document.frm.elements.length; i++)
	{
		document.write("The field name is: " + document.frm.elements[i].name + " and it's value is: " + document.frm.elements[i].value + ".<br />");
	}
	*/
}



function calculateTotalMarks(weight_list) {
	// calculate total marks
	var totalMark = 0;

  weight_list.each(function(s) {
    totalMark += parseInt(eval(s.value));
  });

  $('total').value = totalMark;
}

function calculateWeight(e, zero_mark) {
  var zero_mark_value = zero_mark ? 1 : 0;
  var weight = $(e).value / ($(e).up(1).select('.criteria-mark').length - zero_mark_value);
  $(e).up(1).select('.criteria-mark').each(function(s, index) {
    $(s).value = Math.round(weight * (index+1-zero_mark_value) * 100) / 100;
  });
}

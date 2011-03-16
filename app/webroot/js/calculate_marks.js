function calculateMarks(lom_num, criteria, zero_mark) {
	// calculate total marks
	var totalMark = 0;

	for(var j = 1; j <= criteria; j++) {
		var field2 = "criteria_weight_"+j;
		var c_weight = parseInt(eval("document.getElementById('" + field2 + "').value"));
		totalMark = parseInt(totalMark) + c_weight;
	}
	document.getElementById('total').value = totalMark;

	// calculate each mark field
	for(var i = 1; i <= criteria; i++) {
		for(var j = 1; j <= lom_num; j++) {
			var field = "criteria_mark_" +i +"_" +j;
			var field2 = "criteria_weight_"+i;
			var c_weight = parseInt(eval("document.getElementById('" + field2 + "').value"));

			if(zero_mark=="on")
				var mark = (c_weight/(lom_num-1))*(j-1);
			else
				var mark = (c_weight/lom_num)*j;

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

function checkSubmit(){
	if (!$('frm')['include_course'].getValue() && !$('frm')['include_eval_event_names'].getValue()) {
		alert('Please include at least one of Course Name and Event Name');
		return false;}

	if (!$('frm')['include_student_name'].getValue() && !$('frm')['include_student_id'].getValue()) {
			alert('Please include at least one of Student Name and Student id');	
		return false;}

	if(!$$('input.csv')[0].getValue() && !$$('input.csv')[1].getValue() && $('frm')['export_type'].getValue() =='excel') {
		alert('Please include at least one of the orange fields');	
		return false;
	}
	return true;
}

Event.observe(window, 'load', function(){
    $$('.export_type').each(function(chk1){
      chk1.observe('click', function(evt){
        var csv = ($('frm')['export_type'].getValue() =='excel');
				$$('input.csv').each(function(s) {
        	if (csv) {
        		s.enable().up('tr').removeClassName('cssDisabled');
        	} else { 
        		s.disable().up('tr').addClassName('cssDisabled');
        	}
        });
      });
    });
});
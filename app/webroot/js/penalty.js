var i = 0;

function displayPenalty(){ 

	 	if($('PenaltySetupType').getValue() == 'simple'){
			$('penaltyAdvanced').hide(); $('simplePenalty').show();
			$$('#simplePenalty .penaltyInput').each(function(s) {
	        		s.down('input').enable();	        		
			});
			$$('#penaltyAdvanced .percentage').each(function(s) {
        		s.disable();	        		
		});
		}
	 	if($('PenaltySetupType').getValue() == 'advanced'){
			$('penaltyAdvanced').show(); $('simplePenalty').hide();			
			$$('#simplePenalty .penaltyInput').each(function(s) {
        		s.down('input').disable();	        		
			});
			$$('#penaltyAdvanced .percentage').each(function(s) {
    		s.enable();	        		
			});	
		}
}


function validatePenalty(){
  if($('EventPenalty1').checked){
	  var after = $('PenaltySetupPenaltyAfter').getValue();
  	if($('PenaltySetupType').getValue() == 'simple'){
  	  var days = $('PenaltySetupNumberOfDays').getValue();
  	  var deduction = $('PenaltySetupPercentagePerDay').getValue();
  
  
  	  if (!days) {alert('Please enter number of days for deduction.'); return false;}
  	  if (!deduction) {alert('Please enter Penalty Deduction per day.'); return false;}
  	  if (deduction<0 || deduction>100) {alert('Deduction per Day should be between 1% and 100% (inclusively).'); return false;}
  	 
  	  var maximum = (100/deduction).ceil();
    	if(days>maximum) {
    			alert('Number of Days cannot be more then ' + maximum + ' at this deduction per day'); return false;
  		}
    	var minimum = days*deduction;
      if(minimum > 100){minimum = 100;}
      if(after < minimum){
      	alert('Final Deduction cannot be less then ' + minimum + ' at these settings'); return false;
      }  		
  	}
  	if($('PenaltySetupType').getValue() == 'advanced'){
  	  for(var k = 1; k<=i; k++){
  	  	var pen = $("penaltyDay" + k).down('.percentage').getValue();
  	  		if(!pen) {alert('Please enter deduction for day '+ k); return false;}
					if(pen<0 || pen>100) {alert('Penalty for day ' + k + ' has to be between 0 and 100'); return false;}
					if(k>1) {
			  	  var prev = $("penaltyDay" + (k-1)).down('.percentage').getValue();
			  	        alert(prev." ".pen);
						if(prev>pen) {
							alert('Penalty for day ' + k + ' cannot be less then the previous day entry'); return false;
						}
					}
  	    }
  	    var last = $("penaltyDay" + i).down('.percentage').getValue();
		if((after < last) && (after!=100)){alert( 'Final deduction cannot be less then deduction for day ' + i); return false;}
  	  }
	if (!after) {alert('Please enter final Deduction.'); return false;}
	if (after<1 || after>100) {alert('Final deduction should be between 1% and 100%.'); return false;}
  }

  return true;
}

function addDay(percent) {
	if(percent === undefined ){percent = '';}
	i++;	
  var day = Builder.node("tr", {id: "penaltyDay" + i}, [ Builder.node("td", {class: "numberOfDays"}, i),                                                           
                                                           Builder.node("td", {class: "per"},[ 
																											Builder.node("input", {class: "percentage", name: "data[Penalty][" + i + "][percent_penalty]", value: percent})
																										]), 	 Builder.node("td", {class: "warning", value: i}),  Builder.node("input", {type:"hidden", name:"data[Penalty][" + i + "][days_late]", value: i})                                                 
                                                   ]);
  $('penaltyTable').appendChild(day);       
}

function removeDay(){
	$('penaltyDay'+ i).remove();
	i--;
}


function maximum(x){
	if(x.getValue() > 100){
		x.up('.penaltyInput').down('.warning').update('Cannot be more then 100%');
	}
}

function minimum(){
	if($('PenaltySetupType').getValue() == 'simple'){
  	var days = $('PenaltySetupNumberOfDays').getValue();
  	var deduction = $('PenaltySetupPercentagePerDay').getValue();
  	var after = $('PenaltySetupPenaltyAfter').getValue();
  	var minimum = days*deduction;
  	if(minimum > 100){minimum = 100;}
  	if(after < minimum){
  		$('PenaltySetupPenaltyAfter').up('.penaltyInput').down('.warning').update('Cannot be less then ' + minimum);
  	}
  }
}

function calculateDays(){
	var days = $('PenaltySetupNumberOfDays').getValue();
	var deduction = $('PenaltySetupPercentagePerDay').getValue();
	var maximum = (100/deduction).ceil();
		if(days>maximum) {
				$('PenaltySetupNumberOfDays').up('.penaltyInput').down('.warning').update('Cannot be more then ' + maximum + ' at this deduction per day');
		}
}

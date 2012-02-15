
var gErrors = 0; //number of errors is set to none to begin with

function Trim(TRIM_VALUE){
  if(TRIM_VALUE.length < 1){
    return"";
  }
  TRIM_VALUE = RTrim(TRIM_VALUE);
  TRIM_VALUE = LTrim(TRIM_VALUE);
  if(TRIM_VALUE==""){
    return "";
  }
  else{
    return TRIM_VALUE;
  }
} //End Function

function RTrim(VALUE){
var w_space = String.fromCharCode(32);
var v_length = VALUE.length;
var strTemp = "";
  if(v_length < 0){
    return"";
  }
  var iTemp = v_length -1;

  while(iTemp > -1){
    if(VALUE.charAt(iTemp) == w_space){
  }
  else{
    strTemp = VALUE.substring(0,iTemp +1);
    break;
  }
  iTemp = iTemp-1;

  } //End While
return strTemp;

} //End Function

function LTrim(VALUE){
var w_space = String.fromCharCode(32);
  if(v_length < 1){
    return"";
  }
  var v_length = VALUE.length;
  var strTemp = "";

  var iTemp = 0;

  while(iTemp < v_length){
    if(VALUE.charAt(iTemp) == w_space){
  }
  else{
    strTemp = VALUE.substring(iTemp,v_length);
  break;
  }
  iTemp = iTemp + 1;
  } //End While
return strTemp;
} //End Function


function validate()
{
var tables; //variable which will become an array holding all elements with the td tagname
var msgTab;
var requiresField;
gErrors = 0;

  //Check for Required Field in hidden input variable "required" is Empty or not
  if (document.getElementById('required') != null) {
		requiresField = document.getElementById('required').value.split(' '); //get all required fields

		for (i=0; i<requiresField.length; i++) {
			requiredF = requiresField[i];
			requiredValue = document.getElementById(requiredF).value;
			//requiredLabel = document.getElementById(requiredF+'_label');
      requiredLabel = $$('label[for='+requiredF+']');
			if (requiredValue=='') {
			  //Required field left in blank
			  gErrors = gErrors + 1;
			  //Set its label color
        requiredLabel[0].setStyle({color:'#ff0000'});
			} else {
			  //Check its valiate return error or not
			  requiredMessage = document.getElementById(requiredF+'_msg');
        if (requiredMessage != null) { // Fix for bug #210 in IE
          if (Trim(requiredMessage.innerHTML) != '' || Trim(requiredMessage.innerHTML) != '\n' )
          {
            requiredLabel[0].setStyle({color:'#000000'});//the color is changed to blank or stays black
          } else {
            gErrors = gErrors + 1; //the error count increases by 1
            requiredLabel[0].setStyle({color:'#ff0000'});//error messages are changed to red
          }
        }
			}
	  }
  }

  //Check for error message for all type of valiation error
/*  tables = document.getElementsByTagName('td');
	for (j=0; j<tables.length; j++)
	{
  	// if the class name of that td element is rules check to see if there are error warnings
  	if (tables[j].className == "error")
  		{
  			//if there is a thank you or its blank then it passes
  			if (Trim(tables[j].innerHTML) == '' || Trim(tables[j].innerHTML) == '\n')
  			{
  			tables[j].style.color = '#000000';//the color is changed to blank or stays black
  			}
  			else
  			{
  			gErrors = gErrors + 1; //the error count increases by 1
  			tables[j].style.color = '#ff0000';//error messages are changed to red
  			}
  		}
	}*/
	if (gErrors > 0){
		//if there are any errors give a message
		alert ("Please make sure all fields are properly inputed.\n\nError/Required field(s) are marked in red!");
		gErrors = 0;// reset errors to 0
		return false;
	}

  return true;
}

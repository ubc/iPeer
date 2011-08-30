window.onload = attachFormHandlers;
//var USERNAME_FORMAT = /^[a-zA-Z0-9@_\.-]{6,}$/;
var STUDENTNO_FORMAT = /^[a-zA-Z0-9@_\.-]{6,}$/;
var PASSWORD_FORMAT = /^[a-zA-Z0-9]{6,}$/;
var EMAIL_FORMAT = /^[\w-\.]+\@[\w\.-]+\.[a-z]{2,4}$/;
//var TEXT_FORMAT = /^[\w ]+$/; //at least one word
var TEXT_FORMAT = /^.+$/; //at least one word
var DATE_FORMAT = /(((0[13578]|10|12)([-.\/])(0[1-9]|[12][0-9]|3[01])([-.\/])(\d{4}))|((0[469]|11)([-.\/])([0][1-9]|[12][0-9]|30)([-.\/])(\d{4}))|((2)([-.\/])(0[1-9]|1[0-9]|2[0-8])([-.\/])(\d{4}))|((2)(\.|-|\/)(29)([-.\/])([02468][048]00))|((2)([-.\/])(29)([-.\/])([13579][26]00))|((2)([-.\/])(29)([-.\/])([0-9][0-9][0][48]))|((2)([-.\/])(29)([-.\/])([0-9][0-9][2468][048]))|((2)([-.\/])(29)([-.\/])([0-9][0-9][13579][26])))/;
var NUMERIC_FORMAT = /^(\d|-)?(\d|,)*\.?\d*$/;
var PHONE_FORMAT = /^(\d{3}-\d{3}-\d{4})*$/;


function attachFormHandlers()
{
  var form = document.getElementById('frm') // get the form
  if (document.getElementsByTagName)//make sure were on a newer browser
  {
    var objInput = document.getElementsByTagName('input'); // store all input fields
    for (var iCounter=0; iCounter<objInput.length; iCounter++)
    //objInput[iCounter].onchange = function(){return attach(this);} //attach the onchange to each input field
		if (objInput[iCounter].className != "") {
			objInput[iCounter].onblur = function(){return attach(this);} //attach the onblur to each input field
		}
  }
  if (form != null)
  form.onsubmit = function(){return validate();} //attach validate() to the form
}

var gContinue = true;
function attach(objInput) {
    var sVal = objInput.value; //get value inside of input field
    var sFeedBack; //feedback is the feedback message sent back to the user
    gContinue = true;

    var sRules = objInput.className.split(' '); // get all the rules from the input box classname
    var sValidate = sRules[0];                  // validate means we will validate the field
    var sRequired = sRules[1];                  // required means field is required
    var sTypeCheck = sRules[2];                 // typecheck are additional validation rules (ie. email, phone, date)
    var sFeedbackLoc = sRules[3];               // feedbackLoc is the td id where feedback is sent to.
    var sErrorMsg =  sRules[4];                 // error message if it is invalid

    sFeedback = validateRequired (sRequired, sVal, sTypeCheck); //validateRequired() checks if it is required and then sends back feedback

    if (gContinue) //if it is required and blank gContinue is false and we don't validate anymore.  // this is done because if it is blank
    //it will also fail other tests.  We don't want to spam the user with INVALID EMAIL!! if the field is still blank.
    {
        // check the different validation cases (ie: email, phone, etc.)
        sFeedback = validateObject(sRequired, sTypeCheck, sVal, sErrorMsg);
    }
    // after validation is complete return the feedback
    if (sFeedback == null || sFeedback == undefined) {
        sFeedback = "";
    }

    // Fixes bug #211
    var sFeedbackLocElem = document.getElementById(sFeedbackLoc);
    if (sFeedbackLocElem) {
        sFeedbackLocElem.innerHTML = sFeedback;
    }
}

function validateRequired(sRequired, sVal, sTypecheck)
{
  if (sRequired == "required")  //check if required if not, continue validation script
  {
    if (sVal == "") //if it is rquired and blank then it is an error and continues to be required
    {
      gContinue = false;
      return  "Required";
    }
    else if (sTypecheck == "none")  //if its not blank and has no other validation requirements the field passes
    {
      return "";
    }
  }
}

function validateObject(sRequired, sFormat, sVal, sErrorMsg)
{
  //Get the value of the format
  var sRegExp = eval(sFormat);

  //return OK if it is not a required field
  if (sVal =="" && sRequired == "none")
  {
    return "";
  }
  // do the comparison, prompt error message if we do not have a match
  if (sRegExp != null && !sRegExp.test(sVal))
  {
      return sErrorMsg.replace(/_/g, ' ');    // replace all '_' to space
  } else {
     return '';
  }
}


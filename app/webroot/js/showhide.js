var state = 'none';

function showhide(layer_ref) {
	hza = document.getElementById(layer_ref);

	if (hza.style.display == 'block') {
		state = 'none';
	}
	else {
		state = 'block';
	}
	/*if (document.all) { //IS IE 4 or 5 (or 6 beta)
		eval( "document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
		document.layers[layer_ref].display = state;
	}*/
	/*if (document.getElementById) {
		hza = document.getElementById(layer_ref);
		hza.style.display = state;
	}*/
}

function toggle1(link)
{
	var lText = link.innerHTML;
	//var showHideFlag = document.getElementById(link).innerHTML;
	if (lText == '[+]') { link.innerHTML = '[-]'; }
	else { link.innerHTML = '[+]'; }
}


Element.activate = function(element, activate) {
    element = $(element);

    // get the inactive text, which defaults to the element's innerHTML
    var inactive_text = element['inactivetext'];
    if(!inactive_text) {
      element['inactivetext'] = element.innerHTML;
      inactive_text = element.innerHTML;
    }

    // get the active text and active class, which default to the current
    // innerHTML and className, respectively
    var active_text = element.getAttribute('activetext') || element.innerHTML;
    var active_class = element.getAttribute('activeclass') || element.className;

    if(!activate) {
      element.innerHTML = inactive_text;
      if(active_class) Element.removeClassName(element, active_class);
      element['active'] = false;
    } else {
      element.innerHTML = active_text;
      if(active_class) Element.addClassName(element, active_class);
      element['active'] = true;
    }
  }

   function toggleEdit(divName, editLinkName) {

    Element.toggle(divName);
    Element.activate(editLinkName, !$(editLinkName)['active']);
    return false;
  }


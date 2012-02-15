/**
 *
 * This is the main JavaScript file for Data Bindings. It depends on
 * Prototype (tested with Prototype 1.3.1; http://prototype.conio.net).
 * While Data Bindings currently targets CakePHP, this file can be used
 * independently of Cake, or indeed without any server-side support at
 * all.
 *
 * Data Bindings for CakePHP
 * Copyright (c) 2005, David A. Feldman (http://InterfaceThis.com)
 *
 * Author(s): David Feldman aka Dave
 *
 *  Licensed under The MIT License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource 
 * @author       David A. Feldman
 * @copyright    Copyright (c) 2005 David A. Feldman
 * @link         
 * @package      cake_bindings
 * @subpackage   
 * @since        Data Bindings v0.1
 * @version      0.1
 * @modifiedby   Dave
 * @lastmodified 2005-09-20
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License
 */

// [class BoundProperty]
var BoundProperty = function(idString) {
	this.string = idString;
	}

	BoundProperty.prototype.object = null;
	BoundProperty.prototype.parentProperty = null;
	BoundProperty.prototype.property = null;
	BoundProperty.prototype.type = null;
	
	BoundProperty.prototype.parse = function() {
		// only execute if this property hasn't already been parsed
		if (!this.type) {
			if (this.string.indexOf('/') != -1) {
				this.type = 'url';
				this.url = this.string;
				return true;
			}
			else {
				// bound item is not a URL; parse source
				this.type = 'property';
				var parts = this.string.split('.');
				if (parts.length < 2)
					return Binding.error('Bound property must have at least two parts, i.e. "object.property"');
				
				// get object
				this.object = $(parts[0]);
				if (!this.object)
					return Binding.error('Attempt to bind nonexistant object ' + parts[0]);
							
				var testProp = this.object;
				for (var i=1; i < parts.length-1; i++) {
					if (!testProp[parts[i]])
						return Binding.error('Attempt to bind sub-property of nonexistant property.');
					testProp = testProp[parts[i]];
				}
				
				this.parentProperty = testProp;
				this.property = parts[i];							
			}
		}
		return true;
	}


// [/class BoundProperty]


// [class Binding]
var Binding = function(src, target) {
		this.src = new BoundProperty(src);
		this.target = new BoundProperty(target);
			
		Binding.bindingTable.push(this);
	}

	Binding.bindingTable = new Array;
	Binding.objectTable = new Object;
	
	Binding.create = function(src, target, triggers) {
		if (!triggers)
			triggers = null;
		
		return new Binding(src, target, triggers);
	}
	
	Binding.init = function() {
		for (var i = 0; i < Binding.bindingTable.length; i++) {
			Binding.bindingTable[i].init();
		}
	}
	
	
	Binding._triggerHandler = function(event) {
		var myEvent = window.event ? window.event : event;
		
		return Binding.updateObject(this, myEvent.type);
	}
	
	Binding.updateObject = function(objectOrUrl) {
		var obid = (typeof objectOrUrl == 'string') ? objectOrUrl : objectOrUrl.id;
		if (Binding.objectTable[obid]) {
			// one or more bindings exist with this object as source
			for (var i = 0; i < Binding.objectTable[obid].length; i++) {
				Binding.objectTable[obid][i].update();
			}
		}
	}
	Binding.propagate = Binding.updateObject;
		
	
	Binding.error = function(errString) {
		alert("Binding Error: " + errString);
		return false;
	}

	Binding.prototype.triggers = null;
	Binding.prototype.lastValue = 'BINDING_UNDEFINED';
	Binding.prototype.processing = false;
	
	Binding.prototype.init = function() {
		this.triggers = new Object;
		
		if (!this.src.parse())
			return Binding.error('Error parsing binding.');

		if (this.src.type == 'property') {
			var obID = this.src.object.id;
			if (!(Binding.objectTable[obID]))
				Binding.objectTable[obID] = new Array;
			Binding.objectTable[obID].push(this);
			
			// get object type
			if (this.src.object.tagName) {
				// it's an HTML element
				switch (this.src.object.tagName) {
					case 'INPUT':
					case 'TEXTAREA':
						if (this.src.object.type == 'text') {
							this.triggers['change'] = true;
						}
						break;
					default:
						this.triggers['click'] = true;
						break;
				}
				
				// so, add event handlers
				for (i in this.triggers)
					this.src.object['on' + i] = Binding._triggerHandler;
			}
							
		}
		else if (this.src.type == 'url') {
			if (!(Binding.objectTable[this.src.url])) {
				Binding.objectTable[this.src.url] = new Array;
			}
			Binding.objectTable[this.src.url].push(this);
		}
	}
	
	Binding.prototype.update = function(val) {
		var newValue;
		if (val)
			newValue = val;
		else if (this.src.type == 'property')
			newValue = this.src.parentProperty[this.src.property];
		else if (this.src.type == 'url') {
			var params = 'reqType=get';
			new Ajax.Request(this.src.url, {parameters: params, onComplete: this._srcCallback()});
			this.processing = true;
			newValue = 0;
		}
		
		if (!this.processing) {
			// Bindings cache their last values to avoid
			// unnecessary updates.
			if (newValue != this.lastValue) {
				this.target.parse();
				if (this.target.type == 'url') {
					var params = 'reqType=put&value=' + newValue;
					new Ajax.Request(this.target.url, {method: 'post', parameters: params, onComplete: this._targetCallback()});
					this.processing = true;
				}
				else if (this.target.type == 'property') {
					if (this.target.object && this.target.parentProperty)
						this.target.parentProperty[this.target.property] = newValue;
					Binding.propagate(this.target.object.id);
				}
			}
		}
	}
	
	Binding.prototype._srcCallback = function() {
		var thisBinding = this;
		return function(req) {
			thisBinding.processing = false;
			thisBinding.update(req.responseText);
		}
	}
	
	Binding.prototype._targetCallback = function() {
		var thisBinding = this;
		return function(req) {
			thisBinding.processing = false;
			Binding.propagate(thisBinding.target.url);
		}
	}
// [/class Binding]


function getChildrenByTag(e, tag) {
   var children = [];
   var allChildren = e.childNodes;
	 if (allChildren == null) return [];
   for( var i = 0 ; i < allChildren.length ; i++ ) {
      if ( allChildren[i] && allChildren[i].tagName && allChildren[i].tagName == tag )
         children.push(allChildren[i]);
	 }
   return children;
}

function getChildDivs(e) {
   return getChildrenByTag(e, 'DIV');
}

Rico.PanelManager = Class.create();
Rico.PanelManager.prototype = {

	initialize: function(containerDiv, options) {
		this.containerDiv = $(containerDiv);
		this.options = options;
        this.options.maxPanels = this.options.maxPanels || 1;
		this.selectedPanels = Array();
		this.panels = this._parsePanels(this.containerDiv, options)
        if (this.options.selectPanelIndex > 0)
            this.options.selectPanelIndex = Array(this.options.selectPanelIndex);
        else if (typeof(this.options.selectPanelIndex) != 'object')
            this.options.selectPanelIndex = Array('0');
        var self = this;
        this.options.selectPanelIndex.each(function(index){self.panels[index].select();});
	},
	
	setSelector: function(panel, selector) {
		panel.setSelector(selector);
		this.selectorsMap[selector] = panel;
	},
	
  _parsePanels: function(container, options) {
			var panels = [];
		  this.selectorsMap = {};
      var panels = getChildDivs(container);

      for ( var i = 0 ; i < panels.length ; i++ ) {
         var panelDiv  = panels[i];
         var panelChildren = getChildDivs(panelDiv);

         if ( panelChildren.length != 2 )
            continue; 			
				 var panel = new ManagedPanel(this, panelChildren[0], panelChildren[1], panelDiv, options);
				 panels[i] = panel;
      }
			return panels;
   },
	
	 showTabByIndex: function( anIndex, animate ) {
      var doAnimate = arguments.length == 1 ? true : animate;
      this.showTab( this.accordionTabs[anIndex], doAnimate );
   },

   updatePanel: function(panelDiv, url) {         
         var panelChildren = getDirectChildrenByTag(panelDiv,'DIV');
         if ( panelChildren.length != 2 )
            return; // unexpected

         var headerDiv   = panelChildren[0];
         var contentDiv = panelChildren[1];
         new Ajax.Updater(contentDiv.id, url, 
                          {asynchronous:true, evalScripts:true}); 
   },
   
   panelToClose: function () {
       if (this.selectedPanels.length > this.options.maxPanels) {
           var panelToClose = this.selectedPanels.shift();
           panelToClose.markUnselected();
           return panelToClose;
       } else {
           return false;
       }
    }
}


ManagedPanel = Class.create();
ManagedPanel.prototype = {
	initialize: function(manager, title, content, panelDiv, options) {
		this.manager = manager;
		this.title = title;
		this.content = content;
		this.panel = panelDiv;
		this.selector = title; //default is title
		this.selected = false;
		this.options = manager.options;
		this.onSelect = options.onSelect;
		this.listenerHover    = this.hover.bindAsEventListener(this)
		this.listenerEndHover = this.unHover.bindAsEventListener(this)
		this.listenerSelect   = this.select.bindAsEventListener(this);
		this.listenerClick    = this.click.bindAsEventListener(this);
		this.isLoaded = false;
		this.activate();
	},
	
	setSelector: function(selector) {
		this.selector = selector;
		var defClass = this.selector.className;
		this.options.unselectedClass = this.options.unselectedClass || defClass;
		this.options.selectedClass = this.options.selectedClass || defClass;
		this.options.hoverClass = this.options.hoverClass || defClass;
        this.options.clickedClass = this.options.clickedClass || defClass;
        this.options.hoverSelectedClass = this.options.hoverSelectedClass || this.options.hoverClass;
	},
	
	//listener methods
	hover: function() {
		this.selector.className = (this.selector.selected) ? this.options.hoverSelectedClass : this.options.hoverClass ;
	},
	
	unHover: function() {
		if (this.selected)
			this.selector.className = this.options.selectedClass;	
		else
			this.selector.className = this.options.unselectedClass;	
	},
	
	select: function(event) {
		if (this.selected)
			return;
		if (!this.isLoaded) {
			this.isLoaded = true;
 			if (this.content.getAttribute('href')) {
    		new Ajax.Updater(this.content, this.content.getAttribute('href'), 
	                   	{asynchronous:true, evalScripts:true}); 
 			}
		}
		this.manager.selectedPanels.push(this);
		this.markSelected();
		this.onSelect(this)
	},
    
    click: function(event) {
        this.selector.className = this.options.clickedClass;
    },
	//public methods
	activate: function() {
		Event.observe(this.selector, "mousemove", this.listenerHover); 
		Event.observe(this.selector, "mouseout", this.listenerEndHover);
        Event.observe(this.selector, "mousedown", this.listenerClick);
		Event.observe(this.selector, "mouseup", this.listenerSelect);
	},
	
	deactivate: function() {
		Event.stopObserving(this.selector, "mousemove", this.listenerHover);
		Event.stopObserving(this.selector, "mouseout", this.listenerEndHover);
		Event.stopObserving(this.selector, "mousedown", this.listenerClick);
        Event.stopObserving(this.selector, "mouseup", this.listenerSelect);
	},
	
	markUnselected: function() {
        //TODO only remove related classes.
		this.selector.className = this.options.unselectedClass
		this.selected = false;
	},

	markSelected: function() {
        //TODO only remove related classes.
		this.selector.className = this.options.selectedClass
		this.selected = true;
	}
}

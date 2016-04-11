Rico.Accordion = Class.create();
Rico.Accordion.prototype = {
   initialize: function(container, options) {
        this.container  = $(container);
        this.options    = {
            initHideAll         : false,
            onHideTab           : null,
            onShowTab           : null,
            animate             : true,
             animationDuration   : 100,
             animationSteps      : 8
        }
        Object.extend(this.options, options || {});
        var myOnSelect = this.showPanel.bind(this);
        var onSelect = options.onSelect;
        options.onSelect = function(panel) {myOnSelect(panel); if (onSelect) onSelect(panel)};
        this.panelManager = new Rico.PanelManager(container, options);
        var panels = this.panelManager.panels;
        
        if (this.options.initHideAll) {
            var panelToClose = this.panelManager.selectedPanels.shift();
            panelToClose.markUnselected();
        }
        
        for ( var i=0 ; i < panels.length ; i++ ){
            if (!panels[i].selected) panels[i].content.style.display = 'none';
        }
   },

   showPanel: function( panel) {
    if (!this.panelManager) {
        panel.content.style.height = this.options.panelHeight + "px";
        panel.content.style.overflow = 'auto';
        return;
    }
    if ( this.options.onDeselect )
        this.options.onDeselect(this.selectedPanel);
    var panelToClose = this.panelManager.panelToClose();
    if (panelToClose) {
        panelToClose.content.style.height = (this.options.panelHeight) + 'px';
        panelToClose.content.style.overflow = 'hidden';
    }
    panel.content.style.display = 'block';
    panel.content.style.overflow = 'hidden';
    panel.content.style.height = "0px"

    var showOpen = this.showPanelOpen.bind(this);
    if ( this.options.animate ) 
        new Rico.AccordionEffect( panelToClose.content, panel.content, this.panelManager.selectedPanels,
                                  this.options.panelHeight,
                                  this.options.animationDuration, this.options.animationSteps,
                                  { complete: function() {showOpen(selectedPanel, panel)} } );
    else 
      this.showPanelOpen(panelToClose, panel);
    panel.content.style.overflow = 'auto';
   },

   showPanelOpen: function(previousPanel, panel) {
       return;
      previousPanel.content.style.display = 'none';
      panel.content.style.overflow = "auto";
      panel.content.style.height = this.options.panelHeight + "px";
      if ( this.options.onShowTab )
         this.options.onShowTab(panel);
   }
};
Rico.AccordionEffect = Class.create();
Rico.AccordionEffect.prototype = {
    initialize: function(e1, e2, panels, height, duration, steps, options) {
      this.e1       = e1;
      this.e2       = e2;
      this.panels   = panels;
      this.endHeight      = height;
      this.options  = options || {};
      this.animation = new Rico.Effect.Animation(duration, steps, {step : this.animateStep.bind(this), onFinish : this.options.onFinish});
    },
    animateStep: function(stepsLeft) {
       var delta = this.e1 ?
            this.e1.offsetHeight/stepsLeft :
            (this.endHeight - this.e2.offsetHeight)/stepsLeft;
       if(this.e1) {
           var h1 = (this.e1.offsetHeight - delta) + "px";
           this.e1.style.height = h1;
       } 
       var h2 = (this.e2.offsetHeight + delta) + "px";
       this.e2.style.height = h2
    }
};
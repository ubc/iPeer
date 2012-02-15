
Rico.Effect.Animation = Class.create();
Rico.Effect.Animation.prototype = {
	initialize : function(duration, steps, options) {
		this.duration = duration;
		this.stepsLeft = steps;
		this.options = options
		this.animateMethod = this.animate.bind(this);
		this.start();
	},
	
	start: function() {
		this.animate();
	},
	
	animate: function() {
     if (this.stepsLeft <=0) {
	 			if (this.options.onFinish)
				 	this.options.onFinish();
        return;
     }

     if (this.timer)
        clearTimeout(this.timer);

     this.options.step(this.stepsLeft);
			this.startNextStep();
  },
  
	startNextStep: function() {
		var stepDuration = Math.round(this.duration/this.stepsLeft) ;
    this.duration -= stepDuration;
    this.stepsLeft--;
    this.timer = setTimeout(this.animateMethod, stepDuration);
	}
}
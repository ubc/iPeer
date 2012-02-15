Rico.Effect.SizeAndPosition = Class.create();
Rico.Effect.SizeAndPosition.prototype = {
   initialize: function(element, x, y, w, h, duration, steps, options) {
      this.element = $(element);
      this.x = x || this.element.offsetLeft;
      this.y = y || this.element.offsetTop;
      this.w = w || this.element.offsetWidth;
      this.h = h || this.element.offsetHeight;
      this.options  = options || {};
      this.animation = new Rico.Effect.Animation(duration, steps,
									  														{step : this.animateStep.bind(this),
																				 				 onFinish : this.options.complete});
   },

   animateStep: function(stepsToGo) {  
			var left = this.element.offsetLeft + ((this.x - this.element.offsetLeft)/stepsToGo) + "px"
			var top = this.element.offsetTop + ((this.y - this.element.offsetTop)/stepsToGo) + "px"
			var width = this.element.offsetWidth + ((this.w - this.element.offsetWidth)/stepsToGo) + "px"
			var height = this.element.offsetHeight + ((this.h - this.element.offsetHeight)/stepsToGo) + "px"

      var style = this.element.style;

			style.left = left;
			style.top = top;
			style.width = width;
			style.height = height;
   }
}
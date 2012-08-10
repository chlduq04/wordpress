(function ($) {
$.fn.markerboard = function(options){
	var defaults = {
		color: "rgba(0,0,0,1)",
		width: 1,
		joint: 'round'
		
	}
	
	var options = $.extend(defaults, options);
	
	return this.each( function() {
		var canvas = this;
		if (canvas.getContext){
			var ctx = canvas.getContext('2d');	
			
			
			$(this).mouseenter(function(event) {
				var offset = $(this).offset();
				var offsetX = offset.left;
				var offsetY = offset.top;
				
				var lastX = event.pageX - offsetX;
				var lastY = event.pageY - offsetY;
				ctx.strokeStyle = options.color;
				ctx.lineWidth = options.width;
				ctx.lineJoin = options.joint;
				ctx.beginPath();
				ctx.moveTo(lastX,lastY);
				
				function update(ctx, x ,y) {
					ctx.lineTo(x, y);
					ctx.stroke(options.color);
				}
				
				$(this).mousemove(function (event) {
					var currentX = event.pageX - offsetX;
					var currentY = event.pageY - offsetY;
					update(ctx, currentX, currentY);
				});
			});
		}
	});
	}
}) (jQuery);

// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.

(function($) {


	$.fn.extend({ 
	
		opacity: function() {
			
			return this.each(function(){
			
				$(this).hover(function(){
				
					$(this).stop().animate({"opacity":"0.7"}, 200, "linear");
				
				},
				function(){
					
					$(this).stop().animate({"opacity":"1"}, 200, "linear");
				
				
				});
			
			});
		
			
		},
	
		colorHover: function(hover, normal, speed){
			
			return this.each(function() {
				
				if(!$(this).hasClass("current")){

					$(this).hover(function(){
					
						$(this).stop().animate({"color":hover}, speed, "linear");
					
					},
					function(){
						
						$(this).stop().animate({"color":normal}, speed, "linear");
					
					
					});
				
				
				}
				

				
				
			});
		
		},
		
		buttonHover: function(hover, normal, speed){
		
			return this.each(function(){
			
				$(this).hover(function(){
					
					$(this).animate({"color":hover, "border-color":hover}, speed, "linear");
					
				},
				function(){
					
					$(this).animate({"color":normal, "border-color":normal}, speed, "linear");
						
				}
				);
			
			});
		
		},

		backgroundHover: function(hover, normal, speed){
		
			return this.each(function(){
			
				$(this).hover(function(){
					
					$(this).animate({"background-color":hover}, speed, "linear");
					
				},
				function(){
				
					$(this).animate({"background-color":normal}, speed, "linear");
				
				})
			
			});
		
		}
	
	});
	
})(jQuery);

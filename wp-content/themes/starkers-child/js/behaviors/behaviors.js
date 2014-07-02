DLN.Behaviors.flexslider = function(container){
	container.flexslider({
		controlNav: false
	});
};

DLN.Behaviors.masonry = function(container){
	container.imagesLoaded(function() {
		container.masonry({
	  	itemSelector: '.product',
	  	gutter: 20
		});
	});
};
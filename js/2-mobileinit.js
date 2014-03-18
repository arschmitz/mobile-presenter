$( document ).on( "mobileinit", function(){
	$.mobile.defaultPageTransition = "slide";
	$.extend( $.Widget.prototype, {
		_getCreateOptions: $.noop
	});
});
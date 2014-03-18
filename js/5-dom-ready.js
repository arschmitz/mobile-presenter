(function( $, undefined ) {

	// Enhance all the content outside the page on first page create
	$.mobile.document.one( "pagecreate", function(){

		// Enhance the panel
		$( ".preso-panel" ).panel({
			display: "overlay",
			theme: "b"
		});

		// Bind forward and back button navigation
		$( ".preso-back-button" ).on( "click", function(){
			navigate( "prev" );
		});
		$( ".preso-forward-button" ).on( "click", function(){
			navigate( "next" );
		});
		$.mobile.document.on( "keyup", function( event ){
			switch ( event.which ) {
				case $.ui.keyCode.LEFT:
					navigate( "prev" );
					break;
				case $.ui.keyCode.RIGHT:
				case $.ui.keyCode.SPACE:
					navigate( "next" );
					break;
			}
		})

		// Enhance the checkbox widget
		$( "#follow" ).checkboxradio({
			enhanced: true
		});

		// Enhance popup on master view
		$( ".preso-master-popup").popup({ theme: "a"});
	});

	// Bind to swipe to navigate forward and back
	$.mobile.document.on( "swipeleft swiperight", function( event ){
		var direction = ( event.type === "swiperight" ) ? "prev": "next";

		navigate( direction );
	});

	// Handle forward and back navigation
	function navigate( direction ) {
		var toPage = $( "body" ).find( ".ui-page-active" )[ direction ]( ".ui-page" );
		if( toPage.length > 0 ) {
			$( "body" ).pagecontainer( "change", toPage, {
				reverse: ( direction === "next" ) ? false : true
			});
		}
	}

	$.mobile.document.on( "pagechange", function( event ){
		$( ".ui-page-active" ).next( ".ui-page" ).find( "img" ).each(function(){
			if( $( this ).attr( "data-src" ) !== "loaded" ){
				$( this ).attr( "src", $( this ).attr( "data-src" ) );
				$( this ).attr( "data-src", "loaded" );
			}
		});
	});
	$.mobile.document.on( "pagebeforechange", function( event, ui ){
		$( ui.toPage[0] ).find( "img" ).each(function(){
			if( $( this ).attr( "data-src" ) !== "loaded" ){
				$( this ).attr( "src", $( this ).attr( "data-src" ) );
				$( this ).attr( "data-src", "loaded" );
			}
		});
	});

	// Auto size images to fight without scrolling
	$.mobile.document.on( "pagecreate", function(){
		$.mobile.document.one( "pagebeforeshow", function( event, toPage ){
			window.setTimeout( function(){
				var contentHeight = window.innerHeight - 88;

				$( ".ui-content", event.target ).height( contentHeight - 32 );
				$( event.target ).find( ".preso-image-auto" ).each( function(){
					var limiter, bigger, clone, ratio, height,
						otherHeight = 0,
						parent = $( this ).parent();

					$( $( this ).parents( ".preso-image-wedge" ).get().reverse() ).each(function(){
						$( this ).height( $( this ).parent().height() );
					});

					limiter = ( parent.width() < parent.innerHeight() )? "width": "innerHeight";
					bigger = ( parent.width() > parent.innerHeight() )? "width": "innerHeight";
					clone = $( this ).clone();
					clone.removeAttr( "class" ).addClass( "preso-image-clone" );
					$( "body" ).append( clone );
					ratio = 1 - Math.round( ( clone[limiter]() / clone[bigger]() ) * 100 ) / 100;
					otherHeight = 0;

					$( this ).closest( ".ui-content" ).children().not( this ).not( $( this ).parents() ).each(function(){
						otherHeight = $( this ).outerHeight( true ) + otherHeight;
					});
					otherHeight = otherHeight + 20;
					if( parent.hasClass( "ui-content" ) ) {
						otherHeight = otherHeight + 32;
					}

					if( $( this ).hasClass( "preso-image-auto-ignoreother" ) ) {
						height = $( this ).closest( ".ui-content" ).height() - otherHeight;
					} else {
						height = ( parent[limiter]() - otherHeight );
					}

					$( this )[limiter]( height );
					$( this )[bigger]( height + height * ratio );
					clone.remove();
					$( this ).parents( ".preso-image-wedge" ).each(function(){
						$( this ).removeAttr( "style" );
					});
				});
			}, 0);
		});
	});

	// Update the highlighted link
	$.mobile.document.on( "pageshow", function( event ){
		var id = $( event.target ).attr( "id" );

		$( "a" ).removeClass( "ui-btn-active" );
		$( "body" ).find( "a[href='#" + id + "']").addClass( "ui-btn-active" );
		$( ".syntaxhighlighter" ).addClass( "ui-corner-all" ).parent().css("margin-bottom","44px");
	});

	// Update the panel height since its fixed
	$.mobile.document.on( "panelbeforeopen", function(){
		$( ".ui-panel, .ui-panel-inner" ).height( window.innerHeight - 32 );
	});

	// Scroll the panel to the current slide
	$.mobile.document.on( "panelopen", function(){
		var index = $( ".ui-panel-inner" ).find( ".ui-btn-active" ).parent().index(),
			top = ( index / $( ".ui-panel-inner" ).find( ".ui-btn" ).length > 0.5 )? true: false,
			direction = ( !top )? "next": "prev";
		$( ".ui-panel-inner" ).find( ".ui-btn-active" ).parent()[direction]()[direction]()[ 0 ].scrollIntoView( top );
	});

	$(function(){
		SyntaxHighlighter.all();
	});

})( jQuery );

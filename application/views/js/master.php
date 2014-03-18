<script>
var socket;
$.mobile.document.one('pagechange', function ( event, data ) {
	socket = io.connect('<?php echo SOCKETPATH; ?>');
	socket.on('connected', function (data) {
		$( ".preso-master-popup").popup( "open" );
	});
	$.mobile.document.on('pagechange', function ( event, data ) {
		socket.emit('changeslide', { page: data.toPage.attr( "id" ) });
	});
});
$.mobile.document.on( "click", ".preso-master-submit-button",function( event ){
	var data = {};
	data.masterKey = $( "#masterKey" ).val();
	socket.emit( "masterconnect", data );
	socket.on( "masterconnect", function( data ){
		$( ".preso-master-popup").popup( "close" );
	});
});
</script>
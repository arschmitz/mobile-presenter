<script>
var socket;
$.mobile.document.one('pagechange', function ( event, data ) {
	socket = io.connect('<?php echo SOCKETPATH; ?>');
	socket.on('connected', function (data) {
		var key = $.cookie( "masterkey" );
		console.log( key )
		if( key == null || key == "" ){
			setTimeout( function(){
				$( ".preso-master-popup").popup( "open" );
			},1000);
		} else {
			masterConnect( key );
		}
	});
	$.mobile.document.on('pagebeforechange', function ( event, data ) {
		if ( typeof data.toPage !== "string" ) {
			socket.emit('changeslide', { page: data.toPage.attr( "id" ) });
		} else {
			socket.emit('changeslide', { page: "#" + data.toPage.split( "#" )[ 1 ] });
		}
	});
});
$.mobile.document.on( "click", ".preso-master-submit-button",function( event ){
	key = $( "#masterKey" ).val();
	masterConnect( key );
});
function masterConnect( key ){
	var data = {};
	data.masterKey = key;
	socket.emit( "masterconnect", data );
	socket.on( "masterconnect", function( data ){
		$.cookie( "masterkey", key,{
			expiration: 9999999999
		})
		$( ".preso-master-popup").popup( "close" );
	});
}
</script>
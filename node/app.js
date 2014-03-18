var app = require('express')(),
	server = require('http').createServer(app),
	io = require('socket.io').listen(server),
	currentSlide,
	masterconnected,
	config = require( "./config.json" );

server.listen(420);

io.sockets.on('connection', function (socket) {
	if( masterconnected && currentSlide ) {
		data = currentSlide;
	} else {
		data = {};
	}
	socket.emit( "connected" , {});
	socket.on( "masterconnect", function( data ){
		if( data.masterKey === config.masterKey ){
			masterconnected = true;
			socket.emit( "masterconnect", {status:"success"});
			socket.on('changeslide', function (data) {
				currentSlide = data;
				socket.broadcast.emit( "changeslide", data );
			});
		} else {
			socket.emit( "masterconnected", {status: "failed"} );
		}
	});
});
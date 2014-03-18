<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns= "http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="<?php echo SOCKETPATH; ?>/socket.io/socket.io.js"></script>
	<link rel="stylesheet" href="/dist/main.min.css"/>
	<script src="/dist/main.min.js"></script>
	<?php
		$CI =& get_instance();
		if( $master ){
			$CI->load->view( "js/master" );
		} else {
			$CI->load->view( "js/slave" );
		}
	?>
</head>
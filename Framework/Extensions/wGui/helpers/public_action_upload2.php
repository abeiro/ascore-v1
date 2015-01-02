<?php
while (@ob_end_clean());
$fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);

if ($fn) {
	
	
	$data=file_get_contents('php://input');
	//file_put_contents("/tmp/test.png",$data);
	if (strlen($data)>65535) {
		header("HTTP/1.0 404 Not Found");
		die();
	}
	if (!imagecreatefromstring ( $data ) ) {
	    header("HTTP/1.0 415 Unsupported Media Type");
	    die();
	}	
	echo "data:{$_SERVER["HTTP_X_TYPE"]};base64,".base64_encode($data);
	
} else {
	header("");
}
?>
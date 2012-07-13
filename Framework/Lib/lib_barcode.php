<?php

function barDrawSimple($code) {

	global $SYS;

	if (is_dir($SYS["DOCROOT"]."/Extensions/barcodeGen_4v123pl1")) {
		/************************************** BARCODE */
		define('IN_CB',true);
		set_include_dir($SYS["DOCROOT"]."/Extensions/barcodeGen_4v123pl1/-");
		
		
		// Including all required classes
		require('class/index.php');
		require('class/Font.php');
		require('class/FColor.php');
		require('class/BarCode.php');
		require('class/FDrawing.php');
		
		// Including the barcode technology
		include('class/code39.barcode.php');
		
		// Loading Font
		$font =& new Font($SYS["DOCROOT"].'/Data/Fonts/Vera.ttf', 8);
		
		// Creating some Color (arguments are R, G, B)
		$color_black =& new FColor(0,0,0);
		$color_white =& new FColor(255,255,255);
		
		/* Here is the list of the arguments:
		1 - Thickness
		2 - Color of bars
		3 - Color of spaces
		4 - Resolution
		5 - Text
		6 - Text Font */
		$code =& new code39(60,$color_black,$color_white,2,$code,$font);
		//$code =& new code39(60,$color_black,$color_white,2,"001492026D67",$font);
	
		
		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing =& new FDrawing('',$color_white);
		$drawing->setBarcode($code);
		$drawing->draw();
		
		// Header that says it is an image (remove it if you save the barcode to a file)
		header('Content-Type: image/png');
		
		// Draw (or save) the image into PNG format.
		$bardata=$drawing->finish(IMG_FORMAT_PNG);
		
		
		/************************************** BARCODE */
	}
}
?>
<?php
/* 
Very simple captcha 
*/


function vscGenerateImage() {

	$code=rand(1000,9999);
	$_SESSION["code"]=$code;
	$im = imagecreatetruecolor(100, 24);
	$bg = imagecolorallocate($im, 22, 86, 165); //background color blue
	$fg = imagecolorallocate($im, 255, 255, 255);//text color white
	imagefill($im, 0, 0, $bg);
	for ($i=0;$i<strlen("$code");$i++)
		imagestring($im, 5, 5+rand(-3,3)+$i*15, 5+rand(0,5)-3,  substr("$code",$i,1), $fg);
	$file=time().".png";
	debug("vscGenerateImage {$GLOBALS["SYS"]["DOCROOT"]}/../Pool/Tmp/$file {$GLOBALS["SYS"]["ROOT"]}/Pool/Tmp/$file","red");
	imagepng($im,"{$GLOBALS["SYS"]["DOCROOT"]}/../Pool/Tmp/$file");
	return  "{$GLOBALS["SYS"]["ROOT"]}/Pool/Tmp/$file";
}
?>
<?php
while(@ob_end_clean());
$nl=newObject("fileh",$ID);

$tsstring = gmdate('D, d M Y H:i:s ', (($nl->S_Date_M>0)?$nl->S_Date_M:$nl->S_Date_C)) . 'GMT';
$etag = md5($language . $timestamp.$ID);

$if_modified_since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false;
$if_none_match = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : false;
if ((($if_none_match && $if_none_match == $etag) || (!$if_none_match)) &&
    ($if_modified_since && $if_modified_since == $tsstring))
{
    header('HTTP/1.1 304 Not Modified');
    exit();
}
else
{
    header("Last-Modified: $tsstring");
    header("ETag: \"{$etag}\"");
	header("Cache-Control: public"); // HTTP/1.1
	header("Expires: "); // Date in the past
	header("Pragma: "); // Date in the past
}


$IMAGECACHETTL=60*60*24*7;
$nl=newObject("fileh",$ID);
$realname=$nl->localname();
$origname=$nl->nombre;

if ($_GET["thumb"]) {
	if (filemtime($nl->localname()."thumb{$_GET["thumb"]}")<time()-$IMAGECACHETTL) {
		debug("Using realsized image","red");
		if(!$original=imagecreatefromjpeg($realname))
				if(!$original=imagecreatefrompng($realname))
					if(!$original=imagecreatefromgif($realname))
						if(!$original=imagecreatefromgd2($realname))
							$notimage=true;

			if ($original) {
				$width=$_GET["thumb"];
				$true_width = imagesx($original);
				$true_height = imagesy($original);
				$height = ($width/$true_width)*$true_height;
				$img_des =  ImageCreateTruecolor($width,$height);
				imagecopyresampled($img_des, $original, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
				header("Content-Type: image/jpeg");
				imagejpeg($img_des,$nl->localname()."thumb{$_GET["thumb"]}.");
				header("Content-Disposition: inline; filename=\"".basename($nl->localname())."thumb{$_GET["thumb"]}.jpg\"");
				imagejpeg($img_des);
				die();
			}
	} else {
		debug("Using cached image","red");
		header("Content-Type: image/jpeg");
		header("Content-Disposition: inline; filename=\"".basename($nl->localname())."thumb{$_GET["thumb"]}.jpg\"");
		
		readfile($nl->localname()."thumb{$_GET["thumb"]}");
		die();
	}

}

if ($databus=fopen($realname,"r")) {
	header("Content-Type: ".$nl->mime);
	header("Content-Length: ".$nl->len);
	header ("Content-Disposition: inline; filename=$origname");
	
	while($data=fread($databus,8192))
		print($data);
	
	fclose($databus);
	$nl->stats=$nl->stats+1;
	$nl->update();
}
else
	echo "File not found";

?>
<?php




function resizeImgW($imgname,$width)
{

 $img_src = ImageCreateFromjpeg ($imgname);

 $true_width = imagesx($img_src);
 $true_height = imagesy($img_src);

    $width;
    $height = ($width/$true_width)*$true_height;

 $img_des =  ImageCreateTruecolor($width,$height);
 imagecopyresampled($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
 return $img_des;
}

function resizeImgH($imgname,$height)
{

 $img_src = ImageCreateFromjpeg ($imgname);

 $true_width = imagesx($img_src);
 $true_height = imagesy($img_src);

    $width = ($height/$true_height)*$true_width;

 $img_des =  ImageCreateTruecolor($width,$height);
 imagecopyresampled($img_des, $img_src, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
 return $img_des;
}
function imgSave($img,$dest) {

return imagejpeg($img,$dest,CALIDAD_JPG);

}

function jpegThumb($filename,$fondo,$w,$h) {
		
	global $SYS;
	
	require_once("Lib/lib_image.php");
	
	if(!$original=imagecreatefromjpeg($filename))
		if(!$original=imagecreatefrompng($filename))
			if(!$original=imagecreatefromgif($filename))
				if(!$original=imagecreatefromgd2($filename))
					return false;
	
	$pics[0]=prepare_thumb($original,$w,$h);
	$pics[1]=imagecreatefrompng($SYS["ROOT"]."/Themes/Default/Img/f_marco_{$w}_{$h}.png");

	if (!$pics[1])
		$pics[1]=prepare_thumb($original,$w,$h);
	
	if ($fondo=="resolve")
		$thumb=CoreImageMerge($pics,$fondo,$fondo);
	else
		$thumb=CoreImageMerge($pics,$fondo);
	
        if ($this->imgSave($thumb,ini_get("session.save_path")."/".basename($filename)))
		return ini_get("session.save_path")."/".basename($filename);
	else
		return false;


}


function createThumb($filename,$fondo,$w,$h)
{
	debug(" createThumb($filename,$fondo,$w,$h)","yellow");
	error_reporting(E_ERROR);
	if ((imagecreatefromjpeg($filename))||(imagecreatefrompng($filename))||(imagecreatefromgif($filename))||(imagecreatefromgd2($filename)))
		return $this->jpegThumb($filename,$fondo,$w,$h);
	else	{
		debug("IMAGE NOT SUPPORTED " .mime_content_type($filename),"white");
		return false;
	}

}


function save($fondo="ffffff",$w=160,$h=128)
{
	$this->forceUpload=(isset($this->forceUpload))?$this->forceUpload:false;
	$t=newObject("fileh");
	$t->familia_id=$this->familia_fileh_ID;
	$t->CaptureFile=$this->CaptureFile;
	$this->id_foto=$t->save($this->forceUpload);
	
	$tfname=$this->createThumb($t->localname(),$fondo,$w,$h);
	$tt=newObject("fileh");
	$tt->familia_id=$this->familia_fileh_ID;
	$tt->CaptureFile=$this->CaptureFile;
	$this->id_thumb=$tt->save($tfname);
	
	if (($this->id_foto==0)||($this->id_thumb==0))	{
		$this->ERROR=$tt->ERROR." ".$t->ERROR;
		return false;
	}
	
	$par=typecast($this,"Ente");
	return $par->save();
}

function delete()
{
	$t=newObject("fileh",$this->id_foto);
	$t->delete();
	$t=newObject("fileh",$this->id_thumb);
	$t->delete();
	
	$par=typecast($this,"Ente");
	return $par->delete();
}


?>
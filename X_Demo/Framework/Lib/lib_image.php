<?php

 function CoreImageMerge($i,$colorfondo,$resolvecolor='') {
 
  $s = imagecreatetruecolor(imagesx($i[1]),imagesy($i[1]));
  
  $int = hexdec("0x$colorfondo");
  $fondo = array(0 => 0xFF & ($int >> 0x10),
                1 => 0xFF & ($int >> 0x8),
                2 => 0xFF & $int); 
		
 
  debug("\n"."**** IMAGE COLORING **** $colorfondo is : {$fondo[0]}, {$fondo[1]} ,{$fondo[2]} in ".__FILE__,"white");
  $WHITE=imagecolorallocate($s,$fondo[0],$fondo[1],$fondo[2]);
  
  
  
  $x1=imagesx($i[0]);
  $y1=imagesy($i[0]);
  
  $x2=imagesx($i[1]);
  $y2=imagesy($i[1]);
  
  $posx=($x2-$x1)/2;
  $posy=($y2-$y1)/2;
  
  imagecopy($s,$i[0],$posx,$posy,0,0,$x1,$y1);
  if (!empty($resolvecolor))
  	$color=mainColor($i[0]);
  else
  	$color=$WHITE;
  
  imagefill($s,imagesx($i[1])-1,imagesy($i[1])-1,$color);
  
  imagecopy($s,$i[0],$posx,$posy,0,0,$x1,$y1);
  imagecopy($s,$i[1],0,0,0,0,imagesx($i[1]),imagesy($i[1]));
  
  
  
  return $s;
 }
 
 function mainColor($image) {
 
 $i=0;
 for ($x=0;$x<imagesx($image);$x++)
 	for ($y=0;$y<imagesy($image);$y++) {
 		$rgb = ImageColorAt($image, $x,$y);
 		$r += ($rgb >> 16) & 0xFF;
 		$g += ($rgb >> 8) & 0xFF;
 		$b += $rgb & 0xFF;
		$i++;
 	}
	return imageColorAllocate($image,$r/$i,$g/$i,$b/$i);
 
 }
 function prepare_thumb($image,$thumb_w=160,$thumb_h=128) {
 
  $true_width = imagesx($image);
  $true_height = imagesy($image);
  
  if (($true_width/$thumb_w)>($true_height/$thumb_h)) {
    	$width = $thumb_w;
	$height = ($true_height*$thumb_w)/$true_width;
  }
  else {
   	$height = $thumb_h;
	$width = ($height*$true_width)/$true_height;

  }
  
 $img_des =  ImageCreateTruecolor($width,$height);
 imagecopyresampled($img_des, $image, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
 debug(__FILE__."**** IMAGE RESAMPLING ****: $width, $height","green");
 return $img_des;
 
 }
 
 function blur (&$image) {
     $imagex = imagesx($image);
     $imagey = imagesy($image);
     $dist = 2;
 
     for ($x = 0; $x < $imagex; ++$x) {
         for ($y = 0; $y < $imagey; ++$y) {
             $newr = 0;
             $newg = 0;
             $newb = 0;
 
             $colours = array();
             $thiscol = imagecolorat($image, $x, $y);
 
             for ($k = $x - $dist; $k <= $x + $dist; ++$k) {
                 for ($l = $y - $dist; $l <= $y + $dist; ++$l) {
                     if ($k < 0) { $colours[] = $thiscol; continue; }
                     if ($k >= $imagex) { $colours[] = $thiscol; continue; }
                     if ($l < 0) { $colours[] = $thiscol; continue; }
                     if ($l >= $imagey) { $colours[] = $thiscol; continue; }
                     $colours[] = imagecolorat($image, $k, $l);
                 }
             }
 
             foreach($colours as $colour) {
                 $newr += ($colour >> 16) & 0xFF;
                 $newg += ($colour >> 8) & 0xFF;
                 $newb += $colour & 0xFF;
             }
 
             $numelements = count($colours);
             $newr /= $numelements;
             $newg /= $numelements;
             $newb /= $numelements;
 
             $newcol = imagecolorallocate($image, $newr, $newg, $newb);
             imagesetpixel($image, $x, $y, $newcol);
         }
     }
 } 
 
 
 if(!function_exists("imageconvolution"))
 {
  function imageconvolution(&$img,$mat,$div,$off)
  {
    if(!imageistruecolor($img) || !is_array($mat) || count($mat)!=3 || count($mat[0])!=3 || count($mat[1])!=3 || count($mat[2])!=3) return FALSE;
    unset($bojainfo);
    for($nx=0;$nx<imagesx($img)-1;$nx++)
    {
      for($ny=0;$ny<imagesy($img)-1;$ny++)
      {
        $rgb=imagecolorat($img,$nx,$ny);
        $bojainfo[$nx][$ny][r]=($rgb>>16)&0xFF;
        $bojainfo[$nx][$ny][g]=($rgb>>8)&0xFF;
        $bojainfo[$nx][$ny][b]=$rgb&0xFF;
      }
    }
    for($nx=1;$nx<imagesx($img)-1;$nx++)
    {
      for($ny=1;$ny<imagesy($img)-1;$ny++)
      {
        $nr=$mat[0][0]*$bojainfo[$nx-1][$ny-1][r] + $mat[0][1]*$bojainfo[$nx][$ny-1][r] + $mat[0][2]*$bojainfo[$nx+1][$ny-1][r] + $mat[1][0]*$bojainfo[$nx-1][$ny][r] + $mat[1][1]*$bojainfo[$nx][$ny][r] + $mat[1][2]*$bojainfo[$nx+1][$ny][r] + $mat[2][0]*$bojainfo[$nx-1][$ny+1][r] + $mat[2][1]*$bojainfo[$nx][$ny+1][r] + $mat[2][2]*$bojainfo[$nx+1][$ny+1][r];
        $nr=intval(round($nr/$div));
        if($nr<0) $nr=0;
        elseif($nr>255) $nr=255;
        $ng=$mat[0][0]*$bojainfo[$nx-1][$ny-1][g]  + $mat[0][1]*$bojainfo[$nx][$ny-1][g] + $mat[0][2]*$bojainfo[$nx+1][$ny-1][g] + $mat[1][0]*$bojainfo[$nx-1][$ny][g] + $mat[1][1]*$bojainfo[$nx][$ny][g] + $mat[1][2]*$bojainfo[$nx+1][$ny][g] + $mat[2][0]*$bojainfo[$nx-1][$ny+1][g] + $mat[2][1]*$bojainfo[$nx][$ny+1][g] + $mat[2][2]*$bojainfo[$nx+1][$ny+1][g];
        $ng=intval(round($ng/$div));
        if($ng<0) $ng=0;
        elseif($ng>255) $ng=255;
        $nb=$mat[0][0]*$bojainfo[$nx-1][$ny-1][b] + $mat[0][1]*$bojainfo[$nx][$ny-1][b] + $mat[0][2]*$bojainfo[$nx+1][$ny-1][b] + $mat[1][0]*$bojainfo[$nx-1][$ny][b] + $mat[1][1]*$bojainfo[$nx][$ny][b] + $mat[1][2]*$bojainfo[$nx+1][$ny][b] + $mat[2][0]*$bojainfo[$nx-1][$ny+1][b] + $mat[2][1]*$bojainfo[$nx][$ny+1][b] + $mat[2][2]*$bojainfo[$nx+1][$ny+1][b];
        $nb=intval(round($nb/$div));
        if($nb<0) $nb=0;
        elseif($nb>255) $nb=255;
        $nrgb=($nr<<16)+($ng<<8)+$nb;
        if(!imagesetpixel($img,$nx,$ny,$nrgb)) return FALSE;
      }
    }
    return TRUE;
   }
 }
 
  function resize_on_the_fly($data,$width=160) {
 
  $image=imagecreatefromstring($data);
  $true_width = imagesx($image);
  $true_height = imagesy($image);
  
  $height = ($true_height*$width)/$true_width;
  
  $img_des =  ImageCreateTruecolor($width,$height);
  imagecopyresampled($img_des, $image, 0, 0, 0, 0, $width, $height, $true_width, $true_height);
  debug(__FILE__."**** IMAGE RESAMPLING ****: $width, $height","green");
  return imagejpeg($img_des);
 
 }
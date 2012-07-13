<?php

$dir=session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/";

if (is_dir($dir)) 
    if ($dh = opendir($dir)) 
        while (($file = readdir($dh)) !== false) 
		if (($file!=".")&&($file!="..")) 
			if (unlink($dir.$file))
				echo ""._("deleted")."&nbsp;&nbsp;".$dir.$file."<br>";
			
			
	
$monitor->MonClose();
?>
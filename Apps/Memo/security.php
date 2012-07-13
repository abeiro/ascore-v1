<?php

function checkSecurity($p) {

	$current_user=$_SESSION["__auth"]["userId"];
	
	if (BILO_isAdmin()) {
		debug("Sec passed by Group Administrador Is God(TM)",'red');
		return True;
	}
    	
    	

	if ($p->ID<2) 
	{
		// We are creating a new file or new folder
		// Check if directory has write access for all
		
			
		$dir=newObject("data_object",$p->inode);
		if (strpos($dir->p_other,'w')!==False) {
			debug("Sec passed by Other:w",'red');
			return True;
		}
		
		// Check if directory has write access for group
		if (BILO_checkGroup($dir->gid))
			if (strpos($dir->p_group,'w')!==False) {
				
				debug("Sec passed by Group:w",'red');
				return True;
				
			}
		// Check if directory has write access for user
		if (BILO_uid()==$dir->uid)
			if (strpos($dir->p_owner,'w')!==False) {
				
				debug("Sec passed by Owner:w ".BILO_uid()."|".$dir->uid,'red');
				return True;
			}
	
	}
	else
	{
		// We are modyfing a file or new folder
		// Check if element has write access for all
	
		// Check if element has write access for user
		if (BILO_uid()==$p->uid)
			if (strpos($p->p_owner,'w')!==False) {
				
				debug("Sec passed by Owner:w ".BILO_uid()."|".$p->uid,'red');
				return True;
			}
	
	}

	return false;
}

function checkReadSecurity($p) {

	$current_user=BILO_uid();
	
	

	

	if (BILO_isAdmin()) {
		debug("Sec passed by Group Administrador Is God(TM)",'red');
		return True;
	}
	
	if ($p->inode==0) {
		debug("Sec passed by root dir",'red');
		return True;
	}
	
	
		// We are creating a new file or new folder
		// Check if directory has write access for all
		
			
		
		if (strpos($p->p_other,'r')!==False) {
			debug("Sec passed by Other:r {$p->p_other} {$p->nombre}",'red');
			return True;
		}
		
		// Check if directory has write access for group
		if (BILO_checkGroup($p->gid))
			if (strpos($p->p_group,'r')!==False) {
				
				debug("Sec passed by Group:r",'red');
				return True;
				
			}
		// Check if directory has write access for user
		if (BILO_uid()==$p->uid)
			if (strpos($p->p_owner,'r')!==False) {
				
				debug("Sec passed by Owner:r ".BILO_uid()."|".$p->uid,'red');
				return True;
			}
	
	
	return false;
}


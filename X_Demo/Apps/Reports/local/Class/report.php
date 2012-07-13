<?php
function Is_Print()
{
	if($this->printable=="No")
		$print=false;
	else	
		$print=true;
		
	return $print;
}
?>
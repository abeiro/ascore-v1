<?php
$DACTION=strtolower($ACTION);
if (!empty($ACTION))
	HTML("static_metodologia_$DACTION");
	
else
	HTML("static_metodologia");



?>
		

<?php
require_once("JasperReports.php");
$ID=(isset($ID))?$ID:1;
if ($ID>1)
{
	$ane=newObject("jasperreport",$ID);
	$ane->delete();

}
FrameReload("fbody");
?>
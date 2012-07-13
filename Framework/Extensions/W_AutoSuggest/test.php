<?php

require_once("coreg2.php");
foreach($SYS["APPS"] as $v=>$k) {
	
	set_include_dir($SYS["DOCROOT"]."../Apps/$k/");
	//echo $SYS["DOCROOT"]."../Apps/$k"."\n";
	
}
function buildQuery($tokens) {

        $fields=array($this->properties[$fieldtoshow]);
        $token=explode(" ",$tokens);
        foreach ($token as $kk=>$vv) {
            foreach ($fields as $k=>$v)  {
                $xmultiquery[]="$k LIKE '%$vv%' ";    
            }
            $multiquery=implode(" OR ",$xmultiquery);
            unset($xmultiquery);
            $smultiquery[]="(".$multiquery.")";
        }
        $query=implode(" AND ",$smultiquery);
        return $query;
}
	
$obj=newObject($CL);

	
	
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
$n=0;	
foreach ($obj->selectA($obj->buildMultiQuery($input)) as $k=>$v) {
		echo "\t<rs id=\"$k\" info=\"--\">{$v[$fieldtoshow]}</rs>\n";
		
}

echo "</results>";

?>
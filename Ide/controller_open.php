<?php
  
  
  require(dirname(__FILE__)."/controller_base_js.php");
  
  $data=file_get_contents(dirname(__FILE__)."/../".trim($file));
  
  $e=new jsO("editor","parent",true);
  $e->setMethod("setCode",array("$data"));

  if (substr($file,-3)==".js") {
	$e->setMethod("setParser",array("JSParser"=>"string"));
        $parser="(JS parser)";
  }
  if ((substr($file,-4)==".xml")||(substr($file,-4)==".def")) {
	$e->setMethod("setParser",array("XMLParser"=>"string"));
        $parser="(XML parser)";
  }
  $e->setMethod("reindent");

  //$e->setMethod("grabKeys",array("docSave"=>"string","parent.filterSave"=>"literal"));
  $e->jsMapO();
  $e->jsMapM();
  
  $textarea=new jsO("msgbox","parent",false);
  $textarea->jsO->value="$file abierto. $parser";
  $textarea->jsMapO();
  $textarea->jsMapM();
  
  
  jsCodeFlush();
  
  
  
  
  ?>


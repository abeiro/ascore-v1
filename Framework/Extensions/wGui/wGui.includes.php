<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
    <link href="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//wGui.css" rel="stylesheet"  type="text/css" />
    <link type="text/css" href="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/css/mtg/mytablegrid.css" rel="stylesheet">
    <link type="text/css" href="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/css/mtg/calendar.css" rel="stylesheet">
  </head>
  <script src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//scriptaculous/lib/prototype.js" type="text/javascript"></script>
  <script src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
  <script src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//wGui.js" type="text/javascript"></script>
  
  <script type="text/javascript" src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/scripts/mtg/mytablegrid.js"></script>
  
  <!--
  <script type="text/javascript" src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/scripts/mtg/calendar_date_select.js"></script>
  <script type="text/javascript" src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/scripts/mtg/controls.js"></script>
  
  <script type="text/javascript" src="<?php echo $SYS["ROOT"]?>/Framework/Extensions//wGui//grid/scripts/mtg/keytable.js"></script>
  -->
  
  <script src='<?php echo $SYS["ROOT"]?>/Framework/Extensions//FileUpload/client/fileuploader.js' type='text/javascript'></script>
  
  <link href='<?php echo $SYS["ROOT"]?>/Framework/Extensions//FileUpload/client/fileuploader.css' rel='stylesheet' type='text/css'>  
  
  <link href="<?php echo $SYS["ROOT"]?>/Framework/Extensions/wGui//TabbedPane/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
  
  <script src="<?php echo $SYS["ROOT"]?>/Framework/Extensions/wGui//TabbedPane/SpryTabbedPanels.js" type="text/javascript"></script> 
  
  <body>
    
    
    
    <?php
      
      function javascript_encode($code) {
        
        $buffer=json_encode($code);
        $b2=str_replace('"#',"",$buffer);
        $buffer=str_replace('#"',"",$b2);
        
        return $buffer;
        
      }
      
      
      
      
      
      
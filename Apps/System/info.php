<?php

require_once("System.php");
?>


<h1 align="center">System info</h1>

<div align="center"><h2 align="center"><?php 
echo "CoreG2 Framework "._COREVER; 
 ?></h2>
 <a href="http://www.activasistemas.com">
 <img src="Data/Img/actsys.png" width="128" height="55"  border="0">
 </a>
 <a href="http://www.activasistemas.com">
 <img src="Data/Img/coreg2_logo_simple.png" width="128" height="72"  border="0">
 </a>

</div>
<div align="center">
  <h3>PHP Engine version <?php echo phpversion()?></h3>
</div>

<div style="margin-left:auto;margin-right:auto;width:75%">

		<h3 align="center" style="color : #000000;">Components</h3>

		<div align="left" >&nbsp;<strong>HTML2FPDF</strong></div>
		<div align="left" style="float:left">&nbsp;Copyright (C) 2004-2005 Renato Coelho  </div>
		<div align="right">&nbsp;LGPL</div>
		
		<div align="left" >&nbsp;<strong>Graph Class</strong></div>
		<div align="left" style="float:left">&nbsp;Copyright (C) 2000  Herman Veluwenkamp</div>
		<div align="right">&nbsp;LGPL</div>
		

		<div align="left" >&nbsp;<strong>FCKEditor</strong></div>
		<div align="left" style="float:left">&nbsp;Copyright (C) 2003-2005 Frederico Caldeira Knabben</div>
		<div align="right">&nbsp;LGPL</div>
		
		<div align="left" >&nbsp;<strong>HTTP protocol client class</strong></div>
		<div align="left" style="float:left">&nbsp;Copyright (C) Manuel Lemos http://www.ManuelLemos.net/</div>
		<div align="right" >&nbsp;BSD License</div>
		
		<div align="left" >&nbsp;<strong>PHPmailer</strong></div>
		<div align="left" style="float:left">&nbsp;http://phpmailer.sourceforge.net/</div>
		<div align="right" >&nbsp;LGPL License</div>
 		
</div>

<SCRIPT type="text/javascript" language="JavaScript1.3">
	/*
	try {m=FireApp;} catch (err) {m=0;}
	if (m>0) {
		ex='<a href="<?php echo str_replace("http://","chrome://",$SYS["ROOT"])?>">Cambiar a modo FireApp</a>';
		document.write('<br><div align="center">FireApp SI habilitado (' + ex + m+')</div>');
		
	}
	else {
	ex='<a href="jar:http://arda/proyectos/cepes-gc/fireapp/secure.jar!/index.html" >Instalar modo FireApp</a>';
		document.write('<br><div align="center">FireApp NO habilitado. (' + ex +')</div>');
		
	}
	*/
</SCRIPT>


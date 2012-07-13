<?php
$TrazaStatus=false;
require_once("coreg2.php");
require_once("Bilo/Bilo.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style>
* {
    font-family :verdana, geneva, sans-serif;
    font-size: 10px;
    margin:0px;
    padding:0px;
  }

a {
    color:black;
    text-decoration:none;
  }

.icon {
  border:1px solid #DADADA;
  }

.icon:hover {
    background-color:#C5C2C5;
  }

</style>
<script type="text/javascript" language="JavaScript1.3">
function URLencode(sStr) {
    return escape(sStr).replace(/\+/g, '%2B').replace(/\"/g,'%22').replace(/\'/g, '%27');
  }
function dev_go() {
  current=new String(parent.fbody.location.href);
  pos=current.lastIndexOf("/");
  go=new String(current.slice(0,pos+1));
  go=go+"dev.php";
  
  parent.fbody.location.href=go;
}
function help_go() {
  current=new String(parent.fbody.location.href);
  pos=current.lastIndexOf("/");
  go=new String(current.slice(0,pos+1));
  go=go+"help.php";
  parent.fbody.location.href=go;
}
function addbookmarks() {

  doc=parent.fbody.location.href;
  if (name=prompt('Introduzca un nombre para el marcador')) {
  p=new String(parent.fbody.location.href);
  pa=URLencode(p);
  parent.parent.footer.location.href='<?php echo $SYS["ROOT"]?>/Framework/Extensions/Bookmarks/add.php?name='+name+'&params=' + pa;
  }
}
function logOut() {

    parent.location.href='<?php echo $SYS["ROOT"]?>/Login/logout.php';
  
}

function gobookmarks() {

  window.open('<?php echo $SYS["ROOT"]?>/Framework/Extensions/Bookmarks/list.php?void_framming=yes','bookWindow','width=400,height=300,toolbar=0,scrollbar=0');
  
}

function fReload() {

  parent.fbody.location.reload();
  
}

function fPrint() {

  parent.fbody.print();
  
}
function fBack() {

  parent.fbody.history.go(-1);
  
}
</script>
</head>
<body bgcolor="#EEEAEE" style="border:1px solid #EBEBEB">
<?php

  
?>
<table width="100%">
  <tr>
    
    
    <td nowrap valign="bottom">
    
    <?php if ($SYS["GLOBAL"]["DEV_MODE"]) {?>
    <a href="javascript:dev_go()"><img class="icon" src="Data/Img/Icons/develop.png" alt="Panel de Desarrollo" title="Panel de Desarrollo" width="16" height="16" border="0"></a>&nbsp;
    <?php }?>
          <a href="javascript:help_go()"><img  class="icon"  src="Data/Img/Icons/help.png" alt="Ayuda" title="Ayuda" width="16" height="16" border="0"></a>&nbsp;

    
    <a href="javascript:gobookmarks()"><img  class="icon"  src="Data/Img/Icons/bookmark.png" alt="Ver favoritos" title="Ver favoritos" width="16" height="16" border="0"></a>&nbsp;

    <a href="javascript:addbookmarks()"><img  class="icon"  src="Data/Img/Icons/bookmark_add.png" alt="A&#6244;ir a favoritos" title="A&#6244;ir a favoritos" width="16" height="16" border="0"></a>&nbsp;
    
    <a href="javascript:fReload()" ><img  class="icon"  src="Data/Img/Icons/reload.png" border="0" alt="Recargar" title="Recargar"></a>&nbsp;
    
    <a href="javascript:fBack()" ><img   class="icon" src="Data/Img/Icons/back.png" border="0" alt="Retroceder" title="Retroceder"></a>&nbsp;
    
    <a href="javascript:fPrint()" ><img   class="icon" src="Data/Img/Icons/print.png" border="0" alt="Imprimir" title="Imprimir"></a>&nbsp;
    
    <a href="javascript:logOut()" ><img   class="icon" src="Data/Img/Icons/unlock.png" border="0" alt="Cerrar sesion" title="Cerrar sesion"></a>&nbsp;

        
    </td>
    <td nowrap>&nbsp;</td>
    <td  width="50%" align="center" nowrap><strong><?php echo BILO_username()._(' conectado')?></strong></td>    
    
    
    
    

    
    
    <td nowrap width="104" align="left" style="border:1px solid lightgray">
        <span id="progress" style="background-color:#EEEAEE;">&nbsp;</span>
    
    </td>
    <td align="right" nowrap>
      
      <img src="Data/Img/Icons/ok.gif" width="16" height="16" align="right" border="" style="border:1px solid lightgray" id="semaforo">
    </td>
  </tr>
</table>
</body>
</html>
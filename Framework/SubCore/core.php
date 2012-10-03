<?php



/* Some chars macros */

define("_ALMOHA","#");


class core
{

  var $VERSION=_COREVER;
  var $RELEASE_DATE;
  
  function core(){
    

    $this->RELEASE_DATE=time();
    
  }

  function version()
  {
    return $this->VERSION;
  }
  function release()
  {
    return $this->RELEASE_DATE;
  }
    
  /*
  mixed f (string propiedad)

  Compatibilidad con Core-rc4
  
  Devuelve la propiedad pasada como parï¿½metro
  */
  function f($field) {

    return $this->$field;
  }

  /*
  mixed _s (string prop, mixed val)

  Alias for $this->$prop==$val

  */
  function _s($prop,$val) {

    return $this->$prop=$val;
  }
  
  function __clone() {

    debug("Object cloning","green");
  }
}

function nodebug($var,$color) {
    return;
}

function debug($var,$color="blue") {
  global $core,$TrazaStatus,$nodebug,$DEBUGHANDLER;
  if ($TrazaStatus&&(!$nodebug)) {

  $colors=array(
    "black"=>'[30;40;1m',
    "red"=>'[31;40;1m',
    "green"=>'[32;40;1m',
    "yellow"=>'[33;40;1m',
    "blue"=>'[34;40;1m',
    "magenta"=>'[35;40;1m',
    "cyan=">'[36;40;1m',
    "white"=>'[37;40;1m'
  );


  $data=$colors["$color"].$var." \n\r";
  fputs($DEBUGHANDLER,$data);
  fputs($DEBUGHANDLER,$colors["black"]);
  //echo "$var<br>";
  return $var;
  }
}


function newObj($class) {
  global $SYS,$MEMORY_CACHE;
  
  
  $bm=getmicrotime();
  if (class_exists("Ente_$class")) {
      debug("Clase ya cargada","cyan");
      return eval("return new Ente_$class($class);");
    }
  if (isset($MEMORY_CACHE["$class"])) {
      debug("Usando clase base ya cargada","cyan");
      return new Ente($class);
      }
    
  
    
  if ((file_exists($SYS["DOCROOT"].$SYS["DATADEFPATH"].$class.".php"))&&(!class_exists("Ente_$class"))) {

    debug("Analizando clase extendida en ".$SYS["DOCROOT"].$SYS["DATADEFPATH"].$class.".php","yellow");
    $handle = fopen ($SYS["DOCROOT"].$SYS["DATADEFPATH"].$class.".php", "r");
    $len=filesize ($SYS["DOCROOT"].$SYS["DATADEFPATH"].$class.".php");
    $buffer=fread ($handle,$len);
    fclose($handle);
    $buffer=str_replace("<?php","",$buffer);
    $buffer=str_replace("?>","",$buffer);
    
    debug("initing time  Ente_$class ".(getmicrotime()-$bm)." s.","yellow" );
    return eval("
    class Ente_$class extends Ente {
    ".
    $buffer
    ."  
    }
    return new Ente_$class($class);
    ");
    
      
    
  }
  else if ((e_file_exists("local/Class/{$class}.php"))&&(!class_exists("Ente_$class"))) {
    
    debug("Analizando clase extendida en local/Class/{$class}.php","yellow");
    $handle = c_fopen ("local/Class/{$class}.php","rt",True);
    $len=c_filesize ("local/Class/{$class}.php");
    $buffer=fread ($handle,$len);
    fclose($handle);
    $buffer=str_replace("<?php","",$buffer);
    $buffer=str_replace("?>","",$buffer);
    
    debug("initing time Ente_$class ".(getmicrotime()-$bm)." s.","yellow" );
    return eval("
    class Ente_$class extends Ente {
    ".
    $buffer
    ."  
    }
    return new Ente_$class($class);
    ");
      
    
  }
  else {
    if (class_exists("Ente_$class")) {
      debug("Clase ya cargada Ente_$class","cyan");
      return eval("return new Ente_$class($class);");
    }
    else {
      debug("Cargando clase base $class","cyan");
      return eval("
        class Ente_$class extends Ente {}
        return new Ente_$class($class);
      ");
      //return new Ente($class);
    }
  }
  
}

function coreNew($class) {
  return new Ente($class);
}

/*
********************************
Compatibilidad CoreRC4
********************************
*/
function newObject($class,$id=0) {
  global $SYS;
  
  $bm=getmicrotime();
  
  $fname=session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/".$class."_".$SYS["PROJECT"].".obj";
  if ((file_exists($fname))&&($SYS["bcompiler_extension"])) {
    if (!class_exists("Ente_$class")) {
      $fd = fopen ($fname, "r");
      bcompiler_read($fd);
      fclose($fd);
    }
      $class_name="Ente_$class";
      $tmp=new $class_name($class);
      $tmp->load($id);
  }

  
  else {
  
    $tmp=newObj($class);
    debug("Inicialiando objecto $id");
    $tmp->load($id);
    
    debug("Tiempo de carga de la clase ".(getmicrotime()-$bm)." s.","yellow" );
    $SYS["load_class_time"]+=(getmicrotime()-$bm);
    $SYS["total_classes_loaded"]++;
    if ($SYS["bcompiler_extension"]) {
      $fd = fopen ($fname, "w");
      bcompiler_write_header($fd);
      bcompiler_write_class($fd,"Ente_$class");
      bcompiler_write_footer($fd);
      
      fclose($fd);
    }
  }
  return $tmp;
}

/* Casting entre clases */


function typecast($old_object, $new_classname) {
  if(class_exists($new_classname)) {

    //echo "OLD<br>";
    //dataDump($old_object);

    $old_serialized_object = serialize($old_object);
    $n=explode(":",$old_serialized_object);
    $new_serialized_object = 'O:' . strlen($new_classname) . ':"' . $new_classname . '":' .$n[3].":". 
    substr($old_serialized_object, strpos($old_serialized_object,"{"));

    //echo "Casting OK $old_serialized_object<br>$new_serialized_object";
    //echo "NEW<br>";
    //dataDump(unserialize($new_serialized_object));

    return unserialize($new_serialized_object);
  }
  else {
    debug("$new_classname no existe o no ha sido definida!!!!","red");
    return false;
  }
}

/*

setLimitRows (int numero_de_filas)

Especifica el numero de filas a devolver
en una consulta. Se aplicarï¿½ atodas las consultas 
posteriores a la llamada

*/

function setLimitRows($c) {
    global $SYS,$FAK;

  $FAK=$SYS["DEFAULTROWS"];
  $SYS["DEFAULTROWS"]=$c;
}

/*

resetLimitRows ()

Restablece  el numero de filas a devolver
en una consulta al valor por defecto especificado en fichero
de configuracion. Se aplicarï¿½ atodas las consultas 
posteriores a la llamada

*/

function resetLimitRows() {
    global $SYS,$FAK;

  $SYS["DEFAULTROWS"]=$FAK;
}

/*

setNavVars (array variables_navegacion)

El array navvars especifica las variables que
se pasarï¿½n entre diferentes paginas. Esto es ï¿½til
para la paginaciï¿½n de resultados, manteniendo fijas
algunas de ellas.

*/
function setNavVars($vars) {
 global $SYS;

 $SYS["NAVVARS"]=array_merge($SYS["NAVVARS"],$vars);

}

/*

setNavVarsSeparator (separador)

Separador de variables  (para usar con mod_rewrite)

*/
function setNavVarsSeparator($t) {
 global $SYS;

 $SYS["NAV_SEPARATOR"]=$t;
 $SYS["NAV_SEPARATOR_I"]="";

}

/*

setNavVarsSeparator (separador)

Separador de variables  (para usar con mod_rewrite)

*/

/*
set_include_dir (string filename|dirname )

Usada en la conjuncion con __FILE__
Establece una ruta de busqueda para abrir ficheros.
Ejemplo comï¿½n: set_include_dir(__FILE__) establece que se buscara
en el directorio del script para hacer una apertura de fichero.
(open, fopen, fpassthru...)

*/

function set_include_dir($filename) {

  $current=ini_get("include_path");  
  if (ini_set("include_path",dirname($filename)._PATH_SYMBOL."$current"))
    return True;
  else
    return False;
}

/* 
e_file_exists (string nombre_de_fichero)

Chequea la existencia de un fichero
buscando en el path actual. Extiende
la funcionalidad de file_exists
  
*/
function e_file_exists($filename) {
  global $CACHE_FILE_EXIST;
  
  $CACHE_FILE_EXIST["petitions"]++;
  if (!isset($CACHE_FILE_EXIST[$filename])) {
    $current=ini_get("include_path")._PATH_SYMBOL."/";
    debug("PATH: $current","red");
    $dirs=explode(_PATH_SYMBOL,$current);
    foreach ($dirs as $k=>$v) {
      $res=file_exists($v."/$filename");
      debug($v."/$filename");
      if ($res) {
        debug($v."/$filename"." OK","blue");
        $CACHE_FILE_EXIST[$filename]=$v."/$filename";
        $CACHE_FILE_EXIST["cached"]++;
        return ($v."/$filename");
      }
    }
    $CACHE_FILE_EXIST[$filename]=false;
    return False;
  }
  else
    return $CACHE_FILE_EXIST[$filename];
}

/* 
c_fopen (string nombre_de_fichero)

Wrapper para fopen, para controlar la cantidad de ficheros abiertos
  
*/
function c_fopen($fl,$mode,$inc=False) {

  global $FILES_OPENED;
  $FILES_OPENED[$fl]=True;
  $FILES_OPENED["total"]++;
  return fopen($fl,$mode,$inc);
}
/* 
c_filesize (string nombre_de_fichero)

Wrapper para filesize, que busca en el PATH
  
*/

function c_filesize($filename) {

  $serach_name=e_file_exists($filename);
  return filesize($serach_name);
}


/* 
_safe_strftime(string $format,int $timestamp)

Uses strftime if timestamp>3600
  
*/
function _safe_strftime($format,$timestamp) {
    
    if ($timestamp>3600)
        return strftime($format,$timestamp);
    else
        return "--";
    
}

/* 
_fixed(string cade, int size)

Wrapper para cademas, las amplia en size/2 por cada lado
  
*/

function _fixed($string,$len=17) {

  $l=strlen($string);
  if ($l<$len) {
    $iz=str_repeat("&nbsp;",($len-$l)/2);
    if ($l%2!=0)
      $de=str_repeat("&nbsp;",(($len-$l)/2)-1);
    else
      $de=$iz;
  }
  return "${iz}$string{$de}";
}

/* 
_rfixed(string cade, int size)

Wrapper para cademas, las amplia en size por la derecha
  
*/

function _rfixed($string,$len=25) {

  $l=strlen($string);
  if ($l<$len) 
    $de=str_repeat("&nbsp;",($len-$l));
    
  return "$string{$de}";
}

function _closeSystem() {
  global $SYS,$DEBUGHANDLER,$CODEINITTIME,$monitor;
  
  $CORETIME=getmicrotime()-$CODEINITTIME;
  if (isset($SYS["monitor_enabled"]))
    if ($SYS["monitor_enabled"]) {
      $monitor->MonAverageUpdate($CORETIME);
      $stats=$monitor->MonGetStat();
      debug("Ending System: Pages {$stats["pages"]} Average:{$stats["avg"]} spp","white");
    }
  
  //$monitor->MonClose();
  ob_start();print_r(error_get_last());$err=ob_get_contents();ob_end_clean();
  debug("Debug: $err","red");
  debug("Ending System: Total ms of ASCore:$CORETIME","white");
  fflush($DEBUGHANDLER);
  fclose($DEBUGHANDLER);
}

function getmicrotime(){ 
    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 
  } 

function core_ucfirst($data){ 
    
    return ucwords(strtolower($data));
  } 

/* 
javascript_collector (string data)

Ordena los scripts del buffer
  
*/
  
function javascript_collector($buffer) {


  $from=strpos ( $buffer,"<script",0);
  $xhtml=substr ( $buffer,0,$from);
  if ($from===False)
    return $buffer;
  while ($from) {

        $to=strpos ($buffer,"</script>",$from+1);
        //echo "Cortar desde $from hasta $to";
        $script.=substr ( $buffer,$from,$to-$from+strlen("</script>"))."\n";
         $from=strpos ( $buffer,"<script",$to+1);
    if ($from===False)
      $xhtml.=substr ( $buffer,$to+strlen("</script>"),-1);
    else
      $xhtml.=substr ( $buffer,$to+strlen("</script>"),$from-($to+strlen("</script>")));
  }

  $jpos=str_replace("<!--_JAVASCRIPTCODE-->",$script,$xhtml);
  
  return $jpos."";
}

function css_collector($buffer) {


  $from=strpos ( $buffer,"<style",0);
  $xhtml=substr ( $buffer,0,$from);
  if ($from===False)
    return $buffer;
  while ($from) {

    $to=strpos ($buffer,"</style>",$from+1);
    debug("CSSCOLLETOR: Cortar desde $from hasta $to","white");
    $script.=substr ( $buffer,$from,$to-$from+strlen("</style>"))."\n";
    $from=strpos ( $buffer,"<style",$to+1);
      if ($from===False)
        $xhtml.=substr ( $buffer,$to+strlen("</style>"),-1);
      else
        $xhtml.=substr ( $buffer,$to+strlen("</style>"),$from-($to+strlen("</style>")));
  }

  $jpos=str_replace("<!--_CSSCODE-->",$script,$xhtml);
  
  
  return $jpos;
}


function reArrangeMetas($buffer) {


  
 $ptn = "/<link.*>/i";
  $ptnh = "/<head>/i";
  preg_match_all($ptn, $buffer, $matches);
  $toInsert=implode("\n",$matches[0]);
  $newBuffer=preg_replace($ptn, "", $buffer);
  $buffer=preg_replace($ptnh, "<head>\n$toInsert", $newBuffer);
  
  return $buffer;
}

if (!function_exists("http_build_query")) {

  function http_build_query($data) {
    foreach ($data as $keyz=>$valuez)
      $res.="&$keyz=$valuez";
    return $res;  
  }
}

/* Init code of Core */
/* Debug issues */

$CODEINITTIME=getmicrotime();
if ($TrazaStatus&&(!isset($nodebug))) {

  if ($_SERVER["REMOTE_ADDR"]=="::1")
    $_SERVER["REMOTE_ADDR_OK"]="udp://localhost";
  else
    $_SERVER["REMOTE_ADDR_OK"]="udp://".$_SERVER["REMOTE_ADDR"];
  $DEBUGHANDLER=fsockopen($_SERVER["REMOTE_ADDR_OK"],7869);
  if ($DEBUGHANDLER==False)
    $TrazaStatus=False;
  else {
    
    debug("###########################################################################","white");
    fputs($DEBUGHANDLER,'');
  }
  register_shutdown_function("_closeSystem");

}


/* Intl support */

if (isset($SYS["LANG"])) {
  setlocale(LC_ALL, $SYS["LANG"]);
// Specify location of translation tables
  bindtextdomain("coreg2", dirname(__FILE__)."/../Locale/");
// Choose domain
  textdomain("coreg2");
}


$SYS["NAV_SEPARATOR"]="&amp;";
$SYS["NAV_SEPARATOR_I"]="?";



?>

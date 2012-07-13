<?php 
require_once("System.php");
if (!BILO_isAdmin()) {
        if (($PSECRETKEY!=md5($SECRETKEY))||(!empty($_SERVER)))
                die("No safety");
}
require_once("conf.php");
ob_end_clean();
// MySQL hostname
$host = $SYS["mysql"]["DBHOST"];
//MySQL basename
$dbname =$SYS["mysql"]["DBNAME"];
// MySQL user
$uname = $SYS["mysql"]["DBUSER"];
// MySQL password
$upass =$SYS["mysql"]["DBPASS"];
// set FALSE to get table content
$structure_only=false;
//set TRUE to to get file with dump
$output = true;



//////////////////////////////////////////////////
//
//  phpMyDump v 1.0
//
//  check for new version
//  http://szewo.com/php/mydump/eng
//
// some functions are adapted from the phpMyAdmin 
//
//  do not change anything below this line
//////////////////////////////////////////////////
function mysqlbackup($host,$dbname, $uid, $pwd, $structure_only, $crlf) {  
  
 

 global $prefix;

 $con=@mysql_connect("localhost",$uid, $pwd) or die("Could not connect");  
 $db=@mysql_select_db($dbname,$con) or die("Could not select db");

 // here we check MySQL Version
 $result=@mysql_query("SELECT VERSION() AS version"); 
 if ($result != FALSE && @mysql_num_rows($result) > 0) {
  $row   = @mysql_fetch_array($result);
  $match = explode('.', $row['version']);
 } else {
  $result=@mysql_query("SHOW VARIABLES LIKE \'version\'"); 
  if ($result != FALSE && @mysql_num_rows($result) > 0){
   $row   = @mysql_fetch_row($result);
   $match = explode('.', $row[1]);
  }
 }

 if (!isset($match) || !isset($match[0])) {
  $match[0] = 3;
 }
 if (!isset($match[1])) {
  $match[1] = 21;
 }
 if (!isset($match[2])) {
  $match[2] = 0;
 }
 if(!isset($row)) {
  $row = '3.21.0';
 }

 define('MYSQL_INT_VERSION', (int)sprintf('%d%02d%02d', $match[0], $match[1], intval($match[2])));
 define('MYSQL_STR_VERSION', $row['version']);
 unset($match);

 $sql = "# MySQL dump by phpMyDump".$crlf;
 $sql.= "# Host: $host Database: $dbname".$crlf;
 $sql.= "# ----------------------------".$crlf;
 $sql.= "# Server version: ".MYSQL_STR_VERSION.$crlf;

 $sql.= $crlf.$crlf.$crlf;     
 out(1,$sql);
 $res=@mysql_list_tables($dbname);  
 $nt=@mysql_num_rows($res);  

 for ($a=0;$a<$nt;$a++) {  
  $row=mysql_fetch_row($res);  
  $tablename=$row[0];
  if (strpos($tablename,$prefix."_")===false)
	continue;

  $sql=$crlf."# ----------------------------------------".$crlf."# table structure for table '$tablename' ".$crlf;
  // For MySQL < 3.23.20  
  echo "DROP TABLE IF EXISTS `$tablename`;";
  if (MYSQL_INT_VERSION >= 32321) {
   $result=mysql_query("SHOW CREATE TABLE $tablename");
   if ($result != FALSE && mysql_num_rows($result) > 0) {
    $tmpres = mysql_fetch_array($result);
    $pos           = strpos($tmpres[1], ' (');
    $tmpres[1]     = substr($tmpres[1], 0, 13)
                     . $tmpres[0]
                     . substr($tmpres[1], $pos);
				 
    $sql .= $tmpres[1].";".$crlf.$crlf;
   }
   mysql_free_result($result);
  } else { 
   $sql.="CREATE TABLE $tablename(".$crlf;  
   $result=mysql_query("show fields  from $tablename",$con);  

   while ($row = mysql_fetch_array($result)) {
    $sql .= "  ".$row['Field'];
    $sql .= ' ' . $row['Type'];
    if (isset($row['Default']) && $row['Default'] != '') {
     $sql .= ' DEFAULT \'' . $row['Default'] . '\'';
    }
    if ($row['Null'] != 'YES') {
     $sql .= ' NOT NULL';
    }
    if ($row['Extra'] != '') {
     $sql .= ' ' . $row['Extra'];
    }
    $sql .= ",".$crlf;
   }
 
   mysql_free_result($result);
   $sql = ereg_replace(',' . $crlf . '$', '', $sql);
 
   $result = mysql_query("SHOW KEYS FROM $tablename");
    while ($row = mysql_fetch_array($result)) {
     $ISkeyname    = $row['Key_name'];
     $IScomment  = (isset($row['Comment'])) ? $row['Comment'] : '';
     $ISsub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';
     if ($ISkeyname != 'PRIMARY' && $row['Non_unique'] == 0) {
      $ISkeyname = "UNIQUE|$kname";
     }
     if ($IScomment == 'FULLTEXT') {
      $ISkeyname = 'FULLTEXT|$kname';
     }
     if (!isset($index[$ISkeyname])) {
      $index[$ISkeyname] = array();
     }
     if ($ISsub_part > 1) {
      $index[$ISkeyname][] = $row['Column_name'] . '(' . $ISsub_part . ')';
     } else {
      $index[$ISkeyname][] = $row['Column_name'];
     }
    } 
    mysql_free_result($result);
    
    while (list($x, $columns) = @each($index)) {
     $sql     .= ",".$crlf;
     if ($x == 'PRIMARY') {
      $sql .= '  PRIMARY KEY (';
      } else if (substr($x, 0, 6) == 'UNIQUE') {
      $sql .= '  UNIQUE ' . substr($x, 7) . ' (';
     } else if (substr($x, 0, 8) == 'FULLTEXT') {
      $sql .= '  FULLTEXT ' . substr($x, 9) . ' (';
     } else {
      $sql .= '  KEY ' . $x . ' (';
     }
     $sql     .= implode($columns, ', ') . ')';
    } 
    $sql .=  $crlf.");".$crlf.$crlf;
  
  } 
  out(1,$sql);
 if ($structure_only == FALSE) {
  // here we get table content
  $result = mysql_query("SELECT * FROM  $tablename");
  $fields_cnt   = mysql_num_fields($result);
  while ($row = mysql_fetch_row($result)) {
   $table_list     = '(';
   for ($j = 0; $j < $fields_cnt; $j++) {
    $table_list .= mysql_field_name($result, $j) . ', ';
   }
   $table_list = substr($table_list, 0, -2);
   $table_list     .= ')';

   $sql = 'INSERT INTO ' . $tablename 
                                   . ' VALUES (';
   for ($j = 0; $j < $fields_cnt; $j++) {
    if (!isset($row[$j])) {
     $sql .= ' NULL, ';
    } else if ($row[$j] == '0' || $row[$j] != '') {
     $type          = mysql_field_type($result, $j);
     // a number
     if ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' ||
                        $type == 'bigint'  ||$type == 'timestamp') {
      $sql .= $row[$j] . ', ';
     }
     // a string
     else {
      $dummy  = '';
      $srcstr = $row[$j];
      for ($xx = 0; $xx < strlen($srcstr); $xx++) {
       $yy = strlen($dummy);
       if ($srcstr[$xx] == '\\')   $dummy .= '\\\\';
       if ($srcstr[$xx] == '\'')   $dummy .= '\\\'';
       if ($srcstr[$xx] == "\x00") $dummy .= '\0';
       if ($srcstr[$xx] == "\x0a") $dummy .= '\n';
       if ($srcstr[$xx] == "\x0d") $dummy .= '\r';
       if ($srcstr[$xx] == "\x1a") $dummy .= '\Z';
       if (strlen($dummy) == $yy)  $dummy .= $srcstr[$xx];
      }
      $sql .= "'" . $dummy . "', ";
     }
    } else {
     $sql .= "'', ";
    } // end if
   } // end for
   $sql = ereg_replace(', $', '', $sql);
   $sql .= ");".$crlf;
   out(1,$sql);   

  } 
  mysql_free_result($result);
  } 
 }
 return;  
}  

function define_crlf() {
 global $HTTP_USER_AGENT;
 $ucrlf = "\n";
 if (strstr($HTTP_USER_AGENT, 'Win')) {
  $ucrlf = "\r\n";
 }
 else if (strstr($HTTP_USER_AGENT, 'Mac')) {
  $ucrlf = "\r";
 }
 else {
  $ucrlf = "\n";
 }
 return $ucrlf;
} 

//print the result
function out($fptr,$s)   { 
 echo $s; 
} 

if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
 define('USER_BROWSER_AGENT', 'OPERA');
} else if (ereg('MSIE ([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
 define('USER_BROWSER_AGENT', 'IE');
} else if (ereg('OmniWeb/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
 define('USER_BROWSER_AGENT', 'OMNIWEB');
} else if (ereg('Mozilla/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
 define('USER_BROWSER_AGENT', 'MOZILLA');
} else if (ereg('Konqueror/([0-9].[0-9]{1,2})', $HTTP_USER_AGENT, $log_version)) {
 define('USER_BROWSER_AGENT', 'KONQUEROR');
} else {
 define('USER_BROWSER_AGENT', 'OTHER');
}

  $mime_type = (USER_BROWSER_AGENT == 'IE' || USER_BROWSER_AGENT == 'OPERA')
                   ? 'application/octetstream'
                   : 'application/octet-stream';

				   
$now = gmdate('D, d M Y H:i:s') . ' GMT';
$filename = $dbname."-".(date("d-m-y"));
$ext = "sql";
$crlf = define_crlf();
// Send headers
if ($output == true) {
 header('Content-Type: ' . $mime_type);
 header('Expires: ' . $now);
 header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
 // lem9 & loic1: IE need specific headers
 if (USER_BROWSER_AGENT == 'IE') {
  header('Content-Disposition: inline; filename="' . $filename . '.' . $ext . '"');
  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  header('Pragma: public');
 } else {
  header('Content-Disposition: attachment; filename="' . $filename . '.' . $ext . '"');
  header('Pragma: no-cache');
      
 }
 mysqlbackup($host,$dbname,$uname,$upass,$structure_only,$crlf);
} 
 else {
 echo "<pre>";
 echo htmlspecialchars(mysqlbackup($host,$dbname,$uname,$upass,$structure_only,$crlf));
 echo "</PRE>";
}
?>

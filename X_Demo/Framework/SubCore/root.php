<?php
  
  if(substr(PHP_VERSION, 0, 1) < 5) {
    /* 
    
    Previous XML stuff 
    
    Funciones XML usadas por el parser para definir
    la clase al vuelo.
    
    */
    
    /*
    
    FUNCION   xmlstartElement
    
    Internal use only
    
    
    */
    function xmlstartElement($parser, $name, $attrs) {
      global $curEle;
      
      $curEle[0]=strtolower($name);
      $curEle[1]=$attrs["TYPE"].":".$attrs["OPTION"];
      
      
    }
    
    /*
    
    FUNCION   xmlendElement
    
    Internal use only
    
    
    */
    function xmlendElement($parser, $name) {
      global $curEle,$prop,$TrazaStatus;
      
      
      if ($TrazaStatus>1)
        debug("Propiedad \"$curEle[0]\" Tipo \"$curEle[1]\" Descripcion \"$curEle[2]\"","blue");
      $prop["p"][$curEle[0]]="";
      $prop["pd"][$curEle[0]]=$curEle[2];
      $prop["pt"][$curEle[0]]=$curEle[1];
      
      
      
      
    }
    
    /*
    
    FUNCION   xmlcharacterData
    
    Internal use only
    
    
    */
    
    function xmlcharacterData($parser, $data) {
      global $curEle;
      $curEle["saved"]=$curEle[2];
      $curEle[2]=$data;
      
    }
    
    /*
    
    FUNCION   load_prop
    
    Internal use only
    
    
    */
    function load_prop($file) {
      global $prop,$curEle;
      $prop=array(array(array()));
      $curEle="";
      $xml_parser = xml_parser_create();
      // use case-folding so we are sure to find the tag in $map_array
      xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
      xml_set_element_handler($xml_parser, "xmlstartElement", "xmlendElement");
      xml_set_character_data_handler($xml_parser, "xmlcharacterData");
      if (!($fp = c_fopen($file, "r"))) {
        die(debug("could not open XML input","red"));
      }
      
      while ($data = fread($fp, 4096)) {
        if (!xml_parse($xml_parser, $data, feof($fp))) {
          die(debug("XML error: ".xml_error_string(xml_get_error_code($xml_parser))." at line ".xml_get_current_line_number($xml_parser),"red"));
        }
      }
      xml_parser_free($xml_parser);
      $prop["pd"][$curEle[0]]=$curEle["saved"];//What a fuckin' patch!! Ugh!
      
      /* Some default System properties */
      $prop["p"]["S_UserID_CB"]="";
      $prop["pd"]["S_UserID_CB"]="Creado por";
      $prop["pt"]["S_UserID_CB"]="ref:";
      
      $prop["p"]["S_UserID_MB"]="";
      $prop["pd"]["S_UserID_MB"]="Modificado por";
      $prop["pt"]["S_UserID_MB"]="ref:";
      
      $prop["p"]["S_Date_C"]="";
      $prop["pd"]["S_Date_C"]="Fecha Creacion";
      $prop["pt"]["S_Date_C"]="date:";
      
      $prop["p"]["S_Date_M"]="";
      $prop["pd"]["S_Date_M"]="Fecha Modificacion";
      $prop["pt"]["S_Date_M"]="date:";
      
      return ($prop);
      
    }
    
    
    
  } else if(substr(PHP_VERSION, 0, 1)==5) {
    
    
    function load_prop($file) {
      
      if (!($fp = c_fopen($file, "r"))) {
        die(debug("could not open XML input","red"));
      }
      
      while ($tdata = fread($fp, 4096))
        $data.=$tdata;
      
      
      $entity=simplexml_load_string(($data));
      $prop=array(array(array()));
      //dataDump(get_object_vars($entity));
      foreach(get_object_vars($entity) as $k=>$v) {
        $prop["p"][$k]='';
        $prop["pd"][$k]=$v;
        $atts=$entity->$k->attributes();
        //print_r($atts[0]);
        $prop["pt"][$k]=$atts[0].":".$atts[1];
      }
      
      
      $prop["p"]["S_UserID_CB"]="";
      $prop["pd"]["S_UserID_CB"]="Creado por";
      $prop["pt"]["S_UserID_CB"]="ref:";
      
      $prop["p"]["S_UserID_MB"]="";
      $prop["pd"]["S_UserID_MB"]="Modificado por";
      $prop["pt"]["S_UserID_MB"]="ref:";
      
      $prop["p"]["S_Date_C"]="";
      $prop["pd"]["S_Date_C"]="Fecha Creacion";
      $prop["pt"]["S_Date_C"]="date:";
      
      $prop["p"]["S_Date_M"]="";
      $prop["pd"]["S_Date_M"]="Fecha Modificacion";
      $prop["pt"]["S_Date_M"]="date:";
      
      return ($prop);
      
    }
  }
    
    
    /* 
    ****************************************
    DECLARACION DE LA CLASE RAIZ
    ****************************************
    */
    
    
    class Ente extends core{
      
      var $properties;
      var $properties_desc;
      var $properties_type;
      var $name;
      var $ID="0";
      var $nRes=0;
      var $offset;
      var $ERROR;
      var $ROOT;
      
      /*
      
      FUNCION Ente
      
      Sintaxis  Ente($name)
      $name    Nombre de la clase a cargar
      Descripcion:
      Carga una clase des un fichero .def
      El fichero debe estar guardado en el directorio
      especificado por la configuracion:
      
      $SYS["DOCROOT"].$SYS["DATADEFPATH"]
      
      No se suele usar este método directamente. Es más
      aconsejable usar las funciones newObject o coreNew
      
      
      */
      
      function Ente($name) {
        
        global $SYS,$TrazaStatus,$MEMORY_CACHE;
        
        
        $this->properties_desc=array();
        $this->properties=array();
        $this->properties_type=array();
        $this->ROOT=$SYS["ROOT"];
        $cache_time=false;
        $source_time=false;
        
        /* Soporte para precompilados */    
        
        if (!isset($MEMORY_CACHE["$name"])) {
          if (file_exists(session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/".$name.".cached_core_object_properties_".$SYS["PROJECT"])) 
            $cache_time=filemtime(session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/".$name.".cached_core_object_properties_".$SYS["PROJECT"]);
          
          else
            $cache_time=false;
          
          if (file_exists($SYS["DOCROOT"].$SYS["DATADEFPATH"].$name.".def"))
            $source_time=filemtime($SYS["DOCROOT"].$SYS["DATADEFPATH"].$name.".def");
          
          else if (e_file_exists("local/Class/{$name}.def"))
            $source_time=filemtime(e_file_exists("local/Class/{$name}.def"));
          
          else
            $source_time=false;
          
          
          
          
          
          
          
          
          if (($cache_time!==False)&&($cache_time>$source_time)) {
            
            debug("Cargando definicion compilada de '$name'","yellow");
            $fd = c_fopen (session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/".$name.".cached_core_object_properties_".$SYS["PROJECT"], "r");
            $buffer="";
            while (!feof($fd)) {
              $buffer .= fgets($fd, 4096);
            }
            fclose ($fd);
            $prop=unserialize($buffer);
            unset($buffer);
            
          }
          else {
            
            /* Establece las propiedades desde un fichero XML */
            
            if (file_exists($SYS["DOCROOT"].$SYS["DATADEFPATH"].$name.".def")) {
              debug("Fichero definicion ".$SYS["DOCROOT"].$SYS["DATADEFPATH"].$name.".def existe");
              $file = $SYS["DOCROOT"].$SYS["DATADEFPATH"].$name.".def";
            }
            else
              if (e_file_exists("local/Class/{$name}.def")) {
                debug($SYS["BASE"]."/local/Class/{$name}.def existe");
                $file = e_file_exists("local/Class/{$name}.def");
              }
            else
              die(debug($SYS["BASE"]."/local/Class/{$name}.def no  existe"));
            
            
            
            debug("Cargando definicion de '$name'","yellow");
            
            $prop=load_prop($file);
            
            
            
            debug("Compilando dinamicamente '$name'","magenta");
            $fd = c_fopen (session_save_path()."/coreg2_cache/{$SYS["ASCACHEDIR"]}/".$name.".cached_core_object_properties_".$SYS["PROJECT"], "w");
            fwrite($fd,serialize($prop),strlen(serialize($prop)));
            fclose($fd);
            
            
          }
          $MEMORY_CACHE["$name"]=serialize($prop);
        }
        else {
          
          $prop=unserialize($MEMORY_CACHE["$name"]);
        }
        $this->properties=$prop["p"];
        $this->properties_desc=$prop["pd"];
        $this->properties_type=$prop["pt"];
        $this->_normalize();
        
        $this->name=$name;
        
        
        
      }
      
      /*********************
      Function show
      
      Sintaxis  show($propertie)
      $propertie  propertie to print
      
      Print a propertie value
      
      *********************/
      
      
      function show($prop) {
        
        echo $this->properties[$prop];
      }
      
      /*********************
      Function get
      *********************/
      
      function get($prop) {
        
        return $this->properties[$prop];
      }
      
      /*********************
      Function set
      *********************/
      
      function set($prop,$value) {
        
        
        if (!in_array($prop,array_keys($this->properties))) {
          debug("Propiedad - \"$prop\" - no disponible","red");
          return false;
        }
        else {
          $this->properties[$prop]=$value;
          $this->_normalize();
          return true;
        }
        
      }
      
      /*********************
      Function get_ext
      *********************/
      
      function get_ext($object,$reference,$field,$function=False,$functionargs="") {
        
        $tmp=newObject("$object",$reference);
        if (($function)||(method_exists($tmp,$field)))
          return $tmp->$field($functionargs);
        else
          return $tmp->$field;
        
      }
      
      /*********************
      Function setAll
      
      Syntax    setAll(array)
      
      Load all properties from array
      Example: setAll($_POST);
      
      Do some magic on types
      *********************/
      
      function setAll($arraydata) {
        
        foreach ($arraydata as $k=>$v) 
                 if (in_array($k,array_keys($this->properties)))
                   $this->properties[$k]=$arraydata["$k"];
        
        foreach ($this->properties as $pk=>$pv) {
          if (strpos($this->properties_type[$pk],"boolean:")!==False)
            $this->properties[$pk]=(($arraydata["$pk"]=="on")||($arraydata["$pk"]=="Si")||($arraydata["$pk"]=="1"))?'Si':'No';
          if (strpos($this->properties_type[$pk],"money:")!==False)
            $this->properties[$pk]=str_replace(",",".",str_replace(".","",$arraydata["$pk"]));
          
          
        }
        
        $this->ID=$arraydata["ID"];
        $this->_normalize();
      }
      
      /*********************
      Function setAllSoft
      
      Syntax    setAllSoft(array)
      
      Load all properties from array
      Example: setAllSoft($_POST);
      
      Do some magic on types. Don't use if checks.
      *********************/
      
      function setAllSoft($arraydata) {
        
        foreach ($arraydata as $k=>$v) 
                 if (in_array($k,array_keys($this->properties)))
                   $this->properties[$k]=$v;
        
        
        
        $this->ID=$arraydata["ID"];
        $this->_normalize();
        
        
        
        
      }
      
      
      /*********************
      Function save
      
      Syntax:    save()
      
      Saves the current object
      if ID<2, it will create a new elemente in the table
      
      *********************/
      
      function save() {
        global $res,$prefix;
        
        /* Normalizamos datos */
        
        $this->data_normalize();
        
        $res="";
        if (($this->ID>1)&&!empty($this->ID)) {
          debug("Llamada ".$this->name."->save redirigida a update con ".$this->ID,"yellow");
          return $this->update();
        }
        else
        {
          if (!function_exists("fsadd")) {
            function fsadd(&$str) {
              global $res;
              if ($str!="ID")
                $res.=",`$str`";
            }
          }
            if (!function_exists("dsadd")) {
              /* Funcion dinamica */
              function dsadd(&$str,$key) {
                global $res;
                if ($key!="ID")
                  $res.=",'".addslashes($str)."'";
              }
            }
              $this->S_UserID_CB=$_SESSION["__auth"]["uid"];
              $this->S_Date_C=time();
              
              $this->_flat();
              array_walk(array_keys($this->properties),"fsadd");
              $q="INSERT INTO `{$prefix}_".$this->name."`( `ID` ".$res.")";
              $res="";
              reset($this->properties);
              array_walk($this->properties,"dsadd");
              $q.=" VALUES (''$res)";
              
              $bdres=_query($q);
              $this->ID=_last_id();
              //----------------------------------------------------------------------------------
              
              $xref=array();
              foreach ($this->properties as $pk=>$pv) {
                if (strpos($this->properties_type[$pk],"xref")!==False) {
                  $xref=explode(":",$this->properties_type[$pk]);
                  $table_name=$this->name."_".$xref[1];
                  
                  $field2=$this->name."_id";
                  $field3=$xref[1]."_id";
                  
                  $xref_2_array=array();
                  
                  if(!empty($this->$pk)) {
                    // Rellenamos la tabla de referencias externas
                    foreach ($this->$pk as $k=>$v) {
                      $q="INSERT INTO `{$prefix}_".$table_name."`( `ID` ".",`$field2`".",`$field3`".")";
                      $q.=" VALUES (''".",'$this->ID'".",'$v'".")";
                      $bdres=_query($q);
                      
                      $q2="SELECT `$xref[2]` FROM `{$prefix}_".$xref[1]."` WHERE `ID`=$v";
                      $bdres=_query($q2);
                      $rawres=_fetch_array($bdres);
                      $xref_2_array[$v]=$rawres[$xref[2]];
                    }    
                  }      
                  $this->$pk=$xref_2_array;          
                }
              }      
              
              //----------------------------------------------------------------------------------
              $this->nRes=_affected_rows();
              if ($this->nRes>0)
                return $this->ID;
              else
                return False;
            }
            
          }
          
          /*********************
          Function delete
          *********************/
          
          function delete() {
            
            global $res,$prefix;
            //----------------------------------------------------------------------------------
            
            $xref=array();
            foreach ($this->properties as $pk=>$pv) {
              if (strpos($this->properties_type[$pk],"xref")!==False) {
                $xref=explode(":",$this->properties_type[$pk]);
                $table_name=$this->name."_".$xref[1];
                
                $field2=$this->name."_id";        
                
                $q="DELETE FROM `{$prefix}_".$table_name."` WHERE `".$field2."`=".$this->ID;
                $bdres=_query($q);
              }
            }
            
            //----------------------------------------------------------------------------------
            $q="DELETE FROM `{$prefix}_".$this->name."` WHERE `ID` = '".$this->ID."'  ";
            $bdres=_query($q);
            $this->nRes=_affected_rows();
            if ($this->nRes>0)
              return True;
            else
              return False;
            
          }
          
          /*********************
          Function deletes
          *********************/
          
          function deletes($cond) {
            
            global $res,$prefix;
            $q="DELETE FROM `{$prefix}_".$this->name."` WHERE $cond  ";
            $bdres=_query($q);
            $this->nRes=_affected_rows();
            if ($this->nRes>0)
              return True;
            else
              return False;
            
          }
          
          
          /*********************
          Function load
          *********************/
          
          function load($id){
            
            global $prefix;
            
            if ($id==0)
              return array();  
            $q="SELECT * from {$prefix}_".$this->name." WHERE ID=$id";
            $bdres=_query($q);
            $rawres=_fetch_array($bdres);
            if ($rawres===False)
              return False;
            $this->ID=$rawres["ID"];
            $this->properties=array_slice($rawres,0);
            $this->_normalize();
            
            return True;
            
          }
          
          
          /*********************
          Function update
          *********************/
          
          function update(){
            
            global $res,$prefix;
            
            $this->S_UserID_MB=$_SESSION["__auth"]["uid"];
            $this->S_Date_M=time();
            $this->_flat();
            if (!function_exists("fadd")) {
              function fadd(&$val,&$key) {
                global $res;
                $res.=" `$key`='".addslashes($val)."' , ";
              }
            }
              //------------------------------------------------------------------------------------------
              
              $xref=array();
              foreach ($this->properties as $pk=>$pv) {
                if (strpos($this->properties_type[$pk],"xref")!==False) {
                  $xref=explode(":",$this->properties_type[$pk]);
                  $table_name=$this->name."_".$xref[1];
                  
                  $field2=$this->name."_id";
                  $field3=$xref[1]."_id";
                  
                  $xref_2_array=array();
                  
                  // Antes de actualizar borramos lo que hubiera en la tabla de referencias externas asociado al objeto que llama al método
                  $q="DELETE FROM `{$prefix}_".$table_name."` WHERE `".$field2."`=".$this->ID;
                  $bdres=_query($q);
                  
                  // Actualizamos la tabla de referencias externas
                  foreach ($this->$pk as $k=>$v) {      
                    $q="INSERT INTO `{$prefix}_".$table_name."`( `ID` ".",`$field2`".",`$field3`".")";
                    $q.=" VALUES (''".",'$this->ID'".",'$v'".")";
                    
                    $bdres=_query($q);
                    
                    $q2="SELECT `$xref[2]` FROM `{$prefix}_".$xref[1]."` WHERE `ID`=$v";
                    $bdres=_query($q2);
                    $rawres=_fetch_array($bdres);
                    $xref_2_array[$v]=$rawres[$xref[2]];
                  }
                  $this->$pk=$xref_2_array;    
                }          
              }    
              
              //------------------------------------------------------------------------------------------
              array_walk($this->properties,"fadd");
              $q="UPDATE `{$prefix}_".$this->name."` SET ";
              $q.=substr($res,0,strlen(sizeof($res))-3)."  WHERE `ID`=".$this->ID. " LIMIT 1";
              $bdres=_query($q);    
              $this->nRes=_affected_rows();    
              if ($this->nRes>-1)
                return $this->ID;
              else
                return False;
              
            }
            
            /*********************
            Function selectAll
            *********************/
            
            function selectAll($offset=0,$sort="ID") {
              
              global $prefix,$SYS;
              debug($SYS["DEFAULTROWS"]);
              if ((empty($sort)))
                $sort="ID";
              $All=array();
              if ((empty($offset))||($offset<0))
                $offset=0;
              $q="SELECT SQL_CALC_FOUND_ROWS * from {$prefix}_".$this->name." WHERE ID>1";
              $q.=" ORDER BY $sort LIMIT $offset,".$SYS["DEFAULTROWS"];
              
              $bdres=_query($q);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              
              for ($i=0,$af_rows=_affected_rows();$i<$af_rows;$i++) {
                $rawres=_fetch_array($bdres);
                //$p=array_slice($rawres,1);
                $All[$i]=$this->_clone($rawres);
              }
              $this->nRes=_affected_rows();
              if ($this->nRes<$SYS["DEFAULTROWS"])
                $this->nextP=$offset;
              else
                $this->nextP=$offset+$this->nRes;
              $this->prevP=$offset-$SYS["DEFAULTROWS"];
              
              $bdres=_query("SELECT FOUND_ROWS()");
              $aux=_fetch_array($bdres);
              $this->totalPages=$aux["FOUND_ROWS()"];
              
              return $All;
              
              
            }
            
            /*********************
            Function select
            *********************/
            
            function select($q,$offset=0,$sort="ID",$groupby='',$addfields='') {
              
              global $prefix,$SYS;
              
              $All=array();
              if ((empty($sort)))
                $sort="ID";
              if ((empty($offset))||($offset<0))
                $offset=0;
              
              $q="SELECT SQL_CALC_FOUND_ROWS *$addfields from {$prefix}_".$this->name." WHERE $q AND ID>1 $groupby";
              $q.=" ORDER BY $sort LIMIT $offset,".$SYS["DEFAULTROWS"];
              
              $bdres=_query($q);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              
              for ($i=0,$aff_rows=_affected_rows();$i<$aff_rows;$i++) {
                $rawres=_fetch_array($bdres);
                //$p=array_slice($rawres,1);
                $All[$i]=$this->_clone($rawres);
              }
              $this->nRes=_affected_rows();
              if ($this->nRes<$SYS["DEFAULTROWS"])
                $this->nextP=$offset;
              else
                $this->nextP=$offset+$this->nRes;
              $this->prevP=$offset-$SYS["DEFAULTROWS"];
              
              $bdres=_query("SELECT FOUND_ROWS()");
              $aux=_fetch_array($bdres);
              $this->totalPages=$aux["FOUND_ROWS()"];
              
              return $All;
              
            }
            /*********************
            Function select
            *********************/
            
            function query($q,$offset=0,$sort="ID") {
              
              global $prefix,$SYS;
              
              $All=array();
              if ((empty($sort)))
                $sort="{$prefix}{$this->name}.ID";
              if ((empty($offset))||($offset<0))
                $offset=0;
              
              
              $q.=" ORDER BY $sort LIMIT $offset,".$SYS["DEFAULTROWS"];
              
              
              $bdres=_query($q);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              
              for ($i=0,$af_rows=_affected_rows();$i<$af_rows;$i++) {
                $rawres=_fetch_array($bdres);
                //$p=array_slice($rawres,1);
                $All[$i]=$this->_clone($rawres);
              }
              $this->nRes=_affected_rows();
              if ($this->nRes<$SYS["DEFAULTROWS"])
                $this->nextP=$offset;
              else
                $this->nextP=$offset+$this->nRes;
              $this->prevP=$offset-$SYS["DEFAULTROWS"];
              return $All;
              
              
            }
            
            /*********************
            Function listAll
            
            Syntax    listAll($field,$addVoidValue=True,$more="") 
            
            Load all objects that meet optional $more criterial (default all)
            Example: listAll("username");
            
            $field=>  field or method
            $addVoidValue=>  add null value at the beginning of the array
            $more=>    Criteria (default all)
            
            return value => array of objects
            
            *********************/
            
            
            function listAll($field,$addVoidValue=True,$more="",$offset=0,$sort="ID ASC") {
              
              setLimitRows(15000);
              if (empty($more))
                $all=$this->selectAll();
              else
                $all=$this->select($more,$offset,$sort);
              
              resetLimitRows();
              
              if ($addVoidValue)
                $list[1]="--";
              foreach ($all as $k=>$o)
                       if (method_exists($o,$field)) {
                         debug("Llamada listAll tiene argumento a funcion","red");
                         $list[$o->ID]=$o->$field();
                       }
              else
                $list[$o->ID]=$o->$field;
              return $list;
              
            }
            
            
            
            
            /*********************
            Function _clone
            *********************/
            
            
            function _clone($data) {
              
              if (((int)substr(PHP_VERSION,0,1)==5)) {
                $bin=clone($this);
              $bin->properties=$data;
              $bin->_normalize();
              return $bin;
            }
            else {
              $bin=$this;
              $bin->properties=$data;
              $bin->_normalize();
              return $bin;
            }
          }
            
            /*********************
            Function _normalize
            *********************/
            
            function _normalize() {
              if (is_array($this->properties))
                foreach ($this->properties as $k=>$v)  {
                  
                  if (strpos($this->properties_type[$k],"boolean:")!==False) {
                    $this->$k=(($v=="on")||($v=="Si"))?'Si':'No';
                    
                    
                  }
                  else
                    if (!is_array($this->properties[$k]))
                      $this->$k="".$v."";
                  else
                    $this->$k=$this->properties[$k];
                  
                }
              
              $this->data_normalize();
            }
            
            /*********************
            Function _escape_chars
            *********************/
            
            function _escape_chars() {
              if (is_array($this->properties))
                foreach ($this->properties as $k=>$v)  {
                  
                  
                  $this->$k=addslashes($v);
                  
                }
            }
            
            /*********************
            Function _flat
            *********************/
            
            function _flat() {
              $newproperties=$this->properties;
              foreach ($newproperties as $k=>$v)
                       if(!is_array($this->properties[$k]))
                         $this->properties[$k]="".$this->$k."";
              else
                $this->properties[$k]=$this->$k;    
            }
            
            
            /*********************
            Function data_normalize
            *********************/
            
            function data_normalize() {
              $this->_flat();
              foreach ($this->properties_type as $k=>$v) {
                if ((strpos($v,"datex")===0)&&(!ereg("^[0-9]+$",$this->$k))) {
                  $fecha=explode("/", $this->properties[$k]);
                  $fecha=$fecha[0]+$fecha[1]*100+$fecha[2]*10000;      
                  $this->$k=$fecha;
                }
                else  if (strpos($v,"date")===0){
                  if (!is_numeric($this->$k)) {
                    $format=substr($v,strpos($v,":")+1);
                    $format=($format)?$format:"%d/%m/%Y";
                    $ftime=strptime($this->$k,$format);
                    $unxTimestamp = mktime( 
                      $ftime['tm_hour'], 
                      $ftime['tm_min'], 
                      $ftime['tm_sec'], 
                      1 , 
                      $ftime['tm_yday'] + 1, 
                      $ftime['tm_year'] + 1900 
                             ); 
                    $fecha=$unxTimestamp;
                    debug("DATA NORM  {$arrFecha['errors'][0]} -$format- Fecha #".$this->$k."# {$k} : $fecha :".strftime($format,$fecha),"white");
                    $this->$k=$fecha;
                  }
                }
                else  if (strpos($v,"password")===0){
                  $options=explode(":",$v);
                  if ($options[1]=="basic")
                    continue;
                  else if ($options[1]=="md5") {
                    if (!empty($this->$k))
                      $this->$k=md5($this->$k);
                    else
                      unset($this->properties[$k]);
                  }
                  
                }
              }
              
              
              $this->_flat();
            }
            
            /* 
            ***********************************************************
            ***********************************************************
            ********** Métodos de útiles para el framework ************
            ***********************************************************
            ***********************************************************
            
            */
            
            function showProperties() {
              global $prefix;
              
              reset($this->properties);
              reset($this->properties_type);
              reset($this->properties_desc);
              echo "<table border=\"1\" cellpadding=\"1\">";
              echo "<th>Propiedad</th><th>Valor actual</th><th>Tipo</th><th>Descripcion</th>";
              for ($i=0,$loop_c=sizeof($this->properties);$i<$loop_c;$i++) {
                
                
                echo "<tr>";
                echo "<td>".key($this->properties)."</td>";
                echo "<td>".current($this->properties)."</td>";
                echo "<td>".current($this->properties_type)."</td>";
                echo "<td>".current($this->properties_desc)."</td>";
                echo "</tr>";
                $q.=key($this->properties)."\t".current($this->properties)."\t".current($this->properties_type)."\t".current($this->properties_desc)."\n";
                next($this->properties_desc);
                next($this->properties_type);
                next($this->properties);
              }
              echo "<tr><td>DataBase Instance</td><td colspan=\"3\">";
              $res=_query("SHOW TABLE STATUS LIKE '{$prefix}_{$this->name}'");
              dataDump(_fetch_array($res));
              
              echo "</td></tr></table>";
              
            }
            function sqlGenesis() {
              
              global $prefix;
              
              debug("Prefijo $prefix tabla $this->name","red");
              $q="SHOW TABLES";
              $bdres=_query($q);
              $exists=False;
              
              while ($rawres=_fetch_array($bdres)) {
                if (current($rawres)=="{$prefix}_{$this->name}")
                  $exists=True;
              }
              if ($exists) {
                $q="";
                /* La tabla  existe */
                $q="SHOW FIELDS FROM `{$prefix}_{$this->name}`";
                $bdres=_query($q);
                $j=0;
                while ($rawres=_fetch_array($bdres)) {
                  $fieldlst[$j]=current($rawres);
                  $j++;
                }
                $q="";
                reset($this->properties_type);
                reset($this->properties);
                for ($i=0,$loop_c=sizeof($this->properties);$i<$loop_c;$i++) {
                  //echo "-".current($this->properties_type).".<br>";
                  if (in_array(key($this->properties),$fieldlst))
                    $action="ALTER TABLE `{$prefix}_".$this->name."` CHANGE `".key($this->properties)."` `".key($this->properties)."` ";
                  else
                    $action="ALTER TABLE `{$prefix}_".$this->name."` ADD `".key($this->properties)."` ";
                  
                  if (strstr(current($this->properties_type),"string")) {  
                    $len=explode(":",current($this->properties_type));
                    $q.= $action."VARCHAR( $len[1] ) NOT NULL ;\n";
                  }
                  else if (strstr(current($this->properties_type),"longtext")) {
                    $len=explode(":",current($this->properties_type));
                    $q.= $action."BLOB  NOT NULL ;\n";
                  }
                  elseif (strstr(current($this->properties_type),"text")) {
                    $q.=$action." TEXT  NOT NULL ;\n";
                  }
                  elseif (strstr(current($this->properties_type),"password")) {
                    $q.=$action." TEXT  NOT NULL ;\n";
                  }
                  else if (strstr(current($this->properties_type),"list")) {
                    $len=explode(":",current($this->properties_type));
                    $options=explode("|",$len[1]);
                    $enum="'".$options[0]."'";
                    for ($j=1,$options_size=sizeof($options);$j<$options_size;$j++) {
                      $enum.=",'".$options[$j]."'";
                    }
                    $q.=$action." ENUM ( ".$enum." ) NOT NULL;\n";
                    
                  }
                  
                  elseif (strstr(current($this->properties_type),"nulo")) {
                    $q.="";
                  }
                  //------------------------------------------------------------------------------------------
                  
                  else if (strstr(current($this->properties_type),"xref")) {
                    $q.=$action." INT;\n";
                    
                    $xref=explode(":",current($this->properties_type));
                    $table_name=$this->name."_".$xref[1];
                    
                    $field2=$this->name."_id";
                    $field3=$xref[1]."_id";
                    
                    $q2="SHOW TABLES";
                    $bdres=_query($q2);
                    $exists=False;
                    while ($rawres=_fetch_array($bdres)) {
                      if (current($rawres)=="{$prefix}_{$table_name}")
                        $exists=True;
                    }
                    if (!$exists) { // Si no existe, creamos la tabla de referencias externas
                      $q2="CREATE TABLE `{$prefix}_".$table_name."` (\n";
                      $q2.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
                      $q2.="`".$field2."` INT NOT NULL ,\n";
                      $q2.="`".$field3."` INT NOT NULL ,\n";
                      $q2.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
                      $bdres=_query($q2);
                      $warning="La tabla de referencias externas creada anteriormente deberá ser borrada manualmente de la base de datos";
                    }        
                    
                  }
                  
                  //------------------------------------------------------------------------------------------
                  else if (strstr(current($this->properties_type),"ref")) {
                    $q.=$action." INT;\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"int")) {
                    $q.=$action." INT;\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"date")) {
                    $q.=$action." INT;\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"datex")) {
                    $q.=$action." INT;\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"time")) {
                    $q.=$action." INT;\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"money")) {
                    $q.=$action." DECIMAL(15,5);\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"float")) {
                    $q.=$action." DECIMAL(15,5);\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"boolean")) {
                    $len=explode(":",current($this->properties_type));
                    $q.=$action." ENUM('Si','No') ";
                    $q.="DEFAULT '{$len[1]}' NOT NULL;\n";
                    
                  }
                  
                  next($this->properties_type);
                  next($this->properties);
                }
                //      LIMPIEZA
                print_r($fieldlst);
                for ($i=0,$fieldlst_options=sizeof($fieldlst);$i<$fieldlst_options;$i++)
                     if ((in_array($fieldlst[$i],array_keys($this->properties)))||($fieldlst[$i]=="ID"))
                       echo "";
                else {
                  $q.="ALTER TABLE `{$prefix}_{$this->name}` DROP `".$fieldlst[$i]."`;\n";
                }
                echo "<pre>$q</pre>";
                //------------------------------------------------------------------------------------------
                
                echo $warning;
                
                //------------------------------------------------------------------------------------------
                return $q;
                
              }
              else
              {
                //------------------------------------------------------------------------------------------
                
                // Creamos previamente la tabla de referencias externas
                foreach ($this->properties as $pk=>$pv) {
                  if (strpos($this->properties_type[$pk],"xref")!==False) {
                    $xref=explode(":",$this->properties_type[$pk]);
                    $table_name=$this->name."_".$xref[1];
                    
                    
                    $field2=$this->name."_id";
                    $field3=$xref[1]."_id";
                    
                    $q="CREATE TABLE `{$prefix}_".$table_name."` (\n";
                    $q.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
                    $q.="`".$field2."` INT NOT NULL ,\n";
                    $q.="`".$field3."` INT NOT NULL ,\n";
                    $q.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
                    $bdres=_query($q);
                  }
                }
                
                //------------------------------------------------------------------------------------------
                $q ="CREATE TABLE `{$prefix}_".$this->name."` (\n";
                $q.="`ID` INT NOT NULL AUTO_INCREMENT ,\n";
                reset($this->properties_type);
                reset($this->properties);
                for ($i=0,$properties_size=sizeof($this->properties);$i<$properties_size;$i++) {
                  //echo "-".current($this->properties_type).".<br>";
                  if (strstr(current($this->properties_type),"string")) {
                    $len=explode(":",current($this->properties_type));
                    $q.="`".key($this->properties)."` VARCHAR( $len[1] ) NOT NULL ,\n";
                  }
                  else if (strstr(current($this->properties_type),"longtext")) {
                    $len=explode(":",current($this->properties_type));
                    $q.="`".key($this->properties)."` BLOB NOT NULL ,\n";
                  }
                  
                  elseif (strstr(current($this->properties_type),"text")) {
                    $q.="`".key($this->properties)."` TEXT  NOT NULL ,\n";
                  }
                  else if (strstr(current($this->properties_type),"list")) {
                    $len=explode(":",current($this->properties_type));
                    $options=explode("|",$len[1]);
                    
                    $enum="'".$options[0]."'";
                    for ($j=1,$options_size=sizeof($options);$j<$options_size;$j++) {
                      $enum.=",'".$options[$j]."'";
                    }
                    
                    $q.="`".key($this->properties)."` ENUM ( ".$enum." ) NOT NULL,\n";
                    
                  }
                  
                  else if (strstr(current($this->properties_type),"ref")) {
                    $q.="`".key($this->properties)."` INT,\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"int")) {
                    $q.="`".key($this->properties)."` INT,\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"date")) {
                    $q.="`".key($this->properties)."` INT,\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"datex")) {
                    $q.="`".key($this->properties)."` INT,\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"time")) {
                    $q.="`".key($this->properties)."` INT,\n";
                    
                  }
                  elseif (strstr(current($this->properties_type),"nulo")) {
                    $q.="";
                  }
                  else if (strstr(current($this->properties_type),"money")) {
                    $q.="`".key($this->properties)."` DECIMAL(15,5),\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"float")) {
                    $q.="`".key($this->properties)."` DECIMAL(15,5),\n";
                    
                  }
                  else if (strstr(current($this->properties_type),"boolean")) {
                    $len=explode(":",current($this->properties_type));
                    $q.="`".key($this->properties)."` ENUM('Si','No') ";
                    $q.="DEFAULT '{$len[1]}' NOT NULL,\n";
                    
                  }
                  next($this->properties_type);
                  next($this->properties);
                }
                $q.="INDEX ( `ID` ), PRIMARY KEY ( `ID` )\n)\n";
                echo "<pre>$q</pre>";
                return $q;
              }
            }
            
            function makeTemplate($name) {
              
              $q='
                <!--HEAD-->
                <style  type="text/css">
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                <table width="95%" cellspacing="1" border="1" cellpadding="1" align="center" style="border:solid 1px gray">
                ';
              
              reset($this->properties_desc);
              reset($this->properties);
              $q.="<th>Nº</th>\n";
              for ($i=0;$i<sizeof($this->properties);$i++) {
                $q.="<th><a href=\"<!-- N:navvars -->&sort=".key($this->properties)."\">".current($this->properties_desc)."</a></th>\n";
                next($this->properties_desc);
                next($this->properties);
              }        
              $q.='
                <!--SET-->
                <tr>
                ';
              reset($this->properties_type);
              reset($this->properties);
              $q.='
                <td class="<!-- dynamic_class -->"><!-- D:ID --></td>
                ';
              for ($i=0;$i<sizeof($this->properties);$i++) {
                
                if (current($this->properties_type)=="datex:")
                  $q.='
                    <td class="<!-- dynamic_class -->"><!-- R:'.key($this->properties).' --></td>';
                else if (current($this->properties_type)=="date:")
                  $q.='
                    <td class="<!-- dynamic_class -->"><!-- A:'.key($this->properties).' --></td>';
                else
                  $q.='
                    <td class="<!-- dynamic_class -->"><!-- D:'.key($this->properties).' --></td>';
                
                next($this->properties_type);
                next($this->properties_desc);
                next($this->properties);
              }
              
              $q.='    
                </tr>
                <!--END-->
                </table>
                <br>
                <table width="95%" cellspacing="0" border="0" cellpadding="4" align="center" style="border:solid 1px gray">
                <tr><th align="left">
                <!-- IFPAGER1 --><a id="trfPP" href="<!-- N:prevpage -->">Página anterior</a><!-- FIPAGER1 -->
                </th>
                <th align="center"><!-- D:Pager -->
                </th><th align="right">
                <!-- IFPAGER2 --><a id="trfNP" href="<!-- N:nextpage -->">Página siguiente</a><!-- FIPAGER2 -->
                </th>
                </tr>
                </table>
                <br>
                ';
              return $q;
              
            }
            
            
            function makeEditTemplate($name) {
              
              $q='
                <!--HEAD-->
                <!--SET-->
                <script type="text/javascript" language="JavaScript1.3">
                <!--
                function checkDate(ele) {
                dat=document.getElementById(ele).value;
                b1=dat.search("/");
                dat2=dat.slice(b1+1);
                b2=dat2.search("/")+b1+1;
                dia=parseInt(dat.slice(0,b1));
                mes=parseInt(dat.slice(b1+1,b2));
                an=parseInt(dat.slice(b2+1));
                ok=0;
                if ((dia>31)||(dia<1)||(isNaN(dia)))
                ok=1;
                if ((mes>12)||(mes<1)||(isNaN(mes)))
                ok=1;
                if ((an<2000)||(an>2030)||(isNaN(an)))
                ok=1;
                if (ok==1) {
                alert("Fecha incorrecta");
                dia="01";
                mes="01";
                an="2004";
                document.getElementById(ele).value="";     
                }
                }
                
                function checkMoney(ele) {
                
                money=new String(document.getElementById(ele).value);
                dot=money.indexOf(",");
                if (dot!=-1) {
                money2=money.slice(0,dot)+".";
                money2+=money.slice(dot+1);
                money=money2;
                }
                value=parseFloat(money);
                if (isNaN(value)) {
                alert("Valor monetario incorrecto");
                money=0;
                }
                else
                money=value.toString();
                
                document.getElementById(ele).value=money;     
                }
                
                
                
                -->
                </script>
                <style  type="text/css">
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                
                <table width="95%" cellspacing="1" border="0" cellpadding="1" align="center" style="border:solid 1px gray">
                ';
              
              reset($this->properties_desc);
              reset($this->properties);
              reset($this->properties_type);
              
              for ($i=0;$i<sizeof($this->properties);$i++) {        
                $q.="<tr>\n\t<td>".current($this->properties_desc)."</td>\n";
                if (strstr(current($this->properties_type),"string")) {
                  if (substr(current($this->properties_type),strpos(current($this->properties_type),":")+1)<51) {
                    $q.="\n\t<td><input type=\"text\" name=\"".key($this->properties)."\" maxlength=\"".substr(current($this->properties_type),strpos(current($this->properties_type),":")+1)."\"
                      value=\"<!-- D:".key($this->properties)." -->\" size=\"".substr(current($this->properties_type),strpos(current($this->properties_type),":")+1)."\"/>";
                  }
                  else {
                    $q.="\n\t<td><textarea name=\"".key($this->properties)."\" cols=\"50\"><!-- D:".key($this->properties)." --></textarea>";
                  }
                }
                
                else if (strstr(current($this->properties_type),"date"))
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"10\"
                    size=\"10\" value=\"<!-- A:".key($this->properties)." -->\" onblur=\"javascript:checkDate('".key($this->properties)."')\"/>";
                else if (strstr(current($this->properties_type),"datex"))
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"10\"
                    size=\"10\" value=\"<!-- A:".key($this->properties)." -->\" onblur=\"javascript:checkDate('".key($this->properties)."')\"/>";          
                else if (strstr(current($this->properties_type),"time"))
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"10\"
                    size=\"10\" value=\"<!-- T:".key($this->properties)." -->\" />";
                
                else if (strstr(current($this->properties_type),"int"))
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"11\"
                    size=\"11\" value=\"<!-- D:".key($this->properties)." -->\"/>";
                
                else if (strstr(current($this->properties_type),"list")) {
                  $q.="\n\t<td><select name=\"".key($this->properties)."\">";
                  $options=substr(current($this->properties_type),strpos(current($this->properties_type),":")+1);
                  $ops=explode("|",$options);
                  //dataDump($ops);
                  foreach ($ops as $minikey=>$minival)
                           $q.="\t<option value=\"$minival\" <!-- O:".key($this->properties).$minival." -->>$minival</option>\n";
                  $q.= "</select>";
                }
                else if (strstr(current($this->properties_type),"money")) {
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"15\"
                    size=\"15\" value=\"<!-- S:".key($this->properties)." -->\" onblur=\"javascript:checkMoney('".key($this->properties)."')\"/>";
                }
                else if (strstr(current($this->properties_type),"float")) {
                  $q.="\n\t<td><input type=\"text\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" maxlength=\"15\"
                    size=\"15\" value=\"<!-- F:".key($this->properties)." -->\" onblur=\"javascript:checkMoney('".key($this->properties)."')\"/>";
                }
                else if (strstr(current($this->properties_type),"boolean")) {
                  $q.="\n\t<td><input type=\"checkbox\" id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" <!-- G:".key($this->properties)." -->/>";
                  
                  
                }
                
                //----------------------------------------------------------------------------------
                else if (strstr(current($this->properties_type),"xref")) {
                  $q.="\n\t<td><select id=\"".key($this->properties)."\" name=\"".key($this->properties)."\" multiple>";
                  $xref=explode(":",current($this->properties_type));      
                  $q.="<!-- X:".$xref[1]." -->";
                  $q.= "</select>";
                }
                
                else if (strstr(current($this->properties_type),"ref")) {
                  $q.="\n\t<td><select id=\"".key($this->properties)."\" name=\"".key($this->properties)."\">";
                  $xref=explode(":",current($this->properties_type));      
                  $q.="<!-- X:".$xref[1]." -->";
                  $q.= "</select>";
                }
                //----------------------------------------------------------------------------------
                
                else                       
                  $q.="<td>".current($this->properties);
                $q.="\n\t</td>\n</tr>\n";
                next($this->properties_desc);
                next($this->properties);
                next($this->properties_type);
                
              }        
              
              $q.="
                <tr>
                <td colspan=\"2\" align=\"right\"><!-- D:_boton0 -->&nbsp;&nbsp;<!-- D:_boton1 -->&nbsp;&nbsp;<!-- D:_boton2 -->&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr></table>
                <input type=\"hidden\" name=\"ID\" value=\"<!-- D:ID -->\"/>
                <!--END-->
                ";
              return $q;
              
            }
            
            function makeViewTemplate($name) {
              
              $q='
                <!--HEAD-->
                <style  type="text/css">
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                <!--SET-->
                <table width="95%" cellspacing="1" border="0" cellpadding="1" align="center" style="border:solid 1px gray">
                ';
              
              reset($this->properties_desc);
              reset($this->properties);
              reset($this->properties_type);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                $q.="<tr>\n\t<td>".current($this->properties_desc)."</td>\n";
                if (strpos(current($this->properties_type),"date")!==False)
                  $q.="\n\t<td><!-- A:".key($this->properties)." -->";
                if (strpos(current($this->properties_type),"datex")!==False)
                  $q.="\n\t<td><!-- R:".key($this->properties)." -->";
                
                else if (strpos(current($this->properties_type),"money")!==False)
                  $q.="\n\t<td><!-- S:".key($this->properties)." -->";
                else if (strpos(current($this->properties_type),"time")!==False)
                  $q.="\n\t<td><!-- T:".key($this->properties)." -->";
                else
                  $q.="\n\t<td><!-- D:".key($this->properties)." -->";
                
                $q.="\n\t</td>\n</tr>\n";
                next($this->properties_desc);
                next($this->properties);
                next($this->properties_type);
                
              }        
              
              $q.="</table>
                <!--END-->
                ";
              return $q;
              
            }
            
            function metacompile() {
              
              return True;
              
            }
            
            /*********************
            Function allID
            *********************/
            
            function allID() {
              
              global $prefix,$SYS;
              
              $q="SELECT ID from {$prefix}_".$this->name." WHERE ID>1";
              
              $bdres=_query($q);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              $All=array();
              for ($i=0,$rows_affected=_affected_rows();$i<$rows_affected;$i++) {
                $rawres=_fetch_array($bdres);
                //$p=array_slice($rawres,1);
                $All[$i]=$rawres["ID"];
              }
              
              return $All;
              
              
            }
            
            /*********************
            Funcion selectA
            
            Sintaxis  selectD($q)
            $q    Condiciones SQL
            Descripcion:
            Comportamiento similar al select normal, pero
            realiza una query sin paginacion y devuelve
            los datos como un array.
            
            Es más rápida y consume menos memoria que el
            select normal, pero carece de muchas funcionalidades.
            Se usaría para listados grandes o informes
            
            *********************/
            
            function selectA($q="") {
              
              global $prefix,$SYS;
              
              if (!empty($q))
                $qry="SELECT SQL_CALC_FOUND_ROWS * from {$prefix}_".$this->name." WHERE ID>1 AND $q";
              else
                $qry="SELECT SQL_CALC_FOUND_ROWS * from {$prefix}_".$this->name." WHERE ID>1";
              
              $bdres=_query($qry);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              $All=array();
              for ($i=0,$rows_affected=_affected_rows();$i<$rows_affected;$i++) {
                $rawres=_fetch_array($bdres);
                //$p=array_slice($rawres,1);
                $All[$rawres["ID"]]=$rawres;
              }
              $this->nRes=sizeof($All);
              $bdres=_query("SELECT FOUND_ROWS()");
              $aux=_fetch_array($bdres);
              $this->totalPages=$aux["FOUND_ROWS()"];
              
              
              return $All;
              
              
            }
            
            /*********************
            Funcion nextID(ID)
            
            Siguiente elemento en la tabla
            
            
            *********************/
            
            function nextID($ID='') {
              
              global $prefix,$SYS;
              
              if (empty($ID)) {
                $ID=$this->ID;
              }
              
              
              $qry="SELECT ID from {$prefix}_".$this->name." WHERE ID>$ID AND ID>1 ORDER BY ID ASC";
              
              $bdres=_query($qry);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              if ($bdres)
                if ($rawres=_fetch_array($bdres))
                  return $rawres["ID"];
              else
                return $ID;
              
              
            }
            
            /*********************
            Funcion prevID(ID)
            
            Anterior elemento en la tabla
            
            
            *********************/
            
            function prevID($ID='') {
              
              global $prefix,$SYS;
              
              if (empty($ID)) {
                $ID=$this->ID;
              }
              
              
              $qry="SELECT ID from {$prefix}_".$this->name." WHERE ID<$ID AND ID>1 ORDER BY ID DESC";
              
              $bdres=_query($qry);
              /*$rawres=fetch_array($bdres);
              $this->ID=$rawres["ID"];
              $this->properties=array_slice($rawres,1);*/
              if ($bdres)
                if ($rawres=_fetch_array($bdres))
                  return $rawres["ID"];
              else
                return $ID;
              
              
            }
            
            function buildMultiquery($tokens) {
              
              $fields=($this->properties);
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
            
            /*********************
            Funcion get_external_reference($field)
            
            Siguiente elemento en la tabla
            
            
            *********************/
            
            function get_external_reference($field) {
              
              $data=explode(":",$this->properties_type["$field"]);
              
              $tclass=$data[1];
              $tfield=$data[2];
              debug("{$this->properties_type["$field"]} $tclass|$field|$tfield","red");
              return "xref#$tclass|$field|$tfield";
            }
            
            /*
            
            FUNCION   get_external_method
            
            $field
            $method
            
            */
            
            
            function get_external_method($field,$method) {
              
              $data=explode(":",$this->properties_type["$field"]);
              
              $tclass=$data[1];
              $tfield=$data[2];
              debug("{$this->properties_type["$field"]} $tclass|$field|$tfield","red");
              return "fref#$tclass|$field|$method";
            }
            
            
            /*
            
            FUNCION resolve
            
            Sintaxis  resolve($field)
            $field    Etiqueta del atributo de tipo ref ó xref
            Descripción:
            Obtiene las properties_type del atributo de la siguiente forma:
            
            
            
            */
            
            function resolve($field) {
              $xref=array();
              $xref=explode(":",$this->properties_type[$field]);
              $obj=newObject($xref[1],$this->$field);
              if (method_exists($obj,$xref[2])) 
                return $obj->$xref[2]();
              else
                return $obj->$xref[2];
              
            }  
            
            
            /*
            
            FUNCION get_references
            
            Sintaxis  get_references($field)
            $field    Etiqueta del atributo de tipo ref ó xref
            Descripción:
            Obtiene las properties_type del atributo de la siguiente forma:
            
            xref[0]=ref ó xref[0]=xref
            xref[1]=entidad donde se accederá para obtener las referencias
            xref[2]=atributo de la entidad anterior para la visualización
            de las referencias
            xref[3]=atributo opcional de la entidad anterior que permite
            establecer una condición
            
            Y devuelve un array con las referencias obtenidas como valores visualizadas según
            xref[2] que cumplan la condición opcional
            
            */
            
            function get_references($field) {
              $xref=array();
              $xref=explode(":",$this->properties_type[$field]);
              $obj=newObject($xref[1]);
              
              $cond=(!empty($xref[3]))?"{$xref[3]}={$this->ID}":"";
              
              return $obj->listAll($xref[2],True,$cond);
            }  
            
            /*
            
            FUNCION get_xref_selected_options
            
            Sintaxis  get_xref_selected_options($field)
            $field    Etiqueta del atributo de tipo xref
            Descripción:
            Obtiene las properties_type del atributo de la siguiente forma:
            
            xref[0]=xref
            xref[1]=entidad donde se accederá para obtener las referencias
            seleccionadas
            
            Accede a la tabla de referencias cruzadas $table_name creada
            durante el save() y devuelve un array con las referencias
            seleccionadas como claves
            
            */
            
            function get_xref_selected_options($field) {  
              global $prefix;
              $xref=array();
              $selected = array();
              $xref=explode(":",$this->properties_type[$field]);
              $table_name=$this->name."_".$xref[1];
              $field2=$this->name."_id";
              $field3=$xref[1]."_id";
              
              $q="SELECT `$field3` FROM `{$prefix}_".$table_name."` WHERE `$field2`=$this->ID";
              $bdres=_query($q);
              
              $af_rows=_affected_rows();
              if($af_rows) {
                for($i=0;$i<$af_rows;$i++) {
                  $rawres=_fetch_array($bdres);
                  $selected[$rawres[$field3]] = "";
                }
              }
              
              return $selected;
            }
            
            /*
            
            FUNCION createFormFromEntity
            
            Sintaxis  createFormFromEntity()  
            Descripción:
            1) Transforma el XML (.def) de la entidad asociada al objeto
            que llama al método en un array
            
            2) Se modifica convenientemente el array para poder generar a
            partir de él un formulario
            
            3) Una vez modificado el array se transforma a XML y tanto el
            XML como el array resultante se almacenan como atributos en
            el objeto que llama al método
            
            */
            
            function createFormFromEntity() {
              global $SYS;
              
              if (file_exists($SYS["DOCROOT"].$SYS["DATADEFPATH"].$this->name.".def"))
                $def=$SYS["DOCROOT"].$SYS["DATADEFPATH"].$this->name.".def";
              
              else if (e_file_exists("local/Class/{$this->name}.def"))
                $def="local/Class/{$this->name}.def";
              
              $file=file($def,FILE_USE_INCLUDE_PATH);
              $xml=implode("\n",$file);
              
              $xmlObj=new XmlToArray($xml);
              $arrayData=$xmlObj->createArray();
              
              $arrayForm=array();
              $arrayForm["form"]["entity"]=$this->name;
              $arrayForm["form"]["name"]=$this->name;
              $arrayForm["form"]["id"]=$this->name;
              $arrayForm["form"]["action"]="index.php";
              $arrayForm["form"]["method"]="post";
              $arrayForm["form"]["target"]="_self";
              
              foreach($arrayData as $k1=>$v1)
                      foreach($v1 as $k2=>$v2) {
                        switch($v2["type"]) {
                          case "string":
                          case "text":
                          case "longtext":
                            
                            if($v2["option"]<130) {
                              $arrayForm["form"]["elements"][$k2]["type"]="text";
                              $arrayForm["form"]["elements"][$k2]["attributes"]["maxlength"]=$v2["option"];
                            } else {
                              $arrayForm["form"]["elements"][$k2]["type"]="textarea";
                              $arrayForm["form"]["elements"][$k2]["attributes"]["cols"]=$v2["option"]/2;
                              $arrayForm["form"]["elements"][$k2]["attributes"]["rows"]=$v2["option"]/10;
                            }
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "xref":
                            $arrayForm["form"]["elements"][$k2]["type"]="select";
                            $arrayForm["form"]["elements"][$k2]["attributes"]["multiple"]="true";
                            $arrayForm["form"]["elements"][$k2]["datasource"]="get_references";
                            $arrayForm["form"]["elements"][$k2]["dataselections"]="get_xref_selected_options";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "ref":
                            $arrayForm["form"]["elements"][$k2]["type"]="select";
                            $arrayForm["form"]["elements"][$k2]["datasource"]="get_references";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "date":
                            $arrayForm["form"]["elements"][$k2]["type"]="text";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "int":
                            $arrayForm["form"]["elements"][$k2]["type"]="text";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "float":
                            $arrayForm["form"]["elements"][$k2]["type"]="text";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "boolean":
                            $arrayForm["form"]["elements"][$k2]["type"]="checkbox";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "money":
                            $arrayForm["form"]["elements"][$k2]["type"]="text";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                            
                          case "password":
                            $arrayForm["form"]["elements"][$k2]["type"]="text";
                            $arrayForm["form"]["elements"][$k2]["format"]="password";
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          case "list":
                            $arrayForm["form"]["elements"][$k2]["type"]="select";
                            $arrayForm["form"]["elements"][$k2]["format"]=$v2["type"];
                            $arrayForm["form"]["elements"][$k2]["label"]=$v2["value"];
                            break;
                            
                          default:
                            break;
                                           }
                      }
              
              $this->_arrayToXml($arrayForm,$xml_mod);
              $this->_xml=$xml_mod;
              
              $this->_arrayForm=$arrayForm;
            }
            
            /*
            
            FUNCION saveFormXml
            
            Sintaxis  saveFormXml()  
            Descripción:
            Escribe en un fichero .xml el contenido del XML que se encuentra
            como atributo dentro del objeto que llama al método y lo almacena
            en $FORMDIR
            
            */
            
            function saveFormXml() {
              global $SYS;
              $FORMDIR=$SYS["DOCROOT"]."../Apps/".$SYS["PROJECT"]."/local/Forms/";
              $file = fopen($FORMDIR."{$this->name}_form.xml","w+");
              if($file)
                fputs ($file,$this->_xml);
              fclose($file);
            }
            
            /*
            
            FUNCION saveFormTemplate
            
            Sintaxis  saveFormTemplate()  
            Descripción:
            Escribe en un fichero .html el contenido de la plantilla que se
            encuentra como atributo dentro del objeto que llama al método
            
            */
            
            function saveFormTemplate() {
              $file = fopen("{$this->name}_form.html","w+");
              if($file)
                fputs ($file,$this->_template);
              fclose($file);
            }
            
            /*
            
            FUNCION loadFormXml
            
            Sintaxis  loadFormXml($xml)
            $xml    Cadena de texto con el XML a cargar
            Descripción:
            Almacena el XML pasado a la función como atributo dentro del objeto
            que llama al método, transforma dicho XML en array y luego lo almacena
            también como atributo dentro del objeto. Este array se fusiona con lo que tuviera el objeto instanciado dando preferencia a lo que tuviera
            
            */
            
            function loadFormXml($xml) {
              $this->_xml=$xml;
              
              $xmlObj=new XmlToArray($xml);
              $arrayForm=$xmlObj->createArray();
              $this->_arrayForm=(is_array($this->_arrayForm))?$this->_arrayForm:array();
              $this->_arrayForm=array_merge_recursive($arrayForm,$this->_arrayForm);
            }
            
            /*
            
            FUNCION formMakeEditTemplate
            
            Sintaxis  formMakeEditTemplate($arrayForm,$name)
            $arrayForm  Array del formulario necesario para construir
            la plantilla
            $name    Nombre para las clases dinámicas
            Descripción:
            Devuelve una plantilla de creación/edición a partir del array
            del formulario pasado como argumento
            
            Esta función es llamada desde renderEditForm
            
            */
            
            function formMakeEditTemplate($arrayForm,$name) {
              $q='
                <!--HEAD-->
                <!--SET-->
                <style  type="text/css">
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                
                <table width="95%" cellspacing="1" border="0" cellpadding="1" align="center" style="border:solid 1px gray">
                ';
              
              foreach($arrayForm["form"]["elements"] as $k1=>$v1) {
                if($v1["format"] == "date")
                  $q.="<tr>\n\t<td>".$v1["label"]." (dd/mm/aaaa)</td>\n";
                else
                  $q.="<tr>\n\t<td>".$v1["label"]."</td>\n";
                
                switch($v1["type"]) {
                  case "text":
                    switch($v1["format"]) {
                      case "string":
                        $q.="\n\t<td>{$v1["custom"]}<input type=\"text\" name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"])) {
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        }
                        $q.="value=\"<!-- D:".$k1." -->\"/>";
                        break;
                        
                      case "date":
                        $q.="\n\t<td>{$v1["custom"]}<input type=\"text\" name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"])) {
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        }
                        $q.="value=\"<!-- A:".$k1." -->\"/>";
                        break;
                        
                      case "int":
                        $q.="\n\t<td>{$v1["custom"]}<input type=\"text\" name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"])) {
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        }
                        $q.="value=\"<!-- D:".$k1." -->\"/>";
                        break;
                        
                      case "float":
                        $q.="\n\t<td>{$v1["custom"]}<input type=\"text\" name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"])) {
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        }
                        $q.="value=\"<!-- F:".$k1." -->\"/>";
                        break;
                        
                      case "money":
                        $q.="\n\t<td>{$v1["custom"]}<input type=\"text\" name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"])) {
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        }
                        $q.="value=\"<!-- E:".$k1." -->\">";
                        break;
                        
                      default:
                        break;
                                         }
                    break;
                    
                  case "textarea":
                    $q.="\n\t<td>{$v1["custom"]}<textarea name=\"".$k1."\" id=\"".$k1."\" ";
                    foreach($v1["attributes"] as $k3=>$v3)
                            $q.="$k3=\"$v3\" ";
                    $q.="><!-- D:".$k1." --></textarea>";
                    break;
                    
                  case "select":
                    switch($v1["format"]) {
                      case "list":
                        $q.="\n\t<td>{$v1["custom"]}<select name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"]))
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        $q.=">";
                        $options=substr($this->properties_type[$k1],strpos($this->properties_type[$k1],":")+1);
                        $ops=explode("|",$options);
                        foreach($ops as $minikey=>$minival)
                                $q.="\t<option value=\"$minival\" <!-- O:".$k1.$minival." -->>$minival</option>\n";
                        $q.="</select>";
                        
                        break;
                        
                      default:
                        if(isset($v1["attributes"]["multiple"])) {
                          $q.="\n\t<td>{$v1["custom"]}<select name=\"".$k1."[]\" id=\"".$k1."\" ";
                          
                        } else
                          $q.="\n\t<td>{$v1["custom"]}<select name=\"".$k1."\" id=\"".$k1."\" ";
                        if(isset($v1["attributes"]))
                          foreach($v1["attributes"] as $k3=>$v3)
                                  $q.="$k3=\"$v3\" ";
                        $q.="><!-- X:".$k1." --></select>";
                        break;
                                         }
                    break;
                    
                  case "checkbox":
                    $q.="\n\t<td>{$v1["custom"]}<input type=\"checkbox\" name=\"".$k1."\" id=\"".$k1."\" ";
                    if(isset($v1["attributes"])) {
                      foreach($v1["attributes"] as $k3=>$v3)
                              $q.="$k3=\"$v3\" ";
                    }
                    $q.="<!-- G:".$k1." -->>";
                    break;
                    
                  case "hidden":
                    $q.="\n\t<td>{$v1["custom"]}<input type=\"hidden\" name=\"".$k1."\" id=\"".$k1."\" ";
                    if(isset($v1["attributes"])) {
                      foreach($v1["attributes"] as $k3=>$v3)
                              $q.="$k3=\"$v3\" ";
                    }
                    $q.="value=\"<!-- D:".$k1." -->\">";
                    break;
                    
                  case "image":
                    $q.="\n\t<td>{$v1["custom"]}<img name=\"".$k1."\" name=\"".$k1."\" id=\"".$k1."\" ";
                    if(isset($v1["attributes"])) {
                      foreach($v1["attributes"] as $k3=>$v3)
                              $q.="$k3=\"$v3\" ";
                    }
                    $q.=">";
                    break;
                    
                  default:
                    break;
                                   }
                $q.="\n\t</td>\n</tr>\n";
              }
              $q.="
                <tr>
                <td colspan=\"2\" align=\"right\"><!-- D:_boton0 -->&nbsp;&nbsp;<!-- D:_boton1 -->&nbsp;&nbsp;<!-- D:_boton2 -->&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr></table>
                <input type=\"hidden\" name=\"ID\" id=\"ID\" value=\"<!-- D:ID -->\"/>
                <input type=\"hidden\" name=\"CL\" id=\"CL\" value=\"<!-- D:name -->\"/>
                <!--END-->
                ";
              return $q;
            }
            
            /*
            
            FUNCION formMakeListTemplate
            
            Sintaxis  formMakeListTemplate($arrayForm,$name)
            $arrayForm  Array del formulario necesario para construir
            la plantilla
            $name    Nombre para las clases dinámicas
            Descripción:
            Devuelve una plantilla de listado a partir del array
            del formulario pasado como argumento
            
            Esta función es llamada desde renderListForm
            
            */
            
            function formMakeListTemplate($arrayForm,$name,$buttons=true,$hasInvisibleChecks=false) {
              $reserved=array("S_UserID_CB","S_UserID_MB","S_Date_C","S_Date_M");
              global $SYS;
              
              
              $q.='
                </script>
                <!--HEAD-->
                '.$inCk.'
                <style  type="text/css">
                #'.$name.' td {background-color:white}      
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                <table width="100%" cellspacing="1" border="0" cellpadding="1" align="center" style="border:solid 1px gray;background-color:#DCDCDC" id="'.$name.'">
                ';
              $q.="<tr>";
              reset($this->properties);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                if (in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                }
                $q.="<th name=\"".key($this->properties)."\"></th>\n";
                
                next($this->properties);
              }
              $q.="</tr>";
              
              $q.="<tr>";
              reset($this->properties);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                if (in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                }
                else if(key($this->properties)=="ID")
                  $q.="<th><a href=\"<!-- N:navvars -->&sort=".key($this->properties)."\">Nº</a></th>\n";
                else
                  $q.="<th name=\"".key($this->properties)."\"><a title=\"Ocultar columna\" style=\"text-decoration: none; color: red;\" href=\"javascript:hide('".key($this->properties)."')\">x</a> <a href=\"<!-- N:navvars -->&sort=".key($this->properties)."\">".$this->properties_desc[key($this->properties)]."</a></th>\n";
                
                next($this->properties);
              }
              $q.="</tr>";
              
              if ($hasInvisibleChecks)
                $inCk='onclick="ICSelectMe(\'ck_'.key($this->properties).'<!-- D:ID -->\',this,true)" id="ICRow"';
              else
                $inCk="";
              $q.="
                <!--SET-->
                <tr $inCk>
                ";
              reset($this->properties);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                if (in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                }
                else if(key($this->properties)=="ID")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- D:ID --></td>';
                else if ($this->properties_type[key($this->properties)]=="datex:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- R:'.key($this->properties).' --></td>';
                else if ($this->properties_type[key($this->properties)]=="date:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- A:'.key($this->properties).' --></td>';
                else if ($this->properties_type[key($this->properties)]=="money:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- E:'.key($this->properties).' --></td>';
                else
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- D:'.key($this->properties).' --></td>';
                
                next($this->properties);
              }
              
              if ($hasInvisibleChecks) {
                $q.='
                  <td class="<!-- dynamic_class -->">
                  <input type="radio" name="InvCk" id="ck_'.key($this->properties).'<!-- D:ID -->" value="<!-- D:ID -->">
                  </td> 
                  ';    }
              
              if ($buttons) {
                $q.='
                  <td class="<!-- dynamic_class -->">
                  <div class="sexybutton">
                  <a class="sexybutton"  target="footer"  href="<!-- D:_delbutton -->?CL=<!-- D:name -->&amp;ID=<!-- D:ID -->" onclick="return confirm(\'¿Está usted seguro?\')">Borrar</a>
                  </div>
                  <div class="sexybutton">
                  <a href="<!-- D:_editbutton -->?ID=<!-- D:ID -->&amp;CL=<!-- D:name -->" class="sexybutton">Editar</a>
                  </div>
                  </td>
                  ';    }
              
              $q.='    
                </tr>
                <!--END-->
                </table>
                <br>
                <table width="100%" cellspacing="0" border="0" cellpadding="4" align="center" style="border:solid 1px gray;background-color:#DCDCDC">
                <tr><th align="left" width="33%"><!-- IFPAGER1 --><a id="trfPP" href="<!-- N:prevpage -->">Página anterior</a><!-- FIPAGER1 -->
                </th>
                <th align="center"  width="33%"><!-- D:Pager -->
                </th><th align="right"  width="33%">
                <!-- IFPAGER2 --><a id="trfNP"  href="<!-- N:nextpage -->">Página siguiente</a><!-- FIPAGER2 -->
                </th>
                </tr>
                </table>
                <br>
                ';
              return $q;
            }
            
            /*
            
            FUNCION makePrintPreviewTemplate
            
            Sintaxis  makePrintPreviewTemplate($name)
            $name    Nombre para las clases dinámicas
            Descripción:
            Devuelve una plantilla para la vista preliminar
            
            Esta función es llamada desde renderPrintPreview
            
            */
            
            function makePrintPreviewTemplate($name) {
              $reserved=array("S_UserID_CB","S_UserID_MB","S_Date_C","S_Date_M");
              
              $q='
                <!--HEAD-->
                <style  type="text/css">
                #'.$name.' td {background-color:white}      
                td.dynamic_class_'.$name.'0 {text-align:center;vertical-align:top;background-color:#EEEEF6}
                td.dynamic_class_'.$name.'1 {text-align:center;vertical-align:top;background-color:white}
                td.dynamic_class_'.$name.'2 {text-align:center;vertical-align:top;background-color:#F7F7F7}
                td.dynamic_class_'.$name.'3 {text-align:center;vertical-align:top;background-color:white}
                </style>
                <table width="100%" cellspacing="1" border="0" cellpadding="1" align="center" style="border:solid 1px gray;background-color:#DCDCDC" id="'.$name.'">
                ';
              reset($this->properties);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                if (in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                  
                } else {
                  $th=$this->properties_desc[key($this->properties)];
                  $q.="<th name=\"".key($this->properties)."\">$th</th>\n";
                }
                
                next($this->properties);
              }
              
              $q.='
                <!--SET-->
                <tr>
                ';
              reset($this->properties);
              for ($i=0;$i<sizeof($this->properties);$i++) {
                if (in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                }
                else if ($this->properties_type[key($this->properties)]=="datex:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- R:'.key($this->properties).' --></td>';
                else if ($this->properties_type[key($this->properties)]=="date:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- A:'.key($this->properties).' --></td>';
                else if ($this->properties_type[key($this->properties)]=="money:")
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- E:'.key($this->properties).' --></td>';
                else
                  $q.='
                    <td name="'.key($this->properties).'" class="<!-- dynamic_class -->"><!-- D:'.key($this->properties).' --></td>';
                
                next($this->properties);
              }
              
              $q.='
                </tr>
                <!--END-->
                ';
              
              return $q;
            }
            
            /*
            
            FUNCION renderPrintPreview
            
            Sintaxis  renderPrintPreview($criterion)
            $criterion  Criterio opcional a la hora de mostrar los
            resultados del listado
            Descripción:
            Renderiza el listado para la vista preliminar a partir de
            la plantilla generada por el método makePrintPreviewTemplate
            
            Antes del renderizado se encarga de resolver todas las posibles
            referencias
            
            */
            
            function renderPrintPreview($criterion) {
              global $offset,$sort;
              
              $arrayForm=$this->_arrayForm;
              $this->_template=$this->makePrintPreviewTemplate($this->name);
              
              $references=array();
              foreach($arrayForm["form"]["elements"] as $k1=>$v1) {
                if($v1["type"]=="select") {
                  if($v1["attributes"]["multiple"]) {
                    // PENDIENTE
                  } else {
                    if($v1["datasource"]) {
                      if($v1["datasource"]=="get_references")
                        $references[$k1]=$this->get_external_reference($k1);
                      else
                        $references[$k1]=$this->$v1["datasource"]($k1);
                    }
                  }
                } else if($v1["datasource"])
                  $references[$k1]=$this->$v1["datasource"]($k1);
              }
              
              $this->searchResults=($criterion)?$this->select($criterion,$offset,$sort):$this->selectAll($offset,$sort);
              
              listList($this,$references,$this->_template);
            }
            
            /*
            
            FUNCION renderEditForm
            
            Sintaxis  renderEditForm($no_merge)
            $no_merge  Si esta variable se encuentra definida y en $FORMDIR
            existe el XML del formulario, no se efectúa la "mezcla"
            entre el _arrayForm del objeto y del obtenido a través
            del XML anterior
            
            Descripción:    
            Renderiza el formulario de creación/edición a partir de la plantilla
            que se crea en la llamada al método formMakeEditTemplate
            
            Si en $FORMDIR existe el XML del formulario, mediante loadFormXml lo
            almacena en _xml, lo transforma en array y lo almacena en _arrayForm
            Si no existe en $FORMDIR el XML del formulario, lo toma directamente
            del atributo _arrayForm
            
            Antes del renderizado se encarga de resolver todas las posibles
            referencias
            
            */
            
            function renderEditForm($no_merge) {
              global $SYS;
              $FORMDIR=$SYS["DOCROOT"]."../Apps/".$SYS["PROJECT"]."/local/Forms/";
              
              if(method_exists($this,"hook_renderEditForm"))
                $this->hook_renderEditForm();
              
              if(empty($this->_template)) {
                if(file_exists($FORMDIR."{$this->name}_form.xml")) {
                  $def=$FORMDIR."{$this->name}_form.xml";
                  $file=file($def,FILE_USE_INCLUDE_PATH);
                  $xml=implode("\n",$file);
                  
                  if(!isset($no_merge))
                    $this->loadFormXml($xml);
                }
                $arrayForm=$this->_arrayForm;
                
                $this->_boton0=(isset($this->_boton0))?$this->_boton0:gfxBotonAction("Guardar","getElementById('".$arrayForm["form"]["id"]."').submit()",True);
                
                $template=$this->formMakeEditTemplate($arrayForm,$this->name);
                $this->_template=(!empty($this->_template))?$this->_template:$template;
                
                $extras="name=\"{$arrayForm["form"]["name"]}\"";
                
                formAction($arrayForm["form"]["action"],$arrayForm["form"]["target"],$arrayForm["form"]["id"],$extras);
                
                $references=array();
                foreach($arrayForm["form"]["elements"] as $k1=>$v1) {
                  if($v1["type"]=="select" && isset($v1["datasource"])) {
                    if($v1["attributes"]["multiple"]) {
                      $this->$k1=$this->get_xref_selected_options($k1);
                    }
                    $references[$k1]=$this->$v1["datasource"]($k1);
                  }
                }
                plantHTML($this,$this->_template,$references);
                formClose();
              } else {
                //PENDIENTE--------------------------------------------------------------
                plantHTML($this,$this->_template);
                formClose();
                //-----------------------------------------------------------------------
              }
            }
            
            /*
            
            FUNCION renderListForm
            
            Sintaxis  renderListForm($criterion)
            $criterion  Criterio opcional a la hora de mostrar los
            resultados del listado
            Descripción:
            Renderiza el listado a partir de la plantilla almacenada como
            atributo en el objeto que llama al método
            
            Antes del renderizado se encarga de resolver todas las posibles
            referencias
            
            */
            
            function renderListForm($criterion="",$hasButtons=true,$hasInvisibleChecks=false,$obj_method='') {
              global $offset,$sort;
              if (!$obj_method)
                $obj_method=array();
              $this->_delbutton=(isset($this->_delbutton))?$this->_delbutton:"action_delete.php";
              $this->_editbutton=(isset($this->_editbutton))?$this->_editbutton:"new_element.php";
              
              if(empty($this->_template)) {
                $arrayForm=$this->_arrayForm;
                
                $template=$this->formMakeListTemplate($arrayForm,$this->name,$hasButtons,$hasInvisibleChecks);
                $this->_template=$template;
                $references=array();
                foreach($arrayForm["form"]["elements"] as $k1=>$v1) {
                  if($v1["type"]=="select") {
                    if($v1["attributes"]["multiple"]) {
                      // PENDIENTE
                    } else {
                      if($v1["datasource"]) {
                        if($v1["datasource"]=="get_references")
                          $references[$k1]=$this->get_external_reference($k1);
                        else
                          $references[$k1]=$this->$v1["datasource"]($k1);
                      }
                    }
                  } else if($v1["datasource"])
                    $references[$k1]=$this->$v1["datasource"]($k1);
                }
                
                $this->searchResults=($criterion)?$this->select($criterion,$offset,$sort):$this->selectAll($offset,$sort);
                
                listList($this,$references,$this->_template,$navigation_vars="",$parset=1,$plParseTemplateFunction="plParseTemplate",$obj_method);
              } else {
                //PENDIENTE--------------------------------------------------------------
                listList($this,"",$this->_template,$navigation_vars="",$parset=1,$plParseTemplateFunction="plParseTemplate",$obj_method);
                //-----------------------------------------------------------------------
              }
            }
            
            /*
            
            FUNCION _arrayToXml
            
            Sintaxis  _arrayToXml($arrayData,&$xml)
            $arrayData  Array a transformar en XML
            &$xml    Cadena donde se almacenará el XML resultante
            Descripción:
            Función recursiva que construye un XML a partir del array pasado
            como primer argumento y lo almacena en una cadena pasada como
            referencia
            
            */
            
            function _arrayToXml($arrayData,&$xml,$level=0,$first=true) {
              if($first)
                $xml.="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
              
              foreach($arrayData as $k=>$v) {
                for($i=0;$i<$level;$i++)
                    $xml.="\t";
                $xml.="<$k>";
                if(is_array($v)) {
                  $level++;
                  $xml.="\n";
                  $this->_arrayToXml($v,$xml,$level,false);
                  $level--;
                  for($i=0;$i<$level;$i++)
                      $xml.="\t";
                  $xml.="</$k>\n";
                } else
                  $xml.="$v</$k>\n";
              }
            }
            
            /*
            
            FUNCION deleteElement
            
            Sintaxis  deleteElement($keyElement)
            $keyElement  Clave del elemento a borrar
            Descripción:
            Elimina el elemento identificado por $keyElement dentro de la clave
            form elements de array del formulario que se encuentra dentro del
            objeto que llama al método
            
            Transforma el array modificado en XML y lo almacena como atributo
            dentro del objeto
            
            */
            
            function deleteElement($keyElement) {
              unset($this->_arrayForm["form"]["elements"][$keyElement]);
              $this->_arrayToXml($this->_arrayForm,$xml);
              $this->_xml=$xml;
            }
            
            /*
            
            FUNCION insertElement
            
            Sintaxis  insertElement($keyElement,$valueElement)
            $keyElement  Clave del nuevo elemento
            $valueElement  Valor del nuevo elemento
            Descripción:
            Añade un nuevo elemento <$keyElement,$valueElement> dentro de la clave
            form elements del array del formulario que se encuentra como atributo
            dentro del objeto que llama al método
            
            Transforma el array modificado en XML y lo almacena como atributo
            dentro del objeto
            
            */
            
            function insertElement($keyElement,$valueElement) {
              $this->_arrayForm["form"]["elements"][$keyElement]=$valueElement;
              $this->_arrayToXml($this->_arrayForm,$xml);
              $this->_xml=$xml;
            }
            
            /*
            
            FUNCION setFormProperties
            
            Sintaxis  setFormProperties($key,$value)
            $key    Clave de la propiedad
            $value    Valor de la propiedad
            Descripción:
            Añade una nueva propiedad <$key,$value> dentro de la clave form
            del array del formulario que se encuentra como atributo dentro del
            objeto que llama al método
            
            */
            
            function setFormProperties($key,$value) {
              $this->_arrayForm["form"][$key]=$value;
            }  
            
            function hackBooleanList() {
              return array(""=>"--","Si"=>"Si","No"=>"No");
            }
            
            function hackListList($field) {
              $list_type=explode(":",$this->properties_type[$field]);
              $list_values=explode("|",$list_type[1]);
              
              $res=array(""=>"--");
              foreach($list_values as $value)
                      $res[$value]=$value;
              
              return $res;
            }
            
            /*********************
            Function searchByField
            *********************/
            
            
            function eSearch($more="",$class,$field,$addVoidValue=True) {
              $o=newObject("$class");
              
              return ($o->listAll($field,$addVoidValue,$more));
            }
            
            /*
            
            FUNCION nextValue
            
            Sintaxis  nextValue($element,$cond,$sort)
            $element  Nombre de la clase
            $cond    Condición a aplicar sobre los elementos de la clase
            $sort    Atributo de la clase donde se obtendrá el siguiente
            valor
            Descripción:
            Devuelve el último valor + 1 del atributo de la clase especificado
            en $sort. Para ello, se obtienen todos los elementos de $element
            que cumplan la condición $cond, se ordenan por $sort y se devuelve
            el campo $sort + 1
            
            */
            
            function nextValue($element,$cond,$sort) {
              $element_q=newObject($element);
              setLimitRows(15000);
              if($cond == "")
                $element_q->searchResults=$element_q->selectAll($offset,$sort);
              else
                $element_q->searchResults=$element_q->select($cond,$offset,$sort);
              resetLimitRows();
              $last_element=$element_q->searchResults[sizeof($element_q->searchResults)-1];
              
              return $last_element->$sort+1;
            }
            
            /*
            
            FUNCION checkField
            
            Sintaxis  checkField($entity,$field)
            $entity    Nombre de la clase
            $field    Atributo de la clase
            $cond    Condiciones adicionales
            
            Descripción:
            Este método será llamado cuando se realice un save() para controlar
            que no se pueda crear un nuevo objeto de la clase que contenga un valor
            duplicado en el atributo $field
            
            Devolverá -1 en el caso de que exista ya un objeto de la clase con el mismo valor
            en el atributo $field
            
            */
            
            function checkField($entity,$field,$cond) {
              $element_q=newObject($entity);
              
              setLimitRows(15000);
              if(isset($cond))
                $element_q->searchResults=$element_q->select("$cond AND $field={$this->$field}",$offset,$sort);
              else
                $element_q->searchResults=$element_q->select("$field={$this->$field}",$offset,$sort);
              resetLimitRows();
              
              if($this->ID == 1) {
                if($element_q->nRes != 0)
                  $error="";
              } else {
                $element=newObject($entity,$this->ID);
                $previous_value=$element->$field;
                
                if($previous_value != $this->$field && $element_q->nRes != 0)
                  $error="";
              }
              if(isset($error)) {
                $this->ERROR="&iexcl;El campo &lt;".$this->properties_desc[$field]."&gt; no puede repetirse!";
                $this->ERRORFIELD=$field;
                $_SESSION["ERRORFIELD"]=$field;
                return -1;
              }
            }
            
            /*
            
            FUNCION disabledFields
            
            Sintaxis  disabledFields()
            
            Descripción:
            Desactiva todos los campos del formulario salvo los especificados en
            $reserved
            Para ello, se modifica el arrayForm de tal forma que para cada atributo
            de la clase salvo los especificados en $reserved, se añade la propiedad
            disabled=true
            
            */
            
            function disabledFields() {
              $reserved=array("ID","S_UserID_CB","S_UserID_MB","S_Date_C","S_Date_M");
              
              reset($this->properties);
              for($i=0;$i<sizeof($this->properties);$i++) {
                if(in_array(key($this->properties),$reserved)) {
                  next($this->properties);
                  continue;
                }
                $this->_arrayForm["form"]["elements"][key($this->properties)]["attributes"]["disabled"]="true";
                
                next($this->properties);
              }
            }
          }
            
            
            
            
            
            
            
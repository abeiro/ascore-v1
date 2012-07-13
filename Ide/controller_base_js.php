<?php
  
  require("coreg2.php");
  
  function jsCodePush($code) {
    global $SYS;
    if (empty($GLOBALS["jscode"])) {
      $GLOBALS["jscode"]="<script src=\"{$SYS["ROOT"]}/helpers.js\"  type='text/javascript'></script>";
      $GLOBALS["jscode"].="<script type='text/javascript'>\n";
      //$GLOBALS["jscode"].="\ndebugger;\n";
      
    }
    $GLOBALS["jscode"].="$code";
    
  }
  
  function jsCodeFlush() {
    
    echo $GLOBALS["jscode"];
    echo "</script>";
  }
  function toOneLine($data) {
    $codeddata=base64_encode($data);
    return str_replace("\n","\\\n",$codeddata);
  }
  class jsO {
    
    
    function __construct($id,$root='',$existing=false) {
      if (!$existing) {
        $this->instance=time();
        $this->id="{$id}_{$this->instance}";
        if (!$root) 
          jsCodePush("{$this->id}=document.getElementById('$id');");
        else
          jsCodePush("{$this->id}=$root.document.getElementById('$id');");
        $this->jsO=new stdClass;
        $this->__methods=array();
      } else {
        if ($root)
          $this->id="$root.$id";
        else
          $this->id="$id";
        $this->jsO=new stdClass;
        $this->__methods=array();
      }
    }
    function setMethod($name,$parms) {
      foreach($parms as $key=>$value) {
              if (is_numeric($value))
                $args[]="$value";
      	      else if (!is_numeric($key)) {
		if ($value=="literal")
			$args[]="$key";
		else if ($value=="string")
			 $args[]="'$key'";
		}
	      else
	        $args[]="base64_decode(\"".toOneLine($value)."\")";
      }
      $this->__methods["$name"]=$args;
    }
    
    function jsMapO() {
      
      foreach (get_object_vars($this->jsO) as $k=>$v)
               if (is_numeric($v))
                 jsCodePush("{$this->id}.$k=$v;");
      else if (is_array($v)) 
        foreach ($v as $kk=>$vv)
                 jsCodePush("{$this->id}.$k.$kk=\"".addcslashes($vv,"\"")."\";");       
      
      else        
        jsCodePush("{$this->id}.$k=\"".addcslashes($v,"\"")."\";");
    }
    function jsMapM() {
      
      foreach ($this->__methods as $k=>$v)
               jsCodePush("{$this->id}.$k(".implode(",",$v).");");
    }
    
  }
    ?>


<?php

class wListBox extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $dataModel = array();
    var $moreHTMLproperties = "";
    var $readonly = false;

    function __construct($name=null, &$parent, $addPrefix=true) {
        parent::__construct($name, $parent);
        $this->name = $name;
        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";
    }

    function render() {
        global $SYS;
        parent::render();
        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        //value='{$this->value}'
        if (!$this->readonly) {
            echo "<select {$this->moreHTMLproperties}  name='{$this->name}' $eventCode id='{$this->id}'  style='{$this->cssStyle}'>\n";
            foreach ($this->dataModel as $k => $v) {
                $addSel = ($this->value == $k) ? "selected" : "";
                echo "\t<option value='$k' label='$v' $addSel>$v</option>\n";
            }
            echo "</select>\n";
        } else {
            echo "<select {$this->moreHTMLproperties}  disabled   id='faked_{$this->id}'  style='{$this->cssStyle}'>\n";
            foreach ($this->dataModel as $k => $v) {
				if (is_array($this->value))
					$addSel = (in_array($this->value,$k)) ? "selected" : "";
                else
					$addSel = ($this->value == $k) ? "selected" : "";

                echo "\t<option value='$k' label='$v' $addSel>$v</option>\n";
            }
            echo "</select>\n";

            echo $this->dataModel["$this->value"] . " <input type='hidden' id='{$this->id}' name='{$this->name}' style='background-color:gray' onchange='\$(\"faked_{$this->id}\").value=this.value'  value='{$this->value}'>";
        }
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "200px");
        $this->setCSS("min-height", "14px");
    }

    function setDataModel($data) {
        $this->dataModel = $data;
    }

	function setSelectedValue($i) {
		$this->value = $i;

	}

    function setSelectedIndex($i) {
        $this->value = $i;
    }

    function setReadOnly() {
        $this->readonly = true;
    }

}

class wListBoxSearch extends wObject implements wRenderizable {

    var $name = "";
    var $value = "";
    var $dataModel = array();
    var $moreHTMLproperties = "";
    var $readonly = false;

    function __construct($name=null, &$parent, $addPrefix=true) {  
        parent::__construct($name, $parent);
        $this->name = $name;
        if (($addPrefix))
            $this->id = "{$parent->id}.{$this->id}";


    }

	function __postInit() {
		if ($this->alreadyPostInit!==true) {
			$this->alreadyPostInit=true;
			$this->addListener("_onchange", "updateTextBox", $this);
			$this->Listener["_onchange"]->addParameter(XAJAX_QUOTED_VALUE, $this->id);
			$this->Listener["_onchange"]->addParameter(XAJAX_JS_VALUE, 'this.value');

			$this->addListener("ondelayedchange", "checkTerm", $this);
			$this->Listener["ondelayedchange"]->addParameter(XAJAX_QUOTED_VALUE, $this->id);
			$this->Listener["ondelayedchange"]->addParameter(XAJAX_JS_VALUE, 'this.value');
		}
	}

	function updateTextBox($a,$b,$id,$idx) {
		$objResponse = new xajaxResponse;
		$this->dataModel=wDesktop::get($id);
		debug("Data for $idx $id is:".print_r($this->dataModel,true),"white");
		
		if (!is_array($this->dataModel)) {
			$this->dataModel=$this->updateModelDynamic($this->dataModel,$idx);

		}
		
		$objResponse->assign("{$id}_dropdown","value",$this->dataModel[$idx]);
		$objResponse->assign("{$id}_autosg","innerHTML",$final."");
		//$objResponse->alert("fu");
		return $objResponse;
	}

	function updateModelDynamic($stringedDataModel,$selectedvalue) {
		$options=explode("|",$stringedDataModel);
		$sources=explode(":",$options[2]);
		$source=newObject($sources[0],$selectedvalue);
		
		return array($selectedvalue=>$source->$sources[1]);
	}

	function checkTermDynamic($stringedDataModel,$term) {
		$options=explode("|",$stringedDataModel);
		$sources=explode(":",$options[2]);
		$source=newObject($sources[0]);
		$term=strtr($term,array(" "=>"%"));
		return $source->listAll($sources[1], false, "{$sources[1]} LIKE '%$term%'",0,"{$sources[1]} ASC");
	}

	function checkTerm($a,$b,$id,$term) {
		function normaliza ($cadena){
			
			$originales =  'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
			$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
			$cadena = utf8_decode($cadena);
			$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
			//$cadena = strtolower($cadena);
			return utf8_encode($cadena);
		}
		$this->dataModel=wDesktop::get($id);
		if (!is_array($this->dataModel)) {
			debug("NOT AN STATIC DATAMODEL {$this->dataModel}","white");
			$this->dataModel=$this->checkTermDynamic($this->dataModel,$term);

		}
		$objResponse = new xajaxResponse;
		foreach ($this->dataModel as $k=>$v) {
			$result[$k]=similar_text( normaliza(strtoupper($v)),normaliza(strtoupper($term)),$pst);
			if (strpos(strtolower(normaliza($v)),strtolower(normaliza($term)))!==false) 
				$pst*=2;
			$result[$k]=$pst."# $v vs $term";;
		}
		arsort($result,SORT_NUMERIC);
		//debug(print_r($result,true),"blue");
		foreach ($result as $k=>$v) {
			if ($k>1) {
				$text=$this->dataModel[$k];
				if (strpos(strtolower(normaliza($text)),strtolower(normaliza($term)))!==false) {
						$finalTerm="<b>".($text)."</b>";
				} else 
					$finalTerm="".($text)."";
				
				
				$final.="<li class='autodropdown' style='padding:3px'><a style='cursor:pointer' onclick=\"$('{$id}').value=$k;$('{$id}').simulate('change');$('{$id}_autosg').innerHTML=''\" >$finalTerm</a></li>";
				$maxw=(strlen($this->dataModel[$k])*10)>$maxw?(strlen($this->dataModel[$k])*10):$maxw;
			}
			if (++$c>10)
				break;
			
		}
		$solution=key($result);
		//$objResponse->assign("{$this->id}_dropdown","value",$this->dataModel[$idx]);
		//$objResponse->alert("$term");
		$objResponse->assign("{$id}_autosg","innerHTML","<div style='background-color:white;min-width:{$maxw}px;width:100%;border:1px solid gray;padding:5px'>$final</div>");
		

		return $objResponse;

	}


    function render() {
        global $SYS;
		$this->__postInit();
		
		//

        parent::render();
        foreach ($this->Listener as $k => $v) {
			if (($k!="ondelayedchange")&&($k!="_onchange"))
				if (!is_array($v))
					$eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
				else {
					$eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
				}
        }
        //value='{$this->value}'
        if ($this->readonly1=true) {
			$customEventCode=$this->Listener["ondelayedchange"]->getScript($SYS["ROOT"]."/Framework/Extensions/xajax");
            if (is_array($this->dataModel)) 
				echo "<input type='text'  name='{$this->name}_dropdown' id='{$this->id}_dropdown'  style='{$this->cssStyle}' value='{$this->dataModel[$this->value]}' onkeyup='_delayedKeyUp($(\"{$this->id}_dropdown\"),\"{$this->id}_dropdown\")' >";
			else
				echo "<input type='text'  name='{$this->name}_dropdown' id='{$this->id}_dropdown'  style='{$this->cssStyle}' value='{$this->value}' onkeyup='_delayedKeyUp($(\"{$this->id}_dropdown\"),\"{$this->id}_dropdown\")' >";
			echo "<input type='hidden'  name='{$this->name}' $eventCode id='{$this->id}'  style='{$this->cssStyle}' value='{$this->value}'>\n";
			echo "<img onclick='$(\"{$this->id}_dropdown\").value=\"\";$(\"{$this->id}\").value=\"0\";Event.simulate(\"{$this->id}\",\"change\")' style='left:-10px;cursor:pointer;' src='{$GLOBALS["SYS"]["ROOT"]}Framework/Extensions/wGui/Img/mini_del.gif' 
			title='"._("Limpiar")."'/>";
			echo "<div name='{$this->name}_autosg' id='{$this->id}_autosg' value='' style='padding:0px;border:0px;position:absolute;background-color:white;width:{$this->staticCSS["width"]};z-index:5;'></div>\n";
			echo "<script>
				try {
					$('{$this->id}_dropdown').bind('delayedchange',function() { $customEventCode });
					$('{$this->id}').bind('change',function() { ".$this->Listener["_onchange"]->getScript($SYS["ROOT"])." })
				} catch (ie) {
					$('{$this->id}_dropdown').bind('ondelayedchange',function() { $customEventCode });
					$('{$this->id}').bind('onchange',function() { ".$this->Listener["_onchange"]->getScript($SYS["ROOT"])." })

				}
				</script>";
			
            
        } else {
            /*echo "<select {$this->moreHTMLproperties}  disabled   id='faked_{$this->id}'  style='{$this->cssStyle}'>\n";
            foreach ($this->dataModel as $k => $v) {
				if (is_array($this->value))
					$addSel = (in_array($this->value,$k)) ? "selected" : "";
                else
					$addSel = ($this->value == $k) ? "selected" : "";

                echo "\t<option value='$k' label='$v' $addSel>$v</option>\n";
            }
            echo "</select>\n";

            echo $this->dataModel["$this->value"] . " <input type='hidden' id='{$this->id}' name='{$this->name}' style='background-color:gray' onchange='\$(\"faked_{$this->id}\").value=this.value'  value='{$this->value}'>";
			*/
        }
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "200px");
        $this->setCSS("min-height", "14px");
    }

    function setDataModel($data) {

        $this->dataModel = $data;
		
		wDesktop::set($this->id,$data);

    }

	function setSelectedValue($i) {
		$this->value = $i;

	}

    function setSelectedIndex($i) {
        $this->value = $i;
    }

    function setReadOnly() {
        $this->readonly = true;
    }

}

?>

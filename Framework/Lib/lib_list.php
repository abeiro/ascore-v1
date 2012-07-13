<?php


require_once("lib_planty.php");



/*

##################################################################################
                              LISTADO CON PLANTILLAS
##################################################################################

*/

function listList($psObject, $campos, $template,$navigation_vars="",$parset=1,$plParseTemplateFunction="plParseTemplate",$obj_method=array()) {

  global $TOTALTIME,$SYS,$offset,$PET,$styles,$nn,$FFPATCH;
    
    $nplParseTemplateFunction=$plParseTemplateFunction;
    $psObject->sc=$sc;
    $psObject->mod=$mod;
    $psObject->pag=$pag;
    $psObject->offset=$offset;
	$users=new plantilla($template);

	$count=0;
	
	/**************** mod_rewrite patch  */
	if ($SYS["NAV_SEPARATOR"]=="/") {
		$chunk=(strpos($PET,"navvars=on")!==false)?(strpos($PET,"navvars=on")):strlen($PET);
		$navvars=$SYS["ROOT"]."/".(substr($PET,0,$chunk));
		debug("Navvars init $navvars POS=".$chunk."($PET)","green");
	}
	/**************** mod_rewrite patch  */
	
	
	if ($navigation_vars>0) {
		$navvars=base64_encode(serialize($navigation_vars));
		$psObject->navvars=$navvars;
		

	}

	/* Paginacion */
	
	$T=$psObject->totalPages;
	$W=$SYS["DEFAULTROWS"];
	$N=ceil($T/$W);
	$P="";
	
	
	if ($FFPATCH===true)
		$P="".$psObject->totalPages." elementos<br /> ";
	else
		if ($T>$W)
			$P="Páginas ";
	
	/**************** mod_rewrite patch  */
	/**************** mod_rewrite patch  */
	if ($SYS["NAV_SEPARATOR"]=="/") {
		$chunk=(strpos($PET,"navvars=on")!==false)?(strpos($PET,"navvars=on")):strlen($PET);
		$navvars=$SYS["ROOT"]."/".(substr($PET,0,$chunk));
		debug("Navvars init $navvars POS=".$chunk."($PET)","green");
	}
	/**************** mod_rewrite patch  */
	/**************** mod_rewrite patch  */
	$navvars.="{$SYS["NAV_SEPARATOR_I"]}navvars=on";
	foreach ($SYS["NAVVARS"] as $k=>$v) {
		$navvars.="{$SYS["NAV_SEPARATOR"]}$v=".$GLOBALS[$v];
	}
	debug("Navvars built $navvars","green");
    if ($N>1) {
		if ($N<7) {
			for ($i=0;$i<$N;$i++) {
				if (($offset/$W)==($i))
					if ($i==0) 
						$P.="<a style=\"text-decoration:underline;text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i*$W)."\">".($i+1)."</a>";
					else
						$P.="&middot;<a style=\"text-decoration:underline;text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i*$W)."\">".($i+1)."</a>";
	
				else
					if ($i==0) 
						$P.="<a style=\"text-decoration:none; text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i*$W)."\">".($i+1)."</a>";
					else
						$P.="&middot;<a style=\"text-decoration:none; text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i*$W)."\">".($i+1)."</a>";
						
			}
		}
		else {
			$i=$offset/$W;
			if (($i>1)&&($i<($N-2)))
				$smart_pages=array(0,'-',$i-1,$i,$i+1,'-',$N-1);
			else if ($i<2)
				$smart_pages=array(0,1,2,'-',$N-2,$N-1);
			else
				$smart_pages=array(0,'-',$N-3,$N-2,$N-1);
		
			foreach($smart_pages as $j)
				if ($j==="-")
					$P.="&middot;...";
				else {
					if (($offset/$W)==($j))
						$P.="&middot;<a style=\"text-decoration:underline;text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($j*$W)."\">".($j+1)."</a>";
					else
						$P.="&middot;<a style=\"text-decoration:none; text-weight:bold;\" href=\"{$SYS["FORCEDURLFORPAGING"]}$navvars{$SYS["NAV_SEPARATOR"]}offset=".($j*$W)."\">".($j+1)."</a>";
				}
		}
			
		
		
	}


	
	$psObject->Pager=$P;

	echo $users->plParseTemplateHeader(get_object_vars($psObject));
	
	
	if ($psObject->searchResults>0) {
		foreach ($psObject->searchResults as $key => $object) {
			/*
			VNH
			*/
			$count++;
			
				
				/* SLOW CODE */
				
				/* Child inherits parent properties */
				if (is_object($object)) {
					$child=get_object_vars($object);
					$parent=get_object_vars($psObject);
					$diff=array_diff(array_keys($parent),array_keys($child));
					foreach ($diff as $k=>$v) {
						$object->$v=$psObject->$v;
					}
					/* If child and parent of different class, typecast */
					if ($object->name!=$psObject->name)
						$object=typecast($object,get_class($psObject));
					/* Normalization of object*/
					$object->_normalize();
					/* END OF SLOW CODE */
				}
				
				if ($count%$parset==0) {
					$object->parset=False;
					//echo $parset.":".$count.":True";
				}
				else {
					//echo $parset.":".$count.":False";
					$object->parset=True;
				}
				
				
				
				foreach ($campos as $fld=>$type) {
					$multitype=explode("#",$type);

					switch ($multitype[0]) {
					
					case "texto":
						
							$object->$fld=substr(strip_tags($object->f($fld)),0,$multitype[1])." ....";
							break;
					
					case "parrafo":
						
							$object->$fld=nl2br(strip_tags($object->f($fld),"<br>"));
							break;
						
					case "fecha":
							if($object->$fld==0) $object->$fld="";
							else $object->$fld=strftime("%d/%m/%Y",$object->f($fld));
							break;
							
					case "ref":
							$obj=explode("|",$multitype[1]);
							$class=$obj[0];
							$field=$obj[1];
							$nfield=$obj[2];

							$void=newObject($class,$object->f($fld));
                            				if (empty($nfield))
								$object->$fld=$void->f($field);
							else
								$object->$nfield=$void->f($field);
							break;
							
						
					case "xref":
							$obj=explode("|",$multitype[1]);
							$class=$obj[0];
							$field=$obj[2];
							$xid=$obj[1];
							
							if (is_object($object)) {
								$void=newObject($class,$object->f($xid));
								$object->$fld=$void->f($field);
							}
							else
							{
								$void=newObject($class,$object["$xid"]);
								$object["$fld"]=$void->f($field);
							}
							
							
							break;
							
					case "xxref":
							$obj=explode("@",$multitype[1]);
							$class=$obj[0];
							$field=$obj[2];
							$xid=$obj[1];
						
							if (is_object($object)) {
								$void=newObject($class,$object->f($xid));
								$object->$fld=$void->f($field);
							}
							else
							{
								$void=newObject($class,$object["$xid"]);
								$object["$fld"]=$void->f($field);
							}
							
								
							
							break;		
								
					case "fref":
							$obj=explode("|",$multitype[1]);
							$class=$obj[0];
							$field=$obj[2];
							$xid=$obj[1];
						
							$void=newObject($class,$object->f($xid));
							$object->$fld=$void->$field();
							break;
				
							
					case "hora":
							$object->$fld=strftime("%H:%M",$object->f($fld));
							break;
							
					case "guided":
							$object->$fld=$multitype[1];
							break;

					case "code":
							//dataDump(get_class_methods($object));
							//echo ' Antes: '.$object->$fld;
							debug(" Code ".$multitype[1].$multitype[2],"red");
							$aux=eval($multitype[1].$multitype[2]);
							//echo " Despues: ".$aux;
							$object->$fld=$aux;
							break;
					
					case "select":
							$external[$fld]=eval("return array(".$multitype[1].");");
							//echo $multitype[1];
							//dataDump($external);
							break;
							
					}

				}

			//------------------------------------------------
			
			foreach($obj_method as $k=>$method) {
				if(!$object->$k)
					$object->$k=$object->$method();
				else
					$external[$k]=$object->$method();
			}
			
			//-------------------------------------------------

			if (is_object($object))
				echo $users->$plParseTemplateFunction(get_object_vars($object),$external);
			else 
				echo $users->$plParseTemplateFunction($object,$external);

		}
    }

	
	else
		{
		$vars["error"].='<center><b>No hubo resultados</b></center>';
		
	}
	
	unset($styles);unset($nn);
	echo $users->plParseTemplateFooter(get_object_vars($psObject));

	
	return True;
}



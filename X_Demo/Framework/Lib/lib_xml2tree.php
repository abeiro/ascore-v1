<?php
/*
	Requires class XmlToArray
*/

class xml2Tree{
	var $branch = Array();
	var $ID;
	var $value;
  	/*
	function xml2Tree($source){
	
		$file=file($source);
		$xml=implode("\n",$file);

		$xmlObj=new XmlToArray($xml);
		$this->branch = $xmlObj->createArray();
    
	}
	*/
	function xml2Tree(){
		//$this->branch = $tree;	
	}

	function branchPush($tree, $father_ID, $ID, $value){		
		foreach($tree as $k=>$v){
			if(is_array($v)){				
				$path[$k] = $this->branchPush($v, $father_ID, $ID, $value);
				if($v['ID'] == $father_ID ){
					$path[$k][$value]['ID'] = $ID;
				}
			}
			else{
				$path[$k] = $v;
			}
		}
		return $path;
	}


	function TreeRender(){
		echo '<pre>';
		print_r($this->branch);
		echo '</pre>';
	}
}

?>
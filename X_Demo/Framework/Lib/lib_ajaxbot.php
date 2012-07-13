<?php

Class AjaxBot{

	var $privates = Array(
		'this.value',
		'this.name',
		'this.id'
	);

 	function AjaxBot()
	{
		global $SYS;
		$thisfile = pathinfo(__FILE__);
		$this->uri=$SYS["ROOT"].'/Framework/Extensions/AjaxBot/index.php';
		
	}

	function phpF($function, $params_list, $target)
	{
		$this->javascript .= $this->phpFfunction($function, $params_list, $target);
		return $this->phpFcallout($function, $params_list, $target);
	}

	function phpFfunction($function, $params_list, $target)
	{
		$params = explode('|',$params_list);
		list($CL, $CL_function)=explode('::',$function);
		if($CL_function) 
			$function = $CL_function;

		$cont=1;

		foreach($params as $k=>$v){
			$arguments[]="ARG$cont";
			$cont++;
		}

		if($CL_function){
			$arguments[]= "CL";
			$script = "function ".$CL."_$CL_function( func , ".implode(', ',$arguments).", target ){";
		}
		else
			$script = "function $function( func , ".implode(', ',$arguments).", target ){ ";

		$script.= "new Ajax.Updater( target , ";
		$script.= "'{$this->uri}?function='+func+";
		$cont = 1;
		foreach($params as $k=>$v){
			if($cont < sizeof($params))
				$script.= "'&ARG$cont='+ARG$cont+";
			else
				$script.= "'&ARG$cont='+ARG$cont";
			$cont++;
		}
		if($CL_function)
			$script.= "+'&CL='+CL";

		$script.= ");}";

		return $script;
	}

	function phpFcallout($function, $params_list, $target)
	{
		$params = explode('|',$params_list);
		list($CL, $CL_function)=explode('::',$function);
		foreach($params as $k=>$v)
		{
			if(in_array($v, $this->privates) || is_numeric($v))
				$arguments[]= $v;
			else{
				if(strpos($v,"\$(") !== False || strpos($v,"\$F(") !== False)
					$arguments[]= $v;
				else
					$arguments[]= "'$v'";
			}
		}
		if($CL_function)
		{
			$arguments[]= "'$CL'";
			return $CL."_$CL_function( '$CL_function' , ".implode(', ',$arguments).", '$target' );";
		}
		else
			return "$function( '$function' , ".implode(', ',$arguments).", '$target' );";
	}

	function Render()
	{
		echo '<SCRIPT type="text/javascript">'."\n".$this->javascript.'</SCRIPT>'."\n";
	}

}

?>
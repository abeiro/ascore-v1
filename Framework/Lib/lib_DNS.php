<?php
/*
 * email_validation.php
 *
 * @(#) $Header: /cvsroot/ascore/ascore/Framework/Lib/lib_DNS.php,v 1.1.1.1 2007/05/17 16:24:52 actsys Exp $
 *
 */

 
class email_validation_class
{
	var $email_regular_expression="^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}\$";
	var $timeout=0;
	var $data_timeout=0;
	var $localhost="";
	var $localuser="";
	var $debug=1;
	var $html_debug=0;
	var $exclude_address="";
	var $getmxrr="GetMXRR";

	var $next_token="";
	var $preg;
	var $last_code="";

	Function Tokenize($string,$separator="")
	{
		if(!strcmp($separator,""))
		{
			$separator=$string;
			$string=$this->next_token;
		}
		for($character=0;$character<strlen($separator);$character++)
		{
			if(GetType($position=strpos($string,$separator[$character]))=="integer")
				$found=(IsSet($found) ? min($found,$position) : $position);
		}
		if(IsSet($found))
		{
			$this->next_token=substr($string,$found+1);
			return(substr($string,0,$found));
		}
		else
		{
			$this->next_token="";
			return($string);
		}
	}

	Function Debug($message)
	{
		$message.="\n";
		if($this->html_debug)
			$message=str_replace("\n","<br />\n",HtmlEntities($message));
		echo $message;
		flush();
	}

	Function GetLine($connection)
	{
		for($line="";;)
		{
			if(feof($connection))
				return(0);
			$line.=fgets($connection,100);
			$length=strlen($line);
			if($length>=2
			&& substr($line,$length-2,2)=="\r\n")
			{
				$line=substr($line,0,$length-2);
				if($this->debug)
					debug("S $line","magenta");
				return($line);
			}
		}
	}

	Function PutLine($connection,$line)
	{
		if($this->debug)
			debug("C $line","magenta");
		return(fputs($connection,"$line\r\n"));
	}

	Function ValidateEmailAddress($email)
	{
		if(IsSet($this->preg))
		{
			if(strlen($this->preg))
				return(preg_match($this->preg,$email));
		}
		else
		{
			$this->preg=(function_exists("preg_match") ? "/".str_replace("/", "\\/", $this->email_regular_expression)."/" : "");
			return($this->ValidateEmailAddress($email));
		}
		return(eregi($this->email_regular_expression,$email)!=0);
	}

	Function ValidateEmailHost($email,&$hosts)
	{
		if(!$this->ValidateEmailAddress($email))
			return(0);
		$user=$this->Tokenize($email,"@");
		$domain=$this->Tokenize("");
		$hosts=$weights=array();
		$getmxrr=$this->getmxrr;
		if(function_exists($getmxrr)
		&& $getmxrr($domain,$hosts,$weights))
		{
			$mxhosts=array();
			for($host=0;$host<count($hosts);$host++)
				$mxhosts[$weights[$host]]=$hosts[$host];
			KSort($mxhosts);
			for(Reset($mxhosts),$host=0;$host<count($mxhosts);Next($mxhosts),$host++)
				$hosts[$host]=$mxhosts[Key($mxhosts)];
		}
		else
		{
			if(strcmp($ip=@gethostbyname($domain),$domain)
			&& (strlen($this->exclude_address)==0
			|| strcmp(@gethostbyname($this->exclude_address),$ip)))
				$hosts[]=$domain;
		}
		return(count($hosts)!=0);
	}

	Function VerifyResultLines($connection,$code)
	{
		while(($line=$this->GetLine($connection)))
		{
			$this->last_code=$this->Tokenize($line," -");
			if(!strcmp($this->last_code,$code))
				return(1);
			if(strcmp($this->last_code,$code))
				return(0);
		}
		return(-1);
	}

	Function ValidateEmailBox($email)
	{
		if(!$this->ValidateEmailHost($email,$hosts))
			return(0);
		if(!strcmp($localhost=$this->localhost,"")
		&& !strcmp($localhost=getenv("SERVER_NAME"),"")
		&& !strcmp($localhost=getenv("HOST"),""))
		   $localhost="localhost";
		if(!strcmp($localuser=$this->localuser,"")
		&& !strcmp($localuser=getenv("USERNAME"),"")
		&& !strcmp($localuser=getenv("USER"),""))
		   $localuser="root";
		for($host=0;$host<count($hosts);$host++)
		{
			$domain=$hosts[$host];
			if(ereg('^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$',$domain))
				$ip=$domain;
			else
			{
				if($this->debug)
					debug("Resolviendo nombre de host \"".$hosts[$host]."\"...","magenta");
				if(!strcmp($ip=@gethostbyname($domain),$domain))
				{
					if($this->debug)
						debug("No se puede resolver el nombre del host \"".$hosts[$host]."\".","magenta");
					continue;
				}
			}
			if(strlen($this->exclude_address)
			&& !strcmp(@gethostbyname($this->exclude_address),$ip))
			{
				if($this->debug)
					debug("El host \"".$hosts[$host]."\" es una direccion excluida","magenta");
				continue;
			}
			if($this->debug)
				debug("Contectando a la direccion de host \"".$ip."\"...","magenta");
			if(($connection=($this->timeout ? @fsockopen($ip,25,$errno,$error,$this->timeout) : @fsockopen($ip,25))))
			{
				$timeout=($this->data_timeout ? $this->data_timeout : $this->timeout);
				if($timeout
				&& function_exists("socket_set_timeout"))
					socket_set_timeout($connection,$timeout,0);
				if($this->debug)
					debug("Conectado.","magenta");
				if($this->VerifyResultLines($connection,"220")>0
				&& $this->PutLine($connection,"HELO $localhost")
				&& $this->VerifyResultLines($connection,"250")>0
				&& $this->PutLine($connection,"MAIL FROM: <$localuser@$localhost>")
				&& $this->VerifyResultLines($connection,"250")>0
				&& $this->PutLine($connection,"RCPT TO: <$email>")
				&& ($result=$this->VerifyResultLines($connection,"250"))>=0)
				{
					if($result)
					{
						if($this->PutLine($connection,"DATA"))
							$result=($this->VerifyResultLines($connection,"354")!=0);
					}
					else
					{
						if(strlen($this->last_code)
						&& !strcmp($this->last_code[0],"4"))
							$result=-1;
					}
					if($this->debug)
						debug("El servidor determina que la direccion ".($result ? ($result>0 ? "es valida" : "esta sin determinar") : "no es valida").".","magenta");
					fclose($connection);
					if($this->debug)
						debug("Desconectado.","magenta");
					return($result);
				}
				if($this->debug)
					debug("No ha sido posible validar la direccion en el host.","magenta");
				fclose($connection);
				if($this->debug)
					debug("Desconectado.","magenta");
			}
			else
			{
				if($this->debug)
					debug("Fallo.","magenta");
			}
		}
		return(-1);
	}
};


function DNS_validate_email($email) {

	$validator=new email_validation_class;

	
	if(!function_exists("GetMXRR"))
	{
		/*
		 * If possible specify in this array the address of at least on local
		 * DNS that may be queried from your network.
		 */
		$_NAMESERVERS=array();
		include("getmxrr.php");
	}
	
	$validator->timeout=10;
	$validator->data_timeout=0;
	$validator->localuser="info";
	$validator->localhost="activasistemas.com";
	$validator->debug=1;
	$validator->html_debug=0;
	$validator->exclude_address="";

	if(($result=$validator->ValidateEmailBox($email))<0)
			return 3;
	else
			if ($result)	
				return 1;
			else
				return 0;
	
	
}
?>
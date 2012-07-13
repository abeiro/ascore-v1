<?php

    class mime_mail
    {
    var $parts;
    var $to;
    var $from;
    var $headers;
    var $subject;
    var $body;

    function mime_mail()
    {
    $this->parts = array();
    $this->to = "";
    $this->from = "";
    $this->subject = "";
    $this->body = "";
    $this->headers = "";
    }

    function add_attachment($message, $name = "", $ctype = "application/octet-stream")
    {
	$UID=md5(uniqid(time()+rand(0,1000)));
    $this->parts[] = array (
    "ctype" => $ctype,
    "message" => $message,
    "encode" => $encode,
    "name" => $name,
	"cid" => $UID
    );
	return $UID;
    }


    function build_message($part)
    {
    if (strpos($part["ctype"],"alternative")!==False) {
		return "Content-Type: ".$part["ctype"]."\n".$part["message"]."\n";
	}
		$message = $part["message"];
    $message = chunk_split(base64_encode($message));
    $encoding = "base64";
    return "Content-Type: ".$part["ctype"].($part["name"]?"; name = \"".$part["name"]."\"" : "")."\nContent-Transfer-Encoding: $encoding\nContent-ID: <".$part["cid"].">\n\n$message\n"; 
	}

    function build_multipart()
    {
    $boundary = "b".md5(uniqid(time()+rand(0,1000)));
    $multipart = "Content-Type: multipart/mixed; boundary = \"$boundary\"\n\n--$boundary";

    for($i = sizeof($this->parts)-1; $i >= 0; $i--)
    {
    
    $multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary";
    //$boundary = "b".md5(uniqid(time()+rand(0,1000)));
	
    }
    return $multipart.= "--\n";
    }

	function build_alternative()
    {
    $boundary = "b".md5(uniqid(time()+rand(0,1000)));
    $multipart ="--$boundary";

    for($i = sizeof($this->parts)-1; $i >= 0; $i--)
    {
    
    $multipart .= "\n".$this->build_message($this->parts[$i])."--$boundary";
	
    }
	$this->parts="";	
	$this->parts[] = array (
    "ctype" => "multipart/alternative; boundary =\"$boundary\"",
    "message" => $multipart,
    "encode" => "base64",
    "name" => "Data",
	"cid" => ""
    );
    return $multipart.= "--\n";
    }

    function send()
    {
    $mime = "";
    if (!empty($this->from))
    $mime .= "From: ".$this->from."\n";
    if (!empty($this->headers))
    $mime .= $this->headers;

    if ($this->multipart) {
    	$mime .= "MIME-Version: 1.0\n".$this->build_multipart(); 
		//echo "<pre>$mime</pre>";
		return mail($this->to, $this->subject, '', $mime);
	}
	else
			return mail($this->to, $this->subject, $this->body, $mime);	

	}
	}; // end of class
    ?>






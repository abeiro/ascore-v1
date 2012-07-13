<?php



function ValidateCode($xml)
        {
                libxml_use_internal_errors(true);

		$ox=simplexml_load_file($xml);

                $errors = libxml_get_errors();
		ob_start();
		print_r($errors);
		$stringerror=ob_get_contents();
		ob_end_clean();
		
                debug($stringerror,"red");
		 if (empty($errors))
                {
                        return true;
                }

                $error = $errors[ 0 ];
                if ($error->level < 3)
                {
                        return true;
                }


                $lines = explode("r", $xml);
                $line = $lines[($error->line)-1];

                $message = array("msg"=>str_replace("\n","","$error->message"),"line"=>$error->line);

                return $message;
        }

?>



<?php
    /*
     Constructor:
     Give template's name, not file name
     <!-- D:'.key($dat).' -->Normal
     <!-- M:'.key($dat).' -->Normal
     <!-- S:'.key($dat).' -->Money (Euros)
     <!-- H:'.key($dat).' -->Time
     <!-- E:'.key($dat).' -->Float 00,00
     <!-- F:'.key($dat).' -->Float
     <!-- V:'.key($dat).' -->Integer
     <!-- F:'.key($dat).' -->Float
     <!-- A:'.key($dat).' -->Date
     <!-- K:'.key($dat).' -->Function call
     <!-- O:'.key($dat).' -->HardCode selects
     <!-- X:'.key($dat).' -->Dynamic selects
     <!-- G:'.key($dat).' -->Check boxes
     <!-- R:'.key($dat).' -->Datex (Extended Date)
     <!-- I:'.key($dat).' -->Conditional block. Parsed if key(dat) is true.
     <!-- NI:'.key($dat).' -->Relaxed Conditional block. Parsed if key(dat)>1
     <!-- L:'.key($dat).' -->Show first 30 chars
     <!-- B:'.key($dat).' -->
     <!-- C:'.key($dat).' -->
     <!-- W: -->Wordwrap.
     */
    class DataGrid {
        var $file;
        var $data;
        var $mark;
        var $name;
        function InitTemplate($fd = "/dev/null")
        {
            global $tema, $vars, $SYS;
            $tmpl_dir = "";
            if (strstr($fd, "<!--HEAD-->") == False)
                {
                /* VNH. It's a file */
                if ($fd != "/dev/null")
                    $fdo = $tmpl_dir.$fd.".html";
                $this->file = $fdo;
                $this->name = str_replace(".html", "", basename($fd));
                if (!e_file_exists($fdo))
                    {
                    $tmpl_dir = $SYS["DOCROOT"]."/Plantillas/";
                    if ($fd != "/dev/null")
                        $fdo = $tmpl_dir.$fd.".html";
                    $this->file = $fdo;
                    $this->name = str_replace(".html", "", basename($fd));
                }
                $vars["plantillas"] .= $this->file." , ";
                $this->data = file($fdo, 1) or print("Error abriendo $fdo en ".ini_get("include_path"));
                //echo "<textarea cols=\"100\" rows=\"100\">";print_r($this->data);echo "</textarea>";
            }
            else
                {
                /* Must be a memory autotemplate  */
                $this->file = "in_memory";
                $this->name = "autotemplate";
                $this->data = array_values(explode("\n", strtr(nl2br($fd), array("<br>" => " ", "<br />" => " "))));
                debug("Using memory autotemplate");
                //echo "<textarea cols=\"100\" rows=\"100\">";print_r($this->data);echo "</textarea>";
            }
            /*echo "<textarea cols=\"100\" rows=\"100\">";
             print_r($this->data);
             echo "</textarea";*/
            $this->mark = array("head" => "<!--HEAD-->", "set" => "<!--SET-->", "end" => "<!--END-->");
        }
        function _array_search_fuzz($haystack, $needle)
        {
            while (next($haystack))
            {
                //debug("Buscando $needle en".current($haystack));
                if (strpos(current($haystack), $needle) === FALSE)
                    continue;
                else
                    {
                    //debug("Encontrado $needle en".current($haystack)." - ". key($haystack));
                    return key($haystack) ;
                }
            }
            return FALSE;
        }
        function plParseTemplate($dat, $external = "")
        {
            global $styles, $nn, $SYS, $PET;
            static $cut1, $cut2, $head, $navvars, $nextpage, $prevpage, $initialized;
            $bm = getmicrotime();
            if (!isset($initialized))
                {
                $copy = $this->data;
                $cut1 = $this->_array_search_fuzz($copy, $this->mark["set"]);
                $cut2 = $this->_array_search_fuzz($copy, $this->mark["end"]);
                $head = ($cut1 && $cut2) ? array_splice($copy, $cut1, $cut2-$cut1) :
                $copy;
                $cc = "dynamic_class_".$this->name.($nn%$styles)."";
                if (is_object($dat))
                    $dat = get_object_vars($dat);
                //
                debug("total pages:".$dat["totalPages"]." vs ".$dat["nextP"], "green");
                /* Navvars patch */
                /**************** mod_rewrite patch  */
                if ($SYS["NAV_SEPARATOR"] == "/")
                    {
                    $chunk = (strpos($PET, "navvars=on") !== false)?(strpos($PET, "navvars=on")):
                    strlen($PET);
                    $navvars = $SYS["ROOT"]."/".(substr($PET, 0, $chunk));
                    debug("Navvars init $navvars POS=".$chunk."($PET)", "green");
                }
                /**************** mod_rewrite patch  */
                $navvars .= "{$SYS["NAV_SEPARATOR_I"]}navvars=on";
                foreach ($SYS["NAVVARS"] as $k => $v)
                {
                    if ($v != "offset")
                        $navvars .= "{$SYS["NAV_SEPARATOR"]}$v=".$GLOBALS[$v];
                }
                if ($dat["totalPages"] > $dat["nextP"])
                    $nextpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["nextP"];
                else
                    {
                    $nextpage = "-----------";
                    //echo "unveiling next page";
                }
                $prevpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["prevP"];
            }
            else
                $initialized = True;
            $res = implode('', $head);
            $res = str_replace('<!-- N:nextpage -->', $nextpage, $res);
            $res = str_replace('<!-- N:prevpage -->', $prevpage, $res);
            $res = str_replace('<!-- N:navvars -->', $navvars, $res);
            //echo "-".($dat["parset"])."#";
            if (($dat["parset"] == True))
                {
                debug("Using parset", "red");
                $res = ereg_replace("<!--STARTPARSET-->.*<!--ENDPARSET-->", "<!--*-->", $res);
            }
            //dataDump($dat);echo sizeof($dat);
            for ($j = 0, $dat_size = sizeof($SYS["GLOBAL"]); $j < $dat_size; $j++)
            {
                //debug(key($SYS["GLOBAL"])."-".current($SYS["GLOBAL"])."- $i");
                if (current($SYS["GLOBAL"]) == FALSE)
                    {
                    $res = ereg_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->.*<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                /* Patch 20070501 */
                if (current($SYS["GLOBAL"]) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->.*<!-- NI:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->', '', $res);
                /* Patch 20070501 */
                next($SYS["GLOBAL"]);
            }
            for ($j = 0, $dat_size = sizeof($dat); $j < $dat_size; $j++)
            {
                //debug(key($dat)."-".current($dat)."- $i");
                if (current($dat) == False)
                    {
                    $res = ereg_replace('<!-- I:'.key($dat).' -->.*<!-- I:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                if (current($dat) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($dat).' -->.*<!-- NI:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                $res = str_replace('<!-- P:'.key($dat).' -->', nl2br(strip_tags(current($dat), "<br />")), $res);
                $res = str_replace('<!-- D:'.key($dat).' -->', current($dat), $res);
                $res = str_replace('<!-- M:'.key($dat).' -->', current($dat), $res);
                if (is_numeric(current($dat)))
                    $res = str_replace('<!-- S:'.key($dat).' -->', number_format(current($dat), 2, ',', '.')."&euro;", $res);
                else
                    $res = str_replace('<!-- S:'.key($dat).' -->', current($dat), $res);
                $res = str_replace('<!-- F:'.key($dat).' -->', sprintf("%.5f", current($dat)), $res);
                /* A bug here */
                $res = str_replace('<!-- V:'.key($dat).' -->', current($dat), $res);
                /* A bug here */
                $res = str_replace('<!-- E:'.key($dat).' -->', number_format(current($dat), 2, ',', '.'), $res);
                $res = str_replace('<!-- A:'.key($dat).' -->', strftime("%d/%m/%Y &nbsp;", current($dat)), $res);
                $res = str_replace('<!-- T:'.key($dat).' -->', strftime("%H:%M &nbsp;", current($dat)), $res);
                $res = str_replace('<!-- H:'.key($dat).' -->', strftime("%d/%m/%Y %H:%S", current($dat)), $res);
                $res = str_replace('<!-- C:'.key($dat).' -->', "checked", $res);
                $res = str_replace('<!-- R:'.key($dat).' -->', int_to_text_ex(current($dat)), $res);
                $res = str_replace('<!-- L:'.key($dat).' -->', substr(current($dat), 0, 300)."...", $res);
                $res = str_replace('<!-- W:'.key($dat).' -->', WordWrapping(strip_tags(current($dat), "<strong>"), 50)."...", $res);
                $res = str_replace('<!-- NR:'.key($dat).' -->', number_format(sprintf("%.2f", current($dat)), 2, ',', ''), $res);
                /* Checkboxes */
                $checked_if_checkbox = (current($dat) == 'Si') ? "checked" :
                "";
                $res = str_replace('<!-- G:'.key($dat).' -->', $checked_if_checkbox, $res);
                /*VNH*/
                $res = str_replace('01/01/1970 &nbsp;', "-", $res);
                $res = str_replace('01:00 &nbsp;', "-", $res);
                /* Dynamic Selects Try */
                $res = str_replace('<!-- dynamic_class -->', $cc, $res);
                //die("<textarea cols='100' rows='10'>$res</textarea>");
                /* Soporte para selects dinámicos */
                //debug("External data ".$external[key($dat)]." (".key($dat).") ","cyan");
                if (isset($external[key($dat)]))
                    {
                    //debug($external[key($dat)],"cyan");
                    for ($i = 0, $ext_key_isze = sizeof($external[key($dat)]); $i < $ext_key_isze; $i++)
                    {
                        $buffer .= "<option value=\"".key($external[key($dat)])."\" ";
                        if (key($external[key($dat)]) == current($dat))
                            $buffer .= " selected ";
                        if (is_array(current($dat)))
                            if (in_array(key($external[key($dat)]), array_keys(current($dat))))
                            {
                            $buffer .= " selected ";
                        }
                        if (key($external[key($dat)]) == current($dat))
                            $buffer .= " selected ";
                        $aux = explode("|", (current($external[key($dat)])));
                        if ($aux[1] == "Off")
                            $buffer .= " disabled ";
                        $buffer .= ">";
                        $buffer .= $aux[0]."</option>";
                        //debug($buffer,"cyan");
                        next($external[key($dat)]);
                    }
                    $res_aux = str_replace('<!-- X:'.key($dat).' -->', $buffer, $res);
                    $buffer = "";
                    $res = "<!-- EXTERNAL DATA -->".$res_aux;
                    // Soporte para radion button dinámicos
                    reset($external[key($dat)]);
                    for ($i = 0, $ext_key_isze = sizeof($external[key($dat)]); $i < $ext_key_isze; $i++)
                    {
                        $buffer .= "<input type=\"radio\" name=".key($dat)." value=\"".key($external[key($dat)])."\" ";
                        if (key($external[key($dat)]) == current($dat))
                            $buffer .= " checked ";
                        $buffer .= ">";
                        $buffer .= current($external[key($dat)])."";
                        next($external[key($dat)]);
                    }
                    $res_aux = str_replace('<!-- B:'.key($dat).' -->', $buffer, $res);
                    $buffer = "";
                    $res = "<!-- EXTERNAL DATA -->".$res_aux;
                }
                /* Dynamic Selects  2nd Try */
                $res = str_replace('<!-- O:'.key($dat).current($dat).' -->', "SELECTED", $res);
                next($dat);
            }
            /* Function call */
            $res = preg_replace("/<!-- K:(.*)\(\"(.*)\"\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\('(.*)'\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\((.*)\) -->/e", "\\1(\\2)", $res);
            //debug($res,"red");
            //debug("Plantilla parseada!");
            $nn++;
            $res = preg_replace("'<!-- [\/\!]*?[^<>]*? -->'si", "", $res);
            //$res=preg_replace("'<!--[\/\!]*?[^<>]*?-->'si","",$res);
            $SYS["parse_planty_time"] += (getmicrotime()-$bm);
            return $res."\t\n";
            ;
        }
        function plParseTemplateHeader($dat = "")
        {
            global $styles, $nn, $SYS, $PET;
            if (empty($dat))
                $dat = array();
            $copy = $this->data;
            $nn = 0;
            $cut = $this->_array_search_fuzz($copy, $this->mark["set"]);
            debug("SET Encontrado en $cut");
            $head = ($cut !== FALSE) ? array_splice($copy, 0, $cut) :
            $copy;
            $res = implode('', $head);
            $styles = preg_match_all("/dynamic_class_$name/", $res, $null);
            /* Navvars patch */
            /**************** mod_rewrite patch  */
            if ($SYS["NAV_SEPARATOR"] == "/")
                {
                $chunk = (strpos($PET, "navvars=on") !== false)?(strpos($PET, "navvars=on")):
                strlen($PET);
                $navvars = $SYS["ROOT"]."/".(substr($PET, 0, $chunk));
                debug("Navvars init $navvars POS=".$chunk."($PET)", "green");
            }
            /**************** mod_rewrite patch  */
            $navvars .= "{$SYS["NAV_SEPARATOR_I"]}navvars=on";
            foreach ($SYS["NAVVARS"] as $k => $v)
            {
                $navvars .= "{$SYS["NAV_SEPARATOR"]}$v=".$GLOBALS[$v];
            }
            $nextpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["nextP"];
            $prevpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["prevP"];
            for ($j = 0, $dat_size = sizeof($SYS["GLOBAL"]); $j < $dat_size; $j++)
            {
                //debug(key($SYS["GLOBAL"])."-".current($SYS["GLOBAL"])."- $i");
                if (current($SYS["GLOBAL"]) == FALSE)
                    {
                    $res = ereg_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->.*<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                /* Patch 20070501 */
                if (current($SYS["GLOBAL"]) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->.*<!-- NI:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->', '', $res);
                /* Patch 20070501 */
                next($SYS["GLOBAL"]);
            }
            /* Smart pag links */
            if ($dat["nextP"] < 1)
                {
                $res = ereg_replace('<!-- IFPAGER1 -->.*<!-- FIPAGER1 -->', '', $res);
                $res = ereg_replace('<!-- IFPAGER2 -->.*<!-- FIPAGER2 -->', '', $res);
            }
            else if (($dat["nextP"] > 0) && ($dat["offset"] < 1))
            {
                $res = ereg_replace('<!-- IFPAGER1 -->.*<!-- FIPAGER1 -->', '', $res);
            }
            //else if (($dat["nextP"]>=$dat["totalPages"])) {
            else if (($dat["nextP"] == $dat["offset"]))
            {
                $res = ereg_replace('<!-- IFPAGER2 -->.*<!-- FIPAGER2 -->', '', $res);
            }
            /* Smart pag links */
            for ($i; $i < sizeof($dat); $i++)
            {
                if (current($dat) == False)
                    {
                    $res = ereg_replace('<!-- I:'.key($dat).' -->.*<!-- I:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                if (current($dat) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($dat).' -->.*<!-- NI:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                $res = str_replace('<!-- D:'.key($dat).' -->', current($dat), $res);
                if (is_numeric(current($dat)))
                    $res = str_replace('<!-- S:'.key($dat).' -->', number_format(current($dat), 2, ',', '.')."&euro;", $res);
                else
                    $res = str_replace('<!-- S:'.key($dat).' -->', current($dat), $res);
                $res = str_replace('<!-- F:'.key($dat).' -->', sprintf("%.5f", current($dat)), $res);
                $res = str_replace('<!-- E:'.key($dat).' -->', number_format(current($dat), 2, ',', '.'), $res);
                $res = str_replace('<!-- V:'.key($dat).' -->', sprintf("%.0f", current($dat)), $res);
                $res = str_replace('<!-- A:'.key($dat).' -->', strftime("%d/%m/%Y ", current($dat)), $res);
                $res = str_replace('<!-- H:'.key($dat).' -->', strftime("%d/%m/%Y %H:%S", current($dat)), $res);
                $res = str_replace('<!-- R:'.key($dat).' -->', int_to_text_ex(current($dat)), $res);
                $res = str_replace('<!-- N:nextpage -->', $nextpage, $res);
                $res = str_replace('<!-- N:prevpage -->', $prevpage, $res);
                $res = str_replace('<!-- N:navvars -->', $navvars, $res);
                next($dat);
            }
            $res = preg_replace("/<!-- K:(.*)\(\"(.*)\"\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\('(.*)'\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\((.*)\) -->/e", "\\1(\\2)", $res);
            return $res."<!-- END OF PARSEHEADER -->\n";
        }
        function plParseTemplateFooter($dat = "")
        {
            global $SYS, $PET;
            if (empty($dat))
                $dat = array();
            $copy = $this->data;
            $cut = $this->_array_search_fuzz($copy, $this->mark["end"]);
            $head = ($cut) ? array_splice($copy, $cut) :
            $copy;
            $res = implode('', $head);
            /* Navvars patch */
            /**************** mod_rewrite patch  */
            if ($SYS["NAV_SEPARATOR"] == "/")
                {
                $chunk = (strpos($PET, "navvars=on") !== false)?(strpos($PET, "navvars=on")):
                strlen($PET);
                $navvars = $SYS["ROOT"]."/".(substr($PET, 0, $chunk));
                debug("Navvars init $navvars POS=".$chunk."($PET)", "green");
            }
            /**************** mod_rewrite patch  */
            $navvars .= "{$SYS["NAV_SEPARATOR_I"]}navvars=on";
            foreach ($SYS["NAVVARS"] as $k => $v)
            {
                $navvars .= "{$SYS["NAV_SEPARATOR"]}$v=".$GLOBALS[$v];
            }
            $nextpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["nextP"];
            $prevpage = $navvars."{$SYS["NAV_SEPARATOR"]}offset=".$dat["prevP"];
            //dataDump($dat);
            /* Smart pag links */
            //echo "<pre>";print_r($dat);echo "</pre>";die();
            //echo "{$dat["nextP"]}>={$dat["totalPages"]}:{$dat["offset"]}";
            if ($dat["nextP"] < 1)
                {
                $res = ereg_replace('<!-- IFPAGER1 -->.*<!-- FIPAGER1 -->', '', $res);
                $res = ereg_replace('<!-- IFPAGER2 -->.*<!-- FIPAGER2 -->', '', $res);
            }
            else if (($dat["nextP"] > 0) && ($dat["offset"] < 1))
            {
                $res = ereg_replace('<!-- IFPAGER1 -->.*<!-- FIPAGER1 -->', '', $res);
            }
            else if (($dat["nextP"] == $dat["offset"]))
            {
                $res = ereg_replace('<!-- IFPAGER2 -->.*<!-- FIPAGER2 -->', '', $res);
            }
            /* Smart pag links */
            for ($j = 0, $dat_size = sizeof($SYS["GLOBAL"]); $j < $dat_size; $j++)
            {
                //debug(key($SYS["GLOBAL"])."-".current($SYS["GLOBAL"])."- $i");
                if (current($SYS["GLOBAL"]) == FALSE)
                    {
                    $res = ereg_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->.*<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                next($SYS["GLOBAL"]);
                if (current($SYS["GLOBAL"]) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->.*<!-- I:'.key($SYS["GLOBAL"]).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($SYS["GLOBAL"]).' -->', '', $res);
                next($SYS["GLOBAL"]);
            }
            for ($i; $i < sizeof($dat); $i++)
            {
                if (current($dat) == False)
                    {
                    $res = ereg_replace('<!-- I:'.key($dat).' -->.*<!-- I:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- I:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                if (current($dat) < 1)
                    {
                    $res = ereg_replace('<!-- NI:'.key($dat).' -->.*<!-- NI:'.key($dat).' -->', '', $res);
                }
                else
                    $res = str_replace('<!-- NI:'.key($dat).' -->', '', $res);
                /* Patch 20070501 */
                $res = str_replace('<!-- D:'.key($dat).' -->', current($dat), $res);
                if (is_numeric(current($dat)))
                    $res = str_replace('<!-- S:'.key($dat).' -->', number_format(current($dat), 2, ',', '.')."&euro;", $res);
                else
                    $res = str_replace('<!-- S:'.key($dat).' -->', current($dat), $res);
                $res = str_replace('<!-- F:'.key($dat).' -->', sprintf("%.5f", current($dat)), $res);
                $res = str_replace('<!-- E:'.key($dat).' -->', number_format(current($dat), 2, ',', '.'), $res);
                $res = str_replace('<!-- V:'.key($dat).' -->', sprintf("%.0f", current($dat)), $res);
                $res = str_replace('<!-- A:'.key($dat).' -->', strftime("%d/%m/%Y", current($dat)), $res);
                $res = str_replace('<!-- H:'.key($dat).' -->', strftime("%d/%m/%Y %H:%S", current($dat)), $res);
                $res = str_replace('<!-- R:'.key($dat).' -->', int_to_text_ex(current($dat)), $res);
                $res = str_replace('<!-- N:nextpage -->', $nextpage, $res);
                $res = str_replace('<!-- N:prevpage -->', $prevpage, $res);
                $res = str_replace('<!-- N:navvars -->', $navvars, $res);
                next($dat);
            }
            $res = preg_replace("/<!-- K:(.*)\(\"(.*)\"\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\('(.*)'\) -->/e", "\\1('\\2')", $res);
            $res = preg_replace("/<!-- K:(.*)\((.*)\) -->/e", "\\1(\\2)", $res);
            return $res."<!-- END OF PARSEFOOTER -->\n";
        }
        function listList($psObject, $campos, $template, $navigation_vars = "", $parset = 1)
        {
            global $TOTALTIME, $SYS, $offset, $PET, $styles, $nn, $FFPATCH;
            $psObject->sc = $sc;
            $psObject->mod = $mod;
            $psObject->pag = $pag;
            $psObject->offset = $offset;
            $users = $this->InitTemplate($template);
            $count = 0;
            /**************** mod_rewrite patch  */
            if ($SYS["NAV_SEPARATOR"] == "/")
                {
                $chunk = (strpos($PET, "navvars=on") !== false)?(strpos($PET, "navvars=on")):
                strlen($PET);
                $navvars = $SYS["ROOT"]."/".(substr($PET, 0, $chunk));
                debug("Navvars init $navvars POS=".$chunk."($PET)", "green");
            }
            /**************** mod_rewrite patch  */
            if ($navigation_vars > 0)
                {
                $navvars = base64_encode(serialize($navigation_vars));
                $psObject->navvars = $navvars;
            }
            /* Paginacion */
            $T = $psObject->totalPages;
            $W = $SYS["DEFAULTROWS"];
            $N = ceil($T/$W);
            $P = "";
            if ($FFPATCH === true)
                $P = "".$psObject->totalPages." elementos<br /> ";
            else
                if ($T > $W)
                $P = "Páginas ";
            /**************** mod_rewrite patch  */
            /**************** mod_rewrite patch  */
            if ($SYS["NAV_SEPARATOR"] == "/")
                {
                $chunk = (strpos($PET, "navvars=on") !== false)?(strpos($PET, "navvars=on")):
                strlen($PET);
                $navvars = $SYS["ROOT"]."/".(substr($PET, 0, $chunk));
                debug("Navvars init $navvars POS=".$chunk."($PET)", "green");
            }
            /**************** mod_rewrite patch  */
            /**************** mod_rewrite patch  */
            $navvars .= "{$SYS["NAV_SEPARATOR_I"]}navvars=on";
            foreach ($SYS["NAVVARS"] as $k => $v)
            {
                $navvars .= "{$SYS["NAV_SEPARATOR"]}$v=".$GLOBALS[$v];
            }
            debug("Navvars built $navvars", "green");
            if ($N > 1)
                {
                if ($N < 7)
                    {
                    for ($i = 0; $i < $N; $i++)
                    {
                        if (($offset/$W) == ($i))
                            if ($i == 0)
                            $P .= "<a style=\"text-decoration:underline;text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i * $W)."\">".($i+1)."</a>";
                        else
                            $P .= "&middot;<a style=\"text-decoration:underline;text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i * $W)."\">".($i+1)."</a>";
                        else
                            if ($i == 0)
                            $P .= "<a style=\"text-decoration:none; text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i * $W)."\">".($i+1)."</a>";
                        else
                            $P .= "&middot;<a style=\"text-decoration:none; text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($i * $W)."\">".($i+1)."</a>";
                    }
                }
                else
                    {
                    $i = $offset/$W;
                    if (($i > 1) && ($i < ($N-2)))
                        $smart_pages = array(0, '-', $i-1, $i, $i+1, '-', $N-1);
                    else if ($i < 2)
                    $smart_pages = array(0, 1, 2, '-', $N-2, $N-1);
                    else
                        $smart_pages = array(0, '-', $N-3, $N-2, $N-1);
                    foreach($smart_pages as $j)
                    if ($j === "-")
                        $P .= "&middot;...";
                    else
                        {
                        if (($offset/$W) == ($j))
                            $P .= "&middot;<a style=\"text-decoration:underline;text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($j * $W)."\">".($j+1)."</a>";
                        else
                            $P .= "&middot;<a style=\"text-decoration:none; text-weight:bold;\" href=\"$navvars{$SYS["NAV_SEPARATOR"]}offset=".($j * $W)."\">".($j+1)."</a>";
                    }
                }
            }
            $psObject->Pager = $P;
            echo $this->plParseTemplateHeader(get_object_vars($psObject));
            if ($psObject->searchResults > 0)
                {
                foreach ($psObject->searchResults as $key => $object)
                {
                    /*
                     VNH
                     */
                    $count++;
                    /* SLOW CODE */
                    /* Child inherits parent properties */
                    if (is_object($object))
                        {
                        $child = get_object_vars($object);
                        $parent = get_object_vars($psObject);
                        $diff = array_diff(array_keys($parent), array_keys($child));
                        foreach ($diff as $k => $v)
                        {
                            $object->$v = $psObject->$v;
                        }
                        /* If child and parent of different class, typecast */
                        if ($object->name != $psObject->name)
                            $object = typecast($object, get_class($psObject));
                        /* Normalization of object*/
                        $object->_normalize();
                        /* END OF SLOW CODE */
                    }
                    if ($count%$parset == 0)
                        {
                        $object->parset = False;
                        //echo $parset.":".$count.":True";
                    }
                    else
                        {
                        //echo $parset.":".$count.":False";
                        $object->parset = True;
                    }
                    foreach ($campos as $fld => $type)
                    {
                        $multitype = explode("#", $type);
                        switch ($multitype[0])
                        {
                            case "texto":
                            $object->$fld = substr(strip_tags($object->f($fld)), 0, $multitype[1])." ....";
                            break;
                            case "parrafo":
                            $object->$fld = nl2br(strip_tags($object->f($fld), "<br>"));
                            break;
                            case "fecha":
                            if ($object->$fld == 0) $object->$fld = "";
                            else $object->$fld = strftime("%d/%m/%Y", $object->f($fld));
                            break;
                            case "ref":
                            $obj = explode("|", $multitype[1]);
                            $class = $obj[0];
                            $field = $obj[1];
                            $nfield = $obj[2];
                            $void = newObject($class, $object->f($fld));
                            if (empty($nfield))
                                $object->$fld = $void->f($field);
                            else
                                $object->$nfield = $void->f($field);
                            break;
                            case "xref":
                            $obj = explode("|", $multitype[1]);
                            $class = $obj[0];
                            $field = $obj[2];
                            $xid = $obj[1];
                            if (is_object($object))
                                {
                                $void = newObject($class, $object->f($xid));
                                $object->$fld = $void->f($field);
                            }
                            else
                                {
                                $void = newObject($class, $object["$xid"]);
                                $object["$fld"] = $void->f($field);
                            }
                            break;
                            case "fref":
                            $obj = explode("|", $multitype[1]);
                            $class = $obj[0];
                            $field = $obj[2];
                            $xid = $obj[1];
                            $void = newObject($class, $object->f($xid));
                            $object->$fld = $void->$field();
                            break;
                            case "hora":
                            $object->$fld = strftime("%H:%M", $object->f($fld));
                            break;
                            case "guided":
                            $object->$fld = $multitype[1];
                            break;
                            case "code":
                            //dataDump(get_class_methods($object));
                            //echo ' Antes: '.$object->$fld;
                            debug(" Code ".$multitype[1].$multitype[2], "red");
                            $aux = eval($multitype[1].$multitype[2]);
                            //echo " Despues: ".$aux;
                            $object->$fld = $aux;
                            break;
                            case "select":
                            $external[$fld] = eval("return array(".$multitype[1].");");
                            //echo $multitype[1];
                            //dataDump($external);
                            break;
                        }
                    }
                    if (is_object($object))
                        echo $this->plParseTemplate(get_object_vars($object), $external);
                    else
                        echo $this->plParseTemplate($object, $external);
                }
            }
            else
                {
                $vars["error"] .= '<center><b>No hubo resultados</b></center>';
            }
            unset($styles);
            unset($nn);
            echo $this->plParseTemplateFooter(get_object_vars($psObject));
            return True;
        }
    }
?>


<?php


  function lastMonth($timestamp) {
      
      $newDate=strtotime(date("m",$timestamp)."/1/".date("Y",$timestamp)." -1 day");
      return $newDate;
  }
  
  function lastlastMonth($timestamp) {
      
      $newDate=strtotime(date("m",$timestamp)."/1/".date("Y",$timestamp)." -1 day");
      return $newDate;
  }


  function evaluateExpression($expression,$timestamp,$withSTRFTIME=false) {
      $currentLocale=setlocale(LC_ALL,0);

      setlocale(LC_ALL,'es_ES.UTF-8');
      /* Replacementes en español */
      $buffer=preg_replace("/__FECHA{([^\{]{1,100}?)}/e",'strftime("$1",'.$timestamp.')',$expression);
      $buffer=preg_replace("/__ULTIMODIAMESPASADO{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth($timestamp) . ')', $buffer);
      $buffer=preg_replace("/__ULTIMODIADOSMESPASADO{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth(lastMonth($timestamp)) . ')', $buffer);
      
      $buffer=preg_replace("/__phpcode{([^\{]{1,100}?)}/e", "eval('$1')", $buffer);

      setlocale(LC_ALL,$currentLocale);
      /* Replacementes en guiri */
      $buffer=preg_replace("/__FECHA_AME{([^\{]{1,100}?)}/e",'strftime("$1",'.$timestamp.')',$buffer);
      $buffer=preg_replace("/__ULTIMODIAMESPASADO_AME{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth($timestamp) . ')', $buffer);
      $buffer=preg_replace("/__ULTIMODIADOSMESPASADO_AME{([^\{]{1,100}?)}/e", 'strftime("$1",' . lastMonth(lastMonth($timestamp)) . ')', $buffer);
      if ($withSTRFTIME) {
              $buffer=strftime($buffer,$timestamp);
      }
      
      return $buffer;
  }


$buffer="__phpcode{return (ceil(__FECHA{%j}/7)%2==0)?'FDNR-D':'FDNR-PM';}";

echo evaluateExpression($buffer,strtotime("19 april 2013"));

?>

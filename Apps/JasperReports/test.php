<?php
//phpinfo();

include("phpjasper/Java.inc");


$system = new Java('java.lang.System');
        
        // accéder aux propriétés
echo 'Java version=' . $system->getProperty('java.version') . ' <br />';
echo 'Java vendor=' . $system->getProperty('java.vendor') . '<br />';
echo 'OS=' . $system->getProperty('os.name') . ' ' .
                     $system->getProperty('os.version') . ' on ' .
                     $system->getProperty('os.arch') . '<br />';
        
$formater = new Java('java.text.SimpleDateFormat',
                              "EEEE, MMMM dd, yyyy 'at' h:mm:ss a zzzz");
        
echo $formater->format(new Java('java.util.Date'));

?>
                    
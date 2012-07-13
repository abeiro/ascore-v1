<?php

require_once("System.php");
plantHTML(array(),"f_menu");
?>




<br><br><br>
<table width="70%" cellspacing="0" border="0" cellpadding="5" align="center">
  <tbody>
    <tr>
      <td align="center"> 

		<form action="action_import_sql.php" method="POST"  enctype="multipart/form-data">
		<input type="file" name="fichero_sql"><br><br>
		
		<br><br>
		<input type="submit"> 
		</form>

	 </td>
    </tr>
  </tbody>
</table>
<?php

HTML("footer");

?>



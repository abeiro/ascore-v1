<?php
header("Content-Type: text/plain");
require("Lib/prepend.php");
$g_ps["Core"]["debugSet"]=0;

$fields = $db->metaColumns($table);
$author = $auth["username"] ." <".$auth["email"].">\n";
?>
<?php echo "<?php\n"?>
/**
 * Enter a suitable description for your new class.
 *
 * @author <?php echo $author ?>
 * @version $Id: classGenerator.php,v 1.1.1.1 2007/05/17 16:37:04 actsys Exp $
 */
class <?php echo $class?> extends ps<?php echo $module?> {

<?php 
foreach($fields as $key=>$field) {
?>
  /**
   * <?php echo $field->name ?> - field for the the table <?php echo $table."\n"?>
   *
   * @var       string
   * @access	public
   */
  var $<?php echo $field->name ?>;
  
<?php }
reset($fields);?>

  /**
   * Constructor
   * Creates a new <?php echo $class?> object.  If $id is set, it grabs
   * the data from the database.
   *
   * @access public
   * @param integer primary key for <?php echo $table."\n"?>
   * @author <?php echo $author?>
   */
   function <?php echo $class?>($id=0) {
		// Call parent constructor
		$par = get_parent_class($this);
		$this->$par();

		if ($id) {
			$this->_get($id);	
		}
	}

   /**
    * Destructor
    *
    * @access public
    */
   function _<?php echo $class?>() {
		return;
   }
   
   // --  HANDLERS --

   /**
    * (HANDLER) Saves a <?php echo $table?> record
    *
    * Required Fields:
    * $d["<?php echo $column?>"]   ID for table
    *
    * @access public
    * @param array $d HTTP Get/Post Variables
    * @return boolean
    * @author <?php echo $author?>
    */
   function save($d) {
     $db = new psDb;

     if (!$this->_validateSave($d)) {
       return False;
     }

     if ($d["<?php echo $column?>"] == 1) {
       $q = "
	   INSERT INTO <?php echo $table."\n"?>
	   (<?php 
	   foreach($fields as $key=>$field) {
	   		if ($field->name != $column)
	   		$insLine .= "\n\t$field->name,";
	   }
	   reset($fields);
	   echo substr($insLine, 0, strlen($insLine)-1);
	   ?>)
       VALUES 
	   (<?php 
	   foreach($fields as $key=>$field) {
	   		if ($field->name != $column)
	   		$insLine2 .= "\n\t'{\$d[\"$field->name\"]}',";
	   }
	   reset($fields);
	   echo substr($insLine2, 0, strlen($insLine2)-1);
	   ?>)";
	   }
     else {
       $q = "
	   UPDATE <?php echo $table?> SET<?php 
	   foreach($fields as $key=>$field) { 
	   		if ($field->name != $column)
	   		$varLine .= "\n\t$field->name='{\$d[\"$field->name\"]}',";
	   }
	   reset($fields);
	   echo substr($varLine, 0, strlen($varLine)-1);
	   ?>
	   
       WHERE <?php echo $column?>='{$d["<?php echo $column ?>"]}' ";
     }
     $db->query($q);

	 /* Retorno del ID */
	
     if ($d["ID"]==1) 
		$this->ID=$db->Link_ID->insert_id();
	 else
		$this->ID=$d["ID"];
		

     $this->setError(dgettext("Core", "Guardado Correctamente"), MESSAGE);

     return True;
   }

  /**
  * (HANDLER) Deletes a record
  *
  * Required Fields:
  * $d["<?php echo $column ?>"]      primary key
  *
  * @access public
  * @param array $d HTTP Get/Post Variables
  * @return boolean
  * @author <?php echo $author ?>
  */
  function delete($d) {
    $db = new psDb;

    if (!$this->_validateDelete($d)) {
      return False;
    }

    $q ="DELETE FROM <?php echo $table ?> WHERE <?php echo $column ?>='{$d["<?php echo $column ?>"]}'";
    $db->query($q);

    $this->setError(dgettext("Core", "Borrado correctamente"), MESSAGE);

    return True;
  }

  /**
   * gets all from the db based in keyword search
   * and sort order
   *
   * @access public
   * @param integer $offset the current database record offset
   * @param string  $orderBy the field to sort the results by
   * @param string  $keywords the keywords to search for, default is all records
   * @author <?php echo $author ?>
   */
  function search($offset, $orderBy="", $keywords="") {
    global $psPage;
    global $g_ps;
    $db = new psDb;

    // Keyword Search
    if ($keywords) {
      $key = explode(",", $keywords);

      while (list ($k,$v) = each($key)) {
       $where .= " AND ( ";
	   <?php
	   foreach($fields as $key=>$field) {
	   	if ($field->name != $column){
	   ?>
	   	$where .= " AND <?php echo $field->name ?> LIKE '%$v%' OR ";
	   <?php } }
	   reset($fields);
	   ?>
	   $where .= " ) ";
      }
    }

    // Order by
    $orderBy = $orderBy ? $orderBy : "<?php echo $column ?>";

    // Build query
    $q =<<< EOQ
       SELECT * FROM <?php echo $table ?>
       WHERE <?php echo $column ?> > 1
       $where
       ORDER BY $orderBy
EOQ;
    $db->pageExecute($q,$g_ps["Core"]["searchRows"],$offset);

    while ($db->next_record()) {
      $record[] = new <?php echo $class ?>($db->f("<?php echo $column ?>"));
    }
    $this->searchResults = $record;

    $this->currentPage = $psPage->currentPage($db);
    $this->totalPages = $psPage->totalPages($db);
    $this->previousPage = $psPage->previous($db);
    $this->nextPage = $psPage->next($db);
  }

   /**
   * Hace una consulta extendida. Se especifican las condiciones
   * usando directamente los campos de la tabla.
   *
   * @access public
   * @param integer $offset the current database record offset
   * @param string  $condiciones Condiciones de búsqueda
   * @param string  $keywords the keywords to search for, default is all records
   * @author <?php echo $author ?>
*/
  function search_ex($offset, $condiciones,$orderBy="") {
    global $psPage;
    global $g_ps;
    $db = new psDb;



    if (!empty($condiciones))
        $where = " AND ".$condiciones;

    // Order by
    $orderBy = $orderBy ? $orderBy : "<?php echo $column ?>";

    // Build query
    $q =<<< EOQ
       SELECT * FROM <?php echo $table ?>   WHERE <?php echo $column ?> > 1
       $where
       ORDER BY $orderBy
EOQ;
    $db->pageExecute($q,$g_ps["Core"]["searchRows"],$offset);

    while ($db->next_record()) {
      $record[] = new <?php echo $class ?>($db->f("<?php echo $column ?>"));
          }
    $this->searchResults = $record;

    $this->currentPage = $psPage->currentPage($db);
    $this->totalPages = $psPage->totalPages($db);
    $this->previousPage = $psPage->previous($db);
    $this->nextPage = $psPage->next($db);
  }

  
  // -- PRIVATE METHODS

  /**
   * gets a record from the db and assigns the result to the member vars
   *
   * @access private
   * @author <?php echo $author ?>
   * @param integer $id the id of the record to get
   */
  function _get($id=1) {
    $db = new psDb;
    $q = "SELECT * FROM <?php echo $table ?> WHERE <?php echo $column ?>='$id'";
    $db->query($q);
    $db->next_record();
    $this->_assign($db->Record);
  }

  /**
   * assigns contents of var associative array to member vars
   *
   *
   * @access private
   * @author <?php echo $author ?>
   * @param array $d array of results from a query
   */
  function _assign($d) {
     <?php
	   foreach($fields as $key=>$field) { 
	   ?>
    $this-><?php echo $field->name ?> = $d["<?php echo $field->name ?>"];
    <?php
	}
	?>
  }


 /**
  * Validates an add or update.
  *
  * @access private
  * @author <?php echo $author ?>
  * @param  array $d HTTP Get/Post Variables
  * @return boolean
  */
  function _validateSave($d) {
  
  	// Customize to suite your needs.
    return True;
  }


  /**
  * Validates a delete.  
  *
  * Required Fields:
  * $d["<?php echo $column?>"]      Group Reference
  *
  * @access private
  * @author <?php echo $author ?>
  * @param  array $d HTTP Get/Post Variables
  * @return boolean
  */
  function _validateDelete($d) {
    $valid = true;

    if (!$d["<?php echo $column?>"]) {
      $this->setError(dgettext("Core", "Seleccione un registro a borrar."));
      $valid = false;
    }
    return $valid;
  }



}
<?php echo "\n?>"?>



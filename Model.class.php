<?php
// The Model class is our basic data model in the MVC design of RAS blogg. This is where the
// most important database interactin takes place, and the class is designed to be extended by
// other data model classes for database interaction. As such, the methods are generalized
// to work for any class.
class Model {
  public $id;

  // Don't ever instantiate a Model object directly.
  public function __construct() {
    throw new Exception("Model is a generic class and should only be used by subclasses.");
  }

  // Save this Model object to the database.
  public function save($obj) {
      global $database;
      $class_name = get_class($obj);

      // See if this Model object has an id, and run INSERT or UPDATE accordingly
      if (isset($id)) {
        // prepate UPDATE query
          $query = "UPDATE ".$class_name." SET ";
          foreach ($obj as $name=>$val) {
              if($name != "id"){
                  $query .= "$name=? ";
                  $values[] = $val;
              }
          }
          $query .= "WHERE id=".$obj->id;
      } else {
      	// prepare INSERT query variables
          foreach ($obj as $name=>$val) {
          	  if($name != "id"){
                $fields[] = $name;
                $values[] = $val;
                $qms[] = "?";
              }
          }
          $query = "INSERT INTO ".$class_name."(".join(",",$fields).") VALUES (".join(",",$qms).")";
      }
      
      // Preapare a databae statement and execute using the variables found on this Model object.
      $stmt = $database->prepare($query);
      $stmt->execute($values);
      
      // See if the SQL query return an error and throw Exception in case, or the object ID if successful
      if($stmt->errorCode() != '00000') {
        $error = $stmt->errorInfo();
      	throw new Exception($error[2]);
			} else {
				if (preg_match('/INSERT/', $query)) {
					return $database->lastInsertId('id');
				} else {
					return $obj->id;
				}
			}
  }

  // Load an instance of model from given class name with given id
  public function find($class_name, $id) {
      global $database;
      if (func_num_args() != 2) exit("Wrong number of arguments in Model::find()"); 

      $query = "SELECT * FROM $class_name WHERE id = $id";
      $stmt = $database->query($query);
      $stmt->setFetchMode(PDO::FETCH_INTO, new $class_name);
      $obj = $stmt->fetchObject($class_name); 
      
      if (isset($obj->id)) return $obj;
			return false;
  }

  // Retrieve all objects of given class by given SQL conditions
  public function getAll($class_name, $conditions="") {
      global $database;
      if (func_num_args() > 2) exit("Wrong number of arguments in Model::getAll()");
      $query = "SELECT * from $class_name $conditions;";
      //var_dump($query);

      $stmt = $database->query($query);
      $objects = $stmt->fetchAll(PDO::FETCH_CLASS, $class_name);
      
  		if($stmt->errorCode() != '00000') {
				$error = $stmt->errorInfo();
     		throw new Exception($error[2]);
			} else {
			  return $objects;
			}
			return false;
  }
  
  // Verify that attributes on given object arer not empty, except given array of attribute names
  public function verifyNotEmpty($obj, $exceptions=array()) {
		foreach ($obj as $name=>$val) {
			if(!in_array($name, $exceptions) and empty($val)) throw new Exception($name . " kan ikke v&aelig;re tom.");
		}
  }
}
?>

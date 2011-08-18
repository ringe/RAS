<?php
// The Tools class is a diverse collection of various helper functions that is
// needed in different aspects of the RAS blogg. To enable the RAS blogg to function
// at all, a new Tools instance has to be initiated, as that takes care of the
// database connection.
class Tools {
	private $settings;
  
 // When the Controller loads, enable database connetion
  public function __construct(){
		if (file_exists('settings.xml')) {
		 	 $this->settings = simplexml_load_file('settings.xml');
		} else exit('Failed to open settings.xml.');
		
		global $database;
		try {
		    $host = $this->settings->database->host;
		    $db = $this->settings->database->name;
		    $user = $this->settings->database->user;
		    $pwd = $this->settings->database->password;
		    $database = new PDO('mysql:host='. $host .';dbname='. $db, $user, $pwd);
		} catch(PDOException $e) {
		    echo $e->getMessage();
		}
	}

  // Generate a password salt
  public function genSalt() {
    return self::randString(20);
  }

  // Generate an activation key
  public function genActivationKey() {
    return self::randString(14, true);
  }

  // Return a random String of given length, eventually human readable.
  public function randString($length, $humane = false) {
    $str = '';
    for ($i=0; $i<$length; $i++) {
      if ($humane) {
        $one = rand(65,90);
        $two = rand(48,57);
      } else {
        $one = rand(68,126);
        $two = rand(33,67);
      }
      $d = rand(1,100) <= 70 ? chr($one) : chr($two);
      $str .= rand(1,30)%2 ? $d : strtolower($d);
    }
    return $str;
  }

  // Are we on Apache og Nginx?
  public function toBeOrNotToBe() {
    if(preg_match('/apache/i', $_SERVER['SERVER_SOFTWARE']) == 1) return true;
    if(preg_match('/nginx/i', $_SERVER['SERVER_SOFTWARE']) == 1) return true;
    return false;
  }
  
  // Rewrite support is assumed if we have Apache
  public function canRewrite() { return self::toBeOrNotToBe(); }
 
  // Mail support is assumed if we have Apache
  public function mailSupport() { return self::toBeOrNotToBe(); }

  // Define the definitive URL to the RAS blog root
  public function rootURL() {
    $path = 'http://'. $_SERVER['SERVER_NAME'];
    if($_SERVER['SERVER_PORT'] != 80) $path .= ':'.$_SERVER['SERVER_PORT'];
    $path.= str_replace('index.php', '', $_SERVER['PHP_SELF']);
    return $path;
  }

  // Return a full URL for the given RAS destination
  public function url($dest) {
    if(self::canRewrite() or empty($dest)) {
      return self::rootURL() . $dest;
    } else {
      return self::rootURL(). 'index.php?url=' . $dest;
    }
  }

  // Return the definitive document root path
  public function documentRoot() {
    ereg('/(.*)index\.php', $_SERVER['SCRIPT_FILENAME'], $docroot); 
    return '/'.$docroot[1];
  }

  // illegalRequest() Takes an array of acceptable request parameters to check if there is any 
  // unwanted ones in the HTTP request. Returns true if the request is deemed illegal.
  public function illegalRequest($acceptable=array()) {
      // The RAS default and PHP default request parameters are always acceptable
      $acceptable[] = 'url';
      $acceptable[] = 'PHPSESSID';
#var_dump($acceptable);
      $results=array();
      foreach ($_REQUEST as $param=>$val) {
          if(isset($acceptable[$param])) {
            $param = $param. ' is this an intended form element? In case, update the acceptable array.';
            //var_dump($param);
            $results[] = false;
          }
      }
      if(in_array(false, $results)) return true;
      return false;
  }
  
  // Return all month names, or the month number if string given, or the month name if number given
  public function months($ask=null) {
    for($m=1;$m<=12;$m++) { 
    	$name = date('F', mktime(0, 0, 0, $m, 1));
      $months[$name] = $m;
      $names[$m] = $name;
    }
    if(!isset($ask)) return $months;
    if(is_numeric($ask)) return $names[(int)$ask];
    return $months[ucfirst($ask)];
  }
  
  // We need to know if a month name is invalid
  public function falseMonth($month) {
  	$month = self::months($month);
    return !isset($month);
  }
  
  // Get a date object from a month name
  public function dateM($format, $month) {
  	if(is_numeric($month)) return date($format, $month);
    return date($format, self::months($month));
	}
	
	// Get the real month name from string or number
	public function monthName($month) {
		if(is_numeric($month)) return self::months($month);
		return self::months(self::months($month));
	}
	
  // Return human readable error message from PHP upload error code
	function file_upload_error_message($error_code) {
	  switch ($error_code) {
	      case UPLOAD_ERR_INI_SIZE: 
	          return 'System error: The uploaded file exceeds the upload_max_filesize directive in php.ini';
	      case UPLOAD_ERR_FORM_SIZE: 
	          return 'Filen du fors&oslash;ker &aring; laste opp er st&oslash;rre enn tillatt.';
	      case UPLOAD_ERR_PARTIAL: 
	          return 'Filen ble bare delvis lastet opp. Pr&oslash;v igjen.'; 
	      case UPLOAD_ERR_NO_FILE: 
	          return 'Ingen fil ble valgt.'; 
	      case UPLOAD_ERR_NO_TMP_DIR: 
	          return 'System error: Missing a temporary folder'; 
	      case UPLOAD_ERR_CANT_WRITE: 
	          return 'System error: Failed to write file to disk'; 
	      case UPLOAD_ERR_EXTENSION: 
	          return 'Ikke godkjent filtype.'; 
	      default: 
	          return 'System error: Unknown upload error'; 
	  }
	}

} // Tools class end
?>

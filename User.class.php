<?php
// The User class benefits from the general Model class for it's basic interactions
// with the database. There is a lot of handling in the User class for the special
// cases of dealing with registration, login, password renewal and account activation.
class User extends Model {
  public $name;
  public $email;
  protected $password;
  protected $salt;
  protected $activationkey;
  
  // Create a user by given user form POST request
  public function __construct($userRequest=null) {
  	if($userRequest != null) {
	    $this->setEmail($userRequest['email'], $userRequest['emailconfirm']);
	    $this->name = $userRequest['name'];
	    $this->password = $this->setPassword($userRequest['password']);
	    $this->setActivationkey();
	    $this->save();
	    $this->sendMail();
		}
  }
  
  // Save the User instance to the database, after verification
  public function save() {
	  	$this->verifyBeforeSave();
      return Model::save($this);
  }
  
  // Return a User instance with given id
  public function find($id) {
		return Model::find("User", $id);
  }
  
  // Activate user account by activationkey
  public function activate($activationkey){
		global $database;
    $query = "SELECT  id FROM  User WHERE  activationkey = ?";
    $stmt = $database->prepare($query);
    $result = $stmt->execute(array($activationkey));
    $error = $stmt->errorInfo();
    if($stmt->errorCode() != '00000') {
     	throw new Exception($error[2]);
    }

    if($result) {
        $query = "UPDATE User SET activated='1', activationkey='' WHERE activationkey=?;";
        $stmt = $database->prepare($query);
        $result = $stmt->execute(array($activationkey));
    
        $error = $stmt->errorInfo();
        if($stmt->errorCode() != '00000') {
         	throw new Exception($error[2]);
    		} else {
          return true;
    		}
    } else throw new Exception("Kunne ikke aktivere konto. Sjekk at n&oslash;kkelen er korrekt");
  } // end activate
  
  // Grabs the salt from the DB by looking up it up in the DB by using the mailaddress
  public function findSalt($mail=null){
		global $database;
    if(empty($mail)) {
      $salt = Tools::genSalt();
    } else {
      $query = "SELECT salt FROM User where activated='1' AND email=?";
      $stmt = $database->prepare($query);
      $stmt->execute(array($mail));
  		$result = $stmt->fetch();
  		
  		if($stmt->errorCode() != '00000') {
			  $error = $stmt->errorInfo();
     		throw new Exception($error[2]);
			} else {
			  $salt = $result['salt'];
			}
    }
		return $salt;
  }
  
  // Checks if the two mail addresses are the same, if they are, it's stored in email variable'
  private function setEmail($imail, $imailconfirm){
    if($imail != $imailconfirm) throw new Exception("Mailadressen er ikke lik");
    $this->email = $imail;
  }
  
  // Takes the users given password, creates a salt "pepper" by SHA1 the password, then MD5 that hash again.
  // Calls to function in Tools, which returns a random generated salt.
  // Password is hashed using SHA1 and adding the random generated salt before the password, and the "pepper" salt after the password
  protected function setPassword($ipassword){
  		if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*/', $ipassword)) {
					throw new Exception("Passordet m&aring; v&aelig;re 8 tegn, kun 'A-Za-z0-9' og minst et siffer, en liten og en stor bokstav.");}
      $this->salt = User::findSalt();
      $pepper = md5(sha1($ipassword));
      return sha1($this->salt.$ipassword.$pepper);
  }
  
  // Calls to function in Tools, which returns an activation key
  private function setActivationkey(){
      $this->activationkey = Tools::genActivationKey();
  }

  // Constructs a mail with a link with activation key as paramether to activate the user account.
  // Then sends the mail to the users mail address.
  private function sendMail(){
    if(Tools::mailSupport()) {
        $to  = $this->email;
        $url = Tools::rootURL()."activate/$this->activationkey";

        $subject = " RAS-blogg registrering";

        $message = "<h1>Velkommen til RAS-blogg!</h1>";
        $message.= "<p>Du eller noen andre har benyttet denne mailadressen for å registrere bruker på RAS-bloggen.";
        $message.= "Du kan fullføre registreringen ved å trykke på følgende link:<a href=\"<? echo $url?>\">$url</a></p>";
        $message.= "<p>Hvis dette er feil, se bort ifra denne mailen, og se frem til videre oppdateringer fra våre samarbeidspartnere.</p>";
        $message.= "<h2>Vennlig Hilsen RAS</h2>";

        $headers = 'From: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
        'Reply-To: noreply@'. $_SERVER['SERVER_NAME'] . "\r\n" .
        'Content-type: text/html; charset=iso-8859-1\n'.
        'X-Mailer: PHP/' . phpversion() . ' RAS Software version 1';

	      mail($to, $subject, $message, $headers);
    }
  }

  // Verify all attributes
  private function verifyBeforeSave() {
  		// Verify not empty variables
			Model::verifyNotEmpty($this, array('id', 'activated','activationkey'));
			if (!preg_match('/(?=^.{2,}$)([A-Za-z0-9_-]+)/', $this->name)) throw new Exception("Navnet m&aring; v&aelig;re 2 tegn, samt tillatte tegn: 'A-Za-z0-9_-'");
  }
  
  // Does the login authentication check
  public function loginCheck(){
	  if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
    
		if(empty($_POST['username'])) throw new Exception( "Du m&aring; oppgi en epost!");
		if(empty($_POST['password'])) throw new Exception( "Du m&aring; oppgi et passord!");

		$user = User::CheckLoginInDB($_POST['username'], $_POST['password']);
//	var_dump($user);

		if(get_class($user) != "User") throw new Exception( "Passordet eller eposten stemmer ikke!");
	  if(!$user->activated) throw new Exception("Du m&aring; aktivere din konto fra lenken som ble sendt i epost.");

		return $user;
  }
  
  // Check the login credential for match in DB, and returns the matching object
  protected function CheckLoginInDB($email, $password){
  	if (func_num_args() != 2) return false; // Check that the function is called with correct number of arguments
  	global $database;
    $salt = User::findSalt($email);
    $pepper = md5(sha1($password));
    $password = sha1($salt.$password.$pepper);
    
    $query = "SELECT * FROM User WHERE email=:email AND password=:password";
    
 		$stmt = $database->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    $stmt->setFetchMode( PDO::FETCH_CLASS, 'User');
    $stmt->execute();
    $user = $stmt->fetch( PDO::FETCH_CLASS );

    if($stmt->errorCode() != '00000') {
		    $error = $stmt->errorInfo();
      	throw new Exception($error[2]);
		} else {
    	return $user;
		}
	}

	// Changes the logged in users password
	// Takes in $POST with old and new password (entered by user)
	// Checks for match in DB for old password with the logged in users mail (set by SESSION)
	public function changeInfo($POST){
		if (func_num_args() != 1) return false; // Check that the function is called with correct number of arguments
		if($_SESSION['auth'] != 'true' || func_num_args() != 1) return false; // Check that the function is called with correct number of arguments while user are logged in
		
		global $database;
		$oldpassword = $POST['oldpassword'];
		$newpassword = $POST['newpassword'];
		
		$user = User::CheckLoginInDB($_SESSION['mail'], $oldpassword); // Grabs the current users object, and checks user credential

		//$query = "UPDATE User SET password = $newpassword WHERE email LIKE $_SESSION['mail'] AND password = $oldpassword";
		
		$salt = User::findSalt($_SESSION['mail']);
    $pepper = md5(sha1($oldpassword));
    $oldpassword = sha1($salt.$oldpassword.$pepper);  // Hashes old password
 
 		// Creates the new password and get a new salt
    if (!preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*/', $newpassword)) {
			throw new Exception("Passordet m&aring; v&aelig;re 8 tegn, kun 'A-Za-z0-9' og minst et siffer, en liten og en stor bokstav.");
		}
    $pepper = md5(sha1($newpassword));
    $salt = User::findSalt();
    $newpassword = sha1($salt.$newpassword.$pepper);

		$query = "UPDATE User SET password = ?, salt = ? WHERE email LIKE ? AND password = ?;";
		$stmt = $database->prepare($query);
		$success = $stmt->execute(array($newpassword, $salt, $_SESSION['mail'], $oldpassword));
		
		return $success; // returns true or false
	}
	
	// Generates a new password for the user with the given mail, and sends a mail to the user with the new password
	public function newPassword($mail){
		if(func_num_args() != 1) return false; // Check that the function is called with correct number of arguments
		
    if(User::checkMail($mail)){
      global $database;
		  
		  $newpasswordclear = Tools::randString(9, true);
		  
		  $pepper = md5(sha1($newpasswordclear));
      $salt = User::findSalt();
      $newpassword = sha1($salt.$newpasswordclear.$pepper);
		  
		  $query = "UPDATE User SET password = ?, salt = ? WHERE email LIKE ?;";
		  
		  $stmt = $database->prepare($query);
		  $success = $stmt->execute(array($newpassword, $salt, $mail));	     
      
      if($success==true)		
        User::sendNewPasswordMail($mail, $newpasswordclear);
        
		  return $success; // returns true or false
    } else {
      return false;
    }
	}
  
  // Sends mail with the users new password
  protected function sendNewPasswordMail($mail, $password){
    
    if(Tools::mailSupport()){
      $subject = " RAS-blogg nytt passord";

      $message = "Nytt passord
      <br/>
      Du eller noen andre har benyttet denne mailadressen for å be om nytt passord til denne brukeren på RAS-bloggen.
      <br/>
      Ditt nye passord er: $password
      <br/></br>
      Vennlig Hilsen,
      <br/> 
      RAS-blogg Team";

      $headers = 'From: noreply@ RASblog.com' . "\r\n" .
      'Reply-To: noreply@RASblog.com' . "\r\n" .
      'Content-type: text/html; charset=iso-8859-1\n'.
      'X-Mailer: PHP/' . phpversion();
                       
      mail($mail, $subject, $message, $headers);
    }                   
  }

  // Checks DB if mail user is registered
  private function checkMail($mail){
    global $database;
    
    $query = "SELECT * FROM User WHERE email =:mail;";
    $stmt = $database->prepare($query);      
    $stmt->bindParam(':mail', $mail);        
    $stmt->setFetchMode( PDO::FETCH_CLASS, 'User');
    $stmt->execute();
    $user = $stmt->fetch( PDO::FETCH_CLASS );

    if($stmt->errorCode() != '00000' || $user!=true) {
        $error = $stmt->errorInfo('Feil mailadresse');                                          
        throw new Exception($error[2]);
    } else {
      return $user;
    }
  } // end checkMail

}
?>

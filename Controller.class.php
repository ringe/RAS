<?php
// The Controller class handles all actions
//
// The run_actions() method loads the Router to determine what function
// to run for the request URL, for instance: '/post/34'
//
// Then the Router loads the correct function. Currently, there is an
// automatic mapping from the request URL to a corresponding method in
// the Controller class. If not, the Router loads the do404() method.
//
// The function should be written like this:
//    public function urlname($params) {
//        if ($params fit with requirements) {
//          $vars = array('title' => "Ny side", 'object' => Object::find($id));
//          $vars['message'] = "Dette er en ny side for Object klassen."
//          new View('object.tpl', $vars);
//          return true;
//        } else
//          return false;
//    }
//
// It is important to return true or false for the Router to operate
// correctly. The if requirement defines if the $params are valid or not.
// Then, we have to load the Smarty instance and use it as usual.
//
class Controller
{
  
 // When the Controller loads, enable database connetion
  public function __construct(){
	}
  
  // Load the Posts from given year and month
  public function archive($year, $month) {
    if(Tools::illegalRequest()) return false;
    if(func_num_args() > 2) return false; // Check that the function is called with correct number of arguments
    if(!is_numeric($year)) return false; // Fail unless the year is numeric
    if(isset($month) and Tools::falseMonth($month)) return false; // Fail unless the month is valid
    if(is_numeric($month) and !(strlen($month) <= 2)) { // Fail if month is numeric and more than two digits
			return false;
    } else {
    	if(is_numeric($month)) $month = Tools::months($month);
		}

	  $vars = array('posts' => Post::getOld($year, $month), 'title' => 'Arkivet');
	  $vars['yr'] = $year;
	  if(is_string($month)) $vars['month'] = Tools::monthName($month);
	  new View('archive.tpl', $vars);
	  return true;
  }
        
  // Load all Posts for the frontpage, doesn't take any params
  public function posts() {
  		if(Tools::illegalRequest()) return false;
    	if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments

      $posts = Post::getNewest(3);
      new View('frontpage.tpl', array('posts'=>$posts, 'title'=>'Welcome'));        
      return true;
  }

  // Receive a comment POST request, discard alle others
  public function comment(){
  	if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            Comment::create($_POST);
            Router::redirect("post/" . $_POST['post_id']);
        } catch (Exception $e) {
            $vars['error'] = $e->getMessage();
            $vars['commentform'] = $_POST;
            new View('comment.tpl', $vars);
        }
        return true;
    } else {
        return false;
    }
  }	
  
  // Load the Post with the given id
  public function post($id=0) {
    if (func_num_args() != 1) return false; // Check that the function is called with correct number of arguments
		
    
	    if($id != "new") {
	      if (!is_numeric($id)) return false; // Fail unless the id is numeric
	    } else { // New Post
				if($_SESSION['auth']=='true' && $_SESSION['id'] == '1'){ // Stops people from using direct link, when not logged in
	      	if($_SERVER['REQUEST_METHOD'] == 'POST') {
	        	try {
			            $post_id = Post::create($_POST);
                  Router::redirect("post/" . $post_id);
                  
	       				} catch (Exception $e) {
					      	$vars['error'] = $e->getMessage();
					        $vars['postform'] = $_POST;
        					new View('posting.tpl', $vars);
	        			}
	        			return true;
	      	} else {
			        new View('posting.tpl', array('title' => "Opprette ny post"));
			        return true;
			    }
			  }else {
	    		Router::goHome();
	    		return false;
			  }
			} // new post end
			
    $post = Post::find($id);
    if (!$post) return false; // With no Post instance, we can't go on
    $post->counter();
		$_SESSION['postid'] = $id;
		
    new View('post.tpl', array('post' => $post));
    return true;
  }
	
	// Function to delete comments
  public function deleteComment($commentId){
				if($_SESSION['auth']=='true' && $_SESSION['id']=='1'){
					Comment::delete($commentId);
          Router::redirect("post/" . $_SESSION['postid']);
				}else {
	    		Router::goHome();
	    		return false;
				}
		
  }
  
  // Log out by destroying the session
  public function destroy(){
		if($_SESSION['auth']=='true') { session_destroy(); }
    Router::goHome();
    return true;
  }

  // Upload function, checks if the file type is approved and if the file size is not too big
  public function upload(){
		if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
	if($_SESSION['auth']=='true' && $_SESSION['id'] == '1'){
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
					$files=array();
					$fdata=$_FILES['upload'];
					$post_id=$_POST['post_id'];
					$finalDir= "uploads/$post_id/";
					
					if(is_array($fdata['name'])){
						 for($i=0;$i<count($fdata['name']);++$i){
							  $files[]=array(
								   'name'    =>$fdata['name'][$i],
								   'tmp_name'=>$fdata['tmp_name'][$i],
							  );
						 }
					} else $files[]=$fdata;
					
					$i = 0;
				  foreach($files as $file) { // each uploaded file
							try {
									$error = $_FILES["upload"]["error"][$i];
									if ($errors[$i] != 0) throw new Exception(Tools::file_upload_error_message($errors[$i]));
	
									
									// Sets all the accepded file formats.
									$accepted_filetypes = array('image/gif','image/jpg','image/jpeg','image/pjpeg','application/msword','application/pdf', 
									'application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/x-pdf','application/vnd.oasis.opendocument.text');                                                     
									// Checks if the uploaded file is of a approved format                 
								  if(!in_array($_FILES["upload"]["type"][$i], $accepted_filetypes)) {
										throw new Exception("Ikke godkjent filtype! Godkjente filtyper: .gif, .jpeg, .doc, .pdf, .docx, .odt");
									}
								  
								  // Checks if the uploaded file is of a approved size    
								  //var_dump($_FILES["upload"]["size"][$i]);
								  if(($_FILES["upload"]["size"][$i] > 5242880)){
										throw new Exception("Filen din er for stor! Filen er st&oslash;rre enn 5MB!");
									}
									// Checks if the uploaded file already is uploaded at the post    
									if (file_exists("uploads/$post_id/" . $file["name"])) throw new Exception($file["name"] . " fins allerede. ");
										
							} catch (Exception $e){
									$vars = array('error' => $e->getMessage());
						      new View('upload.tpl', $vars);
						      return true;
							}
							// Checks if the folder for the post exists, if not it creates the folder
							if (!file_exists($finalDir)) @mkdir($finalDir);
							
							move_uploaded_file($file["tmp_name"], "uploads/$post_id/" . $file["name"]);
              Router::redirect("post/$post_id");
//							echo "Stored in: " . "uploads/$post_id/" . $file["name"];

							$i++;
					} // each uploaded file end
					
		} else { // POST REQUEST end
				$posts=Post::getAll();
				new View('upload.tpl',array('posts'=>$posts));
		} // other REQUEST end
		
		return true; // Router feedback
  } // upload function end
}
  // Download a file with the given name, from the given Post.
  public function get($name, $id) {
    if(Tools::illegalRequest()) return false;
  	if(func_num_args() != 2) return false; // Check that the function is called with correct number of arguments
		if (isset($_SESSION['auth'])){
			$file = "uploads/$id/$name";
      if (file_exists($file)) {
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename='.basename($file));
          header('Content-Transfer-Encoding: binary');
          header('Expires: 0');
          header('Cache-Control: no-cache');
          header('Pragma: no-cache');
          header('Content-Length: ' . filesize($file));
          ob_clean();
          flush();
          readfile($file);
          return true;
      }
	}
    return false;
  } //get

  // Search for content
  public function search() {
  	if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
    if(Tools::illegalRequest(array("keywords"))) return false;
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      new View('search.tpl', array('hits' => Search::find($_POST['keywords'])));
      return true;
    }
    new View('search.tpl');
    return true;
  }
  
  // Login, calls to function in User class, with $_POST as paramether
  public function login() {
    if(Tools::illegalRequest(array("username", "password"))) return false;
  	if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
			try{
				$user = User::loginCheck();
				$_SESSION['auth'] = true;
				$_SESSION['id'] = $user->id;
				$_SESSION['mail'] = $user->email;
        Router::goHome();
			} catch(Exception $e) {
					$vars['error'] = $e->getMessage();
          new View('login.tpl', $vars);
      }
		}else{
    	new View('login.tpl'); 
    }
		return true;
  }
  
  // Gives the user a new random password, and sends it to the registered mail
  public function newpassword(){
		if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mail'])) {
      try {                        
          $a=User::newPassword($_POST['mail']);
          //var_dump($a);
      } catch (Exception $e){
          $vars = array('error' => $e->getMessage(), 'userform' => $_POST['mail']);
          new View('forgotpassword.tpl', $vars);
          return true;
      }
    }else{                   
      new View('forgotpassword.tpl');
      return true;
    }
  } // end newpassword

	//Changing information on user, password for now. And redirects to the front page
  public function changeInfo(){
    if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
			try {
			  	User::changeInfo($_POST);
			} catch (Exception $e){
					$vars = array('error' => $e->getMessage(), 'userform' => $_POST);
          new View('changeInfo.tpl', $vars);
			}
			new View('redirect.tpl');
			return true;
    }else {
			new View('changeInfo.tpl');
			return true;
    }
    
	} // end change user info

  // Register new user
  public function register(){
    if (func_num_args() != 0) return false; // Check that the function is called with correct number of arguments
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      try {
          new User($_POST);
          Router::redirect("login");
      } catch (Exception $e) {
          $vars = array('error' => $e->getMessage(), 'userform' => $_POST);
          new View('registration.tpl', $vars);
      }
      return true;
    } else {
      new View('registration.tpl');
      return true;
    }
    return true;
  } // register end
  
  // Verify activation key (activate registered user)
  public function activate($activationkey=null) {
  	if(!is_null($activationkey) and strlen($activationkey) != 14) return false;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      //var_dump($_POST);
        Router::redirect("activate/".$_POST['activationkey']);
        return true;
    } else {
        if(is_null($activationkey)) {
            new View('activation.tpl');
            return true;
        } else {
            try {
		          User::activate($activationkey);
              Router::redirect("login");
		          return true;
            } catch (Exception $e) {
              new View('activation.tpl', array('error' => $e->getMessage()));
              return true;
            }
        }
    }
  }

  // Send a 404 header and show the 404 page
  public function do404() {
    header("HTTP/1.0 404 Not Found");
    new View('404.tpl');
  }
  
  // Destruct the Controller: Select the global database object and disconnect
  function __destruct() {
    global $database;
    $database = null;
  }
  
}
?>

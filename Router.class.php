<?php
// The Router class is responsible for routing incoming HTTP requests to 
// matching methods of the Controller, and feed the raw URL input as arguments.
// 
// The global Controller object is loaded and fed with an array of variables
// parsed from the incoming HTTP request URL, after verifying that there is
// a method that matches the request.
// 
// In other words, we have an automagic mapping from the request URL to the
// corresponding method in the Controller class.
//
// If there is no match, the Router loads the do404() method of the Controller.
//
class Router {

  // Figure out what action (Controller method) to run. This is where we set special requirements for
  // the routing.
  public function mapAndRun() {
  	$request = self::parse_url();

    // We view the first part of the requested URL as our action
    $action = array_shift($request);
    // The default parameters is the rest of the request
    $params = $request;

    // If the action seeked is a four-digit number, use the archive action in the Controller
    if (preg_match('/^\d{4}$/', $action)) {
    	if (count($request) <= 1) { // The remaining request can only contain one parameter
      	$params = array($action, $request[0]); // Modify the params to fit the archive action
      	$action = "archive";
			} else $action = "fail"; // Else fail (use nonexistent Controller action here)
    }

    // If no action requested, do posts (the index action)
    if (empty($action)) $action = "posts";

    // Run the Controller method
    self::runMethod($action, $params);
  }

  // Redirect to given RAS destination
  public function redirect($dest="") { 
    header("Location: ". Tools::url($dest));
  }

  // Go to the Home page
  public function goHome() { self::redirect(); }

  // Check that the browser is Google Chrome.
  public static function chrome_or_die() {
	  if(isset($_SERVER['HTTP_USER_AGENT'])) $browser = $_SERVER['HTTP_USER_AGENT'];
	  if(preg_match('/w3c/i', $browser)) return;
    if(!preg_match('/chrome/i', $browser)) die("You don't get to enjoy this blog without <a href='http://www.google.no/chrome'>Google Chrome</a>.");
  }

  // Run the given Controller action with the given parameters
	private function runMethod($action, $params) {
    global $controller; // Get the Controller instance
    if (method_exists('Controller', $action)) { // Verify the action corresponds with a Controller method, or do 404
      if (!(call_user_func_array(array($controller, $action), $params))) // Verify the action was successfully performed, or do 404
				$controller->do404();
    } else { // Else, 404
      $controller->do404();
    }
	}

  // Get the current requested url as an array
  private function parse_url() {
    return array_filter(preg_split("/\//", $_REQUEST['url']));
  }

}
?>

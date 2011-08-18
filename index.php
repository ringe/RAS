<?php
  // Copyright 2011 RAS: Runar Ingebrigtsen, Alexander Wold og Stig Jakobsen

	// Starting session
	session_start();
    
  // Include the Router class for URL routing
  include 'Router.class.php'; // Only requirement to kick out other browsers
  Router::chrome_or_die(); // We only accept Google Chrome for this blog

  // Prepare our various Tools
  include 'Tools.class.php';

  // Include Model definitions
  include 'Model.class.php';
  include 'Comment.class.php';
  include 'Post.class.php';
  include 'User.class.php';
  include 'Search.class.php';  

  // Include View definitions
  include 'libs/Smarty.class.php';
  include 'View.class.php';
  
  // Include and load Controller and Tools 
  include 'Controller.class.php';
  new Tools;                    // Prepare Tools
  $controller = new Controller; // Load Controller
  
  // Let the Router do its job.
  Router::mapAndRun();

?>

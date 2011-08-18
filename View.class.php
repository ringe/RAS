<?php
// The View class is responsible for all interaction with the users. Our View class builds
// upon the Smarty template system to combine data and HTML in a clean way.
// The View is loaded by creating a new View instance for every View. The responsibility for
// loading a View and sending it to the user lies with the Controller.
// All data loading should thus, in general, happen in the Controller. There are exceptions
// with special cases.
class View {
  private $smarty;

  // Create a new View and run the Smarty display of the given template
  public function __construct($template, $variables = array()) {
      if(is_array($template)) throw new Exception("The template name have to be a string.");
      
      // Get Smarty
      $this->smarty = new Smarty;         					              // Prepare template rendering
      $this->smarty->plugins_dir[] = 'libs/rasplugins';	          // Load RAS Smarty plugins
      $this->smarty->assign('root', Tools::rootURL());            // We want absolute URLs
      $this->smarty->assign('apache', Tools::canRewrite());    // Pretty URLs or not?

      // Set Smarty dynamic Smarty variables
      foreach($variables as $name=>$value) {
      	$this->smarty->assign($name, $value);
      }
 			
 			// Archive submenu (special case)
 			$this->smarty->assign('years', Post::getYears());
      
      // Render the Smarty template
      try {
        $this->smarty->display($template);
      } catch (SmartyException $e) {
        echo $e->getMessage()."<br/>";
      }
  }

  // Can we send mail?
  public function mailSupport() {
    return Tools::toBeOrNotToBe();
  }

}
?>

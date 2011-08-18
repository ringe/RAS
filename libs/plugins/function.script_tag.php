<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.linktag.php
 * Type:     function
 * Name:     script_tag
 * Purpose:  insert a script tag
 * -------------------------------------------------------------
 */
function smarty_function_script_tag($params, &$smarty) {
  $root = $smarty->getTemplateVars('root');
  return "<script type='text/javascript' src='". $root. "javascript/". $params['file']. "'></script>";
}
?>

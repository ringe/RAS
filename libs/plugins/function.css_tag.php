<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.css_tag.php
 * Type:     function
 * Name:     css_tag
 * Purpose:  return a css link tag
 * -------------------------------------------------------------
 */
function smarty_function_css_tag($params, &$smarty) {
  $root = $smarty->getTemplateVars('root');
  return "<link rel='stylesheet' href='". $root. "css/". $params['file']. "' type='text/css' />";
}
?>

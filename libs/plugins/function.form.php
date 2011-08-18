<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.form.php
 * Type:     function
 * Name:     form
 * Purpose:  return a form tag
 * Example:  {form action=post method=GET}
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.url.php');
function smarty_function_form($params, &$smarty) {
	if(!isset($params['method'])) $params['method'] = "POST";
  if(isset($params['action'])) {
    $params['link'] = $params['action'];
    $params['action'] = smarty_function_url($params, &$smarty);
    unset($params['link']);
  }

  $tag = array("<form");
  foreach ($params as $key=>$val) {
    $tag[] = "$key='$val'";
  }
  $tag[] = ">"; 
 
  return join(" ", $tag);
}
?>

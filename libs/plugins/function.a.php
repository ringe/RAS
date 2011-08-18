<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.a.php
 * Type:     function
 * Name:     a
 * Purpose:  insert a link tag
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.url.php');
function smarty_function_a($params, &$smarty) {
  $tag = "<a href='" . smarty_function_url($params, &$smarty);
  if(isset($params['title'])) $tag.= "' title='" . $params['title'];
  $tag.= "'>";
  return $tag;
}
?>

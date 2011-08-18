<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     block.a.php
 * Type:     block
 * Name:     a
 * Purpose:  insert a link tag
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.url.php');
function smarty_block_a($params, $content, $template, &$repeat) {
	if ($open) {
			$tag = "<a href='" . smarty_function_url(array('link' => $params['href']), &$smarty);
		  if(isset($params['title'])) $tag.= "' title='" . $params['title'];
		  $tag.= "'>";
		  return $tag;
	} else {
			return "</a>";
	}
}
?>
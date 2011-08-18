<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.link_to.php
 * Type:     function
 * Name:     link_to
 * Purpose:  insert a link tag to a given object
 * Example:	 {link_to object=$post}{$post->title}</a>
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.a.php');
function smarty_function_link_to($params, &$smarty) {
	$post = $params['post'];
	$method = strtolower(get_class($post));
  $params['href'] = $method ."/". $post->id;
  $tag = smarty_function_a($params, &$smarty);
  if(!isset($params['title'])) {
  	$tag.= $post->title;
	} else $tag.= $params['title'];
	$tag .= '</a>';
  return $tag;
}
?>

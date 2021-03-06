<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.attachments.php
 * Type:     function
 * Name:     attachments
 * Purpose:  insert a list of links to the attachments of a Post
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.url.php');
function smarty_function_attachments($params, &$smarty) {
  if(!isset($params['post_id'])) throw new Exception("Smarty function attachments missing 'post_id' parameter");
  $id = $params['post_id'];

	$folder = "uploads/$id"; 

	if(!file_exists($folder)) return;
	# Making an array containing the list of files: 
  $files = scandir($folder);

  $list=array();
	foreach ($files as $file) {
    if($file != '.' and $file != '..') {
        $params['href'] = "get/$file/$id";    //TODO URL encode
        $list[] = "<li><a href='" . smarty_function_url($params, &$smarty) ."'>". $file."</a></li>";
    }
	}

  if(empty($list)) return; // return empty if no attachments

  array_unshift($list, "<ul>");
  $list[] = "</ul>";
  $smarty->assign('list', join('', $list));
  $smarty->display('attachments.tpl');
}
?>

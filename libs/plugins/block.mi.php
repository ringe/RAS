<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.mi.php
 * Type:     function
 * Name:     mi
 * Purpose:  insert a menu element
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.a.php');
function smarty_block_mi($params, $content, &$smarty, &$repeat) {
	if (is_null($content)) {
        return;
  } else {
			$tag = "<a href='" . smarty_function_url($params, &$smarty);
			if(isset($params['title'])) $tag.= "' title='" . $params['title'];
			$tag.= "'>";
      return "<li>".$tag.$content."</a></li>";
  }
}
?>
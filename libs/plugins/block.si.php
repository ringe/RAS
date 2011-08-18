<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.mi.php
 * Type:     function
 * Name:     si
 * Purpose:  insert a menu element
 * -------------------------------------------------------------
 */
require_once(SMARTY_PLUGINS_DIR . 'function.a.php');
function smarty_block_si($params, $content, &$smarty, &$repeat) {
	if (is_null($content)) {
        return;
  } else {
			$tag = "<a href='" . smarty_function_url($params, &$smarty);
			if(isset($params['title'])) $tag.= "' title='" . $params['title'];
			$tag.= "'>";
      return "<li>".$tag.$params['name']."</a>".$content."</li>";
  }
}
?>
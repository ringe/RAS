<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.menu.php
 * Type:     function
 * Name:     submenu
 * Purpose:  insert a submenu
 * -------------------------------------------------------------
 */
function smarty_block_submenu($params, $content, &$smarty, &$repeat) {
	if (is_null($content)) {
        return;
  } else {
			$start = $params['main_element']."<ul>";
      return $start.$content."</ul>";
  }
}
?>
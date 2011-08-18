<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.menu.php
 * Type:     function
 * Name:     menu
 * Purpose:  insert a menu
 * -------------------------------------------------------------
 */
function smarty_block_menu($params, $content, &$smarty, &$repeat) {
	if (is_null($content)) {
        return;
  } else {
			$start = "<section id='menu'><ul>";
      return $start.$content."</ul></section>";
  }
}
?>
<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.a.php
 * Type:     function
 * Name:     p
 * Purpose:  plurify a norwegian word based on a number
 * Example:	 {p s="kommentar" n=2}
 * -------------------------------------------------------------
 */
function smarty_function_p($params, &$smarty) {
	$string = $params['s'];
	if ($params['n'] > 1) $string.="er";
	if ($params['n'] ==0) {
		$string = "Ingen ".$params['s'].="er";
	} else {
		$string = "".$params['n'] ." ".$string;
	}
  return $string;
}
?>

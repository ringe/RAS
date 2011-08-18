<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.url.php
 * Type:     function
 * Name:     url
 * Purpose:  return the absolute URL for a href
 * Example:	 {url link=comment}
 * Example2: {url href=comment}
 * -------------------------------------------------------------
 */
function smarty_function_url($params, &$smarty) {
    $root = $smarty->getTemplateVars('root');
    $apache = $smarty->getTemplateVars('apache');
    if(isset($params['href'])) $params['link'] = $params['href'];
    if(!isset($params['link'])) $params['link'] = "";
    
    if($apache==true or empty($params['link'])) {
      $link = $root. $params['link'];
    } else {
      $link = $root. "index.php?url=" . $params['link'];
    }
    return "$link";
}
?>

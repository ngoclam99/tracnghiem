<?php

/**
 * @author honestphan
 * @copyright 2011
 */
defined('SLA') or die('Restricted access');
 
function make_alias($str,$ex){
    $str = trim($str);
    if(!$str) return false;
    $unicode = array(
    'a'=>'�|�|?|�|?|a|?|?|?|?|?|�|?|?|?|?|?',
    'd'=>'d',
    'e'=>'�|�|?|?|?|�|?|?|?|?|?',
    'i'=>'�|�|?|i|?',
    'o'=>'�|�|?|�|?|�|?|?|?|?|?|o|?|?|?|?|?',
    'u'=>'�|�|?|u|?|u|?|?|?|?|?',
    'y'=>'�|?|?|?|?',
    '-'=>' ',
    );
    foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
    //$alias = $str + "." + $ex;
    return $str;
}

?>
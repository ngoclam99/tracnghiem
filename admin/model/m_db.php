<?php

/**
 * @author nbinh88@gmail.com
 * @copyright 2013
 */
 // no direct access


function dbconnect (){
    static $connect= null;
    if($connect == null){
        $connect = mysql_connect('localhost','root','123456');
        mysql_select_db('tracnghiem1');
        mysql_query("SET NAMES 'utf8'", $connect);
		mysql_query("SET CHARACTER SET utf8", $connect);
		mysql_query("SET CHARACTER_SET_CONNECTION=utf8", $connect);
		mysql_query("SET SQL_MODE = ''", $connect);
    }
     return $connect;
}

function escape($value){
    return mysql_real_escape_string($value);
}
function filter($keywords){
   $keywords = str_replace("_","\_",$keywords);
   $keywords = str_replace("%","\%",$keywords);
   return $keywords;
}

function checkrangbuocdulieu($table,$column,$param){
    $sql = "SELECT * FROM '".$table."' where  '".$column."' = '".escape($param)."'";
    $result = mysql_query($sql, dbconnect());
    return mysql_num_rows($result);
}

function write_logs($action, $action_name, $agent, $aip, $this_time){
    mysql_query("insert nhat_ky set 
    user_id = '".$_SESSION['user_id']."',
    user_name = '". $_SESSION['tai_khoan']."',
    user_action = '".$action."',
	agent = '".$agent."',
	action = '".$action_name."',
	action_ip = '".$aip."',
    action_time = '".$this_time."'", 
    dbconnect());
}

function get_list_user_modules($user_id, $start, $limit)
    {
    $local_list = mysql_query("SELECT * FROM phan_quyen
    where user_id = '".$user_id."'  group by module_name
    LIMIT ".(int)$start.",".(int)$limit."
    ", dbconnect());
    $result = array();
    while ($local = mysql_fetch_array($local_list)){
        $result []= $local;
    }
    return $result;
    }


function get_list_action_module($user_id, $module_name){
	
	$local_list = mysql_query("SELECT * FROM phan_quyen
    where user_id = '".$user_id."' and module_name = '".$module_name."'
    ", dbconnect());
    $result = array();
    while ($local = mysql_fetch_array($local_list)){
        $result []= $local;
    }
    return $result;
	
}	

function pr($a)
{
    echo '<pre>';
    echo print_r($a, true);
    echo '</pre>';
    exit();
}

function clean_text($str){
    if(!$str) return false;
    $unicode = array(
        'a'=>array('á','à','ả','ã','ạ','ă','ắ','ặ','ằ','ẳ','ẵ','â','ấ','ầ','ẩ','ẫ','ậ'),
        'A'=>array('Á','À','Ả','Ã','Ạ','Ă','Ắ','Ặ','Ằ','Ẳ','Ẵ','Â','Ấ','Ầ','Ẩ','Ẫ','Ậ'),
        'd'=>array('đ'),
        'D'=>array('Đ'),
        'e'=>array('é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ'),
        'E'=>array('É','È','Ẻ','Ẽ','Ẹ','Ê','Ế','Ề','Ể','Ễ','Ệ'),
        'i'=>array('í','ì','ỉ','ĩ','ị'),
        'I'=>array('Í','Ì','Ỉ','Ĩ','Ị'),
        'o'=>array('ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ'),
        'O'=>array('Ó','Ò','Ỏ','Õ','Ọ','Ô','Ố','Ồ','Ổ','Ỗ','Ộ','Ơ','Ớ','Ờ','Ở','Ỡ','Ợ'),
        'u'=>array('ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự'),
        'U'=>array('Ú','Ù','Ủ','Ũ','Ụ','Ư','Ứ','Ừ','Ử','Ữ','Ự'),
        'y'=>array('ý','ỳ','ỷ','ỹ','ỵ'),
        'Y'=>array('Ý','Ỳ','Ỷ','Ỹ','Ỵ')
    );
    foreach($unicode as $nonUnicode=>$uni){
        foreach($uni as $value)
            $str = @str_replace($value,$nonUnicode,$str);
        $str = preg_replace("/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/","-",$str);
        $str = preg_replace("/-+-/","-",$str);
        $str = preg_replace("/^\-+|\-+$/","",$str);
    }
    return strtolower($str);
}

?>

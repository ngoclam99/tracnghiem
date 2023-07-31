<?php

/**
 * @author huuvung
 * @copyright 2012
 */
 
defined('DSVH') or die('Restricted access');

function getListhuongdan($start,$limit)
    {
        $local_list = mysql_query("SELECT * FROM huong_dan order by
        id_huongdan desc
        limit ".(int)$start.",".(int)$limit."
        ", dbconnect());
        $result = array();
        while ($local = mysql_fetch_array($local_list)){
            $result []= $local;
        }
        return $result;
    }
    
 
 //lay du lieu mot tin tuc   theo id (ma tin tuc))
 
 function gethuongdan($id)
    {
    $pro = mysql_query("select * from huong_dan
    where id_huongdan = '".(int)$id."'", dbconnect());
    return mysql_fetch_array($pro);
    }
    
 //tinh tong ban ghi
 function totalhuongdan(){
   $result = mysql_query("select * from huong_dan",dbconnect());
   return mysql_num_rows($result);
   
}   


function luotxem($soluong,$ma){
    mysql_query("update huong_dan set luot_xem = '".$soluong."' 
           where id_huongdan  = '".$ma."'");
}



    
?>
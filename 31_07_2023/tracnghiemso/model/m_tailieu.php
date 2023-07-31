<?php

/**
 * @author huuvung
 * @copyright 2012
 */
 
defined('DSVH') or die('Restricted access');

function getListtailieu($start,$limit)
    {
        $local_list = mysql_query("SELECT * FROM tai_lieu order by
        id_tailieu desc
        limit ".(int)$start.",".(int)$limit."
        ", dbconnect());
        $result = array();
        while ($local = mysql_fetch_array($local_list)){
            $result []= $local;
        }
        return $result;
    }
    
 
 //lay du lieu mot tin tuc   theo id (ma tin tuc))
 
 function gettailieu($id)
    {
    $pro = mysql_query("select * from tai_lieu
    where id_tailieu = '".(int)$id."'", dbconnect());
    return mysql_fetch_array($pro);
    }
    
 //tinh tong ban ghi
 function totaltailieu(){
   $result = mysql_query("select * from tai_lieu",dbconnect());
   return mysql_num_rows($result);
   
}   


function luotxem($soluong,$ma){
    mysql_query("update tai_lieu set luot_xem_id = '".$soluong."' 
           where id_tailieu  = '".$ma."'");
}



    
?>
<?php

/**
 * @author ict.sonla.gov.vn
 * @copyright 2012
 */
//add can bo
function inserthuongdan($local){
    mysql_query("insert huong_dan set 
    tieu_de = '".clean($local['tieu_de'])."',
   
    noi_dung = '".$local['noi_dung']."',

    time = NOW()
    ",
    dbconnect());
}

function getListhuongdan($start, $limit)
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
    
//ham lay du lieu tu can bo
function gethuongdan($id)
    {
    $pro = mysql_query("select * from huong_dan where id_huongdan = '".(int)$id."'", dbconnect());
    return mysql_fetch_array($pro);
    }
//cap nhat trong thu muc sua
function edithuongdan($id, $local)
{
    mysql_query("update huong_dan
    set tieu_de ='".clean($local['tieu_de'])."',

    noi_dung = '".$local['noi_dung']."',

    time = NOW()
    where id_huongdan = '".(int)$id."'", 
    dbconnect());
}   
// xoa thu muc
 function delhuongdan($id){
    mysql_query("delete from huong_dan where id_huongdan= '".(int)$id."'", dbconnect());
}
// phan trang
 function totalhuongdan(){
   $result = mysql_query("select * from huong_dan",dbconnect());
    return mysql_num_rows($result);
}   
?>
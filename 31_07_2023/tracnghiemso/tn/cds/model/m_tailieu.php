<?php

/**
 * @author ict.sonla.gov.vn
 * @copyright 2012
 */
//add can bo
function inserttailieu($local){
    mysql_query("insert tai_lieu set 
    tieu_de = '".clean($local['tieu_de'])."',
	file = '".clean($local['file'])."',
	dung_luong = '".clean($local['dung_luong'])."',
	luot_xem_id = '".clean($local['luot_xem_id'])."',
    noi_dung = '".$local['noi_dung']."',
	
    time = NOW()
    ",
    dbconnect());
}

function getListtailieu1($start, $limit)
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
    
//ham lay du lieu tu can bo
function gettailieu($id)
    {
    $pro = mysql_query("select * from tai_lieu where id_tailieu = '".(int)$id."'", dbconnect());
    return mysql_fetch_array($pro);
    }
//cap nhat trong thu muc sua
function edittailieu($id, $local)
{
    mysql_query("update tai_lieu
    set tieu_de ='".clean($local['tieu_de'])."',
	file = '".clean($local['file'])."',
    noi_dung = '".$local['noi_dung']."',
	dung_luong = '".$local['dung_luong']."',
    edit_time = NOW()
    where id_tailieu = '".(int)$id."'", 
    dbconnect());
}   
// xoa thu muc
 function deltailieu($id){
    mysql_query("delete from tai_lieu where id_tailieu= '".(int)$id."'", dbconnect());
}
// phan trang
 function totaltailieu(){
   $result = mysql_query("select * from tai_lieu",dbconnect());
    return mysql_num_rows($result);
}   
?>
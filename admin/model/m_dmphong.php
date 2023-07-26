<?php

/**
 * @author ict.sonla.gov.vn
 * @copyright 2012
 */
//add can bo
function insertdmphong($local){
    mysql_query("insert danh_ba_dm set 
    tieu_de = '".$local['tieu_de']."',
    stt = '".$local['stt']."'",
    dbconnect());
}

function getListdm()
    {
    $local_list = mysql_query("SELECT * FROM danh_ba_dm ", dbconnect());
    $result = array();
    while ($local = mysql_fetch_array($local_list)){
        $result []= $local;
    }
    return $result;
    }
    
//ham lay du lieu tu can bo
function getdm($id)
    {
    $pro = mysql_query("select * from danh_ba_dm where id = '".(int)$id."'", dbconnect());
    return mysql_fetch_array($pro);
    }
//cap nhat trong thu muc sua
function editdm($id, $local)
{
    mysql_query("update danh_ba_dm set 
    tieu_de ='".$local['tieu_de']."',
    stt = '".$local['stt']."'
    where id = '".(int)$id."'", 
    dbconnect());
}   
// xoa thu muc
 function deldm($id){
    mysql_query("delete from danh_ba_dm where id= '".(int)$id."'", dbconnect());
}

function getListdmDT() {
    $local_list = mysql_query("SELECT * FROM dm_doituong ORDER BY id DESC", dbconnect());
    $result = array();
    while ($local = mysql_fetch_assoc($local_list)){
        $sql = mysql_query("SELECT * FROM doituong_chitiet WHERE id_doituong = " . $local['id'] . " ORDER BY id DESC", dbconnect());
        $arr_detail = array();
        while ($detail = mysql_fetch_assoc($sql)) {
            $arr_detail[] = $detail;
        }
        $local['detail'] = $arr_detail;
        $result[]= $local;
    }
    return $result;
}

 function deldmdonvi($id){
    mysql_query("delete from dm_doituong where id= '".(int)$id."'", dbconnect());
}

//add can bo
function insertdmdt($ten_donvi) {
    mysql_query("insert dm_doituong set 
    ten_donvi = '".$ten_donvi."'",
    dbconnect());
}

//add can bo
function capnhatdonvi($id, $ten_donvi) {
    mysql_query("update dm_doituong set 
    ten_donvi = '".$ten_donvi."' WHERE id = " . $id,
    dbconnect());
}

 function deldmdonvi_detail($id){
    mysql_query("delete from doituong_chitiet where id= '".(int)$id."'", dbconnect());
}

function insertdmdt_detail($title, $id){
    mysql_query("insert doituong_chitiet set 
    title = '".$title."',
    id_doituong = '".$id."'",
    dbconnect());
}

function capnhatdmdt_detail($title, $id){
    mysql_query("UPDATE doituong_chitiet set 
    title = '".$title."' WHERE id = " . $id,
    dbconnect());
}

function getListDVDetail($id) {
    $sql = mysql_query("SELECT * FROM doituong_chitiet WHERE id_doituong = " . $id . " ORDER BY id DESC", dbconnect());
    $arr_detail = array();
    while ($detail = mysql_fetch_assoc($sql)) {
        $arr_detail[] = $detail;
    }
    return $arr_detail;
}
?>

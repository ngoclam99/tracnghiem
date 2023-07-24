<?php

/**
 * @author ReDo
 * @copyright 2023
 */
include_once('classes/m_message.php');
include_once('m_db.php');


function LoadProvinces()
{  

    $local_list = mysql_query("SELECT code,full_name,default_pro
    FROM provinces  ORDER BY name", dbconnect());
    $msg = new Message();
    if($local_list){
        $result = array();
        while ($local = mysql_fetch_array($local_list)) {
            $result[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Load danh sách tỉnh thành thành công!";
        $msg->statusCode = 200;
        $msg->content = $result;
    }else{
        $msg->icon = "error";
        $msg->title = "Load danh sách tỉnh thành thất bại!";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
    // write_cache($cache_file, $msg);
        return $msg;
}

// Checks whether the page has been cached or not
function is_cached($file) {
    $cache_folder = "../assets/cache/";
    $cache_expires = 3600;
    $cachefile = $cache_folder . $file;
    $cachefile_created = (file_exists($cachefile)) ? @filemtime($cachefile) : 0;
    return ((time() - $cache_expires) < $cachefile_created);
}

// Reads from a cached file
function read_cache($file) {
    $cache_folder = "../assets/cache/";
    $cachefile = $cache_folder . $file;
    return file_get_contents($cachefile);
}

// Writes to a cached file
function write_cache($file, $out) {
    $cache_folder = "../assets/cache/";
    $cachefile = $cache_folder . $file;
    if(!file_exists($cachefile)){
        $fp = fopen($cachefile, "w") or die("Unable to open file!");
    }else{
        $fp = fopen($cachefile, "a") or die("Unable to open file!");
    }
    pr($fp);
    fwrite($fp, $out);
    fclose($fp);
}

function LoadDistrictsByPro($pro_code)
{
    $sql = "SELECT code,full_name FROM districts 
    WHERE province_code='".$pro_code."'
    ORDER BY name
    ";
    $local_list = mysql_query($sql, dbconnect());
    $msg = new Message();
    if($local_list){
        $result = array();
        while ($local = mysql_fetch_array($local_list)) {
            $result[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Load danh sách quận huyện thành công!";
        $msg->statusCode = 200;
        $msg->content = $result;
    }else{
        $msg->icon = "error";
        $msg->title = "Load danh sách quận huyện thất bại!";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
   
    return $msg;
}

function LoadWardsByDist($dist_code)
{
    $sql = "SELECT code,full_name FROM wards WHERE district_code='".$dist_code."'  ORDER BY name";
    $local_list = mysql_query($sql, dbconnect());
    $msg = new Message();
    if($local_list){
        $result = array();
        while ($local = mysql_fetch_array($local_list)) {
            $result[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Load danh sách xã phường thành công!";
        $msg->statusCode = 200;
        $msg->content = $result;
    }else{
        $msg->icon = "error";
        $msg->title = "Load danh sách xã phường thất bại!";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
   
    return $msg;
}

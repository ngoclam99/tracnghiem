<?php

/**
 * @author nbinh88@gmail.com
 * @copyright 2013
 */
if((!$_SESSION['admin'])){
    header('LOCATION: index.php?module=member&act=login');  
    exit();
}

//G?i t?p tin giao di?n trong thu m?c view/theme/template tuong ?ng
include('model/m_dmphong.php');

$page = $_GET['act'];

switch($page) {
    case 'list':
    $url ='index.php?module=dmdonvi&act=list';
    $list_dm = getListdmDT();
    // Xoá bài viết
    if ($id = $_GET['deleteid']) {
        deldmdonvi($id);
        header('LOCATION: ' . $url);  
        exit();
    }

    if ($_POST['addDVDT']) {
        $ten_donvi= $_POST['ten_donvi'];
        insertdmdt($ten_donvi);
        header('LOCATION: ' . $url);  
        exit();
    }

    if ($_POST['delete_detail']) {
        $id = $_POST['id'];
        deldmdonvi_detail($id);
        exit();
    }

    if ($_POST['addDetail']) {
        $title = $_POST['title'];
        $id_doituong = $_POST['id_doituong'];
        insertdmdt_detail($title, $id_doituong);
        $data = getListDVDetail($id_doituong );
        echo json_encode($data);
        exit();
    }

    require('view/template/dmdvduthi/list_dm.tpl');
    break;

    case 'add':
    require('view/template/dmdvduthi/list_dm.tpl');  
    break;
}

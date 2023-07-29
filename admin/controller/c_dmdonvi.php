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
            if ($_POST['addDVDT'] != 'themmoi') {
                capnhatdonvi($_POST['addDVDT'], $ten_donvi);
            } else {
                insertdmdt($ten_donvi);
            }
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
            if ($_POST['id_edit'] != 'themmoi') {
                capnhatdmdt_detail($title, $_POST['id_edit']);
            } else {
                insertdmdt_detail($title, $id_doituong);
            }
            $data = getListDVDetail($id_doituong);
            echo json_encode($data);
            exit();
        }

        if ($_POST['change_height']) {
            $new_height = $_POST['new_height'];
            $id_old = $_POST['id_old'];
            changeHeight($new_height, $id_old);
        }

        if ($_POST['change_height_ct']) {
            $new_height = $_POST['new_height'];
            $id_old = $_POST['id_old'];
            $id_dm = $_POST['id_dm'];
            changeHeightCT($new_height, $id_old, $id_dm);
            header("Content-Type: application/json");
            echo json_encode(loaddmdoituong_Ct($id_dm));
            exit();
        }

        require('view/template/dmdvduthi/list_dm.tpl');
        break;

    case 'add':
    require('view/template/dmdvduthi/list_dm.tpl');  
    break;
}

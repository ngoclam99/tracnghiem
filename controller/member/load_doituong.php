<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('../../model/m_db.php');
include('../../model/m_member.php');

if ($_POST['load_dt']) {
    $data = LoadDoiTuong();
    echo json_encode($data);
    exit();
}

if ($_POST['load_cuocthi']) {
    $data = LoadCuocThi();
    echo json_encode($data);
    exit();
}

if ($_POST['load_dt_chitiet']) {
    $id_dt = $_POST['id_doituong'];
    $data = LoadDoiTuongChiTiet($id_dt);
    echo json_encode($data);
    exit();
}

if ($_POST['load_current_time']) {
    $id = $_POST['id'];
    $data = LoadCurrentTime($id);
    echo ($data);
    exit();
}

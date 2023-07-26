<?php

/**
 * @author ReDo
 * @copyright 2023
 * Load danh sách câu hỏi từ các config của đề thi
 */
include('../../model/m_exam.php');
include_once('../../model/m_location.php');

if ($_POST['load_thongke']) {
	$id = $_POST['id_cuocthi'];
	$id_dt = $_POST['id_dt'];
	$id_dtct = $_POST['id_dtct'];
	$id_tinh = intval($_POST['id_tinh']);
	$id_huyen = intval($_POST['id_huyen']);
	$id_xa = intval($_POST['id_xa']);
	$data = getThongKeCuocThi($id, $id_dt, $id_dtct, $id_tinh, $id_huyen, $id_xa);
	if (sizeof($data) > 0) {
		echo json_encode($data); 
	} else {
		echo (""); 
	}
	exit();
}

if ($_POST['load_thongke_tong']) {
	$id = $_POST['id_cuocthi'];
	$id_dt = $_POST['id_dt'];
	$id_dtct = $_POST['id_dtct'];
	$id_tinh = intval($_POST['id_tinh']);
	$id_huyen = intval($_POST['id_huyen']);
	$id_xa = intval($_POST['id_xa']);
	$data = getThongKeTong($id, $id_dt, $id_dtct, $id_tinh, $id_huyen, $id_xa);
	if (sizeof($data) > 0) {
		echo json_encode($data); 
	} else {
		echo (""); 
	}
	exit();
}

if ($_POST['load_thongke_tong_tinh']) {
	$id = $_POST['id_cuocthi'];
	$id_dt = $_POST['id_dt'];
	$id_dtct = $_POST['id_dtct'];
	$id_tinh = intval($_POST['id_tinh']);
	$id_huyen = intval($_POST['id_huyen']);
	$id_xa = intval($_POST['id_xa']);
	$data = getThongKeTongTinh($id, $id_dt, $id_dtct, $id_tinh, $id_huyen, $id_xa);
	if (sizeof($data) > 0) {
		echo json_encode($data); 
	} else {
		echo (""); 
	}
	exit();
}

if ($_POST['loadtinh']) {
	$provinces = LoadProvinces();
	header("Content-Type: application/json");
	echo json_encode($provinces); 
}

if ($_GET['load_lichsuthi']) {
	$page = intval($_GET['page']);
	$perpage = 200;
	$start = 0;
    if ($page > 0) {
        $start = ($page-1) * $perpage;
        if($start == 0){
            $stt = 1;
        } else{
            $stt = $page + 1;
        }
    } else {
        $page = 1;
        $stt = 1;
    }

	$id = $_GET['id_cuocthi'];
	$id_dt = $_GET['id_dt'];
	$id_dtct = $_GET['id_dtct'];
	$username = $_GET['username'];
	$row_total = CountToalgetLichSuThi($id, $id_dt, $id_dtct, $username, $start, $perpage);
    $sum_record = $row_total['tong'];
    $total_page = CEIL($sum_record/$perpage);
	$data = getLichSuThi($id, $id_dt, $id_dtct, $username, $page, $perpage, $total_page, $start);
	$data['tongnguoithi'] = $row_total['tongthisinh'];
	$data['tongluotthi'] = $row_total['tongluothi'];
	if (sizeof($data) > 0) {
		echo json_encode($data); 
	} else {
		echo (""); 
	}
	exit();
}

if ($_GET['load_lichsuthi_tong']) {
	$page = intval($_GET['page']);
	$perpage = 200;
	$start = 0;
    if ($page > 0) {
        $start = ($page-1) * $perpage;
        if($start == 0){
            $stt = 1;
        } else{
            $stt = $page + 1;
        }
    } else {
        $page = 1;
        $stt = 1;
    }

	$id = $_GET['id_cuocthi'];
	$id_dt = $_GET['id_dt'];
	$id_dtct = $_GET['id_dtct'];
	$username = $_GET['username'];
	$row_total = CountToalgetLichSuThi_Tong($id, $id_dt, $id_dtct, $username, $start, $perpage);
    $sum_record = $row_total['tong'];
    $total_page = CEIL($sum_record/$perpage);
	$data = getLichSuThi_Tong($id, $id_dt, $id_dtct, $username, $page, $perpage, $total_page, $start);
	$data['tongnguoithi'] = $row_total['tongthisinh'];
	$data['tongluotthi'] = $row_total['tongluothi'];
	if (sizeof($data) > 0) {
		echo json_encode($data); 
	} else {
		echo (""); 
	}
	exit();
}


?>

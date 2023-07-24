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
	$data = getThongKeCuocThi($id, $id_dt, $id_dtct);
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

?>

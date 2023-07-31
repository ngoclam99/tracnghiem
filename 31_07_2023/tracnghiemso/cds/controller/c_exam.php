<?php

/**
 * @author ReDo
 * @copyright 2023
 */
if ((!$_SESSION['admin'])) {
	header('LOCATION: index.php?module=member&act=login');
	exit();
}
//G?i t?p tin giao di?n trong thu m?c view/theme/template tuong ?ng
include('model/m_exam.php');
$page = $_GET['act'];
switch ($page) {
	case 'list':
		require('view/template/exam/index.tpl');
		break;
	case 'history':
		require('view/template/exam/history.tpl');
		break;
	case 'history-detail':
		require('view/template/exam/history-detail.tpl');
		break;

	case 'history-sum':
		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}


		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/history-sum.tpl');
		break;

	case 'report-statistic':
		require('view/template/exam/report-statistic.tpl');
		break;

	case 'history-total':
		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}


		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/history-total.tpl');
		break;
		
	case 'report-statistic-new':
		if ($_POST['xuatexcel']) {
			$id = $_POST['selectpicker'];
			$id_dt = $_POST['id_dt'];
			$id_dtct = $_POST['id_dtct'];
			$id_tinh = intval($_POST['id_tinh']);
			$id_huyen = intval($_POST['id_huyen']);
			$id_xa = intval($_POST['id_xa']);
			$data = getThongKeCuocThiThongKe($id, $id_dt, $id_dtct, $id_tinh, $id_huyen, $id_xa);
			xuatExcelCuocThi($data);
		}

		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}
		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/report-statistic-new.tpl');
		break;

	case 'report-statistic-total':
		if ($_POST['xuatexcel']) {
			$id = $_POST['selectpicker'];
			$id_dt = $_POST['id_dt'];
			$id_dtct = $_POST['id_dtct'];
			$data = getThongKeCuocThi($id, $id_dt, $id_dtct);
			xuatExcelCuocThi($data);
		}

		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}
		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/report-statistic-total.tpl');
		break;

	case 'report-statistic-dt':
		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}
		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/report-statistic-dt.tpl');
		break;

	case 'report-statistic-tinh':
		if ($_POST['load_dt']) {
		    $data = LoadDoiTuong();
		    echo json_encode($data);
		    exit();
		}
		if ($_POST['load_dt_chitiet']) {
		    $id_dt = $_POST['id_doituong'];
		    $data = LoadDoiTuongChiTiet($id_dt);
		    echo json_encode($data);
		    exit();
		}
		require('view/template/exam/report-statistic-tinh.tpl');
		break;
}

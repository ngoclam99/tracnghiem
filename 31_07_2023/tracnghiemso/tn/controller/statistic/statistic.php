<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('../../model/m_exam.php');

if ($_POST['load_statisc']) {
    $id = intval($_POST['id_dt']);
    $id_cuocthi = intval($_POST['id_cuocthi']);
    $getDoiTuong = getDoiTuong($id, $id_cuocthi);
}

echo json_encode($getDoiTuong);

<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('../../model/m_exam.php');

if ($_POST['load_statisc']) {
    $id = intval($_POST['id_dt']);
    $getDoiTuong = getDoiTuong($id);
}

echo json_encode($getDoiTuong);

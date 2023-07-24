<?php
    include('../../model/m_exam.php');
    if ($_POST['load_dt']) {
        $data = LoadDoiTuong();
        echo json_encode($data);
        exit();
    }
    $page = $_GET['page'];
    $search = $_GET['search'];
    $pageSize = $_GET['pageSize'];
    $exams = $_GET['exams'];
    $workplaces = $_GET['workplaces'];
    $id_doituong = $_GET['id_doituong'];
    $result = History($page,$search,$pageSize,$workplaces,$exams, $id_doituong);

    header("Content-Type: application/json");
    echo json_encode($result);
?>

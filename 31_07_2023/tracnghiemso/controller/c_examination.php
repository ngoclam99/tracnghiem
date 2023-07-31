<?php
session_start();
if(!isset($_SESSION['profile'])){
    header('LOCATION: index.php?module=member&act=login');  
    exit();
}

include('model/m_exam.php');

$action = $_GET['act'];
switch ($action) {
    case "index":
        require('view/examination/index.tpl');
        break;
    case "do-exam":
        $id = $_GET['id'];
        $check_id = checkExams($id);
        if (empty($check_id)) {
            header("Location: /");
        }
        require('view/examination/do-exam.tpl');
        break;
    case "history":
        require('view/examination/history.tpl');
        break;
    case "history-detail":
        require('view/examination/history-detail.tpl');
        break;
}

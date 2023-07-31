<?php
include_once('../../model/m_member.php');

$search = $_GET['search'];
$pageSize = 300;
$wp = $_GET['wp'];
$page = intval($_GET['page']);
$perpage = $pageSize;
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
$row_total = CountMember($id, $id_dt, $id_dtct, $username);
$sum_record = $row_total['tong'];
$total_page = CEIL($sum_record/$perpage);
$result = mbList($wp,$search, $page, $pageSize, $total_page);
header("Content-Type: application/json");
echo json_encode($result);

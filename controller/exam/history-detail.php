<?php

/**
 * @author ReDo
 * @copyright 2023
 * Load danh sách câu hỏi từ các config của đề thi
 */

include('../../model/m_exam.php');

$id = $_GET["id"];

$result = exHistoryDetail($id);

header("Content-Type: application/json");
echo json_encode($result);
<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('../../model/m_exam.php');
$exam = GetHotExam();
header("Content-Type: application/json");
echo json_encode($exam);

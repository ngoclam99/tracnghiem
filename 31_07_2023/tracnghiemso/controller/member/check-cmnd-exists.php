<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('../../model/m_member.php');
$cmnd = $_GET['cmnd'];
$count = CheckCmndExist($cmnd);
header("Content-Type: application/json");
echo json_encode($count);

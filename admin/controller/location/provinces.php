<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include_once('../../model/m_location.php');
$provinces = LoadProvinces();
header("Content-Type: application/json");
echo json_encode($provinces);

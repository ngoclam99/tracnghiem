<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include('model/m_exam.php');
include('controller/c_left.php');
$getDoiTuong = getDoiTuongMain($id = 4);
require('view/home/index.tpl');

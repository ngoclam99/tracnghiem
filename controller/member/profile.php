<?php
    include('../../model/classes/m_message.php');
    include('../../model/m_member.php');
    session_start();
    $profile = (array)$_SESSION['profile'];
    $user = getUser($profile['id'], 'id_doituong, id_doituong_chitiet');
    $arrDT = dmdoituong();
    $arrDTCT = doituongchitiet();
    $profile['dt'] = trim($arrDT[$user['id_doituong']]['ten_donvi']);
    $profile['dtct'] = $arrDT[$user['id_doituong_chitiet']];
    $msg = new Message();
    $msg->statusCode=200;
    $msg->title = "Lấy thông tin tài khoản thành công!";
    $msg->icon = "success";
    $msg->content  = $profile;
    header("Content-Type: application/json");
    echo json_encode($msg);
?>

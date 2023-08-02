<?php
include_once('m_db.php');
include_once('classes/m_message.php');
include_once('classes/m_profile.php');

function detail($id){
    $sql = "SELECT
                m.id,m.username,m.avatar,m.fullname,m.email,m.phone,
                m.get_birthdate,
                DATE_FORMAT(m.birthdate,'%d/%m/%Y') AS birthdate,

                m.get_gender,
                CASE 
                    WHEN gender = 1 THEN 'Nam'
                    WHEN gender = 0 THEN 'Nữ'
                    ELSE 'Khác'
                END AS gender,

                m.get_job,
                j.name AS job,

                m.get_position,
                p.name AS position,

                m.get_workplace,
                wp.name AS workplace,
               
                m.get_address,
                CASE
                    WHEN LENGTH(m.address) THEN CONCAT(m.address,', ',w.full_name,', ',d.full_name,', ',pr.full_name)
                    ELSE CONCAT(w.full_name,', ',d.full_name,', ',pr.full_name)
                END AS address,
                DATE_FORMAT(applied_date,'%d/%m/%Y %T') AS applied_date,
                DATE_FORMAT(lasttime_login,'%d/%m/%Y %T') AS lasttime_login            
            FROM members m 
            LEFT JOIN jobs j ON m.job_id = j.id
            LEFT JOIN positions p ON m.job_id = p.id
            LEFT JOIN workplaces wp ON m.workplace_id = wp.id
            LEFT JOIN provinces pr ON m.province_code = pr.code
            LEFT JOIN districts d ON m.district_code = d.code
            LEFT JOIN wards w ON m.ward_code = w.code
            WHERE m.id = '".$id."'";

        $result = mysql_query($sql,dbconnect());

        $msg = new Message();
        if($result){
            if(mysql_num_rows($result)>0){
                $msg->statusCode = 200;
                $msg->icon = "success";
                $msg->title = "Lấy thông tin tài khoản thành công!";
                $msg->content = mysql_fetch_array($result);
            }else{
                $msg->statusCode = 404;
                $msg->icon = "error";
                $msg->title = "Không tìm thấy tài khoản này trên hệ thống!";
            }
        }else{
            $msg->statusCode = 500;
            $msg->icon = "success";
            $msg->title = "Lấy thông tin tài khoản thất bại";
            $msg->content = mysql_error();
        }
        return $msg;
}

function login($username, $password)
{
    $sql = "SELECT * from members WHERE username ='" . $username . "' ";
    $result = mysql_query($sql, dbconnect());
    $count = mysql_num_rows($result);

    $msg = new Message();

    if ($count == 0) {
        $msg->statusCode = 404;
        $msg->content = "Tài khoản không tồn tại!";
        $msg->title = "Not found!";
        $msg->icon = "warning";
    } else {
        $sql .= " AND password = '" . MD5($password) . "'";
        $result = mysql_query($sql, dbconnect());
        $count = mysql_num_rows($result);

        if ($count == 0) {
            $msg->statusCode = 400;
            $msg->content = "Mật khẩu không chính xác!";
            $msg->title = "Bad request!";
            $msg->icon = "warning";
        } else {
            $sql .= " AND role_id = 1";
            $result = mysql_query($sql, dbconnect());
            $count = mysql_num_rows($result);

            if ($count == 0) {
                $msg->statusCode = 403;
                $msg->content = "Bạn không có quyền truy cập module này!";
                $msg->title = "Forbidden!";
                $msg->icon = "warning";
            } else {
                $m = mysql_fetch_array(mysql_query($sql, dbconnect()));
                if ($m) {
                    $sql = "SELECT 
                    m.id as id,
                    m.username AS username,
                    m.fullname AS fullname,
                    DATE_FORMAT(m.birthdate,'%d/%m/%Y') AS birthdate,
                    CASE
                    WHEN (LENGTH(TRIM(m.address))>0) THEN CONCAT(m.address,', ',w.full_name,', ',d.full_name,', ',p.full_name)
                    ELSE CONCAT(w.full_name,', ',d.full_name,', ',p.full_name)
                    END AS address,
                    m.phone AS phone,
                    m.email AS email,
                    m.avatar as avatar,
                    m.role_id
                    FROM `members` m 
                    LEFT JOIN provinces p on m.province_code = p.code
                    LEFT JOIN districts d ON m.district_code = d.code
                    LEFT JOIN wards w ON m.ward_code = w.code
                    LEFT JOIN jobs j ON m.job_id = j.id                    
                    WHERE m.id = " . $m['id'];

                    $account = mysql_fetch_array(mysql_query($sql, dbconnect()));
                    if ($account) {
                        $profile = new Profile();
                        $profile->id = $account['id'];
                        $profile->username = $account['username'];
                        $profile->avatar = $account['avatar'];
                        $profile->fullname = $account['fullname'];
                        $profile->phone = $account['phone'];
                        $profile->email = $account['email'];
                        $profile->address = $account['address'];
                        // $profile->job = $account['job'];
                        $profile->job = $account['get_job'];
                        $profile->workplace = $account['workplace'];
                        $profile->role_id = $account['role_id'];

                        // Lưu trữ chuỗi vào session
                        session_start();
                        $_SESSION["admin"] = $profile;


                        $msg->statusCode = 200;
                        $msg->content = $_SESSION['admin'];
                        $msg->title = "Đăng nhập thành công!";
                        $msg->icon = "success";
                    }
                }
            }
        }
    }
    return $msg;
}

function reset_password($id,$default_password){
    $sql = "UPDATE members 
            SET password = '".md5($default_password)."' 
            WHERE id = '".$id."'";
    $result = mysql_query($sql,dbconnect());

    $msg = new Message();
    if($result){
        $msg->icon = "success";
        $msg->statusCode = 200;
        $msg->title = "Khôi phục mật khẩu thành công!";
    }else{
        $msg->icon = "error";
        $msg->title = "Khôi phục mật khẩu thất bại!";
        $msg->statusCode = 500;
        $msg->content = "Lỗi: ".mysql_error();
    }
    return $msg;
}


function mbList($wp,$search, $page, $pageSize, $total_page, $sum_record = 0, $stt = 0)
{
    $sql = "SELECT m.id,m.username,m.fullname, m.id_doituong, m.id_doituong_chitiet, m.cmnd,
            CASE WHEN m.get_birthdate = 1 THEN DATE_FORMAT(m.birthdate,'%d/%m/%Y')
            ELSE '' END AS birthdate,
            CASE WHEN m.get_gender = 1 THEN 
                    CASE WHEN m.gender = 1 THEN 'Nam'
                        WHEN m.gender = 0 THEN 'Nữ'
                        ELSE 'Khác'
                    END
                ELSE ''
            END AS gender,
            m.phone,m.email,
            CASE WHEN m.get_address = 1 THEN
                CONCAT(m.address)
                ELSE '' END AS 'address',
            CASE WHEN m.get_workplace = 1 THEN wp.name ELSE '' END AS workplace,
            m.working_unit,
            CASE WHEN m.get_job = 1 THEN j.name ELSE '' END AS job,
            CASE WHEN m.get_position = 1 THEN m.position ELSE '' END AS position,
            DATE_FORMAT(applied_date,'%d/%m/%Y %T') AS applied_date,
            DATE_FORMAT(lasttime_login,'%d/%m/%Y %T') AS lasttime_login    
            FROM members m
            LEFT JOIN workplaces wp ON m.workplace_id = wp.id
            LEFT JOIN jobs j ON m.job_id = j.id
            WHERE (m.username LIKE '%" . $search . "%'
            OR  m.fullname LIKE '%" . $search . "%'
            OR  m.phone LIKE '%" . $search . "%'
            OR  m.cmnd LIKE '%" . $search . "%') ORDER BY m.id DESC";
            // $sql.= $wp!=null?" AND m.workplace_id ='".$wp."'":"";
            $sql .=" LIMIT " . ($page - 1) * $pageSize . "," . $pageSize ;

    $local_list = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($local_list) {
        $result = array();
        $arrdtct = doituongchitiet();
        $arrdt = dmdoituong();
        $start = ($page - 1) * $pageSize;
        $stt = $start + 1;
        while ($local = mysql_fetch_assoc($local_list)) {
            $local['stt'] = $stt++;
            if (isset($arrdtct[$local['id_doituong_chitiet']])) {
                $local['doituong'] = $arrdtct[$local['id_doituong_chitiet']];
            } else {
                $local['doituong']['title'] = "";
            }

            if (!isset($arrdt[$local['id_doituong']])) {
                $local['donvi']['ten_donvi'] = "";
            } else {
                $local['donvi'] = $arrdt[$local['id_doituong']];
            }

            $result[] = $local;
        }
        $phantrang = listPhanTrang($total_page, $page);
        $msg->icon = "success";
        $msg->phantrang = $phantrang;
        $msg->title = "Load danh sách thành viên thành công!";
        $msg->statusCode = 200;
        $msg->content = $result;
        $msg->tong = $sum_record;
    } else {
        $msg->icon = "error";
        $msg->phantrang = "";
        $msg->title = "Load danh sách thành viên thất bại!";
        $msg->statusCode = 500;
        $msg->content = "Lỗi: " . mysql_error();
    }

    return $msg;
}

function CountMember($id, $id_dt, $id_dtct, $username) {
    $sql = "SELECT count(*) as tong 
        FROM members m";
    $local_list = mysql_query($sql, dbconnect());
    $res = mysql_fetch_assoc($local_list);
    return $res;

}

function listPhanTrang($total_page, $page) {
    $html = '';
    if ($total_page > 1) {
        if ($page > 1) {
            $html .= '<li><a href="javascript:void(0)" data-page="' . ($page-1) . '"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>';
        }

        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $page) {
                $html .= '<li class="active"><span class="page active">' . $i . '</span></li>';
            } else {
                $html .= '<li><a class="page gradient" href="javascript:void(0)" data-page="' . $i . '">' . $i . '</a></li>';
            }

        }

        if ($page < $total_page) {
            $html .= '<li><a class="page gradient" href="javascript:void(0)" data-page="' . ($page+1) . '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>';
        }
    }
    return $html;
}

function dmdoituong() {
    $sql = "SELECT * FROM dm_doituong";
    $result = mysql_query($sql, dbconnect());
    while ($row = mysql_fetch_assoc($result)) {
        $arr[$row['id']] = $row;
    }
    return $arr;
}

function doituongchitiet() {
    $sql = "SELECT * FROM doituong_chitiet";
    $result = mysql_query($sql, dbconnect());
    while ($row = mysql_fetch_assoc($result)) {
        $arr[$row['id']] = $row;
    }
    return $arr;
}

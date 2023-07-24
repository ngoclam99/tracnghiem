<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include_once('m_db.php');
include('classes/m_message.php');

function delete_result($result_id)
{
    $msg = new Message();
    //B1: kiểm tra tồn tại của kết quả bài thi
    $sql = "SELECT * FROM exam_results WHERE id = '" . $result_id . "'";
    $result = mysql_query($sql, dbconnect());



    if ($result && mysql_num_rows($result) > 0) {
        $fr = mysql_fetch_array($result); //focus row: dòng được chọn


        //B2: Xóa kết quả bài thi được chọn trong 2 bảng: exam_results và exam_result_details
        $sql = "DELETE exam_results, exam_result_details
        FROM exam_results
        LEFT JOIN exam_result_details ON exam_results.id = exam_result_details.exam_result_id
        WHERE exam_results.id = '" . $result_id . "'";

        $result = mysql_query($sql, dbconnect());

        if ($result && mysql_affected_rows() > 0) {
            //B3: Cập nhật lại chỉ số lần thi cho các kết quả khác của bài thi tương ứng
            $sql = "UPDATE exam_results 
            SET times = times -1    
            WHERE exam_id = " . $fr['exam_id'] . "
            AND times > " . $fr['times'];
            $result = mysql_query($sql, dbconnect());

            if ($result) {
                $msg->icon = "success";
                $msg->statusCode = 200;
                $msg->title = "Xóa kết quả bài thi thành công!";
            } else {
                $msg->icon = "error";
                $msg->statusCode = 500;
                $msg->title = "Cập nhật lần thi thất bại";
                $msg->content = mysql_error();
            }
        } else {
            $msg->icon = "error";
            $msg->statusCode = 500;
            $msg->title = "Xóa kết quả bài thi thất bại";
            $msg->content = mysql_error();
        }
    } else {
        $msg->icon = "error";
        $msg->title = "Không tìm thấy kết quả bài thi phù hợp";
        $msg->statusCode = 404;
    }

    return $msg;
}
function exResultPagination($id)
{
    $sql = "SELECT q.id,q.title,erd.question_answer,erd.option_id
    FROM exam_result_details erd   
    INNER JOIN questions q ON erd.question_id = q.id         
    WHERE exam_result_id = '" . $id . "'    
    ";
    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result && mysql_num_rows($result)) {

        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->statusCode = 200;
        $msg->title = "Lấy thông tin phân trang thành công!";
        $msg->content = $arr;
    } else {
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->title = "Lấy thông tin phân trang thất bại!";
        $msg->content = "Lỗi: " . mysql_error();
    }
    return $msg;
}


//hàm lấy thông tin tổng quan của 1 bài thi cụ thể
function exResultSummary($result_id, $candidate)
{
    $sql = "SELECT 
    er.id, e.exam_code, e.title, e.duration,e.mark_per_question,
    m.username,m.fullname,m.phone,m.email,m.avatar,er.times,
    COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END) AS correct,
    COUNT(CASE WHEN erd.question_answer != erd.option_id AND erd.option_id !=0 THEN 1 END) AS wrong,
    COUNT(CASE WHEN erd.option_id = 0 THEN 1 END) AS unchoosed,
    COUNT(CASE WHEN erd.option_id !=0 THEN 1 END) AS choosed,
    COUNT( erd.exam_result_id ) AS total_questions,
    er.spent_duration,
    DATE_FORMAT(er.created_at, '%d/%m/%Y %H:%i') AS exam_date
    FROM exams e
    INNER JOIN exam_results er ON er.exam_id = e.id
    INNER JOIN members m ON er.member_id = m.id
    INNER JOIN exam_result_details erd ON erd.exam_result_id = er.id
    WHERE er.member_id = '" . $candidate . "'
    AND er.id = '" . $result_id . "'
    GROUP BY er.id";
    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result && mysql_num_rows($result) > 0) {
        $msg->statusCode = 200;
        $msg->title = "Lấy thông tin tổng quan kết quả thi thành công!";
        $msg->icon = "success";
        $msg->content = mysql_fetch_array($result);
    } else {
        $msg->statusCode = 500;
        $msg->title = "Lấy lịch sử thi thất bại!";
        $msg->icon = "error";
        $msg->content = "Lỗi: " . mysql_error();
    }
    return $msg;
}

function LoadDoiTuong() {
    $result = mysql_query("select * from dm_doituong", dbconnect());
    while ($local = mysql_fetch_assoc($result)) {
        $arr[] = $local;
    }
    return $arr;
}


function LoadDoiTuongChiTiet($id_dt) {
    $result = mysql_query("select * from doituong_chitiet WHERE id_doituong = " . $id_dt, dbconnect());
    while ($local = mysql_fetch_assoc($result)) {
        $arr[] = $local;
    }
    return $arr;
}


function History($page, $search, $pageSize, $workplaces, $exams, $id_doituong)
{
    $where = "";
    if ($id_doituong > 0) {
        $where = " AND m.id_doituong = " .$id_doituong;
    }
    $sql = "SELECT m.id AS candidate,er.id AS result_id,m.username,m.fullname, m.id_doituong,
    CASE 
    WHEN m.gender = 1 THEN 'Nam'
    WHEN m.gender = 0 THEN 'Nữ'
    ELSE 'Khác'
    END AS gender,
    m.get_birthdate,
    CASE WHEN m.get_birthdate = 1 THEN DATE_FORMAT( m.birthdate,'%d/%m/%Y')  ELSE '' END AS birthdate,
    m.phone,m.email,
    m.get_job,j.name AS job,
    m.get_workplace,wp.name AS workplace,
    pro.full_name AS province, dis.full_name AS district, w.full_name AS ward, er.forecast_candidates AS forecast,
    m.get_position,p.name AS position,
    e.title AS exam,
    er.times,
    (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) AS mark ,
    COUNT(erd.question_id)*e.mark_per_question AS total_marks,
    (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) / COUNT(erd.question_id)*e.mark_per_question as test,
    DATE_FORMAT(er.started_at,'%d/%m/%Y %T') AS exam_date,
    er.spent_duration 
    FROM members m
    LEFT JOIN jobs j ON m.job_id = j.id
    LEFT JOIN workplaces wp ON m.workplace_id = wp.id
    LEFT JOIN positions p ON m.position_id = p.id
    JOIN exam_results er ON er.member_id = m.id
    JOIN exam_result_details erd ON erd.exam_result_id = er.id 
    JOIN exams e ON er.exam_id = e.id
    JOIN wards w ON m.ward_code = w.code
    JOIN provinces pro ON m.province_code = pro.code
    JOIN districts dis ON m.district_code = dis.code
    WHERE   (m.username LIKE '%" . $search . "%'
        OR m.fullname LIKE '%" . $search . "%'
        OR m.phone LIKE '%" . $search . "%'
        OR m.email LIKE '%" . $search . "%'
        OR wp.name LIKE '%" . $search . "%'
        OR j.name LIKE '%" . $search . "%') " . $where . "
        ";

        $soNguoiDuThi = soNguoiDuThi($page, $search, $pageSize, $workplaces, $exams, $id_doituong);

        if ($workplaces) {
            $sql .= " AND wp.id IN (";
            for ($i = 0; $i < count($workplaces); $i++) {
                $sql .= $i < count($workplaces) - 1 ? $workplaces[$i] . "," : $workplaces[$i];
            }
            $sql .= ")";
        }
        if ($exams) {
            $sql .= " AND e.id IN (";
            for ($i = 0; $i < count($exams); $i++) {
                $sql .= $i < count($exams) - 1 ? $exams[$i] . "," : $exams[$i];
            }
            $sql .= ")";
        }
        $sql .= " GROUP BY m.id,er.id\nORDER BY test DESC, spent_duration ASC";

    //Tính số trang của kết quả tìm được dựa vào kích thước trang & số dòng của kết quả
        $pages = 1;
        $result = mysql_query($sql, dbconnect());
        $totalRows = mysql_num_rows($result);
        if (strcmp($pageSize, "All") != 0) {
            $pages = $totalRows % $pageSize == 0 ? $totalRows / $pageSize : floor($totalRows / $pageSize) + 1;
            $sql .= " LIMIT " . ($page - 1) * $pageSize . "," . $pageSize . "";
        }

        $result = mysql_query($sql, dbconnect());
        $msg = new Message();
        if ($result) {
            $arr = array();
            while ($local = mysql_fetch_array($result)) {
                $arr[] = $local;
            }


            $msg->icon = "success";
            $msg->statusCode = 200;
            $msg->title = "Lấy danh sách lịch sử thi thành công!";
            $msg->content = $arr;
            $msg->pages = $pages;
            $msg->soluotthi = $totalRows;
            $msg->songuoithi = $soNguoiDuThi;
        } else {
            $msg->statusCode = 500;
            $msg->icon = "error";
            $msg->title = "Load lịch sử thi thất bại!";
            $msg->content = mysql_error();
        }

        return $msg;
    }

    function doituong() {
        $sql = "SELECT * FROM dm_doituong";
        $result = mysql_query($sql, dbconnect());
        while ($row = mysql_fetch_assoc($result)) {
            $arr[$row['id']] = $row;
        }
        return $arr;
    }

    function soNguoiDuThi($page, $search, $pageSize, $workplaces, $exams, $id_doituong) {
        $where = "";
        if ($id_doituong > 0) {
            $where = " m.id_doituong = " .$id_doituong . " AND ";
        }
        $sql = "SELECT distinct m.id AS candidate,er.id AS result_id,m.username,m.fullname, m.id_doituong
        CASE 
        WHEN m.gender = 1 THEN 'Nam'
        WHEN m.gender = 0 THEN 'Nữ'
        ELSE 'Khác'
        END AS gender,
        m.get_birthdate,
        CASE WHEN m.get_birthdate = 1 THEN DATE_FORMAT( m.birthdate,'%d/%m/%Y')  ELSE '' END AS birthdate,
        m.phone,m.email,
        m.get_job,j.name AS job,
        m.get_workplace,wp.name AS workplace,
        pro.full_name AS province, dis.full_name AS district, w.full_name AS ward, er.forecast_candidates AS forecast,
        m.get_position,p.name AS position,
        e.title AS exam,
        er.times,
        (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) AS mark ,
        COUNT(erd.question_id)*e.mark_per_question AS total_marks,
        (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) / COUNT(erd.question_id)*e.mark_per_question as test,
        DATE_FORMAT(er.started_at,'%d/%m/%Y %T') AS exam_date,
        er.spent_duration 
        FROM members m
        LEFT JOIN jobs j ON m.job_id = j.id
        LEFT JOIN workplaces wp ON m.workplace_id = wp.id
        LEFT JOIN positions p ON m.position_id = p.id
        JOIN exam_results er ON er.member_id = m.id
        JOIN exam_result_details erd ON erd.exam_result_id = er.id 
        JOIN exams e ON er.exam_id = e.id
        JOIN wards w ON m.ward_code = w.code
        JOIN provinces pro ON m.province_code = pro.code
        JOIN districts dis ON m.district_code = dis.code
        WHERE " . $where . "  (m.username LIKE '%" . $search . "%'
            OR m.fullname LIKE '%" . $search . "%'
            OR m.phone LIKE '%" . $search . "%'
            OR m.email LIKE '%" . $search . "%'
            OR wp.name LIKE '%" . $search . "%'
            OR j.name LIKE '%" . $search . "%')";

            if ($workplaces) {
                $sql .= " AND wp.id IN (";
                for ($i = 0; $i < count($workplaces); $i++) {
                    $sql .= $i < count($workplaces) - 1 ? $workplaces[$i] . "," : $workplaces[$i];
                }
                $sql .= ")";
            }
            if ($exams) {
                $sql .= " AND e.id IN (";
                for ($i = 0; $i < count($exams); $i++) {
                    $sql .= $i < count($exams) - 1 ? $exams[$i] . "," : $exams[$i];
                }
                $sql .= ")";
            }
            $sql .= " GROUP BY m.id\nORDER BY test DESC, times ASC";

            $pages = 1;
            if (strcmp($pageSize, "All") != 0) {
                $result = mysql_query($sql, dbconnect());

        // $totalRows = mysql_num_rows($result);
        // $pages = $totalRows % $pageSize == 0 ? $totalRows / $pageSize : floor($totalRows / $pageSize) + 1;
            }
            $arrDV = doituong();

            $result = mysql_query($sql, dbconnect());
            if ($result) {
                $arr = array();
                while ($local = mysql_fetch_assoc($result)) {
                    pr($local);
            // if ($local['id_doituong'] > 0) {
            //     $local['donvi'] = "";
            // } else {
            //     $local['donvi'] = $arrDV[$local['id_doituong']]['ten_donvi'];
            // }
                    $arr[] = $local;
                }

                return count($arr);
            }

            return 0;

        }

        function LoadResultByExamsAndWorkplaces($exams, $workplaces, $page, $pageSize, $max,$begin,$end,$province,$districts)
        {
            if (intval($province) != 0) {
                $where_province = " AND m.province_code = '".$province."'";
            } else {
                $where_province = "";
            }
            $sql = "SELECT subquery.candidate, subquery.result_id, subquery.username, subquery.fullname, subquery.gender, subquery.get_birthdate,
            subquery.birthdate, subquery.phone, subquery.email, subquery.get_job, subquery.job, subquery.get_workplace,
            subquery.workplace, subquery.working_unit, subquery.get_position, subquery.position, subquery.exam, subquery.times,
            subquery.mark, subquery.total_marks, subquery.exam_date, subquery.spent_duration, subquery.forecast_candidate,
            subquery.isForecast, subquery.province, subquery.district, subquery.ward, subquery.address
            FROM
            (
                SELECT m.id AS candidate, er.id AS result_id, m.username, m.fullname,
                CASE
                WHEN m.gender = 1 THEN 'Nam'
                WHEN m.gender = 0 THEN 'Nữ'
                ELSE 'Khác'
                END AS gender,
                m.get_birthdate,
                CASE WHEN m.get_birthdate = 1 THEN DATE_FORMAT(m.birthdate, '%d/%m/%Y') ELSE '' END AS birthdate,
                m.phone, m.email,
                m.get_job, j.name AS job,
                m.get_workplace, wp.name AS workplace, m.working_unit,
                pro.full_name AS province, dis.full_name AS district, w.full_name AS ward, m.address,
                m.get_position,
                CASE WHEN m.get_position = 1 THEN m.position ELSE '' END AS position,
                e.title AS exam,
                er.times,
                (COUNT(CASE WHEN erd.option_id = erd.question_answer THEN 1 END) * e.mark_per_question) AS mark,
                (SELECT COUNT(question_id) * e.mark_per_question
                    FROM exam_result_details erd2
                    WHERE erd2.exam_result_id = er.id) AS total_marks,
                DATE_FORMAT(er.started_at, '%d/%m/%Y %T') AS exam_date,
                er.spent_duration,
                CASE WHEN e.forecast_candidates = 1 THEN er.forecast_candidates ELSE 0 END AS forecast_candidate,
                e.forecast_candidates AS isForecast
                FROM members m
                LEFT JOIN jobs j ON m.job_id = j.id
                LEFT JOIN workplaces wp ON m.workplace_id = wp.id
                JOIN exam_results er ON er.member_id = m.id
                JOIN exam_result_details erd ON erd.exam_result_id = er.id
                JOIN exams e ON er.exam_id = e.id
                JOIN wards w ON m.ward_code = w.code
                JOIN provinces pro ON m.province_code = pro.code
                JOIN districts dis ON m.district_code = dis.code
                WHERE er.created_at >= '".$begin."' AND er.created_at <= '". $end . "'" . $where_province;
                if($districts){
                    $sql .= " AND m.district_code IN (";
                    for ($i = 0; $i < count($districts); $i++) {
                        $sql .= $i < count($districts) - 1 ? $districts[$i] . "," : $districts[$i];
                    }
                    $sql .= ")";
                }

                if ($workplaces) {
                    $sql .= " AND wp.id IN (";
                    for ($i = 0; $i < count($workplaces); $i++) {
                        $sql .= $i < count($workplaces) - 1 ? $workplaces[$i] . "," : $workplaces[$i];
                    }
                    $sql .= ")";
                }

                if ($exams) {
                    $sql .= " AND e.id IN (";
                    for ($i = 0; $i < count($exams); $i++) {
                        $sql .= $i < count($exams) - 1 ? $exams[$i] . "," : $exams[$i];
                    }
                    $sql .= ")";
                }
                $sql .= " GROUP BY m.id, er.id
            ) AS subquery";
            if($max == 1){
                $sql .="";
            }
            
            $sql.=" GROUP BY subquery.candidate ORDER BY subquery.mark DESC, subquery.spent_duration ASC";

            $persion_number =  getTotolLuotThi($province);

    //Tính số trang của kết quả tìm được dựa vào kích thước trang & số dòng của kết quả
            $pages = 1;
            if (strcmp($pageSize, "All") != 0) {
                $result = mysql_query($sql, dbconnect());

                $totalRows = mysql_num_rows($result);
                $pages = $totalRows % $pageSize == 0 ? $totalRows / $pageSize : floor($totalRows / $pageSize) + 1;
                $sql .= " LIMIT " . ($page - 1) * $pageSize . "," . $pageSize . "";
            }

            $result = mysql_query($sql, dbconnect());

            $msg = new Message();
            if ($result) {
                $arr = array();
                while ($local = mysql_fetch_array($result)) {
                    $arr[] = $local;
                }

                $msg->icon = "success";
                $msg->statusCode = 200;
                $msg->title = "Lấy danh sách lịch sử thi thành công!";
                $msg->content = $arr;
                $msg->pages = $pages;
                $msg->songuoithi = $persion_number->songuoithi;
                $msg->soluotthi = $persion_number;
            } else {
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Load lịch sử thi thất bại!";
                $msg->content = mysql_error();
            }

            return $msg;
        }
        function all()
        {
            $sql = "SELECT id,title FROM exams";
            $result = mysql_query($sql, dbconnect());
            $msg = new Message();
            if ($result) {
                $arr = array();
                while ($local = mysql_fetch_array($result)) {
                    $arr[] = $local;
                }
                $msg->statusCode = 200;
                $msg->icon = "success";
                $msg->title = "Lấy danh sách cuộc thi thành công!";
                $msg->content = $arr;
            } else {
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Lấy danh sách cuộc thi thất bại!";
                $msg->content = mysql_error();
            }
            return $msg;
        }
        function change_random_questions($id)
        {
            $sql = "UPDATE exams 
            SET random_questions = CASE WHEN random_questions = 1 THEN 0 ELSE 1 END
            WHERE id = '" . $id . "'
            ";
            $result = mysql_query($sql, dbconnect());
            $msg = new Message();
            if ($result && mysql_affected_rows() > 0) {
                $msg->statusCode = 200;
                $msg->icon = "success";
                $msg->title = "Đổi trạng thái đảo câu hỏi thành công!";
            } else {
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Đổi trạng thái đảo câu hỏi thất bại!";
                $msg->content = mysql_error();
            }
            return $msg;
        }
//hàm thay đổi thuộc tính đảo đáp án của cuộc thi
        function change_random_options($id)
        {
            $msg = new Message();
            $result = mysql_query("UPDATE exams 
                SET random_options = CASE WHEN random_options = 1 THEN 0 ELSE 1 END
                WHERE id= " . $id, dbconnect());

            if ($result && mysql_affected_rows() > 0) {
                $msg->statusCode = 200;
                $msg->icon = "success";
                $msg->title = "Cập nhật trạng thái đảo đáp án của bài thi thành công!";
            } else {
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Cập nhật trạng thái đảo đáp án của bài thi thất bại!";
                $msg->content = "Lỗi: " . mysql_error();
            }

            return $msg;
        }

//hàm thay đổi thuộc tính cuộc thi tiêu điểm
        function change_hot($id)
        {
    //Cập nhật tất cả các bài thi khác thành không tiêu điểm
            $result = mysql_query("UPDATE exams SET is_hot = 0 WHERE id !='" . $id . "'", dbconnect());
            $msg = new Message();
            if ($result) {

                $result = mysql_query("UPDATE exams 
                    SET is_hot = CASE WHEN is_hot = 1 THEN 0 ELSE 1 END 
                    WHERE id= " . $id, dbconnect());

                if ($result && mysql_affected_rows() > 0) {
                    $msg->statusCode = 200;
                    $msg->icon = "success";
                    $msg->title = "Cập nhật trạng thái tiêu điểm của bài thi thành công!";
                } else {
                    $msg->statusCode = 500;
                    $msg->icon = "error";
                    $msg->title = "Cập nhật trạng thái tiêu điểm của bài thi thất bại!";
                    $msg->content = "Lỗi: " . mysql_error();
                }
            } else {
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Cập nhật trạng thái tiêu điểm của các bài thi thất bại!";
                $msg->content = "Lỗi: " . mysql_error();
            }
            return $msg;
        }

        function SetStatistic($id){
            $sql = "UPDATE exams SET is_stat = 0";
            $result = mysql_query($sql,dbconnect());
            $msg = new Message();
            if($result){
                $sql1 = "UPDATE exams SET is_stat = 1 WHERE id = '".$id."'";
                $result1 = mysql_query($sql1,dbconnect());
                if($result1 && mysql_affected_rows()>0){
                    $msg->title = "Thiết lập trạng thái truy vấn cho bài thi thành công!";
                    $msg->statusCode = 200;
                    $msg->icon = "success";
                }else{
                    $msg->title = "Thiết lập trạng thái truy vấn cho bài thi thất bại!";
                    $msg->icon = "error";
                    $msg->statusCode = 500;
                    $msg->content = mysql_error();
                }
            }else{
                $msg->title = "Thiết lập trạng thái mặc định cho các bài thi thất bại!";
                $msg->icon = "error";
                $msg->statusCode = 500;
                $msg->content = mysql_error();
            }
            return $msg;
        }
        function SetForeCastCandidates($id){
            $sql = "UPDATE exams
            SET forecast_candidates = CASE WHEN forecast_candidates = 0 THEN 1 ELSE 0 END
            WHERE id = '".$id."'";
            $result = mysql_query($sql,dbconnect());
            $msg = new Message();
            if($result && mysql_affected_rows()>0){
                $msg->title = "Thay đổi trạng thái dự đoán thí sinh thành công!";
                $msg->icon = "success";
                $msg->statusCode = 200;        
            }else{
                $msg->icon = "error";
                $msg->statusCode = 500;
                $msg->title = "Thay đổi trạng thái dự đoán thí sinh thất bại!";
                $msg->content = mysql_error();
            }
            return $msg;
        }

        function retrieve($page, $search, $pageSize)
        {
            $sql = "SELECT 
            e.id,
            e.thumbnail,
            e.title,
            e.duration,
            e.number_of_questions,
            e.mark_per_question,
            e.times,
            DATE_FORMAT(e.begin, '%d/%m/%Y %T') begin,
            DATE_FORMAT(e.end, '%d/%m/%Y %T') end,
            e.random_questions,
            e.random_options,
            e.is_stat,
            e.is_hot,
            e.forecast_candidates,
            e.regulation,        
            CASE
            WHEN `begin` < CURRENT_TIMESTAMP( ) AND `end` < CURRENT_TIMESTAMP( ) THEN 1
            WHEN `begin` < CURRENT_TIMESTAMP( ) AND `end` > CURRENT_TIMESTAMP( ) THEN 0
            WHEN `begin` > CURRENT_TIMESTAMP( ) AND `end` > CURRENT_TIMESTAMP( ) THEN -1
            END AS exam_status
            FROM exams e
            WHERE e.title like '%" . $search . "%'
            OR e.description like '%" . $search . "%'       
            ORDER BY e.id DESC ";

    //Tính số trang của kết quả tìm được dựa vào kích thước trang & số dòng của kết quả
            $pages = 1;
            if (strcmp($pageSize, "All") != 0) {
                $result = mysql_query($sql, dbconnect());

                $totalRows = mysql_num_rows($result);
                $pages = $totalRows % $pageSize == 0 ? $totalRows / $pageSize : floor($totalRows / $pageSize) + 1;
                $sql .= " LIMIT " . ($page - 1) * $pageSize . "," . $pageSize . "";
            }


            $local_list = mysql_query($sql, dbconnect());

            $msg = new Message();
            if ($local_list) {
                $arr = array();
                while ($local = mysql_fetch_array($local_list)) {
                    $arr[] = $local;
                }
                $msg->title = "Load danh sách bài thi thành công!";
                $msg->icon = "success";
                $msg->statusCode = 200;
                $msg->content = $arr;
                $msg->pages = $pages;
            } else {
                $msg->title = "Load danh sách bài thi thất bại!";
                $msg->icon = "error";
                $msg->statusCode = 500;
                $msg->content = mysql_error();
            }
            return $msg;

        }


        function detail($id)
        {
            $sql = "SELECT 
            e.id,e.title,e.thumbnail,e.duration,e.is_hot,
            e.number_of_questions,
            e.mark_per_question,
            e.times,
            description,
            DATE_FORMAT(e.begin, '%d/%m/%Y %H:%i') AS begin,
            DATE_FORMAT(e.end, '%d/%m/%Y %H:%i') AS end,  
            e.is_hot,
            e.forecast_candidates,     
            e.random_questions,
            e.random_options,
            CASE
            WHEN `begin` < CURRENT_TIMESTAMP( )
            AND `end` < CURRENT_TIMESTAMP( )
            THEN 1
            WHEN `begin` < CURRENT_TIMESTAMP( )
            AND `end` > CURRENT_TIMESTAMP( )
            THEN 0
            ELSE -1
            END AS exam_status,
            e.regulation,
            CONCAT('[', GROUP_CONCAT(CONCAT('{\"topic_id\":', ef.topic_id, ', \"percent\":', ef.percent, '}')), ']') AS exam_configs
            FROM exams e
            LEFT JOIN exam_configs ef ON ef.exam_id = e.id
            WHERE e.id = '" . $id . "' 
            GROUP BY e.id ";
            $result = mysql_query($sql, dbconnect());
            $msg = new Message();
            if ($result) {
                $msg->icon = "success";
                $msg->statusCode = 200;
                $msg->title = "Lấy thông tin chi tiết bài thi thành công!";
                $msg->content = mysql_fetch_array($result);
            } else {
                $msg->title = "Lấy thông tin bài thi thất bại!";
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->content = mysql_error();
            }
            return $msg;
        }

        function create(
            $title,
            $thumbnail,
            $description,
            $duration,
            $number_of_questions,
            $mark_per_question,
            $times,
            $begin,
            $end,
            $is_hot,
            $forecast_candidates,
            $random_questions,
            $random_options,
            $configs,
            $regulation,
            $created_by
        ) {
    /*
        Nếu cuộc thi được tick vào là cuộc thi tiêu điểm
        => update các cuộc thi còn lại trở thành không
        => Chỉ có 1 cuộc thi là tiêu điểm
    */
        if ($is_hot == 1) {
            $result = mysql_query("UPDATE exams SET is_hot = 0 ", dbconnect());
        }



        $result = mysql_query("INSERT INTO exams 
         SET title='" . $title . "',
         thumbnail = '" . $thumbnail . "',       
         description = '" . $description . "',
         duration = '" . $duration . "',
         number_of_questions = '" . $number_of_questions . "',
         mark_per_question = '" . $mark_per_question . "',
         times = '" . $times . "',
         begin = '" . $begin . "',
         end = '" . $end . "',    
         is_hot = '" . $is_hot . "',   
         forecast_candidates = '".$forecast_candidates."',
         random_questions = '" . $random_questions . "',
         random_options = '" . $random_options . "',
         regulation = '" . $regulation . "',
         created_by='" . $created_by . "'
         ", dbconnect());

        $msg = new Message();
        if ($result && mysql_affected_rows() > 0) {
            $latestId = mysql_insert_id();
            $chk = setCode($latestId);
            if ($chk->statusCode != 200) {
                return $chk;
            }

            $sql = "INSERT INTO exam_configs(exam_id,topic_id,percent,created_by) VALUES";
            foreach ($configs as $cf) {
                $sql .= "('" . $latestId . "','" . $cf['id'] . "', '" . $cf['percent'] . "','" . $created_by . "')";
            $sql .= $cf == end($configs) ? "" : ","; // kiểm tra xem có phải phần tử cuối của mảng hay không
        }
        $result = mysql_query($sql, dbconnect());
        if ($result) {
            $msg->statusCode = 201;
            $msg->icon = 'success';
            $msg->title = "Tạo mới cuộc thi thành công!";
        } else {
            $msg->icon = 'error';
            $msg->title = 'Lỗi cấu hinh bài thi';
            $msg->content = "Lỗi: " . mysql_error();
            $msg->statusCode = 500;
        }

    } else {
        $msg->icon = 'error';
        $msg->title = 'Tạo cuộc thi thất bại';
        $msg->content = "Lỗi: " . mysql_error();
        $msg->statusCode = 500;
    }
    return $msg;
}

function update(
    $id,
    $title,
    $thumbnail,
    $description,
    $duration,
    $number_of_questions,
    $mark_per_question,
    $times,
    $begin,
    $end,
    $is_hot,
    $forecast_candidates,
    $random_questions,
    $random_options,
    $configs,
    $regulation,
    $updated_by
) {
    if ($is_hot == 1) {
        mysql_query("UPDATE exams SET is_hot = 0", dbconnect());
    }
    $result = mysql_query("UPDATE exams 
        SET title='" . $title . "',
        thumbnail = '" . $thumbnail . "',       
        description = '" . $description . "',
        duration = '" . $duration . "',
        number_of_questions = '" . $number_of_questions . "',
        mark_per_question = '" . $mark_per_question . "',
        times = '" . $times . "',
        begin = '" . $begin . "',
        end = '" . $end . "',    
        is_hot = '" . $is_hot . "', 
        forecast_candidates = '".$forecast_candidates."',  
        random_questions = '" . $random_questions . "',
        random_options = '" . $random_options . "',
        regulation = '" . $regulation . "',
        updated_by='" . $updated_by . "',
        updated_at=CURRENT_TIMESTAMP()
        WHERE id =" . $id, dbconnect());
    $msg = new Message();
    if ($result && mysql_affected_rows() > 0) {
        //xóa các configs cũ cho chắc ăn
        $sql = "DELETE FROM exam_configs WHERE exam_id = '" . $id . "'";
        $result = mysql_query($sql, dbconnect());

        if ($result) {
            //thêm lại từ đầu các config
            $sql = "INSERT INTO exam_configs(exam_id,topic_id,percent,created_by) VALUES";
            foreach ($configs as $cf) {
                $sql .= "('" . $id . "','" . $cf['id'] . "', '" . $cf['percent'] . "','" . $updated_by . "')";
                $sql .= $cf == end($configs) ? "" : ","; // kiểm tra xem có phải phần tử cuối của mảng hay không
            }
            $result = mysql_query($sql, dbconnect());
            if ($result) {
                $msg->statusCode = 200;
                $msg->icon = 'success';
                $msg->title = "Cập nhật cuộc thi thành công!";
            } else {
                $msg->icon = 'error';
                $msg->title = 'Lỗi cập nhật cấu hình đề thi';
                $msg->content = "Lỗi: " . mysql_error();
                $msg->statusCode = 500;
            }
        } else {
            $msg->icon = 'error';
            $msg->title = 'Lỗi xóa các cấu hình cũ của đề thi';
            $msg->content = "Lỗi: " . mysql_error();
            $msg->statusCode = 500;
        }
    } else {
        $msg->icon = 'error';
        $msg->title = 'Cập nhật cuộc thi thất bại';
        $msg->content = "Lỗi: " . mysql_error();
        $msg->statusCode = 500;
    }
    return $msg;
}
function delete($id)
{
    $sql = "DELETE exams, exam_configs
    FROM exam_configs 
    INNER JOIN exams ON exams.id = exam_configs.exam_id
    WHERE exams.id = '".$id."'";

    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result && mysql_affected_rows() > 0) {
        $msg->statusCode = 200;
        $msg->icon = "success";
        $msg->title = "Xóa bài thi thành công!";
    } else {
        $msg->icon = 'error';
        $msg->title = 'Xóa cuộc thi thất bại';
        $msg->content = "Lỗi: " . mysql_error();
        $msg->statusCode = 500;
    }
    return $msg;
}

function setCode($id)
{
    $idx = $id < 1000000 ? "0" . (string) $id :
    $id < 100000 ? "00" . (string) $id :
    $id < 10000 ? "000" . (string) $id :
    $id < 1000 ? "0000" . (string) $id :
    $id < 100 ? "00000" . (string) $id :
    $id < 10 ? "000000" . (string) $id : $id;

    $exam_code = "EX" . date("Y") . $idx;

    $result = mysql_query("UPDATE exams 
        SET exam_code='" . $exam_code . "'
        WHERE id =" . $id, dbconnect());
    $msg = new Message();
    if ($result && mysql_affected_rows() > 0) {
        $msg->statusCode = 200;
        $msg->icon = 'success';
        $msg->title = "Cập nhật exam code thành công!";
    } else {
        $msg->icon = 'error';
        $msg->title = 'Cập nhật exam code thất bại';
        $msg->content = "Lỗi: " . mysql_error();
        $msg->statusCode = 500;
    }
    return $msg;
}

function History_NumberPersion($page, $search, $pageSize, $workplaces, $exams)
{
    $sql = "SELECT m.id AS candidate,er.id AS result_id,m.username,m.fullname,
    CASE 
    WHEN m.gender = 1 THEN 'Nam'
    WHEN m.gender = 0 THEN 'Nữ'
    ELSE 'Khác'
    END AS gender,
    m.get_birthdate,
    CASE WHEN m.get_birthdate = 1 THEN DATE_FORMAT( m.birthdate,'%d/%m/%Y')  ELSE '' END AS birthdate,
    m.phone,m.email,
    m.get_job,j.name AS job,
    m.get_workplace,wp.name AS workplace,
    pro.full_name AS province, dis.full_name AS district, w.full_name AS ward, er.forecast_candidates AS forecast,
    m.get_position,p.name AS position,
    e.title AS exam,
    er.times,
    (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) AS mark ,
    COUNT(erd.question_id)*e.mark_per_question AS total_marks,
    (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) / COUNT(erd.question_id)*e.mark_per_question as test,
    DATE_FORMAT(er.started_at,'%d/%m/%Y %T') AS exam_date,
    er.spent_duration 
    FROM members m
    LEFT JOIN jobs j ON m.job_id = j.id
    LEFT JOIN workplaces wp ON m.workplace_id = wp.id
    LEFT JOIN positions p ON m.position_id = p.id
    JOIN exam_results er ON er.member_id = m.id
    JOIN exam_result_details erd ON erd.exam_result_id = er.id 
    JOIN exams e ON er.exam_id = e.id
    JOIN wards w ON m.ward_code = w.code
    JOIN provinces pro ON m.province_code = pro.code
    JOIN districts dis ON m.district_code = dis.code
    WHERE   (m.username LIKE '%" . $search . "%'
        OR m.fullname LIKE '%" . $search . "%'
        OR m.phone LIKE '%" . $search . "%'
        OR m.email LIKE '%" . $search . "%'
        OR wp.name LIKE '%" . $search . "%'
        OR j.name LIKE '%" . $search . "%')
        ";

        if ($workplaces) {
            $sql .= " AND wp.id IN (";
            for ($i = 0; $i < count($workplaces); $i++) {
                $sql .= $i < count($workplaces) - 1 ? $workplaces[$i] . "," : $workplaces[$i];
            }
            $sql .= ")";
        }
        if ($exams) {
            $sql .= " AND e.id IN (";
            for ($i = 0; $i < count($exams); $i++) {
                $sql .= $i < count($exams) - 1 ? $exams[$i] . "," : $exams[$i];
            }
            $sql .= ")";
        }
        $sql .= " GROUP BY m.id,er.id\nORDER BY test DESC, spent_duration ASC";

    //Tính số trang của kết quả tìm được dựa vào kích thước trang & số dòng của kết quả
        $result = mysql_query($sql, dbconnect());
        $totalRows = mysql_num_rows($result);

        $msg = new Message();
        $msg->icon = "success";
        $msg->statusCode = 200;
        $msg->title = "Lấy thông tin người dự thi!";
        $msg->soluotthi = $totalRows;

        return $msg;
    }

    function getTotolLuotThi($provice) {
        if ($provice != '') {
            $sql = 'SELECT count(*) 
            FROM members m
            LEFT JOIN jobs j ON m.job_id = j.id
            LEFT JOIN workplaces wp ON m.workplace_id = wp.id
            LEFT JOIN positions p ON m.position_id = p.id
            JOIN exam_results er ON er.member_id = m.id
            JOIN exam_result_details erd ON erd.exam_result_id = er.id 
            JOIN exams e ON er.exam_id = e.id
            JOIN wards w ON m.ward_code = w.code
            JOIN provinces pro ON m.province_code = pro.code
            JOIN districts dis ON m.district_code = dis.code
            WHERE m.province_code = ' . $provice  . '
            GROUP BY m.id,er.id';
        } else {
            $sql = 'SELECT count(*) 
            FROM members m
            LEFT JOIN jobs j ON m.job_id = j.id
            LEFT JOIN workplaces wp ON m.workplace_id = wp.id
            LEFT JOIN positions p ON m.position_id = p.id
            JOIN exam_results er ON er.member_id = m.id
            JOIN exam_result_details erd ON erd.exam_result_id = er.id 
            JOIN exams e ON er.exam_id = e.id
            JOIN wards w ON m.ward_code = w.code
            JOIN provinces pro ON m.province_code = pro.code
            JOIN districts dis ON m.district_code = dis.code
            GROUP BY m.id,er.id';
        }
        $result = mysql_query($sql, dbconnect());
        $totalRows = mysql_num_rows($result);
        return  $totalRows;      
    }


    function getTinh() {
        $sql = "SELECT * FROM provinces";
        $result = mysql_query($sql, dbconnect());
        while ($row = mysql_fetch_assoc($result)) {
            $arr[$row['code']] = $row;
        }
        return $arr;
    }

    function getHuyen() {
        $sql = "SELECT * FROM districts";
        $result = mysql_query($sql, dbconnect());
        while ($row = mysql_fetch_assoc($result)) {
            $arr[$row['code']] = $row;
        }
        return $arr;
    }

    function getXa() {
        $sql = "SELECT * FROM wards";
        $result = mysql_query($sql, dbconnect());
        while ($row = mysql_fetch_assoc($result)) {
            $arr[$row['code']] = $row;
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

    function getThongKeCuocThi($id, $id_dt, $id_dtct) {
        $where = array();
        if ($id != '') {
            $strID = implode(",", $id);
            $where[] = "ex.id IN (" . $strID . ")";
        }

        if ($id_dt != '') {
            $where[] = "mb.id_doituong = " . $id_dt;
        }

        if ($id_dtct != '') {
            $where[] = "mb.id_doituong_chitiet = " . $id_dtct;
        }
        $wh = '';
        if (!empty($where)) {
            $wh = " WHERE " . implode(" and ", $where);
        }   

        $sql = "SELECT COUNT( DISTINCT t1.member_id ) AS tongthisinh, COUNT( t1.member_id ) AS soluotthi, t1.tongcaudung, ex.mark_per_question, DATE_FORMAT(t1.started_at,'%d/%m/%Y %T') AS exam_date, mb.fullname, ex.number_of_questions, t1.spent_duration, max(t1.times) as tonglanthi, avg(t1.tongcaudung) as trungbinh_diem, mb.province_code, mb.district_code, mb.ward_code, mb.id_doituong_chitiet, mb.phone, mb.email, mb.birthdate, ex.title, mb.address
        FROM exam_results t1
        INNER JOIN (
            SELECT member_id, MAX( tongcaudung ) AS tongdung
            FROM exam_results
            GROUP BY member_id
            )t2 ON t1.member_id = t2.member_id
        AND t1.tongcaudung = t2.tongdung
        INNER JOIN exams AS ex ON t1.exam_id = ex.id
        INNER JOIN members AS mb ON t1.member_id = mb.id
        " . $wh . "
        GROUP BY T1.member_id 
        ORDER BY (t2.tongdung*ex.mark_per_question) DESC , t1.spent_duration LIMIT 50";
        $result = mysql_query($sql, dbconnect());

        $sql1 = "SELECT COUNT( DISTINCT t1.member_id )
        FROM exam_results t1
        INNER JOIN (
            SELECT member_id, MAX( tongcaudung ) AS tongdung
            FROM exam_results
            GROUP BY member_id
            )t2 ON t1.member_id = t2.member_id
        AND t1.tongcaudung = t2.tongdung
        INNER JOIN exams AS ex ON t1.exam_id = ex.id
        INNER JOIN members AS mb ON t1.member_id = mb.id
        " . $wh . "
        GROUP BY T1.member_id 
        ORDER BY (t2.tongdung*ex.mark_per_question) DESC , t1.spent_duration";
        $total_sql = mysql_query($sql1, dbconnect());
        $total = mysql_num_rows($total_sql);
        $arr = array();
        $i = 0;

        $tongluotthi = 0;
        while ($row = mysql_fetch_assoc($result)) {
            switch ($i) {
                case 0:
                $row['logo'] = 'hcvang.png';
                break;

                case 1:
                $row['logo'] = 'hcbac.png';
                break;

                case 2:
                $row['logo'] = 'hcdong.png';
                break;    

                default:
                $row['logo'] = 'hc.png';
                break;
            }
            $arrTinh = getTinh();
            $arrHuyen = getHuyen();
            $arrXa = getXa();
            $arrdt = doituongchitiet();
            $row['tinh'] = $arrTinh[$row['province_code']];
            $row['huyen'] = $arrHuyen[$row['district_code']];
            $row['xa'] = $arrXa[$row['ward_code']];
            if ($row['id_doituong_chitiet'] > 0) {
                $row['doituong'] = $arrdt[$row['id_doituong_chitiet']];
            } else {
                $row['doituong']['title'] = "";
            }
            $tongluotthi += $row['tonglanthi'];
            $arr[] = $row;
            $i++;
        }
        $data = array(
            'arr' => $arr,
            'total' => $total,
            'tong_luotthi' => $tongluotthi,
        );

        return $data;
    }

//  Include thư viện PHPExcel_IOFactory vào
    include 'Classes/PHPExcel/IOFactory.php';
// PHP extension php_zip enabled
// PHP extension php_xml enabled
// PHP extension php_gd2 enabled (if not compiled in)
    function xuatExcelCuocThi($data = array()) {
        $objPHPExcel                 = new PHPExcel(); 
        $nam                         = date('d/m/Y H:i:s');
        $filename                    = 'Báo cáo tổng cả cuộc thi';
        // Read the file
        $objPHPExcel->getProperties()->setCreator("Văn Lâm")
        ->setLastModifiedBy("Administrator")
        ->setTitle("Báo cáo tổng cả cuộc thi")
        ->setSubject("Báo cáo tổng cả cuộc thi");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Times new Roman')->setSize(13);

            /**********************************************************************
            ****************          FILE EXCEL 8.1               ****************
            ****************                                       ****************
            ***********************************************************************/
            $objPHPExcel->getActiveSheet()->setTitle("Báo cáo tổng cả cuộc thi");
            $day= date('Y/m/d');
            // pr($sinhvien);
            $dem = sizeof($data['arr']) + 3;

            //Border
           //Border
            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );
            $objPHPExcel->getActiveSheet()->getStyle('A3:N'.$dem)->applyFromArray($styleArray);
            unset($styleArray);
            // Căn cỡ cột tự động
            foreach(range('A','Z') as $columnID){
                $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }

            // Căn cỡ hàng tự động
            foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) {
                $rd->setRowHeight(-1);
            }

            //Xuống dòng
            $objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setWrapText(true);

            // Merge cell
            $array_merge = array('A1:E1');
            foreach($array_merge as $cell){
                $objPHPExcel->getActiveSheet()->mergeCells($cell);
            }
            // Căn giữa ngang
            $canngang= array();
            foreach($canngang as $cell){
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            // Căn giữa dọc
            $array_vertical_center = array('A1:K15');
            foreach($array_vertical_center as $cell){
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            }

            // In đậm
            $array_bold = array('A1', 'A3:M3');
            foreach($array_bold as $cell){
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
            }
            // In nghiêng
            $array_italic = array();
            foreach($array_italic as $cell){
                $objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setItalic(true);
            }

            // Chỉnh cỡ font
            $array_font_size = array(
                'A1' => 16,
            );
            foreach($array_font_size as $key => $value){
                $objPHPExcel->getActiveSheet()->getStyle($key)->getFont()->setSize($value);
            }

            // Chỉnh cỡ cột
            $array_column = array(
                'A' => 4,
                'B' => 23,
                'C' => 12,
                'D' => 11,
                'E' => 13,
                'F' => 12,
                'G' => 8,
                'H' => 11,
                'J'=> 10,
                'I'=>12,
                'J'=>12,
                'K'=>12,
                'L'=>12,
                'M'=>12,
                'N'=>12,
            );

            foreach($array_column as $key => $value){
                $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setAutoSize(false);
                $objPHPExcel->getActiveSheet()->getColumnDimension($key)->setWidth($value);
            }
            //Chỉnh cỡ hàng fix cứng
            $array_row = array(


            );
            foreach($array_row as $key => $value){
                $objPHPExcel->getActiveSheet()->getRowDimension($key)->setRowHeight($value);
            }
            //*******************************************
            //************* NỘI DUNG ********************
            //*******************************************
            $title = "XUẤT BÁO CÁO THỐNG KÊ THI SINH CẢ CUỘC THI";
            // $array_content = $this->column_tieude($title)['header'];
            $array_content = array(
                'A1'  => 'BÁO CÁO TỔNG CẢ CUỘC THI ' . date('d/m/Y') ,
                'B2' => 'Tổng số thí sinh: ' . $data['total'], 
                'C2' => 'Tổng lượt thi: ' . $data['tong_luotthi'], 
                'A3' => 'STT',
                'B3' => 'Họ và tên',
                'C3' => 'Số điện thoại',
                'D3' => 'Ngày sinh',
                'E3' => 'Email',
                'F3' => 'Cuộc thi',
                'G3' => 'Tỉnh',
                'H3' => 'Huyện',
                'I3' => 'Xã',
                'J3' => 'Địa chỉ',
                'K3' => 'Đơn vị',
                'L3' => 'Tổng lần thi',
                'M3' => 'Số điểm',
            );
            
            $indexRow = 4;
            
            foreach ($data['arr'] AS $k => $v)
            {
                $array_content['A'.$indexRow] = $k+1;
                $array_content['B'.$indexRow] = $v['fullname'];
                $array_content['C'.$indexRow] = $v['phone'];
                $array_content['D'.$indexRow] = $v['birthdate'];
                $array_content['E'.$indexRow] = $v['email'];
                $array_content['F'.$indexRow] = $v['title'];
                $array_content['G'.$indexRow] = $v['tinh']['full_name'];
                $array_content['H'.$indexRow] = $v['huyen']['full_name'];
                $array_content['I'.$indexRow] = $v['xa']['full_name'];
                $array_content['J'.$indexRow] = $v['address'];
                $array_content['K'.$indexRow] = $v['doituong']['title'];
                $array_content['L'.$indexRow] = $v['tonglanthi'];
                $array_content['M'.$indexRow] = $v['tongcaudung']*$v['mark_per_question'];
                $indexRow++;
            }


            // Muốn thêm nội dung động thì foreach array push là xong.
            foreach($array_content as $key => $value){
                $objPHPExcel->getActiveSheet()->setCellValue($key,$value);
            }
            // End chỉnh sửa nội dung
            // ob_end_clean();
            if (ob_get_contents()) ob_end_clean();
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment;filename=".$filename.".xls");
            header("Cache-Control: max-age=0");

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit();
        }

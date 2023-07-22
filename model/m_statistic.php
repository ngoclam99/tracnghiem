<?php
include_once('m_db.php');
include_once('classes/m_message.php');

function statDistrict1($code){
    $sql = "SELECT 
        d.full_name AS district,
        w.full_name AS ward,
        m.working_unit,
        wp.name AS workplace,
        COUNT(DISTINCT er.member_id) AS candidates,
        COUNT(DISTINCT  er.id) AS total_times,
        COUNT(erd.question_id) AS total_questions,
        COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END) * e.mark_per_question AS total_marks,
        ROUND((COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END)* e.mark_per_question)/ COUNT(erd.question_id),2) AS average_mark
    FROM wards w
    INNER JOIN districts d ON w.district_code = d.code
    INNER JOIN members m ON m.ward_code = w.code
    LEFT JOIN workplaces wp ON m.workplace_id = wp.id
    INNER JOIN exam_results er ON er.member_id = m.id
    INNER JOIN exam_result_details erd ON erd.exam_result_id = er.id
    INNER JOIN exams e ON er.exam_id = e.id
    WHERE w.district_code = '".$code."'
    GROUP BY w.code";
    $result = mysql_query($sql,dbconnect());
    $msg = new Message();
    if($result){
        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Thống kê dữ liệu cuộc thi theo quận, huyện thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
    }else{
        $msg->title = "Thống kê dữ liệu thi theo quận huyện thất bại!";
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
    return $msg;
}

function statDistrict($code) {
    $sql = "SELECT t1.member_id, soluotthi, t1.tongcaudung, ex.mark_per_question, DATE_FORMAT( t1.started_at, '%d/%m/%Y %T' ) AS exam_date, mb.fullname, ex.number_of_questions, t1.spent_duration, sum_tongdung, (sum_tongdung*mark_per_question / soluotthi) as tb_dung
        FROM exam_results t1
        INNER JOIN (
        SELECT member_id, MAX( tongcaudung ) AS tongdung, COUNT( * ) AS soluotthi, sum(tongcaudung) as sum_tongdung
        FROM exam_results
        GROUP BY member_id
        )t2 ON t1.member_id = t2.member_id
        AND t1.tongcaudung = t2.tongdung
        INNER JOIN exams AS ex ON t1.exam_id = ex.id
        INNER JOIN members AS mb ON t1.member_id = mb.id
        WHERE ex.is_stat =1 
        AND mb.province_code = 14
        AND mb.district_code = " . $code . "
        GROUP BY t1.member_id
        ORDER BY t2.tongdung DESC , t1.spent_duration";
    $result = mysql_query($sql,dbconnect());
    $msg = new Message();
    if($result){
        $arr = array();
        while ($local = mysql_fetch_assoc($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Thống kê dữ liệu cuộc thi theo quận, huyện thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
    }else{
        $msg->title = "Thống kê dữ liệu thi theo quận huyện thất bại!";
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
    return $msg;
}

function statProvince($province_code=14)
{
    // $sql = "SELECT code FROM provinces WHERE code_name = 'son_la'";
    // $res = mysql_query($sql, dbconnect());;
    // $res = mysqli_fetch_assoc($res);
    // $row = $res['code'];

    $sql = "SELECT
                e.title,
                d.code,
                d.full_name AS district, 
                COUNT(DISTINCT er.member_id) AS candidates,
                COUNT(DISTINCT  er.id) AS total_times,
                COUNT(erd.question_id) AS total_questions,
                COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END) * e.mark_per_question AS total_marks,
                (COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END)* e.mark_per_question)/ COUNT(erd.question_id) AS average_mark
            FROM exam_result_details erd
            INNER JOIN exam_results er ON erd.exam_result_id = er.id
            INNER JOIN exams e ON er.exam_id = e.id
            INNER JOIN members m ON er.member_id = m.id
            INNER JOIN provinces p ON m.province_code = p.code
            LEFT JOIN districts d ON d.province_code = p.code AND m.district_code = d.code
            WHERE p.code = '" . $province_code . "'
            AND e.is_stat = 1
            GROUP BY d.code";
    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result) {
        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Thống kê theo cuộc thi và tỉnh thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;

    } else {
        $msg->statusCode = 500;
        $msg->title = "Thống kê cuộc thi theo tỉnh thành thất bại!";
        $msg->icon = "error";
        $msg->content = mysql_error();
    }
    return $msg;
}

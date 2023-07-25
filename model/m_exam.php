<?php

/**
 * @author ReDo
 * @copyright 2023
 */

include_once('m_db.php');
include_once('m_question.php');
include_once('m_option.php');
include_once('m_exam_config.php');
include_once('m_exam_result.php');
include_once('m_exam_result_detail.php');
include_once('classes/m_message.php');

function CurrentExam(){
    $sql = "SELECT id, title, thumbnail,`begin`,`end`,regulation
            FROM exams
            WHERE `begin` < CURRENT_TIMESTAMP( )
            AND   `end` > CURRENT_TIMESTAMP( )               
            ORDER BY `end`
            LIMIT 1
            ";
    $result = mysql_query($sql,dbconnect());
    $msg = new Message();

    if($result){
        if(mysql_num_rows($result)==0){
            $sql = "SELECT id, title, thumbnail,`begin`,`end`,regulation
            FROM exams
            WHERE `begin` < CURRENT_TIMESTAMP( )
            AND   `end` < CURRENT_TIMESTAMP( )               
            ORDER BY `end` DESC
            LIMIT 1
            ";
             $result = mysql_query($sql,dbconnect());
             if($result){
                $msg->statusCode = 200;
                $msg->icon = "success";
                $msg->title = "Lấy cuộc thi gần nhất đã diễn thành công!";
                $msg->content = mysql_fetch_array($result);
             }else{
                $msg->statusCode = 500;
                $msg->icon = "error";
                $msg->title = "Lấy cuộc thi gần nhất đã diễn ra thất bại!";
                $msg->content = mysql_error();
             }
        }else{
            $msg->statusCode = 200;
            $msg->icon = "success";
            $msg->title = "Lấy cuộc thi gần nhất đang diễn thành công!";
            $msg->content = mysql_fetch_array($result);
        }
    }else{
        $msg->statusCode = 500;
        $msg->icon = "error";
        $msg->title = "Lấy cuộc thi gần nhất đang diễn ra thất bại!";
        $msg->content = mysql_error();
    }
    return $msg;
}

function Top10Exams()
{
    $sql = "SELECT e.id, title, thumbnail, 
            DATE_FORMAT(begin , '%d/%m/%Y' ) AS begin , 
            DATE_FORMAT(end , '%d/%m/%Y' ) AS end , 
            description,
            CASE
                WHEN `begin` < CURRENT_TIMESTAMP( )
                AND `end` < CURRENT_TIMESTAMP( )
                THEN 1
                WHEN `begin` < CURRENT_TIMESTAMP( )
                AND `end` > CURRENT_TIMESTAMP( )
                THEN 0
                ELSE -1
            END AS exam_status
            FROM exams e
            INNER JOIN exam_configs c ON c.exam_id = e.id
            GROUP BY e.id,title, thumbnail
            ORDER BY e.is_hot DESC,
            e.created_at DESC,
            exam_status ASC
            LIMIT 10";
    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result) {
        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Lấy top 10 cuộc thi thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
    } else {
        $msg->statusCode = 500;
        $msg->icon = "error";
        $msg->title = "Lấy top 10 cuộc thi thất bại!";
        $msg->content = mysql_error();
    }
    return $msg;
}

function LoadExams($page, $pageSize)
{
    $sql = "SELECT e.id, title, thumbnail, 
        DATE_FORMAT(begin , '%d/%m/%Y %H:%i' ) AS begin , 
        DATE_FORMAT(end , '%d/%m/%Y %H:%i' ) AS end , 
        description,
        CASE
            WHEN BEGIN < CURRENT_TIMESTAMP( )
            AND END < CURRENT_TIMESTAMP( )
            THEN 1
            WHEN BEGIN < CURRENT_TIMESTAMP( )
            AND END > CURRENT_TIMESTAMP( )
            THEN 0 
            ELSE -1
        END AS exam_status
        FROM exams e
        INNER JOIN exam_configs c ON c.exam_id = e.id
        GROUP BY e.id,title, thumbnail
            ORDER BY e.is_hot DESC,
            e.created_at DESC,
            exam_status ASC";

    //Tính số trang của kết quả tìm được dựa vào kích thước trang & số dòng của kết quả
    $pages = 1;
    $result = mysql_query($sql, dbconnect());
    $totalRows = mysql_num_rows($result);
    $pages = $totalRows % $pageSize == 0 ? $totalRows / $pageSize : floor($totalRows / $pageSize) + 1;
   //-- kết thúc tính số trang


    $sql .= " LIMIT " . ($page - 1) * $pageSize . "," . $pageSize;

    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result) {
        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $msg->icon = "success";
        $msg->title = "Lấy danh sách cuộc thi thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
        $msg->pages = $pages;
    } else {
        $msg->statusCode = 500;
        $msg->icon = "error";
        $msg->title = "Lấy danh sách cuộc thi thất bại!";
        $msg->content = mysql_error();
    }
    return $msg;
}


function Top10Candidates()
{
    // $sql = "SELECT DISTINCT m.id, m.fullname,
    //             DATE_FORMAT(er.started_at,'%d/%m/%Y %T') AS exam_date,
    //             er.spent_duration,
    //             (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question) AS mark ,
    //             COUNT(erd.question_id)*e.mark_per_question AS total_marks,
    //             (COUNT(CASE WHEN erd.option_id =erd.question_answer THEN 1 END)*e.mark_per_question)/(COUNT(erd.question_id)*e.mark_per_question) AS mark_ratio
    //         FROM members m
    //         JOIN exam_results er ON er.member_id = m.id
    //         JOIN exam_result_details erd ON erd.exam_result_id = er.id
    //         JOIN exams e ON er.exam_id = e.id
    //         WHERE e.is_stat = 1
    //         GROUP BY er.member_id, m.id,e.id,er.id
    //         ORDER BY mark_ratio DESC, spent_duration DESC
    //         LIMIT 10
    //         ";
    $sql = "SELECT t1.tongcaudung, ex.mark_per_question, DATE_FORMAT(t1.started_at,'%d/%m/%Y %T') AS exam_date, mb.fullname, ex.number_of_questions, t1.spent_duration, mb.province_code, mb.district_code, mb.ward_code, mb.id_doituong_chitiet
    FROM exam_results t1
    INNER JOIN (
        SELECT member_id, MAX(tongcaudung) AS tongdung
        FROM exam_results
        GROUP BY member_id
    ) t2 ON t1.member_id = t2.member_id AND t1.tongcaudung = t2.tongdung
    INNER JOIN exams as ex ON t1.exam_id = ex.id
    INNER JOIN members as mb ON t1.member_id = mb.id
    WHERE ex.is_stat = 1
    GROUP BY t1.member_id
    ORDER BY t2.tongdung DESC, t1.spent_duration ASC LIMIT 10";

    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result) {
        $arr = array();
        $i = 0;
        while ($local = mysql_fetch_assoc($result)) {
            $local['tong_mark'] = $local['mark_per_question'] * $local['tongcaudung'];
            $local['mark'] = $local['tongcaudung'];
            $local['spent_duration'] = seconds2human($local['spent_duration']);
            switch ($i) {
                case 0:
                    $local['logo'] = 'hcvang.png';
                    break;

                case 1:
                    $local['logo'] = 'hcbac.png';
                    break;

                case 2:
                    $local['logo'] = 'hcdong.png';
                    break;    

                default:
                    $local['logo'] = 'hc.png';
                    break;
            }
            $arr[] = $local;
            $i++;
        }
        $msg->icon = "success";
        $msg->title = "Lấy top 10 điểm cao nhất thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
    } else {
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->title = "Lấy top 10 điểm cao nhất thất bại!";
        $msg->content = mysql_error();
    }
    return $msg;
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
    $sql = "SELECT * FROM districts";
    $result = mysql_query($sql, dbconnect());
    while ($row = mysql_fetch_assoc($result)) {
        $arr[$row['code']] = $row;
    }
    return $arr;
}

function seconds2human($ss) {
    $s = $ss%60;
    $m = floor(($ss%3600)/60);
    $h = floor(($ss%86400)/3600);
    $d = floor(($ss%2592000)/86400);
    $M = floor($ss/2592000);
    $m = ($m < 10) ? '0' . $m : $m;
    $s = ($s < 10) ? '0' . $s : $s;
    return ($h > 0) ? "$h: " : "" .  "$m:$s";
}

function secondsToTime($seconds) {
    $dtF = new DateTime('@0');
    $dtT = new DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
}

function Top10Units()
{
    $arr = array();

    $sql = "SELECT mb.id_doituong, mb.id_doituong_chitiet, mb.province_code, mb.district_code, mb.ward_code, t1.times, mb.get_workplace, t1.member_id, count(t1.id) as tongluotthi, count(DISTINCT member_id) tongthisinh, ds.full_name as name
    FROM exam_results t1
    INNER JOIN exams as ex ON t1.exam_id = ex.id
    INNER JOIN members as mb ON t1.member_id = mb.id
    INNER JOIN districts as ds ON ds.code = mb.district_code
    WHERE ex.is_stat = 1 AND mb.province_code =14
    GROUP BY mb.district_code
    ORDER BY tongthisinh DESC
    LIMIT 10";
    $result = mysql_query($sql, dbconnect());
    while ($row = mysql_fetch_assoc($result)) {
        $arr[] = $row;
    }

    $msg = new Message();
    if ($result) {
        $msg->icon = "success";
        $msg->title = "Lấy top 10 đơn vị tham gia thi nhiều nhất thành công!";
        $msg->statusCode = 200;
        $msg->content = $arr;
    } else {
        $msg->statusCode = 500;
        $msg->icon = "error";
        $msg->title = "Lấy top 10 đơn vị tham gia nhiều nhất thất bại!";
        $msg->content = mysql_error();
    }
    return $msg;
}
function exResultPagination($id)
{
    $sql = "SELECT q.id,q.title,erd.question_answer,erd.option_id, erd.stt
            FROM exam_result_details erd   
            INNER JOIN questions q ON erd.question_id = q.id         
            WHERE exam_result_id = " . $id . " ORDER BY erd.stt ASC";

    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result && mysql_num_rows($result)) {

        $arr = array();
        while ($local = mysql_fetch_assoc($result)) {
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
function exResultSummary($result_id)
{
    session_start();
    $p = (array)$_SESSION['profile'];
    $sql = "SELECT 
            er.id, e.exam_code, e.title, e.duration,e.mark_per_question,
            COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END) AS correct,
            COUNT(CASE WHEN erd.question_answer != erd.option_id AND erd.option_id !=0 THEN 1 END) AS wrong,
            COUNT(CASE WHEN erd.option_id = 0 THEN 1 END) AS unchoosed,
            COUNT(CASE WHEN erd.option_id !=0 THEN 1 END) AS choosed,
            COUNT( erd.exam_result_id ) AS total_questions,
            e.forecast_candidates as hasForeCast,
            er.spent_duration,
            er.forecast_candidates,
            DATE_FORMAT(er.created_at, '%d/%m/%Y %H:%i') AS exam_date
            FROM exams e
            INNER JOIN exam_results er ON er.exam_id = e.id
            INNER JOIN exam_result_details erd ON erd.exam_result_id = er.id
            WHERE er.member_id = '" . $p['id'] . "'
            AND er.id = '" . $result_id . "'
            GROUP BY er.id, e.exam_code, e.title";
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
function exHistory($page, $pageSize, $search)
{
    session_start();
    $p = (array)$_SESSION['profile'];
    $sql = "SELECT 
            er.id, e.exam_code, e.title, er.times,
            COUNT(CASE WHEN erd.question_answer = erd.option_id THEN 1 END) AS correct,
            COUNT( erd.exam_result_id ) AS total,
            DATE_FORMAT(er.created_at, '%d/%m/%Y %H:%i') AS exam_date
            FROM exams e
            INNER JOIN exam_results er ON er.exam_id = e.id
            INNER JOIN exam_result_details erd ON erd.exam_result_id = er.id
            WHERE er.member_id = '" . $p['id'] . "'
            GROUP BY er.id, e.exam_code, e.title";
    $result = mysql_query($sql, dbconnect());
    $msg = new Message();
    if ($result) {
        $count = mysql_num_rows($result);
        if ($count > 0) {
            $arr = array();
            while ($local = mysql_fetch_array($result)) {
                $arr[] = $local;
            }
            $msg->statusCode = 200;
            $msg->title = "Lấy lịch sử thi thành công!";
            $msg->icon = "success";
            $msg->content = $arr;
        } else {
            $msg->statusCode = 404;
            $msg->title = "Bạn chưa tham gia cuộc thi nào";
            $msg->icon = "warning";
            $msg->content = $p['id'];
        }
    } else {
        $msg->statusCode = 500;
        $msg->title = "Lấy lịch sử thi thất bại!";
        $msg->icon = "error";
        $msg->content = "Lỗi: " . mysql_error();
    }
    return $msg;
}


function save($exam_id,$result,$times,$spent_duration,$exam_date,$forecast_candidates)
{
    $msg = new Message();
    session_start();
    $p = (array)$_SESSION['profile'];
    $sql = "SELECT MAX(times) as tong FROM exam_results WHERE member_id = " . $p['id'];
    $query_times = mysql_query($sql,dbconnect());
    $countimes = mysql_fetch_assoc($query_times);
    $times = $countimes['tong'] + 1;
    // Lưu bảng result
    $er = erSave($exam_id, $p['id'], $times, $spent_duration, $exam_date,$forecast_candidates);
    if ($er->statusCode != 201) {
        return $er;
    }
   
    foreach ($result as $k => $r) {

        $option = getCorrectOption($r['id']);
        if ($option->statusCode != 200) {
            return $option;
        }

        $is = erdSave($er->content, $r['id'], $option->content['id'], $r['checked'], $p['id'], $k+1);
        if ($is->statusCode != 201) {
            return $is;
        }
    }

    // Lưu bảng result detail
    $sql = "SELECT COUNT(IF(question_answer = option_id, 1,NULL)) as socaudung FROM exam_result_details WHERE exam_result_id = " . $er->content;
    $tongsocaudung = mysql_query($sql,dbconnect());
    $number_true = mysql_fetch_assoc($tongsocaudung);
    // Cập nhật lại vào bảng kết quả:
    $sql = "UPDATE exam_results SET tongcaudung = " . $number_true['socaudung'] . " WHERE id = " . $er->content;
    mysql_query($sql,dbconnect());

    $msg = new Message();
    $msg->icon = "success";
    $msg->statusCode = 201;
    $msg->title = "Lưu kết quả bài thi thành công!";
    $msg->content = $er->content;
    return $msg;
}


function HasHot()
{
    $result = mysql_query("SELECT * FROM exams WHERE is_hot = 1", dbconnect());
    $msg = new Message();
    if ($result) {

        $count = mysql_num_rows($result);
        if ($count > 0) {
            $msg->icon = "success";
            $msg->statusCode = 200;
            $msg->title = "Có bài thi tiêu điểm!";
        } else {
            $msg->icon = "error";
            $msg->statusCode = 404;
            $msg->title = "Không có bài thi tiêu điểm!";
        }
    } else {
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->title = "Kiểm tra tồn tại bài thi tiêu điểm thất bại!";
        $msg->content = "Lỗi: " . mysql_error();
    }
    return $msg;
}
function GetHotExam()
{
    $result = mysql_query("SELECT *
    FROM exams 
    WHERE is_hot = 1
    -- AND end >= CURRENT_TIMESTAMP()
    ", dbconnect());
    $msg = new Message();
    if ($result) {
        $count = mysql_num_rows($result);
        if ($count > 0) {
            $msg->statusCode = 200;
            $msg->title = "Có cuộc thi tiêu điểm";
            $msg->icon = "success";
            $msg->content = mysql_fetch_array($result);
        } else {
            $msg->statusCode = 404;
            $msg->title = "Hiện tại không có cuộc thi tiêu điểm nào";
            $msg->icon = "warning";
            $msg->content = "NOT FOUND";
        }
    } else {
        $msg->statusCode = 500;
        $msg->title = "Kiểm tra hot exam thất bại!";
        $msg->icon = "error";
        $msg->content = "Lỗi: " . mysql_error();
    }
    return $msg;
}
function GetQuestions($exam_id)
{
    $msg = new Message();
    /*
        Lấy thông tin bài thi 
        và các cấu hình liên quan
        -- đảo câu hỏi
        -- đảo đáp án
        -- số câu hỏi
    */
    $sql = "SELECT  e.id,e.title,
                    e.random_questions,e.random_options,
                    e.number_of_questions,
                    ef.topic_id,ef.percent 
            FROM exams e
            INNER JOIN exam_configs ef ON ef.exam_id = e.id
            WHERE e.id = '".$exam_id."' AND ef.percent>0";
    $result = mysql_query($sql,dbconnect());
    if($result && mysql_num_rows($result) > 0){
        $arr = array();
        while ($local = mysql_fetch_array($result)) {
            $arr[] = $local;
        }
        $number_of_questions =intval($arr[0]['number_of_questions']);
        $random_questions = intval($arr[0]['random_questions']);
       
        
        $sql = "SELECT * FROM( ";
                foreach($arr as $i=>$t){
                    $limit = round((intval($t['percent']) / 100) * $number_of_questions);
                    $sql .= "SELECT q".$i.".id, q".$i.".title, q".$i.".topic
                    FROM (
                        SELECT q.id, q.title, q.topic_id,t.name AS topic
                        FROM questions q
                        INNER JOIN topics t ON q.topic_id = t.id
                        WHERE q.topic_id = ".$t['topic_id']."";
                        $sql.= $random_questions==1?" ORDER BY RAND()":" ORDER BY q.id";
                        $sql.= " LIMIT ".$limit."
                    ) AS q".$i;
                    $sql .= $t == end($arr)?"":" UNION ";
                }
                $sql .=")  AS subquery";
                $sql.=$random_questions==1?" ORDER BY RAND();":"";
            $result = mysql_query($sql,dbconnect());
            if($result && mysql_num_rows($result)>0){
                $arr = array();
                while ($local = mysql_fetch_array($result)) {
                    $arr[] = $local;
                }
                $msg->title = "Lấy đề thi thành công!";
                $msg->icon = "success";
                $msg->statusCode = 200;
                $msg->content = $arr; 
            }else{
                $msg->icon = "error";
                $msg->title = "Lấy đề thi thất bại!";
                $msg->statusCode = 500;
                $msg->content = mysql_error();
            } 

    }else{
        $msg->title = "Lấy thông tin chi tiết bài thi thất bại!";
        $msg->icon = "error";
        $msg->statusCode = 500;
        $msg->content = mysql_error();
    }
    return $msg;
}


function ExDetail($id)
{
    $msg = new Message();

    $result = mysql_query("SELECT 
                e.id,e.title,e.thumbnail,e.duration,
                e.number_of_questions,
                e.mark_per_question,
                e.times,
                DATE_FORMAT(e.begin, '%d/%m/%Y %H:%i') AS begin,
                DATE_FORMAT(e.end, '%d/%m/%Y %H:%i') AS end,       
                e.random_questions,
                e.random_options,
                forecast_candidates,
                CASE
                    WHEN `begin` < CURRENT_TIMESTAMP( )
                    AND `end` < CURRENT_TIMESTAMP( )
                    THEN 1
                    WHEN `begin` < CURRENT_TIMESTAMP( )
                    AND `end` > CURRENT_TIMESTAMP( )
                    THEN 0
                    ELSE -1
                END AS exam_status
            FROM exams e
            JOIN exam_configs ef ON ef.exam_id = e.id
            WHERE e.id = '" . $id . "' 
            GROUP BY e.id,e.title,e.thumbnail,e.duration,e.number_of_questions,e.mark_per_question,e.times,e.begin,e.end,e.random_questions,e.random_options
            ", dbconnect());


    if ($result) {
        $exam = mysql_fetch_array($result);
        $msg->statusCode = 200;
        $msg->title = "Load chi tiết bài thi thành công!";
        $msg->icon = "success";
        $msg->content = $exam;
    } else {
        $msg->statusCode = 500;
        $msg->title = "Load chi tiết bài thi thất bại!";
        $msg->icon = "error";
        $msg->content = mysql_error();
    }
    return $msg;
}



function EarliestExam()
{
    $exam = mysql_query("SELECT * 
                FROM exams 
                WHERE begin > CURRENT_TIMESTAMP()
                ORDER BY begin
                LIMIT 1
                ", dbconnect());
    return mysql_fetch_array($exam);
}

function getDoiTuong($id, $id_cuocthi) {
    $sql = "SELECT mb.id_doituong, mb.id_doituong_chitiet, ex.is_stat, t1.member_id, COUNT( DISTINCT t1.member_id ) AS tongthisinh, COUNT(t1.member_id ) AS tongluotthisinh
    FROM exam_results t1
    INNER JOIN exams AS ex ON t1.exam_id = ex.id
    INNER JOIN members AS mb ON t1.member_id = mb.id
    WHERE ex.id = " . $id_cuocthi . "
    AND mb.id_doituong = " . $id ."
    GROUP BY mb.id_doituong_chitiet ORDER BY tongthisinh DESC ";
    $result = mysql_query($sql, dbconnect());
    while ($row = mysql_fetch_assoc($result)) {
        $arr[] = $row;
    }

    if (!empty($arr)) {
        foreach ($arr as $k => $v) {
           $sql = "SELECT * FROM doituong_chitiet WHERE id = " . $v['id_doituong_chitiet'];
           $row = sql_query($sql);
           $arr[$k]['title'] = $row['title'];
           if (empty($row)) {
                $sql = "SELECT * FROM dm_doituong WHERE id = " . $v['id_doituong'];
                $row = sql_query($sql);
                $arr[$k]['title'] = trim($row['ten_donvi']);
           }
        }
    }
    return $arr;
}

function compareByTongNguoiThi($a, $b) {
    if ($a['tongnguoithi'] == $b['tongnguoithi']) {
        return 0;
    }
    return ($a['tongnguoithi'] > $b['tongnguoithi']) ? -1 : 1;
}


function sql_query($sql) {
    $result = mysql_query($sql, dbconnect());
    $row = mysql_fetch_assoc($result);
    return $row;
}

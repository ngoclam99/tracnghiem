<?php include('view/template/common/header.tpl'); ?>
<?php include('view/template/common/left.tpl'); ?>

<div class="content-wrapper" style="clear: both; min-height: 864px;" id="content">
    <section class="content-header">
        <h1 style="font-size: 20px; font-family: Roboto Condensed">
            Lịch sử thi
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?module=home">Trang Chủ</a></li>
            <li class="active">Lịch sử thi</li>
        </ol>
    </section>
    <section class="content animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-warning">
                    <div class="box-body">

                        <div class="panel panel-success" style="margin-top:5px">
                            <div class="panel-body">
                                <h3>Báo cáo lịch sử thi</h3>
                                <hr style=" border: 1px solid #ccc; border-top: none">
                                <div class="row">
                                    <form method="post">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                            <label>Chọn cuộc thi</label>
                                            <select name="selectpicker" class="form-control listCuocThi" data-live-search="true" multiple></select>
                                        </div>
                                        <div class="form-group col-md-3 col-lg-4">
                                            <label>Đối tượng dự thi </label>
                                            <select name="id_dt" class="form-control selectpicker slDoiTuong" style="width: 100%;">
                                                <option value="">---Chọn đối tượng ---</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3 col-lg-4">
                                            <label>Đơn vị dự thi</label>
                                            <select name="id_dtct" class="form-control slDoiTuongChiTiet" name="slDoiTuongChiTiet" style="width: 100%; display: block !important;">
                                                <option value="">---Chọn đơn vị ---</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-lg-4">
                                            <label>Tìm thông tin người dùng</label>
                                            <input type="text" class="form-control" placeholder="Tên đăng nhập hoặc họ tên" id="username">
                                        </div>

                                        <div class="form-group col-md-3 col-lg-4">
                                            <button type="button" id="xuatexcel" class="btn btn-primary" value="1" style="margin-top: 24px;"  name="xuatexcel">Xuất excel</button>
                                        </div>

                                    </form>

                                    <div class="boxStatic">
                                    </div>
                                </div>
                                <div>
                                    <span><b>Số lượt thi:</b> <span class="label label-success" id="soluotthi">0</span></span> |
                                <span><b>Số người thi</b> <span class="label label-primary" id="songuoithi">0</span></span>
                                <hr>    
                                </div>

                                <div class="scroll_tb">
                                    <div class="text-center">
                                        <div class="bs-example text-center">
                                            <ul class="pagination" id="pagination">
                                                
                                            </ul>   
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover" id="tbEx">
                                        <thead>
                                            <tr>
                                                <th class="text-center">
                                                    STT
                                                </th>
                                                <th class="text-left">Tên đăng nhập</th>
                                                <th class="text-left">
                                                    Họ tên
                                                </th>

                                                <th class="text-center">
                                                    Cuộc thi (Thang điểm)
                                                </th>                                             
                                                <th class="text-center">
                                                   Lần thi
                                                </th>
                                                <th class="text-center">
                                                    Điểm số
                                                </th>
                                                <th class="text-center">
                                                   Thời gian bắt đầu
                                                </th>

                                                <th class="text-center">
                                                  Thời gian kết thúc
                                                </th>

                                                <th class="text-center">
                                                    Thời gian làm bài
                                                </th>
                                                <th class="text-center">
                                                    Dự đoán
                                                </th>                                            
                                                <th class="text-center">
                                                    Đối tượng
                                                </th>
                                                <th class="text-center">
                                                    Đơn vị
                                                </th>
                                                <th class="text-center">
                                                    Ngày sinh
                                                </th>
                                                <th class="text-center">
                                                    Điện thoại
                                                </th>
                                                <th class="text-center">
                                                    Email
                                                </th>
                                                <th class="text-center">
                                                    Nghề nghiệp
                                                </th>
                                                <th class="text-center">
                                                    Chức vụ
                                                </th>
                                                <th class="text-center">
                                                    Thao tác
                                                </th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="tbody">
                                            
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include('view/template/common/footer.tpl'); ?>
<script src="assets/dist/js/xlsx.full.min.js"></script>
<script src="assets/dist/js/jquery.table2excel.min.js"></script>
<style>
  table {
    border-collapse: collapse;
}

th, td {
    border: 1px solid black;
    padding: 8px;
}

#soluotthi, #songuoithi {
    font-size: 17px;
    font-weight: 500;
    box-shadow: 0px 5px 10px rgb(137 130 130 / 42%);
}

#tong {
    background: #007eff;
    padding: 5px 7px;
    border-radius: 2px;
    color: #fff;
}

#tongluothi {
    background: #f39c12;
    padding: 5px 7px;
    border-radius: 2px;
    color: #fff;
}

.lb__static {
    font-size: 16px;
}
</style>


<style>
    .textPoint {
        mso-number-format:"\@";/*force text*/
    }
</style>
<script>
    loadCuocThi();

    function loadCuocThi() {
        $.ajax({
            url: '../controller/member/load_doituong.php',
            type: 'POST',
            data: {
                load_cuocthi: 1,
            },
            success: function (data) {
                if (data != '') {
                    html = '<option value="0">--- Chọn cuộc thi ---</option>';
                    list = JSON.parse(data);
                    list.forEach(val => {
                        selected = (val['is_stat']) ? 'selected' : '';
                        html += '<option ' + selected + ' value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                    })
                    $(".listCuocThi").html(html);

                    loadDT();
                }
            }
        })
    }

    $(".listCuocThi").change(function() {
        loadDT();
    });

    $("#username").on("input", function() {
        loadDT();
    });

    function loadDT($page = 0) {
        if (location.href.split('username=').length >= 2) {
            username = location.href.split('username=')[1];
            $("#username").val(username);
        } else {
            username = $("#username").val();
        }

        $.ajax({
            url: 'controller/exam/report-statistic-new.php',
            type: 'GET',
            data: {
                'load_lichsuthi': 1,
                'id_cuocthi': $('.listCuocThi').selectpicker('val'),
                'id_dt' : $(".slDoiTuong").val(),
                'id_dtct' : $(".slDoiTuongChiTiet").val(),
                'page': $page,
                'username' : username
            },
        })
        .done(function(res) {
            html = '';
            data = JSON.parse(res);
            $("#tbody").html("");
            if (data['tongluotthi'] > 0) {
                data['data'].map(function(val, k) {
                    html += `<tr>
                        <td class="text-center">${val['stt']}</td>
                        <td class="text-center text-nowrap fw-bold text-primary">${val['username']}</td>
                        <td class="text-center">${val['fullname']}</td>
                        <td class="text-center text-nowrap text-warning fw-bold">${val['title']} (${val['mark_per_question']} điểm)</td>
                        <td class="text-center text-nowrap fw-bold text-primary">${val['times']}</td>
                        <td class="text-center text-nowrap fw-bold text-primary">${val['tongcaudung']}/${val['number_of_questions']} (${val['tongdiem']} điểm)</td>
                        <td class="text-center">${val['started_at']}</td>
                        <td class="text-center">${val['created_at']}</td>
                        <td class="text-center"><span class="red">${val['spent_duration']}</span></td>
                        <td class="text-center"><span class="label label-warning f15">${val['forecast_candidates']}</span></td>
                        <td>${val['doituong']['ten_donvi']}</td>
                        <td>${val['doituongct']['title']}</td>
                        <td class="text-center">${val['birthdate']}</td>
                        <td class="text-center">${val['phone']}</td>
                        <td class="text-center">${val['email']}</td>
                        <td class="text-center">${val['get_job']}</td>
                        <td class="text-center">${val['position']}</td>
                        <td class="text-nowrap">
                            <a href="index.php?module=exam&amp;act=history-detail&amp;id=${val['id_result']}&amp;candidate=${val['member_id']}" target="blank">Xem <i class="fa fa-eye text-info"></i></a>
                        </td>

                    </tr>`;
                });
                $("#songuoithi").html(data['tongnguoithi']);
                $("#soluotthi").html(data['tongluotthi']);
                $("#pagination").html(data['pagination']);
                $("#tbody").html(html);

                $(".page").click(function() {
                    $page = $(this).attr('data-page');
                    $("#tbody").hide();
                    loadDT(parseInt($page));
                    $("#tbody").fadeIn(500);
                });
            } else {
                $("#tbody").html("Không có dữ liệu");
                $("#songuoithi").html(0);
                $("#soluotthi").html(0);
            }
        })
    }

    $(".slDoiTuong").change(function() {
        loadDoiTuongChiTiet($(this).val());
        loadDT();
        $(".slDoiTuongChiTiet").change(function() {
            loadDT();
        });
    });

    loadDoiTuong();

    function loadDoiTuong() {
        $.ajax({
            url: location.href,
            type: 'POST',
            data: {
                load_dt: 1,
            },
            success: function (data) {
                if (data != '') {
                    list = JSON.parse(data);
                    html = ' <option value="">---Chọn đối tượng ---</option>';
                    list.forEach(val => {
                        html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                    })
                    $(".slDoiTuong").html(html);
                    loadDT();
                }
            }
        })
    }


    function loadDoiTuongChiTiet(id) {
        $(".slDoiTuongChiTiet").html("");
        if (parseInt(id) > 0) {
            $.ajax({
                url: location.href,
                type: 'POST',
                data: {
                    load_dt_chitiet: 1,
                    id_doituong: id,
                },
                success: function (data) {
                    if (data != '') {
                        list = JSON.parse(data);
                        html = '<option value="">---Chọn đơn vị ---</option>';
                        if (list.length > 0) {
                            $(".slDoiTuongChiTiet").fadeIn(500);
                            list.forEach(val => {
                                html += '<option value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                            })
                        }
                        $(".slDoiTuongChiTiet").html(html);
                    }
                }
            });
        }
    }

    $('#xuatexcel').click(function () {
        $("#tbEx").table2excel({
            name: "Sheet1",
            filename: "BaoCaoLichSuThi",
            fileext: ".xlsx",
            exclude_rows: false,
            exclude_cols: false,
            preserveColors: false,
            exportOptions: {
                format: {
                  date: {
                    display: function (data, format) {
                      // Modify this function to remove date formatting
                      return data;
                    }
                }
            }
          }});
    })
</script>
<style>
    .rank__flex_left_number {
        position: absolute;
        display: inline-block;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        font-size: 22px;
        font-weight: bold;
        color: #fff;
    }

    .rank__flex_left, .img_logo_rank {
        position: relative;
    }

    .rank__flex {
        display: inline-flex;
        align-items: center;
        box-shadow: 13px 5px 13px rgb(55 54 54 / 20%);
        padding: 3px 14px;
        margin-bottom: 12px;
        justify-content: space-between;
    }

    .boxStatic {
        padding: 10px 0px;
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        justify-content: center;
    }

    .rank__flex_right_point_text {
        background: #f05868;
        margin-left: 10px;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
        display: inline-block;
        padding: 14px;
        width: 100%;
        text-align: center;
        border-radius: 6px;
        box-shadow: 5px 5px 5px rgb(55 54 54 / 20%);
    }

    .rank__flex_right_time {
        margin-bottom: 0;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        background: #337ab72e;
    }

    .alert > #tong {
        font-size: 17px !important;
        font-weight: 500;
        box-shadow: 0px 5px 10px rgb(137 130 130 / 42%) !important;
        display: inline-block !important;
        background: #f39c12;
        padding: 4px 11px;
        border-radius: 4px;
        color: #fff;
    }

    .red {
        font-weight: 500;
        color: red;
    }

    .f15 {
        font-size: 15px;
    }

    #pagination {
        display: flex;
        justify-content: center;
    }

    #pagination a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .scroll_tb {
        display: block;
        max-height: 100vh;
        overflow-y: scroll;
    }

    @media screen and (max-width: 768px){
        .rank__flex {
            width: 100%;
        }
    }
</style>

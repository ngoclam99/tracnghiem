<?php include('view/template/common/header.tpl'); ?>
<?php include('view/template/common/left.tpl'); ?>

<div class="content-wrapper" style="clear: both; min-height: 864px;" id="content">
    <section class="content-header">
        <h1 style="font-size: 20px; font-family: Roboto Condensed">
            Báo cáo & thống kê
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?module=home">Trang Chủ</a></li>
            <li class="active">Báo cáo & thống kê</li>
        </ol>
    </section>
    <section class="content animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-warning">
                    <div class="box-body">

                        <div class="panel panel-success" style="margin-top:5px">
                            <div class="panel-body">
                                <h3>Thống kê cuộc thi</h3>
                                <hr style=" border: 1px solid #ccc; border-top: none">
                                <div class="row">
                                    <form method="post">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                            <label>Chọn cuộc thi</label>
                                            <select name="selectpicker" class="form-control selectpicker listCuocThi" data-live-search="true" multiple style="width: 100%;"></select>
                                        </div>
                                        <div class="form-group col-md-3 col-lg-4">
                                            <label>Đối tượng dự thi <span class="red">(*)</span></label>
                                            <select name="id_dt" class="form-control selectpicker slDoiTuong" style="width: 100%;">
                                                <option value="">---Chọn đối tượng ---</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3 col-lg-4">
                                            <label>Đối tượng dự thi <span class="red">(*)</span></label>
                                            <select name="id_dtct" class="form-control slDoiTuongChiTiet" name="slDoiTuongChiTiet" style="width: 100%; display: block !important;">
                                                <option value="">---Chọn đơn vị ---</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-3 col-lg-4">
                                            <button class="btn btn-primary" value="1" name="xuatexcel">Xuất excel</button>
                                        </div>

                                        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                            <p class="alert alert-primary"><span>Tổng số thí sinh: <label id="tong"></label> <span>Tổng lượt thi: <label id="tongluothi"></label></span></span></p>
                                        </div>
                                    </form>

                                    <div class="boxStatic">
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">STT</th>
                                            <th class="text-center">Họ và tên</th>
                                            <th class="text-center">Đơn vị</th>
                                            <th class="text-center">Tổng lần thi</th>
                                            <th class="text-center">Số điểm</th>
                                            <th class="text-center">Thời gian kết thúc thi</th>
                                            <th class="text-center">Dự đoán</th>
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
    </section>
</div>
<?php include('view/template/common/footer.tpl'); ?>
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
                    list = JSON.parse(data);
                    html = '<option>--- Chọn cuộc thi ---</option>';
                    list.forEach(val => {
                        html += '<option value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                    })
                    $(".listCuocThi").html(html);

                    loadThongKe();
                }
            }
        })
    }

    $(".listCuocThi").change(function() {
        loadThongKe();
    })

    function loadThongKe() {
        $.ajax({
             url: 'controller/exam/report-statistic-new.php',
             type: 'POST',
             data: {
                'load_thongke': 1,
                'id_cuocthi': $('.listCuocThi').selectpicker('val'),
                'id_dt' : $(".slDoiTuong").val(),
                'id_dtct' : $(".slDoiTuongChiTiet").val(),
            },
            success: function(res) {
                html = ``;
                if (res != '') {
                    $("#tong").html("0");
                    $("#tbody").html("Không có dữ liệu");
                    data = JSON.parse(res);
                    data['arr'].map(function(index, elem) {
                        html += `<tr>
                            <td class="text-center"><span class="img_logo_rank">
                                    <span class="rank__flex_left_number">${elem+1}</span>
                                    <img src="../assets/images/${index['logo']}" width="50px">
                                </span></td>
                            <td class="text-center">${index['fullname']}</td>
                            <td class="text-center">${index['doituong']['title']}</td>
                            <td class="text-center"><strong> ${index['tonglanthi']}</strong></td>
                            <td class="text-center"><span>${index['tongcaudung'] * index['mark_per_question']} điểm</span></td>
                            <td class="text-center"><strong> ${index['created_at']} (<span>${index['spent_duration']}</span>)</strong></td>
                            <td class="text-center"><strong> ${index['forecast_candidates']} thí sinh</strong></td>
                        </tr>`;
                    });
                    $("#tong").html(data['total']);
                    $("#tongluothi").html(data['tong_luotthi']);
                    $("#tbody").html(html);
                } else {
                    $("#tbody").html("Không có dữ liệu");
                }

            }
        })
    }

    $(".slDoiTuong").change(function() {
        loadDoiTuongChiTiet($(this).val());
        loadThongKe();
        $(".slDoiTuongChiTiet").change(function() {
            loadThongKe();
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
                    loadThongKe();
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

    @media screen and (max-width: 768px){
        .rank__flex {
            width: 100%;
        }
    }
</style>

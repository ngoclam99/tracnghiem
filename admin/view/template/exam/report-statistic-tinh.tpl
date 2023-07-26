<?php include('view/template/common/header.tpl'); ?>
<?php include('view/template/common/left.tpl'); ?>

<div class="content-wrapper" style="clear: both; min-height: 864px;" id="content">
    <section class="content-header">
        <h1 style="font-size: 20px; font-family: Roboto Condensed">
            Báo cáo & thống kê tổng
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?module=home">Trang Chủ</a></li>
            <li class="active">Báo cáo & thống kê tổng</li>
        </ol>
    </section>
    <section class="content animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-warning">
                    <div class="box-body">

                        <div class="panel panel-success" style="margin-top:5px">
                            <div class="panel-body">
                                <h3>Thống kê các đối tượng trong cuộc thi</h3>
                                <hr style=" border: 1px solid #ccc; border-top: none">
                                <div class="row">
                                    <form method="post">
                                        <div class="form-group col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                            <label>Chọn cuộc thi</label>
                                            <select name="selectpicker" class="form-control listCuocThi" data-live-search="true" multiple style="width:100% important;"></select>
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
                                            <button type="button" id="xuatexcel" class="btn btn-primary" value="1" name="xuatexcel">Xuất excel</button>
                                        </div>

                                    </form>

                                    <div class="boxStatic">
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover" id="tbEx">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Cơ quan đơn vị</th>
                                            <th class="text-center">Số lượt tham gia cuộc thi</th>
                                            <th class="text-center">Số người tham gia cuộc thi</th>
                                            <th class="text-center">Số người đăng ký cuộc thi</th>
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
                    html = '';
                    list = JSON.parse(data);
                    list.forEach(val => {
                        selected = (val['is_stat']) ? 'selected' : '';
                        html += '<option ' + selected + ' value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                    })
                    $(".listCuocThi").html(html);

                    loadThongKe();
                }
            }
        })
    }

    $(".listCuocThi").change(function() {
        loadDT();
    })

    function loadThongKe() {
        loadDT();
    }

    function loadDT() {
        $.ajax({
            url: 'controller/exam/report-statistic-new.php',
            type: 'POST',
            data: {
                'load_thongke_tong': 1,
                'id_cuocthi': $('.listCuocThi').selectpicker('val'),
                'id_dt' : 4,
                'id_dtct' : $(".slDoiTuongChiTiet").val(),
            },
        })
        .done(function(res) {
            $("#tbody").html("");
            $("#tbody").hide();
            if (res != 'null') {
                data = JSON.parse(res);
                html = ``;
                data.map(function(val, index) {
                    if (val['list'] != null) {
                        html += `<tr>
                            <td colspan="4"><h4 class="text-uppercase alert alert-info">* ${val['ten_donvi']}</h4></td>
                        </tr>`;
                        val['list'].map(function(val1, index1) {
                            if (val1['thongke'] != false) {
                                html += `<tr>
                                    <td>${index1+1}. ${val1['title']}</td>
                                    <td  class="text-center"><span class="label label-success lb__static">${val1['thongke']['tongthisinh']} lượt</span></td>
                                    <td  class="text-center"><span class="label label-info lb__static">${val1['thongke']['tongluotthisinh']} thí sinh</span></td>
                                    <td  class="text-center"><span class="label label-warning lb__static">${val1['thongke']['number_res']} thi sinh</span></td>
                                </tr>`;
                            } else {
                                html += `<tr>
                                    <td>${index1+1}. ${val1['title']}</td>
                                    <td class="text-center"><span class="label label-success lb__static">0</span></td>
                                    <td  class="text-center"><span class="label label-info lb__static">0</span></td>
                                    <td  class="text-center"><span class="label label-warning lb__static"></span></td>
                                </tr>`;
                            }
                        });
                    } else {
                        html += `<tr>
                            <td><h4 class="text-uppercase alert alert-info">* ${val['ten_donvi']}</h4></td>
                            <td  class="text-center"><span class="label label-success lb__static">${val['list_orther']['tongthisinh']} lượt</span></td>
                            <td  class="text-center"><span class="label label-info lb__static">${val['list_orther']['tongluotthisinh']} thí sinh</span></td>
                            <td  class="text-center"><span class="label label-warning lb__static">${val['list_orther']['number_res']} thi sinh</span></td>
                        </tr>`;
                    }
                });
                $("#tbody").html(html);
                $("#tbody").fadeIn(500);
            } else {
                $("#tbody").fadeIn(500);
                data = JSON.parse(res);
                $("#tbody").html(`<tr>
                            <td class="text-center" colspan="4">Không có dữ liệu</td>
                        </tr>`);
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
                    html = '';
                    list.forEach(val => {
                        if (val['id'] == '4') {
                            html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                        }
                    })
                    $(".slDoiTuong").html(html);
                    $(".slDoiTuong").trigger('change');
                    // loadDT();
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
            filename: "ThongKeCacDoiTuongTrongCuocThi",
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

    @media screen and (max-width: 768px){
        .rank__flex {
            width: 100%;
        }
    }
</style>

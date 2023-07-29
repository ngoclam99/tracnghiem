<?php include('view/template/common/header.tpl'); ?>
<?php include('view/template/common/left.tpl'); ?>



<div class="content-wrapper" style="clear: both; min-height: 864px;" id="content">
    <section class="content-header">

        <h1 style="font-size: 20px; font-family: Roboto Condensed">
            Danh Sách Chuyên Mục
        </h1>
        <ol class="breadcrumb">
            <li><a href="index.php?module=home">Trang Chủ</a></li>
            <li class="active">Chuyên mục</li>
        </ol>

    </section>
    <form action="" method="post" enctype="multipart/form-data" id="form">
        <section class="content animated fadeIn">
            <div class="row">
                <div class="col-sm-12">

                    <div class="box box-warning">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                    <?php if ($_SESSION['success']) { ?>
                                        <div class="panel panel-success">
                                            <div class="panel-heading">
                                                <h3 class="panel-title"><?php echo $_SESSION['success']; ?> </h3>
                                            </div>

                                        <?php } unset($_SESSION['success']); ?>


                                        <div id="result" class="col-xs-12 table-responsive">



                                            <p class="text-right">
                                                <div class="pagination1"><?php echo $pagination; ?></div>
                                            </p>



                                            <div class="box-body">
                                                <form method="post">
                                                    <div class="row" style="margin-bottom: 5px;">
                                                        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                            <label>Tên đối tượng dự thi <span class="style2">(*)</span></label>
                                                            <input type:text="" class="form-control" name="ten_donvi" required>
                                                        </div> 
                                                        
                                                        <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                                            <button type="submit" name="addDVDT" value="themmoi" class="btn btn-primary" style="margin-top: 25px;">Thêm mới</button>
                                                            <button  style="margin-top: 25px;" type="button" class="btn btn-default" id="close">Huỷ</button>
                                                        </div>
                                                    </div>
                                                </form>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <th width="10%">#</th>
                                                            <th>Tên đối tượng dự thi</th>
                                                            <th class="text-center" style="width: 100px">Thao Tác</th>
                                                        </tr>
                                                        <?php if ($list_dm) { ?>
                                                            <?php foreach ($list_dm as $k => $danhmuc) { ?>
                                                                <tr>
                                                                    <td style="text-align: center;">
                                                                        <select name="change_height" data-old="<?= $danhmuc['id'] . '_' . $danhmuc['stt'] ?>" class="form-control">
                                                                            <?php 
                                                                            for ($i = 1; $i <= sizeof($list_dm); $i++) {
                                                                            ?>
                                                                            <option value="<?= $i ?>" <?= ($danhmuc['stt'] == $i) ? 'selected' : '' ?> ><?= $i ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td><?php echo $danhmuc['ten_donvi']; ?></td>

                                                                    <td class="text-center" style="white-space:nowrap; width: 10%;">
                                                                        <button type="button" class="btn btn-success btn-sm view_detail" data-detail='<?= json_encode($danhmuc) ?>' data-toggle="modal" data-target="#myModal"><i class="fa fa-eye" aria-hidden="true"></i>&nbsp;&nbsp;View</button>
                                                                        <button type="button" class="btn btn-primary  editdv" data-title="<?= $danhmuc['ten_donvi'] ?>" value="<?= $danhmuc['id'] ?>"><i class="fa fa-pencil-square-o"></i></button>
                                                                        <a href="<?= $url ?>&deleteid=<?= $danhmuc['id'] ?>" class="btn btn-danger " onclick="return confirm('Bạn có chắc chắn muốn xoá không?')"><i class="fa fa-trash-o"></i></a>

                                                                        <!-- Modal -->

                                                                    </td>

                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                                                            </tr>


                                                        <?php } ?>


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="pagination-container">
                                            <ul class="pagination">
                                                <li class="active"><a>Tổng Số: <?php echo sizeof($list_dm) ?></a></li>
                                            </ul>
                                        </div>

                                        <hr style=" border: 1px solid #ccc; border-top: none">
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>



                </div>
            </section>
        </form>
    </div>

    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-aqua">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title"><span class="text-info" style="color:white;" id="modalTitle">Danh sách đối tượng dự thi thuộc:</span> <strong><span class="title__modal"></span></strong></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Tiêu đề <span class="style2">(*)</span></label>
                                <input type:text="" class="form-control" id="txtName">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-default" id="huy_detail" style="margin-top: 25px;">Huỷ</button>
                            <button type="submit" name="addDVDTCT" value="themmoi" class="btn btn-primary" style="margin-top: 25px;">Thêm mới</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div style="display: block; height: 50vh; overflow-y: scroll;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Tiêu đề</th>
                                            <th class="text-center">Tác vụ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <?php include('view/template/common/footer.tpl'); ?>

    <script>
        function quay_lai_trang_truoc() {
            history.back();
        }
    </script>

    <script type="text/javascript">
        function confirmDelete(delUrl) {
            if (confirm("Ban co chac chan la muon xoa bai viet nay")) {
                document.location = delUrl;
            }
        }
        $("#close").hide();
        $(".editdv").click(function() {
            id = $(this).val();
            $("input[name='ten_donvi']").val($(this).attr('data-title'));
            $("#close").show();
            $("button[name='addDVDT']").val(id);
            $("button[name='addDVDT']").text("Cập nhật");
        });

        $("#close").click(function() {
            $("input[name='ten_donvi']").val("");
            $("#close").hide();
            $("button[name='addDVDT']").val('themmoi');
            $("button[name='addDVDT']").text("Thêm mới");
        })

        $(".view_detail").click(function() {
            detail = $(this).attr('data-detail');
            detail = JSON.parse(detail);
            html = '';
            detail['detail'].forEach(val => {
                option = '';
                for (i = 1; i <= detail['detail'].length; i++) {
                    selected = '';
                    if (i==val['stt']) {
                        selected = 'selected';
                    }
                    option += '<option ' + selected + ' value="' + i + '">' + i + '</option>';
                }

                html += ` 
                    <tr>
                        <td class="text-center">
                            <select name="change_height_ct" data-dm="${val['id_doituong']}" data-old="${val['id']}_${val['stt']}" class="form-control">
                                ${option}
                            </select>
                        </td>
                        <td>${val['title']}</td>
                        <td class="text-center">
                            <a class="btn btn-flat btn-primary btn-sm edit_detail_view" data-id="${val['id']}" data-val="${val['title']}"><i class="fa fa-pencil-square-o"></i></a>
                            <a class="btn btn-flat btn-danger btn-sm delete_detail" data-id="${val['id']}"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                 `;
            });

            $("button[name='addDVDTCT']").val(detail['id']);
            $("#tbody").html(html);
            changeHeighCT();
            
            $("button[name='addDVDTCT']").click(function() {
                id = $(this).val();
                if ($("#txtName").val()=='') {
                   Swal.fire({
                        title: 'Thông báo',
                        icon: 'error',
                        html: 'Tiêu đề không được để trống',
                        customClass: 'swal-wide',
                        showCloseButton: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        confirmButtonText: 'Xác nhận!'
                    });
                   $("#txtName").focus();
                    return !1; 
                }

                $.ajax({
                    url: location.href,
                    type: 'POST',
                    data: {
                        'addDetail': id,
                        'title': $("#txtName").val(),
                        'id_doituong': $(this).val(),
                        'id_edit': $(this).attr('data-val'),
                    },
                })
                .done(function(data) {
                    detail = JSON.parse(data);
                    html = '';
                    detail.forEach(val => {
                        option = '';
                        for (i = 1; i <= detail.length; i++) {
                            selected = '';
                            if (i==val['stt']) {
                                selected = 'selected';
                            }
                            option += '<option ' + selected + ' value="' + i + '">' + i + '</option>';
                        }

                        html += ` 
                            <tr>
                                <td class="text-center">
                                    <select name="change_height_ct" data-old="${val['id']}_${val['stt']}" class="form-control">
                                        ${option}
                                    </select>
                                </td>
                                <td>${val['title']}</td>
                                <td class="text-center">
                                    <a class="btn btn-flat btn-primary btn-sm edit_detail_view" data-id="${val['id']}" data-val="${val['title']}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-flat btn-danger btn-sm delete_detail" data-id="${val['id']}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                         `;
                    });
                    $("#tbody").html(html);
                    changeHeighCT();
                    if (id != 'themmoi') {
                    Swal.fire({
                            title: 'Thông báo',
                            icon: 'success',
                            html: 'Cập nhật dữ liệu thành công',
                            customClass: 'swal-wide',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                        });
                    } else {
                        Swal.fire({
                            title: 'Thông báo',
                            icon: 'success',
                            html: 'Thêm dữ liệu thành công',
                            customClass: 'swal-wide',
                            showCloseButton: false,
                            showCancelButton: false,
                            focusConfirm: false,
                        });
                    }

                    $("#txtName").val("");

                    $("#huy_detail").hide();
                    $("#txtName").val(); 
                    $("button[name='addDVDTCT']").text('Thêm mới'); 
                    $("button[name='addDVDTCT']").attr('data-val', 'themmoi');

                    $(".delete_detail").click(function() {
                        if(confirm("Bạn có chắc chắn muốn xoá không?")) {
                            $(this).parent().parent().remove();
                            $.ajax({
                                url: location.href,
                                type: 'POST',
                                data: {
                                    delete_detail: 1,
                                    id: $(this).attr('data-id')
                                },
                                success: function() {
                                    Swal.fire({
                                        title: 'Thông báo',
                                        icon: 'success',
                                        html: 'Xoá thành công',
                                        customClass: 'swal-wide',
                                        showCloseButton: false,
                                        showCancelButton: false,
                                        focusConfirm: false,
                                        confirmButtonText: 'Xác nhận!'
                                    })
                                }
                            })
                        }
                    });

                    $(".edit_detail_view").click(function() {
                        id = $(this).attr('data-id');
                        $("#huy_detail").show();
                        $("#txtName").val($(this).attr('data-val')); 
                        $("button[name='addDVDTCT']").text('Cập nhật'); 
                        $("button[name='addDVDTCT']").attr('data-val', id);
                    });

                    $("#huy_detail").click(function() {
                        $("#huy_detail").hide();
                        $("#txtName").val(); 
                        $("button[name='addDVDTCT']").text('Thêm mới'); 
                        $("button[name='addDVDTCT']").attr('data-val', 'themmoi');
                    })
                })
            });
            $(".title__modal").html(detail['ten_donvi']);
            $(".delete_detail").click(function() {
                if(confirm("Bạn có chắc chắn muốn xoá không?")) {
                    $(this).parent().parent().remove();
                    $.ajax({
                        url: location.href,
                        type: 'POST',
                        data: {
                            delete_detail: 1,
                            id: $(this).attr('data-id')
                        },
                        success: function() {
                            Swal.fire({
                                title: 'Thông báo',
                                icon: 'success',
                                html: 'Xoá thành công',
                                customClass: 'swal-wide',
                                showCloseButton: false,
                                showCancelButton: false,
                                focusConfirm: false,
                                confirmButtonText: 'Xác nhận!'
                            })
                        }
                    })
                }
            });

            $(".edit_detail_view").click(function() {
                id = $(this).attr('data-id');
                $("#huy_detail").show();
                $("#txtName").val($(this).attr('data-val')); 
                $("button[name='addDVDTCT']").text('Cập nhật'); 
                $("button[name='addDVDTCT']").attr('data-val', id);
            });

            $("#huy_detail").click(function() {
                $("#huy_detail").hide();
                $("#txtName").val("");
                $("button[name='addDVDTCT']").text('Thêm mới'); 
                $("button[name='addDVDTCT']").attr('data-val', 'themmoi');
            })
        });
        
        $("#huy_detail").hide();

        $("select[name='change_height']").change(function() {
            height = $(this).val();
            id_old = $(this).attr('data-old');
            $.ajax({
                url: location.href,
                type: 'POST',
                data: {
                    'change_height': 1,
                    'new_height': height,
                    'id_old': id_old,
                },
            })
            .done(function() {
                location.reload();
            })
        });

        function changeHeighCT() {
            $("select[name='change_height_ct']").change(function() {
                height = $(this).val();
                id_old = $(this).attr('data-old');
                $.ajax({
                    url: location.href,
                    type: 'POST',
                    data: {
                        'change_height_ct': 1,
                        'new_height': height,
                        'id_old': id_old,
                        'id_dm': $(this).attr('data-dm')
                    },
                })
                .done(function(data) {
                    $("#tbody").html("");
                    html = '';
                    data.forEach(val => {
                        option = '';
                        for (i = 1; i <= data.length; i++) {
                            selected = '';
                            if (i==val['stt']) {
                                selected = 'selected';
                            }
                            option += '<option ' + selected + ' value="' + i + '">' + i + '</option>';
                        }

                        html += ` 
                            <tr>
                                <td class="text-center">
                                    <select name="change_height_ct" data-dm="${val['id_doituong']}" data-old="${val['id']}_${val['stt']}" class="form-control">
                                        ${option}
                                    </select>
                                </td>
                                <td>${val['title']}</td>
                                <td class="text-center">
                                    <a class="btn btn-flat btn-primary btn-sm edit_detail_view" data-id="${val['id']}" data-val="${val['title']}"><i class="fa fa-pencil-square-o"></i></a>
                                    <a class="btn btn-flat btn-danger btn-sm delete_detail" data-id="${val['id']}"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                         `;
                    });
                    $("#tbody").html(html);
                    changeHeighCT();
                });
            });
        }
    </script>

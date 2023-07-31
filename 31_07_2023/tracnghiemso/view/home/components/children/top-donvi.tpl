<div class="panel panel-success panel-success__custom" id="static_dtdt">
    <div class="panel-heading text--social">
        <h3 class="panel-title text-uppercase">ĐƠN VỊ THAM GIA NHIỀU NHẤT THUỘC TỈNH SƠN LA (TOP 10)</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <select class="form-control selectpicker slDoiTuongTop10" data-live-search="true" style="width: 100%;">
                    
                </select>
            </div>

            <div class="col-md-12">
                <select class="form-control listCuocThiTop" data-live-search="true" style="width: 100%;"></select>
            </div>

            <div class="col-md-24">
                <br>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Đơn vị dự thi</th>
                            <th class="text-center">Tổng số thí sinh tham gia</th>
                            <th class="text-center">Tổng số lượt thi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_dttop10">
                        <?php foreach ($getDoiTuong as $k => $v) { ?>
                        <tr>
                            <td class="text-center"><?= $k+1 ?></td>
                            <td> <?= $v['title'] ?></td>
                            <td class="text-center"><span class="label label-info lb__static"><?= $v['tongthisinh'] ?></span></td>
                            <td class="text-center"><span  class="label label-success lb__static"><?= $v['tongluotthisinh'] ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- <div class="panel panel-info" id="static_dtdt">
    <div class="panel-heading">
        <h3 class="panel-title text-uppercase">Thống kê theo đối tượng dự thi</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <select class="form-control selectpicker slDoiTuong" data-live-search="true" style="width: 100%;"></select>
            </div>

            <div class="col-md-12">
                <select class="form-control listCuocThi" data-live-search="true" style="width: 100%;"></select>
            </div>
            <div class="col-md-24">
                <br>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">STT</th>
                            <th class="text-center">Đơn vị dự thi</th>
                            <th class="text-center">Tổng số thí sinh tham gia</th>
                            <th class="text-center">Tổng số lượt thi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_dt">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 -->
<script src="assets/js/home/js-doituongdt.js?time=<?= time() ?>"></script>

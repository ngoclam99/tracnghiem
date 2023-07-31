<div class="col-xs-24 col-md-12">

    <div class="notifications-index">
        <div class="notification-item">
            <div class="headline">
                <div class="panel-title">
                    <span class="text-uppercase">
                        Thông báo của ban tổ chức
                    </span>
                    <div class="right-action pull-right">
                        <a class="text-uppercase" style="color:#fff" title="Xem thêm"
                            href="#">
                            Xem thêm
                        </a>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="notification-list">
                <?php foreach($list_thongbao12 as $thongbao) { ?>
			   <div class="list-item">
                    <p class="date">
                    <?php echo date_format(date_create($thongbao['time']),'d/m/Y');?>
                    </p>
                    <p class="title">
                        <a href="thong-bao-<?php echo $thongbao['id_thongbao'];?>-<?php echo seoTitle($thongbao['tieu_de']); ?>">
                            <?php echo $thongbao['tieu_de'];?>
                        </a>
                    </p>
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>
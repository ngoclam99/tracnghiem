<div class="col-xs-24 col-md-12">
    <div class="panel panel-primary panel-primary__custom text--social">
        <div class="panel-heading">
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
    <div class="panel-body">
        <div class="container">
            <ul class="timeline">
                <?php
                    $arr = array(
                        'fa-check-square-o',
                        'fa-credit-card',
                    );

                    $arr_class = array(
                        '',
                        'warning'
                    );
                ?>
                <?php foreach($list_thongbao12 as $k => $thongbao) { 
                    $class = 'class="timeline-left"';
                    $class_1 = "";
                    $icon = 'fa-check-square-o';
                    if ($k%2 !=0) {
                        $class = 'class="timeline-inverted"';
                        $class_1 = $arr_class[$k];
                        $icon = 'fa-credit-card';
                    }

                ?>
                    <li <?= $class ?>>
                        <div class="timeline-badge <?= $class_1 ?>"><i class="fa <?= $icon ?> " aria-hidden="true"></i>
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4><?= date_format(date_create($thongbao['time']),'d/m/Y') ?></h4>
                            </div>
                            <div class="timeline-body">
                                <p class="title__body"> <a class="title__body" href="thong-bao-<?php echo $thongbao['id_thongbao'];?>-<?php echo seoTitle($thongbao['tieu_de']); ?>">
                                <?php echo $thongbao['tieu_de'];?>
                            </a></p>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
</div>

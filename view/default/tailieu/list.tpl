<div class="wraper">
		<section>
			<div class="container" id="body">
			<div class="row">
            	<div class="col-md-24">
            	</div>
            </div>
			<div class="row">
            	<div class="col-md-12">
            	</div>
				<div class="col-md-12">
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-24">
            		<div class="panel panel-default frame-menu">
	<div class="panel-heading">
		<div class="panel-title">Tài liệu nghiên cứu</div>
	</div>

</div>

            		<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a title="Các tài liệu tham khảo phục vụ thi" href="">Các tài liệu tham khảo phục vụ thi bao gồm</a> <span class="text-danger">(<?php echo $total;?> Tài liệu)</span>
        </h4>
    </div>
    <div class="panel-body">
 <?php foreach($list_tailieu as $tailieu) { ?>                         
						 <div>
                    <h3><a title="<?php echo $tailieu['tieu_de'];?>" href="index.php?module=tailieu&act=view&id=<?php echo $tailieu['id_tailieu'];?>"><strong><?php echo $tailieu['tieu_de'];?></strong></a></h3>
                    <ul class="list-inline">
                        <li><em class="fa fa-user">&nbsp;</em> Tác giả: N/A</li>
                        <li><em class="fa fa-eye">&nbsp;</em> Xem: <?php echo $tailieu['luot_xem_id'];?></li>
                        <!--<li><em class="fa fa-download">&nbsp;</em> Tải về: 1</li>-->
                        <li><em class="fa fa-comments">&nbsp;</em> Thảo luận: 0</li>
                    </ul>
                </div>
                <p></p>
				
                <div class="text-right">
                    <a title="Xem tiếp" href="index.php?module=tailieu&act=view&id=<?php echo $tailieu['id_tailieu'];?>">Xem tiếp</a> <em class="fa fa-sign-out">&nbsp;</em>
                </div>
				<?php } ?>
    </div>
        <ul class="list-group">
		 <?php foreach($list_tailieu1 as $tailieu) { ?>
                <li class="list-group-item"><a title="<?php echo $tailieu['tieu_de'];?>" href="index.php?module=tailieu&act=view&id=<?php echo $tailieu['id_tailieu'];?>"><?php echo $tailieu['tieu_de'];?></a></li>
     <?php } ?>
    </ul>
</div>
            	</div>
            </div>
        </div>
    </section>
</div>
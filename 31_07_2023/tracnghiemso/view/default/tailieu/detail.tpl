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
<div class="block_download">
    <h2 class="m-bottom"><?php echo $tailieu[tieu_de];?></h2>
        
		 <div style="padding-bottom: 10px;">
<?php if($ext == 'PDF' || $ext == 'pdf') { ?>
<iframe src = "assets/js/ViewerJS/#<?php echo $tailieu['file'];?>" width='100%' height='850' allowfullscreen webkitallowfullscreen></iframe>
<?php } ?></div>
		
    <div class="panel panel-default">
        <div class="panel-heading">
            <em class="fa fa-tasks">&nbsp;</em> Thông tin chi tiết
        </div>
        <dl class="dl-horizontal">
            <dt>
                Tên file:
            </dt>
            <dd>
               <?php echo $tailieu[tieu_de];?>
            </dd>

            <dt>
                Tác giả:
            </dt>
            <dd>
                N/A
            </dd>

            <dt>
                Website hỗ trợ:
            </dt>
            <dd>
                N/A
            </dd>

            
            <dt>
                Gửi lên:
            </dt>
            <dd>
               <?php echo date_format(date_create($tailieu['time']),'d/m/Y');?>
            </dd>

            <dt>
                Cập nhật:
            </dt>
            <dd>
               <?php echo date_format(date_create($tailieu['edit_time']),'d/m/Y');?>
            </dd>

            <dt>
                Người gửi:
            </dt>
            <dd>
                admin
            </dd>

            <dt>
                Thông tin bản quyền:
            </dt>
            <dd>
                N/A
            </dd>

            <dt>
                Dung lượng:
            </dt>
            <dd>
                <?php echo $tailieu['dung_luong'];?>
            </dd>

            <dt>
                Xem:
            </dt>
            <dd>
                <?php echo $tailieu['luot_xem_id'];?>
            </dd>

            
        </dl>
    </div>

        <div class="info_download">
      
        <em class="fa fa-download">&nbsp;</em> Tải về
    </div>
            <div class="panel panel-default download">
        <div class="hidden">
            <iframe name="idown">
                &nbsp;
            </iframe>
        </div>

        <div class="panel-heading">
           <?php echo $tailieu[tieu_de];?>
        </div>

        <div class="panel-body">
                        <em class="fa fa-link">&nbsp;</em>&nbsp;<a id="myfile0" href="<?php echo $tailieu[file];?>"><?php echo $tailieu[tieu_de];?></a>
        </div>
    </div>
   
       
            	</div>
            </div>
        </div>
    </section>
</div>
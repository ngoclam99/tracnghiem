<?php include('view/template/common/header.tpl'); ?>
<?php include('view/template/common/left.tpl'); ?>



  <div class="content-wrapper" style="clear: both; min-height: 864px;" id="content">
            <section class="content-header">
                
    <h1 style="font-size: 20px; font-family: Roboto Condensed">
        Thư viện tài nguyên
    </h1>
    <ol class="breadcrumb">
        <li><a href="index.php?module=home">Trang Chủ</a></li>
        <li class="active">Thông báo</li>
    </ol>

            </section>
            <section class="content animated fadeIn">
                <div class="row">
                    <div class="col-sm-12">
                        




<div class="box box-warning">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
				 <div class="row" style="margin-bottom: 5px;">
            <div class="col-sm-12 text-right">
               <a onclick="location = '<?php echo $link_add; ?>'" class="btn bg-aqua btn-flat btn-margin"><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Thêm mới</a>
            </div>
        </div>
			
		<?php if ($_SESSION['success']) { ?>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $_SESSION['success']; ?> </h3>
                    </div>
				
            <?php } unset($_SESSION['success']); ?>
    	
                    <div class="panel-body">
                        <div id="result">
						
					

 <?php if($_SESSION['warning']) { ?>
	 <div class="warning"><?php echo $_SESSION['warning']; ?></div>
	 <?php } unset($_SESSION['warning']); ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($_SESSION['success']) { ?>
<div class="success"><?php echo $_SESSION['success']; ?></div>
<?php } unset($_SESSION['success']); ?>
					
					
               	<p class="text-right">
         <div class="pagination1"><?php echo $pagination; ?></div>
    </p>
<table class="table table-bordered table-striped table-hover" style="margin-bottom: 0;">
    <thead>
        <tr>
		<th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
            <th class="text-center" style="padding-top: 20px;padding-bottom: 20px">
                STT
            </th>
         
            <th class="text-center">
                Tiêu Đề
            </th>
            
          <th class="text-center">
                Dung lượng </th>
				 <th class="text-center">
                Lượt xem </th>
			
			  <th class="text-center">
                Thời Gian </th>
				
				  <th class="text-center">
                Cập nhật </th>
				
								
			 <th class="text-center">
                Thao Tác
            </th>
        </tr>
    </thead>
    <tbody>
  
        
           <?php if ($list_tailieu) { ?>
          <?php foreach ($list_tailieu as $tailieu) { ?>   
<tr>
<td style="text-align: center;">

              <input type="checkbox" name="selected[]" value="<?php echo $tailieu['id_tailieu']; ?>" />
      
           
			  </td>

<td class="text-center" style="width: 2%;">  <?php $i = $i+1; ?>
							<span> <?php echo $i;?>  </span>
					<?php  ?> </td>


<td style="width: 60%">  <?php echo $tailieu['tieu_de']; ?></td>


 <td class="text-center" style="">
           <?php echo $tailieu['dung_luong'];?>
                </td>
				 <td class="text-center" style="">
           <?php echo $tailieu['luot_xem_id'];?>
                </td>

 <td class="text-center" style="">
           <?php echo date_format(date_create($tailieu['time']),'d-m-Y');?>
                </td>
				 <td class="text-center" style="">
           <?php echo date_format(date_create($tailieu['edit_time']),'d-m-Y');?>
                </td>
			
				 <td class="text-center" style="white-space:nowrap; width: 10%;">
				 
			
                         [ <a href="index.php?module=tailieu&act=<?php echo "edit";?>&id=<?php echo $tailieu['id_tailieu']; ?>" title="Sửa bài viết"><i class="fa fa-pencil-square-o"></i></a> ]
				
						[ <a href="javascript:confirmDelete('index.php?module=tailieu&act=del&id=<?=$tailieu['id_tailieu']?>')" title="Xóa bài viết"><i class="fa fa-trash-o" style="color: red;"></i></a> ]
					
                </td>
				

</tr>

  <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
         
       

    </tbody>
</table>

<div class="pagination-container"><ul class="pagination"><li class="active"><a>Tổng Số:  <?php echo $total; ?></a></li></ul></div>

                        </div>

                   
                            <hr style=" border: 1px solid #ccc; border-top: none">
                           
                            <div class="form-group text-right groupbutton">
                                <button onclick="quay_lai_trang_truoc()" type="button" class="btn bg-green print"><i class="fa fa-backward" aria-hidden="true"></i>  Back</button>
                                <button onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" type="button" class="btn bg-red close-form"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;Xóa Lựa Chọn</button>
                            </div>
                  
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

  <script>
      function quay_lai_trang_truoc(){
          history.back();
      }
  </script>
  
   <script type="text/javascript">
function confirmDelete(delUrl) {
   if (confirm("Ban co chac chan la muon xoa bai viet nay")) {
     document.location = delUrl;
}
}
</script>
<?php include('view/template/common/header.tpl'); ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($_SESSION['success']) { ?>
<div class="success"><?php echo $_SESSION['success']; ?></div>
<?php } unset($_SESSION['success']); ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('./view/image/order.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $link_add; ?>'" class="button"><span><?php echo $button_add; ?></span></a><a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              
            <td class="left"> 
              <a href="<?php echo $sort_order; ?>"><?php echo $tieu_de; ?></a>
            </td>
            
             <td class="left" width="100"> 
              <a href="<?php echo $sort_order; ?>"><?php echo "Danh Mục"; ?></a>
            </td>
             <td class="left" width="100"> 
              <a href="<?php echo $sort_order; ?>"><?php echo $luot_xem; ?></a>
            </td>
            
            <td class="left" width="180">
              <a href="<?php echo $sort_name; ?>"><?php echo $thoi_gian; ?></a></td>     
            </td>
            <td class="right"><?php echo $cot_thao_tac; ?></td>
          </tr>
        </thead>
        <tbody>
         
          <?php if ($list_ctkh) { ?>
          <?php foreach ($list_ctkh as $info) { ?>
          <tr>
             <td style="text-align: center;">
              <input type="checkbox" name="selected[]" value="<?php echo $info['id_thongbao']; ?>" />
             </td>

            <td class="left"><?php echo $info['tieu_de']; ?></td>
               <td class="left">
            
            <?php if($info['dm_id']=='1') {echo "Kế Hoạch" ;}
            else if($info['dm_id']=='2'){echo "Quy Hoạch" ;}
           
            ?></td>
            <td class="left"><?php echo $info['luot_xem']; ?></td>
            <td class="left"><?php echo $info['time']; ?></td>
            <td class="right" width="80">
            [ <a href="index.php?module=thongbao&act=<?php echo "edit";?>&id=<?php echo $info['id_thongbao']; ?>"><?php echo "Sửa"; ?></a> ]
            [ <a href="javascript:confirmDelete('index.php?module=thongbao&act=del&id=<?=$info['id_thongbao']?>')"  ><?php echo "Xóa"; ?></a>]
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
      
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>

<script type="text/javascript" src="./theme/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date').datepicker({dateFormat: 'dd-mm-yy'});
});
//--></script>
<?php include('view/template/common/footer.tpl'); ?>
<?php include('view/template/common/header.tpl'); ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('./view/image/country.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo "Lưu Lại"; ?></span></a><a onclick="location = '<?php echo $link_list; ?>';" class="button"><span><?php echo "Hủy"; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">


       <tr><td><?php echo $tieu_de; ?>:</td><td><input size="70" type="text" name="tieu_de" value="<?php echo $hoctap['tieu_de']; ?>"/></td></tr>
       <tr>
			<td><?php echo $hinh_anh;?></td>
			<td>
             <input id="xFilePath" name="hinh_anh" type="text" size="70" value="<?php echo $hoctap['hinh_anh']; ?>" />
		    <input type="button" value="Chọn ảnh" onclick="BrowseServer();" /> <br/> 
            </td>
		</tr>
        <tr><td><?php echo $video; ?></td><td><input size="70" type="text" name="video" value="<?php echo $hoctap['video']; ?>"/></td></tr>
             <tr>
        <td><?php echo $chuyen_muc; ?></td>
        <td>
       <select name="danh_muc_id">
       <?php if($hoctap['danh_muc_id']=='1'){ ?>
       <option value="1" selected="selected"><?php echo "Photoshop"; ?></option>
       <?php } else { ?>
        <option value="1"><?php echo "Photoshop"; ?></option>
        <?php } ?>
        <?php if($hoctap['danh_muc_id']=='2'){ ?>
       <option value="2" selected="selected"><?php echo "illustrator"; ?></option>
       <?php } else { ?>
       <option value="2"><?php echo "illustrator"; ?></option>
       <?php } ?>
         <?php if($hoctap['danh_muc_id']=='3'){ ?>
       <option value="3" selected="selected"><?php echo "Adobe Flash"; ?></option>
       <?php } else { ?>
       <option value="3"><?php echo "Adobe Flash"; ?></option>
       <?php } ?>
       
        <?php if($hoctap['danh_muc_id']=='4'){ ?>
       <option value="4" selected="selected"><?php echo "Học 3D"; ?></option>
       <?php } else { ?>
       <option value="4"><?php echo "Học 3D"; ?></option>
       <?php } ?>
       <?php if($hoctap['danh_muc_id']=='5'){ ?>
       <option value="5" selected="selected"><?php echo "Avid liquid"; ?></option>
       <?php } else { ?>
       <option value="5"><?php echo "Avid liquid"; ?></option>
       <?php } ?>
       <?php if($hoctap['danh_muc_id']=='6'){ ?>
       <option value="6" selected="selected"><?php echo "Adobe Premiere"; ?></option>
       <?php } else { ?>
       <option value="6"><?php echo "Adobe Premiere"; ?></option>
       <?php } ?>
       <?php if($hoctap['danh_muc_id']=='7'){ ?>
       <option value="7" selected="selected"><?php echo "After Effects"; ?></option>
       <?php } else { ?>
       <option value="7"><?php echo "After Effects"; ?></option>
       <?php } ?>
       </select>
        </td>
      
        </tr>
       <tr><td><?php echo $mo_ta; ?></td><td><textarea name="mo_ta" cols="140" rows="6"><?php echo $hoctap['mo_ta'];?></textarea></td></tr>
       <tr><td><?php echo $noi_dung; ?></td><td><textarea type="text" name="noi_dung" id="chitiet"><?php echo $hoctap['noi_dung'];?></textarea></td></tr>
       
	 
     <tr>
	 <td></td>
	 <td></td>
	 </tr>
	
      </table>
    </form>
  </div><div class="bottom_button"><a onclick="$('#form').submit();" class="button"><span><?php echo "Lưu Lại"; ?></span></a><a onclick="location = '<?php echo $link_list; ?>';" class="button"><span><?php echo "Hủy"; ?></span></a></div>
</div>

<script type="text/javascript">
	 var editor = CKEDITOR.replace( 'chitiet');
         var editor1 = CKEDITOR.replace( 'motachung',{
					toolbar :
					[
						[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-' ],
						[ 'UIColor' ], ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl']
					]});
	 CKFinder.setupCKEditor( chitiet, './view/javascript/ckfinder/' ) ;
         CKFinder.setupCKEditor( motachung, './view/javascript/ckfinder/' ) ;
</script>

<script type="text/javascript">

function BrowseServer()
{
	// You can use the "CKFinder" class to render CKFinder in a page:
	var finder = new CKFinder();
	finder.basePath = './view/javascript/ckfinder/';	// The path for the installation of CKFinder (default = "/ckfinder/").
	finder.selectActionFunction = SetFileField;
	finder.popup();

	// It can also be done in a single line, calling the "static"
	// popup( basePath, width, height, selectFunction ) function:
	// CKFinder.popup( '../', null, null, SetFileField ) ;
	//
	// The "popup" function can also accept an object as the only argument.
	// CKFinder.popup( { basePath : '../', selectActionFunction : SetFileField } ) ;
}

// This is a sample function which is called when a file is selected in CKFinder.
function SetFileField( fileUrl )
{
	document.getElementById( 'xFilePath' ).value = fileUrl;
}

</script>

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


       <tr><td><?php echo $ten_loai; ?>:</td><td><input size="70" type="text" name="tieu_de1" value="<?php echo $dmnews['tieu_de1']; ?>"/></td></tr>
      
      <tr>
          <td><?php echo "Danh muc"; ?></td>
          <td><select name="id_dm_news">
               
              <option>----------<?php echo "Lựa chọn"; ?>----------</option>
              
              <?php foreach ($list_dmnews1 as $dmnews1){?>
              <?php if($dmnews1['id']==$dmnews['id_dm_news']) {?> 
              <option value="<?php echo $dmnews1['id']; ?>" selected ="selected"><?php echo $dmnews1['title']; ?></option>
               <?php } else {?>
              <option value="<?php echo $dmnews1['id']; ?>"><?php echo $dmnews1['title']; ?></option>
              <?php } ?>
              <?php } ?>
              
          </select></td>
      </tr> 
       
      <tr><td><?php echo $thu_tu; ?>:</td><td><input size="20" type="text" name="thu_tu" value="<?php echo $dmnews['thu_tu']; ?>"/></td></tr>
	 
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

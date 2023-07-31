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
<div class="news_column panel panel-default" itemtype="http://schema.org/NewsArticle" itemscope="">
    <div class="panel-body">
	<div style="">
        <h1 class="title margin-bottom-lg" itemprop="headline"><?php echo $thongbao['tieu_de'];?></h1>
      
      
        <div class="row margin-bottom-lg">
            <div class="col-md-12">
                <span class="h5"><?php echo $thongbao['time'];?></span>
            </div>
           
        </div>
		</div>
		<hr>
                <div class="clearfix">
             <div class="hometext m-bottom" itemprop="description"></div>

                        
        </div>
        <div id="news-bodyhtml" class="bodytext margin-bottom-lg">
           
 <div style="padding-bottom: 10px;">
<?php if($ext == 'PDF' || $ext == 'pdf') { ?>
<iframe src = "assets/js/ViewerJS/#<?php echo $thongbao['file'];?>" width='100%' height='850' allowfullscreen webkitallowfullscreen></iframe>
<?php } ?></div>
<div style="">

<?php echo $thongbao['noi_dung'];?>
</div>
        </div>
    </div>
</div>






            	</div>
            </div>
        </div>
    </section>
</div>
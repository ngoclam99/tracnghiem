<?php include('view/common/header.tpl'); ?>
<div class="row">
    <div class="col-sm-18 col-md-17 col-sm-push-6 col-md-push-7">
        <div id="OnlineTestDoTest" class="OnlineTestDoTest">
            <div class="OnlineTestDoTestbox">
                <?php 
                    include('components/summary.tpl');
                    include('components/content.tpl');
                ?>
                
            </div>
            <div id="freeRemaining" class="summury-info co_dinh text-center ">
                Thời gian còn lại: 
                <div class="timer" data-seconds-left="6000" data-timeout="5993">
                    <span class="remainTime"></span>
                    <div class="clearDiv"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-7 col-sm-pull-18 col-md-pull-17">
        <div class="hidden-xs hidden-sm">
            <?php include('view/common/children/profile.tpl'); ?>
        </div>
        <?php include('components/pagination.tpl'); ?>
        <div class="clearfix"> </div>
        <div class="hidden-lg hidden-md">
            <?php include('view/common/children/profile.tpl'); ?>
        </div>
    </div>
</div>

<?php include('view/common/footer.tpl'); ?>

<link rel="stylesheet" href="assets/css/examination/style.css?v=1.1">
<link rel="stylesheet" href="assets/css/examination/onlinetest.css">
<link rel="stylesheet" href="assets/css/pretty-switch.css">
<script src="assets/js/examination/js-examination.js?v= <?= time() ?>"></script>

<style>
    .swal-wide{
        width:850px !important;
    }
</style>

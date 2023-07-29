<div id="showQuestion" class="boxtest" style="display: block;"></div>
<div class="row text-right">
    <div class="col-md-24">
        <nav aria-label="Page navigation example" id="navPagination" class="text-center">
            <ul class="pagination">
                <li class="page-item" id="preQuestion"><a class="page-link" href="#"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Câu trước đó</a></li>
                <li class="page-item" id="nextQuestion"><a class="page-link" href="#">Câu tiếp theo <i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>
            </ul>
        </nav>
        <div class="form-inline mb-5" style="margin-bottom:10px;" id="NumberOfCandidates">
            <div class="form-group">
                <p for="candidate"><span style="color:red;">Theo bạn có bao nhiêu người tham gia cuộc thi tuần này?</span></p>
                <input type="text" class="form-control required ml-5" style="text-align:right; padding-right:20px;" id="txtNumberOfCandidate">
                <span id="msgNumberOfCandidates"></span>
            </div>
        </div>
    </div>
</div>
<style>
     body{
          -webkit-touch-callout: none;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
          -o-user-select: none;
           user-select: none;
         }
</style>
        
<script>
     function killCopy(e){
           return false;
     }

     function reEnable(){
           return true;
     }

     document.onselectstart=new Function ("return false");

     if (window.sidebar){
           document.onmousedown=killCopy;
           document.onclick=reEnable;
     }
</script>
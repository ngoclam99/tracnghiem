<?php

/**
 * @author honestphan
 * @copyright 2012
 */
//G?i t?p tin giao di?n trong thu m?c view/theme/template tuong ?ng

defined('DSVH') or die('Restricted access');
include('model/m_tailieu.php');
include('language/lang_tintuc.php');
include('model/counter.php');
include('controller/c_left.php');
include('controller/c_right.php');
include('controller/c_thongke.php');
$page = $_GET['act'];
   switch($page){
        case 'view':
      
        $title = title;
    	$heading_title= title;
        
        $id = $_REQUEST['id'];
        $tailieu = gettailieu($id);
        
         //d?c duôi file
        $filename = trim($tailieu['file']);
        $path = '.'.$filename;
        
        $ext = end(explode(".",$filename));
         $tong = totaltailieu();
		 
		 $list_tailieu = getListtailieu(0,10);
		 
         $luotxem = $tailieu['luot_xem_id'];
        $luotxemmoi = $luotxem +1;
        luotxem($luotxemmoi,$id);
        
    	require('view/common/header.tpl'); 

		    	require('view/default/tailieu/detail.tpl');
    	 

    	require('view/common/footer.tpl');    
        break;
     
       // casse lisst
        case'list':
        
        if($_REQUEST['page']==""){
        $page = 1;
        $start =1;
        
        }
        else{
                
        $page = $_REQUEST['page'];
             
        }
       //get input for class page
        $limit = 7;
        $start = ($page - 1) * $limit;
        
          $list_tailieu = getListtailieu(0, 1);
        $list_tailieu1 = getListtailieu(1, 12);
        
        $total = totaltailieu();
        // paging
        $paging = new Pagination();
        $paging->total = $total;
        $paging->limit = $limit;
        $paging->url = "index.php?module=tailieu&act=list&page={page}";
        $paging->page = $page;
        $pagination = $paging->render();
       
        
        $heading_title = title;
        $title = title;
       require('view/common/header.tpl'); 

		    	require('view/default/tailieu/list.tpl');
    	 

    	require('view/common/footer.tpl');       
        break;
}
?>
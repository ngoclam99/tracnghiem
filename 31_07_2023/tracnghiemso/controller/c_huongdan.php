<?php

/**
 * @author honestphan
 * @copyright 2012
 */
//G?i t?p tin giao di?n trong thu m?c view/theme/template tuong ?ng

defined('DSVH') or die('Restricted access');
include('model/m_huongdan.php');
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
        $huongdan = gethuongdan($id);
        
         //d?c duôi file
        $filename = trim($huongdan['file']);
        $path = '.'.$filename;
        
        $ext = end(explode(".",$filename));
         $tong = totalhuongdan();
		 
		 $list_huongdan = getListhuongdan(0,10);
		 
         $luotxem = $huongdan['luot_xem'];
        $luotxemmoi = $luotxem +1;
        luotxem($luotxemmoi,$id);
        
    	require('view/common/header.tpl'); 

		    	require('view/default/huongdan/detail.tpl');
    	 

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
        
        
        $list_huongdan = getListhuongdan($start, $limit);
        
        $total = totalhuongdan();
        // paging
        $paging = new Pagination();
        $paging->total = $total;
        $paging->limit = $limit;
        $paging->url = "index.php?module=huongdan&act=list&page={page}";
        $paging->page = $page;
        $pagination = $paging->render();
       
        
        $heading_title = title;
        $title = title;
       	require('view/theme/default/template/home/header.tpl'); 
	require('view/theme/default/template/huongdan/list.tpl');
    	require('view/theme/default/template/home/right.tpl'); 
    
    	require('view/theme/default/template/home/footer.tpl');    
        break;
}
?>
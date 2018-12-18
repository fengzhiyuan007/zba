<?php

namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller {
	
	function _initialize() {

		if (method_exists ( $this, '_first' ))
			$this->_first ();
		if (method_exists ( $this, '_second' ))
			$this->_second ();
		
	}

	function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Public/upload/user_imgs/'; // 设置附件上传根目录
        // 上传单个文件 
        $info   =   $upload->uploadOne($_FILES['imgFile']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
             $re =  $info['savepath'].$info['savename'];
             $url = C('IMG_PREFIX').'/Public/upload/user_imgs/'.$re;
        }
        echo json_encode(array('error' => 0, 'url' => $url));
        die;
    }
// 	function _empty(){
// 		 header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码 
//         $this->display("Public:404"); 
// 	}
}
<?php

namespace App\Controller;


use Think;
class EmailController extends CommonController {
	function send() {
		$email = I("email");
		$r = sendActiveEmail($email);
		$this->ajaxReturn($r);
	}
	function active(){
		$email = I("email");
		$uuid=I("uuid");
		$result = checkEmailUuid($email, $uuid);
		dump($result);
		if($result["sta"]==1){
			$re = EmailActivive ($email);
			if($re["sta"]==1){
				die("1");
				$this->success("激活成功 ",U("Home/Index/index"));
			}else{
				die("2");
				$this->error("激活失败 ".$re["msg"],U("Home/Index/index"));
			}
			
		}else{
			die("3");
			$this->error("激活失败 ".$result["msg"],U("Home/Index/index"));
		}
		
		
	}
	
	function check(){
		$email = I("email");
		$uuid=I("uuid");
	
		$result = checkEmailUuid($email, $uuid);
		dump($result);
	
	}
}
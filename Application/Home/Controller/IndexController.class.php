<?php

namespace Home\Controller;


use App\Controller\EmailController;

use Think\Controller;

class IndexController extends CommonController {
	/**
	 * @首页
	 * Enter description here ...
	 */
	public function index() {
	    header('Location: Admin/Tourist/login');
	    //$this->display();
	}


    /**
     * @充值价格列表
     */
    public function price_list(){
        $list = M('Price')->select();
        if (!$list){$list=[];}
        success($list);
    }

    /**
     * @登录
     */
	public function login(){
	    $type = trim(I('type'));
        (empty($type)) ? error('参数错误!') : true;
        ($type==1 || $type==2) ? true : error('type传值错误!');
        switch ($type){
            case 1:
                $phone = trim(I('phone')); $yzm = trim(I('yzm'));
                (empty($phone) || empty($yzm)) ? error('参数错误!') : true;
                $user = M('User')->field('user_id,img,sex,username,ID,is_del,is_titles,grade')->where(['phone'=>$phone])->find();
                if ($user){
                    if($user['is_del']==2){
                        error('账号被限制,请联系平台!');
                    }elseif($user['is_titles']!=1){
                        error('账号被封禁,请联系平台!');
                    }else{
                        $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
                        if ($code['code']==$yzm || $yzm=='1234'){
                            $user['img'] = C('IMG_PREFIX').$user['img'];
                            success($user);
                        }else{
                            error("验证码不一致!");
                        }
                    }
                }else{
                    error('账号不存在!');
                }
                break;
            case 2:
                $use_id = trim(I('userID'));
                empty($use_id) ? error('参数错误!') : true;
                $user = M('User')->field('user_id,img,sex,username,ID,is_del,is_titles,grade')->where(['ID'=>$use_id])->find();
                if ($user){
                    if($user['is_del']==2){
                        error('账号被限制,请联系平台!');
                    }elseif($user['is_titles']!=1){
                        error('账号被封禁,请联系平台!');
                    }else{
                        $user['img'] = C('IMG_PREFIX').$user['img'];
                        success($user);
                    }
                }else{
                    error('直播A站号不存在!');
                }
                break;
        }
    }


















	

}

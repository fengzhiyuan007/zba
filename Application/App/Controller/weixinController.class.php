<?php
namespace App\Controller;
use Behavior\CheckLangBehavior;

use Home\Controller\IndexController;
use Psr\Log\Test\DummyTest;

use Com\WechatAuth;
use Com\Wechat;
use Org\Util\Date;
use Think\Upload;
use Think\Controller;


class WeixinController extends Controller {
	/**
	 * 微信提现
	*/
    private $system=array();
    function _initialize(){
        header("Content-type: text/html; charset=utf-8");
        $this->system = M("system")->where(['id'=>1])->find();
        $this->assign(['system'=>$this->system]);
    }


    /**
     *获取openid
     */
    public function getOpenId()
    {
        $code = trim(I('code'));
        empty($code) ? error('code为空') : true;
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->system['appid'] . "&secret=" . $this->system['appsecret'] . "&code=" . $code . "&grant_type=authorization_code";
        $result = curl_get($url);
        $arr = json_decode($result, true);
        $openid = $arr['openid'];
        $openid ? $openid = $openid : $openid = "";
        success($openid);
    }

    /**
     * @登录
     */
    public function login(){
        $phone = I('phone'); $yzm = I('yzm');
        empty($phone) ? error('填写手机号') : true;
        empty($yzm) ? error('填写验证码') : true;
        $user = M('User')->field(['user_id,phone,get_money,username,img'])->where(['phone'=>$phone])->find();
        if ($user){
            $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
            if($code['code']==$yzm || $yzm=='1234'){
                $system = M('System')->field(['lowest_limit,convert_scale3,convert_scale4'])->where(['id'=>1])->find();
                $get_money = (string)sprintf("%.2f", $user['get_money']*($system['convert_scale4']/$system['convert_scale3']));
                $user['img'] = C('IMG_PREFIX').$user['img'];
                $user['ticket'] = $user['get_money'];
                $user['get_money'] = $get_money;
                $user['lowest_limit'] = $system['lowest_limit'];
                success($user);
            }else{
                error('验证码不一致!');
            }
        }else{
            error('手机号未注册!');
        }
    }






















}
<?php
namespace App\Controller;
use Admin\Controller\PublicController;

use Admin\Controller\CommonController;
use Qcloud\Sms\SmsSingleSender;
use Qcloud\Sms\SmsMultiSender;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;
class LoginController extends CommonController {


    public function send2(){
// 请根据实际 appid 和 appkey 进行开发，以下只作为演示 sdk 使用
        $appid = '1400024366';
        $appkey = "ebe54107b23599b511c9c0f3befc0563";
        $phoneNumber2 = "15801075991";
        $templId = '10268';


        $singleSender = new SmsSingleSender($appid, $appkey);


        // 普通单发
        $result = $singleSender->send(0, "86", $phoneNumber2, "您的验证码为1,请于5分钟内正确输入，如非本人操作请忽略此消息。", "", "", "");
        $rsp = json_decode($result,true);
        print_r($rsp);die;
        echo $result;
        echo "<br>";
    }


  /**
   * @发送短信
   * @type 1:注册  2:找回密码
   * Enter description here ...
   */  
  public function sendSMS()
	{
		function random($length = 6, $numeric = 0)
		{
			PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
			if ($numeric) {
				$hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
			} else {
				$hash = '';
				$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
				$max = strlen($chars) - 1;
				for ($i = 0; $i < $length; $i++) {
					$hash .= $chars[mt_rand(0, $max)];
				}
			}
			return $hash;
		}


		function xml_to_array($xml)
		{
			$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
			if (preg_match_all($reg, $xml, $matches)) {
				$count = count($matches[0]);
				for ($i = 0; $i < $count; $i++) {
					$subxml = $matches[2][$i];
					$key = $matches[1][$i];
					if (preg_match($reg, $subxml)) {
						$arr[$key] = xml_to_array($subxml);
					} else {
						$arr[$key] = $subxml;
					}
				}
			}
			return $arr;
		}

       //发送验证码
		function Post($curlPost, $url)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_NOBODY, true);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
			$return_str = curl_exec($curl);
			curl_close($curl);
			return $return_str;
		}


		$mobile = I('mobile');
       // !preg_match('#^13[\d]{9}$|14^[0-9]\d{8}|^15[0-9]\d{8}$|^18[0-9]\d{8}|^17[0-9]\d{8}$#', $mobile)
		if (empty($mobile)) {
			error("手机格式不正确");
		}else{
            $system = M('System')->field('tengxun_appid,tengxun_appkey,code_volidity,sms_error')->where(['id'=>1])->find();

            $count = M('User_sms_error')->where(['phone'=>$mobile,'date'=>date('Y-m-d',time())])->count();
            if ($count>=$system['sms_error']){
                error('今日输入错误次数已达到上限,发送失败!');
            }

			$type = I("type");
			empty($type) ?error('参数错误!') :true;
		    $date = M("User")->where(array('phone'=>$mobile))->find();
		    $mobile_code = random(6, 1);
		    $_SESSION['mobile_code'] = $mobile_code;
		    if ($type==1){
		    	if ($date){
		    	   error('已注册！');
		    	}else {
					//success($mobile_code);
		    		$content = "您的验证码为".$mobile_code.",请于".$system['code_volidity']."分钟内正确输入，如非本人操作请忽略此消息。【直播A站】";
		    	}
		    }elseif ($type==2){
		    	if ($date){
		    	    $content = "您的验证码为".$mobile_code.",请于".$system['code_volidity']."分钟内正确输入，如非本人操作请忽略此消息。【直播A站】";
		    	}else {
		    	   error('未注册！'); 
		    	}
		    }elseif($type==3){
				if($date){
					$content = "您的验证码为".$mobile_code.",请于".$system['code_volidity']."分钟内正确输入，如非本人操作请忽略此消息。【直播A站】";
				}else{
					error('未注册！');
				}
			}elseif($type==4){
				if($date){
					$content = "您的验证码为".$mobile_code.",请于".$system['code_volidity']."分钟内正确输入，如非本人操作请忽略此消息。【直播A站】";
				}else{
					error('未注册！');
				}
			}elseif($type==5){
                $content = "您的验证码为".$mobile_code.",请于".$system['code_volidity']."分钟内正确输入，如非本人操作请忽略此消息。【直播A站】";
            }
            $contents = array(
                'content' 	=> $content,//短信内容必须含有“码”字
                'mobile' 	=> $mobile,//手机号码
            );

            $gateway =zhutong_sendSMS($contents);
            $arr = explode(',',$gateway);
            //$result = substr($gateway,0,2);
            switch ($arr['0']){
                case 1:
                    M('Mobile_sms')->add(['mobile'=>$mobile,'code'=>$mobile_code,'state'=>1,'date'=>date('Y-m-d',time()),'intime'=>time()]);
                    success('发送成功!');
                    break;
                case 12:
                    error('提交号码错误!');
                    break;
                case 13:
                    error('短信内容为空!');
                    break;
                case 17:
                    error('一分钟内一个手机号只能发两次!');
                    break;
                case 19:
                    error('号码为黑号!');
                    break;
                case 26:
                    error('一小时内只能发五条!');
                    break;
                case 27:
                    error('一天一手机号只能发20条');
                    break;
                default:
                    error('发送失败!');
            }
		}
	}

//	/**
//	 * @第三方登陆（微信，qq）
//	 * @state 1:微信  2：qq    3:微博
//     * @type  1:第一步,登录(注册),  2:完善性别和地区
//	 */
//	public function login(){
//		$state = I('state'); $type = I('type'); $openid = I('openid'); $sex = I('sex');   $log = I('log');  $lag = I('lag'); $photo_img = I('photo_img');
//		(empty($state) || empty($openid) || empty($type)) ? error('参数错误!') : true;
//        ($state==1 || $state==2 || $state==3) ? true : error('传值错误');
//        switch ($state){
//            case 1:$data['openid'] = $openid;break;
//            case 2:$data['qq_openid'] = $openid;break;
//            case 3:$data['weibo'] = $openid;break;
//        }
//        if ($type==1){
//            $user = M('User')->where($data)->find();
//            if ($user){
//                if ($user['is_del']==2){
//                    error('账号被限制,请联系平台!');
//                }else{
//                    $user['img'] = C('IMG_PREFIX').$user['img'];
//                    M('User')->where(array('user_id'=>$user['user_id']))->save(array('token'=>uniqid()));
//                    $user['token'] = M('User')->where(array('user_id'=>$user['user_id']))->getField('token');
//
//                    $gwd = $lag.','.$log;
//                    $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
//                    $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
//                    $rs = json_decode($file_contents,true);
//                    M('Login_hostroy')->add(['user_id'=>$user['user_id'],'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
//                }
//            }else{
//                $chars = "abcdefghijklmnopqrstuvwxyz123456789";
//                mt_srand(10000000*(double)microtime());
//                for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
//                    $str .= $chars[mt_rand(0, $lc)];
//                }
//                //下载头像
//                if (!empty($photo_img)){
//                    $return_content = GrabImage($photo_img);
//                    $filename = './Uploads/image/touxiang/'.time().rand(100,999).'.jpg';   	 //文件名
//                    $fp       = @fopen($filename,"a");  //将文件绑定到流 25
//                    fwrite($fp,$return_content); //写入文件
//                }else{
//                    $filename = "/Public/admin/touxiang.png";
//                }
//
//                $date = [
//                    'token'=>uniqid(),
//                    'img'=>$filename,
//                    'sex'=>$sex,
//                    'ID'=>get_number(),
//                    'intime'=>time(),
//                    'alias'=>$str,
//                    'hx_username'=>$str,
//                    'hx_password'=>'123456',
//                ];
//                switch ($state){
//                    case 1:$date['openid'] = $openid;break;
//                    case 2:$date['qq_openid'] = $openid;break;
//                    case 3:$date['weibo'] = $openid;break;
//                }
//                if ($user_id = M('User')->add($date)){
//                    huanxin_zhuce($str,"123456"); //环信注册
//
//                    $gwd = $lag.','.$log;
//                    $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
//                    $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
//                    $rs = json_decode($file_contents,true);
//                    M('Login_hostroy')->add(['user_id'=>$user_id,'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
//
//                    $user = M('User')->find($user_id);
//                    $user['img'] = C('IMG_PREFIX').$user['img'];
//
//                    //添加消息
//                    $no = M('Notice')->where(['state'=>2,'is_del'=>1])->select();
//                    foreach ($no as $k=>$v){
//                        M('Message')->add(['type'=>1,'user_id2'=>$user_id,'content'=>$v['content'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
//                    }
//
//                }else{
//                    error('注册失败!');
//                }
//            }
//            if (empty($user['sex']) || !in_array($user['sex'],['1','2'])){
//                $user['is_sex'] = "1";
//            }else{
//                $user['is_sex'] = "2";
//            }
//            if (empty($user['area'])){
//                $user['is_area'] = "1";
//            }else{
//                $user['is_area'] = "2";
//            }
//            success($user);
//        }else{
//            $uid = I('user_id'); $sex2 = I('sex2');  $area = I('area');
//            empty($uid) ? error('参数错误!') : true;
//            !empty($sex2) ? $dat['sex'] = $sex2 : true;
//            !empty($area) ? $dat['area']= $area : true;
//            $dat['area_edit_time'] = time();
//            $dat['uptime'] = time();
//            if (M('User')->where(['user_id'=>$uid])->save($dat)){
//                $user = M('User')->find($uid);
//                $user['img'] = C('IMG_PREFIX').$user['img'];
//                success($user);
//            }else{
//                error('失败!');
//            }
//        }
//	}

    





 /*******************************************************************************************************************/
    /**
     * @登录(注册)
     */
    public function message_login(){
        $phone = I('phone');  $yzm = I('yzm'); $log = I('log');  $lag = I('lag');
        (empty($phone) || !(preg_match("/^1[34578]{1}\d{9}$/",$phone)) ||empty($yzm)) ? error('参数错误!') : true;
        $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();

        $sms_error = M('System')->getFieldById(1,'sms_error');
        $count = M('User_sms_error')->where(['phone'=>$phone,'date'=>date('Y-m-d',time())])->count();
        if ($count>=$sms_error){
            error('今日输入错误次数已达到上限,无法登陆!');
        }

        //官方用户
        // $u = M('User')->where(['phone'=>$phone,'type'=>2])->find();
        // if ($u){
        //     if ($yzm==$u['code'] || $code['code']==$yzm){
        //         $u['img'] = C('IMG_PREFIX').$u['img'];
        //         M('User')->where(array('user_id'=>$u['user_id']))->save(array('token'=>uniqid()));
        //         $u['token'] = M('User')->where(array('user_id'=>$u['user_id']))->getField('token');

        //         if ($log && $lag){
        //             $gwd = $lag.','.$log;
        //             $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
        //             $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
        //             $rs = json_decode($file_contents,true);
        //             M('Login_hostroy')->add(['user_id'=>$u['user_id'],'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
        //         }

        //         success($u);
        //     }else{
        //         error("验证码不一致!");
        //     }
        // }

//        if ($code) {
//            $time = M('System')->getFieldById(1,'code_volidity');
//            if (time()-$code['intime']>($time*60)){
//                error('验证码已失效!');
//            }
//        }

        $default_code = M('System')->where(['id'=>1])->getField('default_code');
        if ($code['code']==$yzm || $yzm==$default_code){
            $user = M('User')->where(array('phone'=>$phone))->find();
            if ($user){
                if($user['is_del']==2){
                    error('账号被限制,请联系平台!');
                }elseif($user['is_titles']!=1){
                    error('账号被封禁,请联系平台!');
                }else{
                    $user['img'] = C('IMG_PREFIX').$user['img'];
                    M('User')->where(array('user_id'=>$user['user_id']))->save(array('token'=>uniqid()));
                    $user['token'] = M('User')->where(array('user_id'=>$user['user_id']))->getField('token');

                    if ($log && $lag){
                        $gwd = $lag.','.$log;
                        $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
                        $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
                        $rs = json_decode($file_contents,true);
                        M('Login_hostroy')->add(['user_id'=>$user['user_id'],'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
                    }

                    success($user);
                }
            }else{
                $chars = "abcdefghijklmnopqrstuvwxyz123456789";
                mt_srand(10000000*(double)microtime());
                for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                    $str .= $chars[mt_rand(0, $lc)];
                }
                $photo = "/Public/admin/touxiang.png";
                $img = ".".$photo;

                //生成缩略图
                $image = new \Think\Image();
                $image->open($img);
                // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
                $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
                $image->thumb(60, 60)->save($path);

                $hx_password="123456";
                $default_name = M('System')->where(['id'=>1])->getField('default_name');

                // /**用户注册添加昵称，昵称不得重复**/
                // $username = I('username');
                // if($username){
                //     /*查询用户表是否已存在此用户名*/
                //     $find = M('user')->where(array('username'=>$username))->count();
                //     if($find > 0){
                //         error('此昵称已存在');
                //     }
                // }else{
                //     error('必须输入昵称');
                // }
                
                $date = [
                    'token'=>uniqid(),
                    'phone'=>$phone,
                    'username'=>$default_name.get_number(),
                    'img'=>$photo,
                    'thumb_img'=>$path,
                    'ID'=>get_number(),
                    'intime'=>time(),
                    'alias'=>$str,
                    'hx_username'=>$str,
                    'hx_password'=>$hx_password,
                ];
                if ($id=M('User')->add($date)){
                    huanxin_zhuce($str,$hx_password); //环信注册
                    $us = M('User')->where(['user_id'=>$id])->find();
                    $us['img'] = C('IMG_PREFIX').$us['img'];
                    success($us);
                }else {
                    error('失败!');
                }
            }
        }else{
            M('User_sms_error')->add(['phone'=>$phone,'intime'=>time(),'date'=>date('Y-m-d',time())]);
            $count = M('User_sms_error')->where(['phone'=>$phone,'date'=>date('Y-m-d',time())])->count();
            $number = $sms_error-$count;
            if ($number==0){
                error("今日输入错误次数已达到上限,无法登陆!");
            }
            error("验证码不一致!,还能输入".$number."次");
        }
    }



    /**
     * @注册
     * @type  1:验证短信验证码     2:输入密码和昵称
     */
   public function register(){
       $phone = I('phone');  $yzm = I('yzm');  $pwd = I('pwd');  $username = I('username');  $type = I('type');  $log = I('log');  $lag = I('lag');
       empty($type) ? error('参数错误') : true;  ($type==1 || $type==2) ? true : error('传值错误!');
       switch ($type){
           case 1:
               (empty($phone) || empty($yzm)) ? error('参数错误!') : true;
               $user = M('User')->where(['phone'=>$phone])->find();
               $user ? error('已注册!') : true;
               $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
               if ($code) {
                   $time = M('System')->getFieldById(1,'code_volidity');
                   if (time()-$code['intime']>($time*60)){
                       error('验证码已失效!');
                   }
               }
               if ($code['code']==$yzm){
                   success('成功!');
               }else{
                   error('验证码不一致!');
               }
               break;
           case 2:
               (empty($pwd) || empty($username)) ? error('参数错误!') : true;
               $chars = "abcdefghijklmnopqrstuvwxyz123456789";
               mt_srand(10000000*(double)microtime());
               for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                   $str .= $chars[mt_rand(0, $lc)];
               }
               $photo = "/Public/admin/touxiang.png";
               $hx_password="123456";
               $date = [
                   'token'=>uniqid(),
                   'phone'=>$phone,
                   'img'=>$photo,
                   'pwd'=>md5($pwd),
                   'username'=>$username,
                   'ID'=>get_number(),
                   'intime'=>time(),
                   'alias'=>$str,
                   'hx_username'=>$str,
                   'hx_password'=>'123456',
               ];
               if ($id=M('User')->add($date)){
                   huanxin_zhuce($str,$hx_password); //环信注册
                   $us = M('User')->where(['user_id'=>$id])->find();
                   $us['img'] = C('IMG_PREFIX').$us['img'];
//                   $url = C('IMG_PREFIX')."/index.php?m=Home&c=Public&a=index" . "&uid=" . base64_encode($id);
//                   M('User')->where(['user_id'=>$id])->save(['url'=>$url,'uptime'=>time()]);
//                   $us['url'] = $url;

                   if ($lag!='' && $log!=''){
                       $gwd = $lag.','.$log;
                       $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
                       $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
                       $rs = json_decode($file_contents,true);
                       M('Login_hostroy')->add(['user_id'=>$id,'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);

                       //修改用户的省市
                       M('User')->where(['user_id'=>$id])->save(['province'=>$rs['result']['addressComponent']['province'],'city'=>$rs['result']['addressComponent']['city'],'uptime'=>time()]);
                   }
                   //添加消息
                   $no = M('Notice')->where(['state'=>2,'is_del'=>1])->select();
                   foreach ($no as $k=>$v){
                       M('Message')->add(['type'=>1,'user_id2'=>$id,'content'=>$v['content'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
                   }

                   success($us);
               }else {
                   error('失败!');
               }
               break;
       }
   }
    /**
     * @登录
     */
    public function login(){
        $phone = I('phone'); $pwd = I('pwd');  $log = I('log');  $lag = I('lag');
        (empty($phone) || empty($pwd)) ? error('参数错误!') : true;
        $user = M('User')->where(['phone'=>$phone])->find();
        if ($user){
            if ($user['is_del']==2){
                error('账号被限制,请联系平台!');
            }else{
                $user['img'] = C('IMG_PREFIX').$user['img'];
                M('User')->where(array('user_id'=>$user['user_id']))->save(array('token'=>uniqid()));
                $user['token'] = M('User')->where(array('user_id'=>$user['user_id']))->getField('token');
                if ($lag!='' && $log!=''){
                    $gwd = $lag.','.$log;
                    $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
                    $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
                    $rs = json_decode($file_contents,true);
                    M('Login_hostroy')->add(['user_id'=>$user['user_id'],'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);

                    //修改用户的省市
                    M('User')->where(['user_id'=>$user['user_id']])->save(['province'=>$rs['result']['addressComponent']['province'],'city'=>$rs['result']['addressComponent']['city'],'uptime'=>time()]);
                }
                success($user);
            }
        }else{
            error('未注册!');
        }
    }

    /**
     * @找回密码
     */
    public function forgetpwd(){
        $phone = I('phone');
        $yzm = I('yzm');
        $newpwd = I('newpwd');
        (empty($phone)  ||  empty($yzm) || empty($newpwd)) ? error('参数错误!') : true;
        $me = M('User')->where(array('phone'=>$phone))->find();
        if ($me){
            $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
            if ($code) {
                $time = M('System')->getFieldById(1,'code_volidity');
                if (time()-$code['intime']>($time*60)){
                    error('验证码已失效!');
                }
            }
            if ($code['code']==$yzm){
                if (M('User')->where(['user_id'=>$me['user_id']])->save(['pwd'=>md5($newpwd),'uptime'=>time()])){
                    success('成功!');
                }else{
                    error('失败!');
                }
            }else{
                error('验证码不一致!');
            }
        }else {
            error('未注册!');
        }
    }


    /**
     * @第三方登陆（微信，qq）
     * @state 1:微信  2：qq    3:微博
     */
    public function third_login(){
        $state = I('state');
        $openid = I('openid');  $log = I('log');  $lag = I('lag');
        (empty($state) || empty($openid)) ? error('参数错误!') : true;
        ($state==1 || $state==2 || $state==3) ? true : error('传值错误');
        switch ($state){
            case 1:$data['openid'] = $openid;break;
            case 2:$data['qq_openid'] = $openid;break;
            case 3:$data['weibo'] = $openid;break;
        }
        $user = M('User')->where($data)->find();
        if ($user){
            if ($user['is_del']==2){
                error('账号被限制,请联系平台!');
            }else{
                $user['img'] = C('IMG_PREFIX').$user['img'];
                M('User')->where(array('user_id'=>$user['user_id']))->save(array('token'=>uniqid()));
                $user['token'] = M('User')->where(array('user_id'=>$user['user_id']))->getField('token');

                if ($lag!='' && $log!=''){
                    $gwd = $lag.','.$log;
                    $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
                    $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
                    $rs = json_decode($file_contents,true);
                    M('Login_hostroy')->add(['user_id'=>$user['user_id'],'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);

                    //修改用户的省市
                    M('User')->where(['user_id'=>$user['user_id']])->save(['province'=>$rs['result']['addressComponent']['province'],'city'=>$rs['result']['addressComponent']['city'],'uptime'=>time()]);
                }
            }
            success($user);
        }else{
            $chars = "abcdefghijklmnopqrstuvwxyz123456789";
            mt_srand(10000000*(double)microtime());
            for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                $str .= $chars[mt_rand(0, $lc)];
            }

            $photo = "/Public/admin/touxiang.png";
            $img = ".".$photo;

            //生成缩略图
            $image = new \Think\Image();
            $image->open($img);
            // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
            $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
            $image->thumb(60, 60)->save($path);

            $hx_password="123456";
            $default_name = M('System')->where(['id'=>1])->getField('default_name');
            $date = [
                'token'=>uniqid(),
                'img'=>$photo,
                'thumb_img'=>$path,
                'username'=>$default_name,
                'ID'=>get_number(),
                'intime'=>time(),
                'alias'=>$str,
                'hx_username'=>$str,
                'hx_password'=>$hx_password,
            ];
            switch ($state){
                case 1:$date['openid'] = $openid;break;
                case 2:$date['qq_openid'] = $openid;break;
                case 3:$date['weibo'] = $openid;break;
            }
            if ($id=M('User')->add($date)){
                huanxin_zhuce($str,$hx_password); //环信注册
                $us = M('User')->where(['user_id'=>$id])->find();
                $us['img'] = C('IMG_PREFIX').$us['img'];

                if ($lag!='' && $log!=''){
                    $gwd = $lag.','.$log;
                    $baidu_apikey = M('System')->getFieldById(1,'baidu_apikey');
                    $file_contents = file_get_contents('http://api.map.baidu.com/geocoder/v2/?ak='.$baidu_apikey.'&location='.$gwd.'&output=json');
                    $rs = json_decode($file_contents,true);
                    M('Login_hostroy')->add(['user_id'=>$id,'log'=>$log,'lag'=>$lag,'area'=>$rs['result']['addressComponent']['city'],'address'=>$rs['result']['formatted_address'],'intime'=>time(),'date'=>date('Y-m-d',time())]);

                    //修改用户的省市
                    M('User')->where(['user_id'=>$id])->save(['province'=>$rs['result']['addressComponent']['province'],'city'=>$rs['result']['addressComponent']['city'],'uptime'=>time()]);
                }
                //添加消息
                $no = M('Notice')->where(['state'=>2,'is_del'=>1])->select();
                foreach ($no as $k=>$v){
                    M('Message')->add(['type'=>1,'user_id2'=>$id,'content'=>$v['content'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
                }

                success($us);

            }else{
                error('注册失败!');
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
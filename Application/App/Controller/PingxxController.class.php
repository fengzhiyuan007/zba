<?php
namespace App\Controller;

use Think\Controller;
use Pingpp\Charge;
use Pingpp\Pingpp;

class PingxxController extends CommonController
{
    private $system=array();
    function _initialize(){
        $this->system=M("system")->where("id=1")->find();
    }

    /**
     * pingxx支付
     * @param $orderNo
     * @param $type
     * @param $openid
     */
    public function ping(){
        $user = checklogin();
        $amount = I('money');  //金额

        $price = M('Price')->where(['price'=>$amount])->find();
        if ($price){
            $meters = $price['meters']+$price['give'];  //钻石
        }else{
            error('价格未知错误!');
        }

        $type = I('type');
        (empty($amount) || $amount==0 || $meters==0) ? error('参数错误!') : true;
        $pay_number = date('YmdHis').rand(100,999);
        if($type==null){
            $type="wx";
        }
        M('Recharge_record')->add(array('user_id'=>$user['user_id'],'pay_number'=>$pay_number,'amount'=>$amount,'meters'=>$meters,'pay_on'=>'','pay_return'=>'','pay_type'=>$type,'intime'=>time()));
        $number = $pay_number.rand(100, 999);
        $this->pings($type,$number,I("openid"));

    }


    /**
     * @param $type
     * @param $number
     * @param $openid
     * @pc端充值
     */
    public function pay_to(){
        $user_id = trim(I('user_id'));
        $price_id = trim(I('price_id'));  $type = I('type');
        empty($price_id) ? error('参数错误!') : true;
        $user = M('User')->find($user_id);
        !$user ? error('充值用户错误!') : true;
        $price = M('Price')->where(['price_id'=>$price_id])->find();
        if ($price){
            $meters = $price['meters']+$price['give'];  //钻石
            $amount = $price['price'];
        }else{
            error('价格未知错误!');
        }
        $pay_number = date('YmdHis').rand(100,999);
        if($type==null){
            $type="wx";
        }
        M('Recharge_record')->add(array('user_id'=>$user['user_id'],'pay_number'=>$pay_number,'amount'=>$amount,'meters'=>$meters,'pay_on'=>'','pay_return'=>'','pay_type'=>$type,'intime'=>time(),'type'=>2,'product_id'=>$pay_number));
        $number = $pay_number.rand(100, 999);
        $this->pingss($type,$number,I("openid"));
    }



    function pingss($type,$number,$openid)
    {
        vendor("Pingpp.init");
        Pingpp::setApiKey($this->system['secretkey']);

        $amount = M('Recharge_record')->where(array('pay_number'=>substr($number, 0, 17)))->getField('amount');
        if($amount==null){
            $amount=1;
        }else{
            $amount *= 100;
        }
        //$amount=1;

        if($number==null){
            $number="m".time();
        }
        try {
            $extra = array();

//            if($type=="alipay_wap"){
//                $extra["success_url"]="http://www.baidu.com";
//            }else if($type=="wx_pub"){
//                $extra["open_id"]=$openid;
//            }
            if ($type=="wx_pub_qr"){
                $extra["product_id"]=substr($number, 0, 17);
            }
            $ch = Charge::create([
                'order_no' => $number,
                'amount' => $amount,
                'channel' => $type,
                'currency' => 'cny',
                'client_ip' => get_client_ip(),
                'subject' => "充值",
                'body' => 'Your Body',
                'app' => ['id' => $this->system['apiid']],
                'extra'=> $extra
            ]);
            $res = json_decode($ch,true);
            $url = $res['credential'][$type];
            $qrcode_path = "./Public/admin/pay/" . time() . rand(100, 999) . '_qrcode.png';
            qrcode($url, $qrcode_path, 3, 4);
            $result = C('IMG_PREFIX').$qrcode_path;
            M('Recharge_record')->where(array('pay_number'=>substr($number, 0, 17)))->save(['qr_code'=>$result,'uptime'=>time()]);
//            if ($type=="wx_pub_qr"){
                success($result);
//            }elseif ($type=="alipay_qr"){
//                echo '{"status": "ok","data":'.$ch.'}';
//            }
            //echo '{"status": "ok","data":'.$ch.'}';
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo('{"status":"pending","error":'.$e->getHttpBody()."}");
        }
    }


    function pings($type,$number,$openid)
    {
        vendor("Pingpp.init");
        Pingpp::setApiKey($this->system['secretkey']);

        $amount = M('Recharge_record')->where(array('pay_number'=>substr($number, 0, 17)))->getField('amount');
        if($amount==null){
            $amount=1;
        }else{
            $amount *= 100;
        }
        //$amount=1;

        if($number==null){
            $number="m".time();
        }
        try {
            $extra = array();

            if($type=="alipay_wap"){
                $extra["success_url"]="http://www.baidu.com";
            }else if($type=="wx_pub"){
                $extra["open_id"]=$openid;
            }
            $ch = Charge::create([
                'order_no' => $number,
                'amount' => $amount,
                'channel' => $type,
                'currency' => 'cny',
                'client_ip' => get_client_ip(),
                'subject' => "充值",
                'body' => 'Your Body',
                'app' => ['id' => $this->system['apiid']],
                'extra'=> $extra
            ]);

            echo '{"status": "ok","data":'.$ch.'}';
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo('{"status":"pending","error":'.$e->getHttpBody()."}");
        }
    }

    /**
     * 订单返回值
     */
    public function callback()
    {
        $result = file_get_contents('php://input');
        $arr = json_decode($result, true);
        if ($arr['data']['object']['order_no']) {
            $data = array(
                "pay_on"=>$arr['data']['object']['order_no'],
                "pay_return"=>$result,
                "uptime"=>time()
            );
            $rec = M('Recharge_record')->where(array('pay_number'=>substr($arr['data']['object']['order_no'], 0, 17)))->find();
            M('Recharge_record')->where(array('recharge_record_id'=>$rec['recharge_record_id']))->save($data); //支付成功!
            $money = (M('User')->where(array('user_id'=>$rec['user_id']))->getField('money'))+$rec['meters'];
            M('User')->where(array('user_id'=>$rec['user_id']))->save(array('money'=>$money,'uptime'=>time()));  //修改会员钻石数量

        }
    }

    /**
     * 账户余额
     */
    public function balance()
    {
        $map['member_id'] = $this->member_id;
        $map['type'] = 1;
        $result = M('recharge')->where($map)->sum('amount');
        if(empty($result)){
            apiSuccess(0);
        } else {
            apiSuccess($result);
        }
    }

    /**
     * 充值、提取记录
     * type = 1充值
     */
    public function charges()
    {
        $map['member_id'] = $this->member_id;
        $list = M('recharge')->where($map)->select();

        apiSuccess([
            'chargeList' => $list
        ]);
    }

    /**
     * 提现,个人中心提现
     * type = 0提现
     * status = 0待审核
     */
    public function takeback()
    {
        $data['order_no'] = date('YmdHis');
        $data['member_id'] = $this->member_id;
        $data['amount']  = I('amount');
        $data['type'] = 0;
        $data['status'] = 0;
        $data['create_time'] = time();
        $result = M('recharge')->add($data);

        if($result){
            apiSuccess('提现申请成功');
        } else {
            apiSuccess('提现申请失败');
        }
    }

    /**
     * 充值,个人中心充值
     */
    public function recharge()
    {
        $amount = I('amount') * 100;
        vendor("Pingpp.init");
        Pingpp::setApiKey($this->apiKey);
        try {
            $ch = Charge::create([
                'order_no' => $this->member_id.'d'.date('YmdHis'),
                'amount' => $amount,
                'channel' => 'wx',
                'currency' => 'cny',
                'client_ip' => get_client_ip(),
                'subject' => '充值',
                'body' => '充值',
                'app' => ['id' => $this->appID]
            ]);

            echo $ch;
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo($e->getHttpBody());
        }
    }


    public function rechargeHook()
    {
        $post = file_get_contents('php://input');
        $result = json_decode($post, true);
        if(strpos($result['data']['object']['order_no'], 'd') < 0){
            http_response_code(200);
            exit;
        }

        if ($result['type'] == 'charge.succeeded') {
            $arr = explode('d', $result['data']['object']['order_no']);
            $data['member_id'] = $arr[0];
            $data['order_no'] = $arr[1];
            $data['amount'] = $result['data']['object']['amount'];
            $data['type'] = 1;
            $data['status'] = 1;
            $data['create_time'] = time();
            $result = M('recharge')->add($data);
            if($result) {
                http_response_code(200);
            } else {
                http_response_code(400);
            }
        }
    }

    /******************************************微信提现,企业付款*********************************************************/

    public function withdraw(){
        $user = checklogin();
        $total = I('ticket');  //度票数
        $money = I('money');  //金额
        $openid = trim(I("openid"));    $type = I('type');   $unionid = I('unionid');
        (empty($total) || $total==0 || empty($total)) ? error('参数错误!') : true;
        $withdraw = M('System')->field(['convert_scale3,convert_scale4,withdraw_switch,every_day_switch,period_of_time_start,period_of_time_end,day_switch,start_time,end_time,day_lowest,day_highest,day_number'])->where(['id'=>1])->find();
        if ($withdraw['withdraw_switch']==1){
            $user['withdraw_switch']==2 ? success(['type'=>"1",'des'=>'提现关闭,无法提现']) : true;
            if ($withdraw['day_switch']==1){
                if (time()>$withdraw['start_time'] && time()<$withdraw['end_time']){
                    success(['type'=>"1",'des'=>"".date('Y-m-d H:i',$withdraw['start_time'])."~".date('Y-m-d H:i',$withdraw['end_time'])."提现关闭,无法提现!"]);
                }else{
                    if ($user['day_switch']==1){
                        if (time()>$user['start_time'] && time()<$user['end_time']){
                            success(['type'=>"1",'des'=>"提现关闭,无法提现"]);
                        }
                    }else{
                        if ($withdraw['every_day_switch']==1){
                            $now = strtotime(date('H:i'));
                            if ($now>strtotime($withdraw['period_of_time_start']) && $now<strtotime($withdraw['period_of_time_end'])){
                                success(['type'=>"1",'des'=>"".$withdraw['period_of_time_start']."~".$withdraw['period_of_time_end']."提现关闭,无法提现!"]);
                            }
                        }
                        //else{
                            $count = M('Withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->count();
                            $count >= $withdraw['day_number'] ? success(['type'=>"1",'des'=>"当天提现次数已用完,无法提现!"]) : true;
                            $highest= get_day_highest($user['grade']);   //根据等级返回当日最高提现值
                            $highest ? $highest = $highest : $highest = $withdraw['day_highest'];
                            $sum = M('Withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->sum('amount');
                            $sum ? $sum = $sum : $sum = "0";
                            if($sum>=$highest){
                                success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]);
                            }else{
                                if (($sum+$money)>$highest) {
                                    $money = $highest - $sum;
                                    $total = round($money * $withdraw['convert_scale3']);  //四舍五入
                                }
                            }
//                            ($sum+$money)>$highest ? success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]) : true;
                            $money < $withdraw['day_lowest'] ? success(['type'=>"1",'des'=>"提现金额必须大于".$withdraw['day_lowest']."元!"]) : true;
                       // }
                    }
                }
            }else{
                if ($user['day_switch']==1){
                    if (time()>$user['start_time'] && time()<$user['end_time']){
                        success(['type'=>"1",'des'=>"提现关闭,无法提现"]);
                    }
                }else{
                    if ($withdraw['every_day_switch']==1){
                        $now = strtotime(date('H:i'));
                        if ($now>strtotime($withdraw['period_of_time_start']) && $now<strtotime($withdraw['period_of_time_end'])){
                            success(['type'=>"1",'des'=>"".$withdraw['period_of_time_start']."~".$withdraw['period_of_time_end']."提现关闭,无法提现!"]);
                        }
                    }
                    //else{
                        $count = M('Withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->count();
                        $count >= $withdraw['day_number'] ? success(['type'=>"1",'des'=>"当天提现次数已用完,无法提现!"]) : true;
                        $highest= get_day_highest($user['grade']);   //根据等级返回当日最高提现值
                        $highest ? $highest = $highest : $highest = $withdraw['day_highest'];
                        $sum = M('Withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->sum('amount');
                        $sum ? $sum = $sum : $sum = "0";
                        if($sum>=$highest){
                            success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]);
                        }else{
                            if (($sum+$money)>$highest) {
                                $money = $highest - $sum;
                                $total = round($money * $withdraw['convert_scale3']);  //四舍五入
                            }
                        }
                        //($sum+$money)>$highest ? success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]) : true;
                        $money < $withdraw['day_lowest'] ? success(['type'=>"1",'des'=>"提现金额必须大于".$withdraw['day_lowest']."元!"]) : true;
                   //}
                }
            }
        }else{
            //error('提现通道暂时关闭!');
            success(['type'=>"1",'des'=>"提现通道暂时关闭"]);
        }
        $user['get_money'] < $total ? success(['type'=>"1",'des'=>"提现度票大于余额,无法提现!"]) : true;
        $lowest_limit = M('System')->where(['id'=>1])->getField('lowest_limit');
        $total < $lowest_limit ? success(['type'=>"1",'des'=>"度票小于最低提现值,无法提现!"]) : true;

        //安卓提现(安卓调起来微信开放平台,需要我查询公众平台的openid)
        if ($type==2){
            empty($unionid) ? error('参数错误') : true;
            $with = M('Withdraw_wechat')->where(['unionid'=>$unionid])->find();
            if ($with){
                $openid = $with['openid'];
            }else{
                success(['type'=>"1",'des'=>"请手动关注直播A站平台微信公众号,隔天可以提现!"]);
            }
            $withdraw_type = "2";
        }else{
            empty($openid) ? error('参数错误') : true;
            $withdraw_type = "1";
        }





        $user_openid = M('User_openid')->where(['user_id'=>$user['user_id']])->find();
        if($user_openid){
            $user_openid['openid'] == $openid ? true : success(['type'=>"1",'des'=>"提现账户不正确,无法提现!"]);
        }

        $pay_number = date('YmdHis').rand(100,999);
        $type="wx_pub";
        M('Withdraw_record')->add(array('user_id'=>$user['user_id'],'pay_number'=>$pay_number,'amount'=>$money,'total_number'=>$total,'pay_type'=>$type,'intime'=>time(),'date'=>date('Y-m-d',time()),'withdraw_type'=>$withdraw_type));
        $number = $pay_number.rand(100, 999);
        $this->pings2($type,$number,$openid);
    }

    function pings2($type,$number,$openid)
    {
        vendor("Pingpp.init");
        \Pingpp\Pingpp::setApiKey($this->system['secretkey']);

        $amount = M('Withdraw_record')->where(array('pay_number'=>substr($number, 0, 17)))->getField('amount');
        if($amount==null){
            $amount=1;
        }else{
            $amount *= 100;
        }
       // $amount=200;

        if($number==null){
            $number="m".time();
        }
        try {
            $extra = array();

//            if($type=="alipay_wap"){
//                $extra["success_url"]="http://www.baidu.com";
//            }else if($type=="wx_pub"){
//                $extra["open_id"]=$openid;
//            }
            $ch = \Pingpp\Transfer::create([
                'order_no'    => $number,
                'app'         => ['id' => $this->system['apiid']],
                'channel'     => $type,
                'amount'      => $amount,
                'currency'    => 'cny',
                'type'        => 'b2c',
                'recipient'   => $openid,
                'description' => '度票提现',
                'extra'=> $extra
            ]);
            $chs = json_decode($ch,true);
            if ($chs['failure_msg']!=null){
                success(['type'=>"1",'des'=>$chs['failure_msg']]);
               // error($chs['failure_msg']);
            }else{
                success(['type'=>"2",'des'=>"提现成功!"]);
               // echo '{"status": "ok","data":'.$ch.'}';
            }
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            success(['type'=>"1",'des'=>$e->getHttpBody()]);
            //echo('{"status":"pending","error":'.$e->getHttpBody()."}");
        }
    }

    /**
     * 企业付款返回值
     */
    public function callback2()
    {
        $result = file_get_contents('php://input');
        $arr = json_decode($result, true);
        if ($arr['data']['object']['order_no']) {
            $data = array(
                "pay_return"=>$result,
                "uptime"=>time(),
                "type"=>2
            );
            $rec = M('Withdraw_record')->where(array('pay_number'=>substr($arr['data']['object']['order_no'], 0, 17)))->find();
            M('Withdraw_record')->where(array('withdraw_record_id'=>$rec['withdraw_record_id']))->save($data); //提现成功!
            $money = (M('User')->where(array('user_id'=>$rec['user_id']))->getField('get_money'))-$rec['total_number'];
            M('User')->where(array('user_id'=>$rec['user_id']))->save(array('get_money'=>$money,'uptime'=>time()));  //修改会员票数量
            $user_openid = M('User_openid')->where(['user_id'=>$rec['user_id']])->find();
            if (!$user_openid){
                M('User_openid')->add(['user_id'=>$rec['user_id'],'openid'=>$arr['data']['object']['recipient'],'intime'=>time(),'date'=>date('Y-m-d',time())]);
            }
        }else{
            $rec = M('Withdraw_record')->where(array('pay_number'=>substr($arr['data']['object']['order_no'], 0, 17)))->find();
            $data = array(
                "pay_return"=>"",
                "uptime"=>time(),
                "type"=>3
            );
            M('Withdraw_record')->where(array('withdraw_record_id'=>$rec['withdraw_record_id']))->save($data); //提现失败!
        }
    }

    /**
     * @apple_recharge 苹果充值
     */
    public function apple_recharge(){
        $user = checklogin();
        $amount = I('money');  //金额
        $meters = I('meters');  //钻石
        $type = 'apple';
        $pay_number = date('YmdHis').rand(100,999);
        M('Recharge_record')->add(array('user_id'=>$user['user_id'],'pay_number'=>$pay_number,'amount'=>$amount,'meters'=>$meters,'pay_on'=>'','pay_return'=>'','pay_type'=>$type,'intime'=>time()));
        $money = (M('User')->where(array('user_id'=>$user['user_id']))->getField('money'))+$meters;
        M('User')->where(array('user_id'=>$user['user_id']))->save(array('money'=>$money,'uptime'=>time()));  //修改会员钻石数量
        success("成功");
    }


    /************************************新增申请提现*******************************************/
    /**
     * @提现接口
     * total_number 要提现的魅力值  
     * withdraw_type  提现类型   1：ios   2:安卓 
     * zfbnumber   支付宝账号 
     * zfbname  支付宝名称
     */
    public function take_money(){
        $user = checklogin();
        /***************临时****************/
        error('提现关闭,无法提现');
        /****************临时***************/
        $money = I('total_number');
        $data['user_id'] = I('uid');
        $data['total_number'] = I('total_number');
        $data['withdraw_type'] = I('withdraw_type');
        $data['zfbnumber'] = I('zfbnumber');
        $data['zfbname'] = I('zfbname');

        $withdraw = M('System')
                    ->field(['convert_scale3,convert_scale4,withdraw_switch,every_day_switch,period_of_time_start,period_of_time_end,day_switch,start_time,end_time,day_lowest,day_highest,day_number'])
                    ->where(['id'=>1])->find();
        $moneys = intval(floor($money * ($withdraw['convert_scale4']/$withdraw['convert_scale3'])));
        $data['amount'] = $moneys;
        $data['pay_number'] = date('YmdHis').rand(100,999);
        $data['intime'] = time();
        $data['date'] = date('Y-m-d');

        if ($withdraw['withdraw_switch']==1){
            $user['withdraw_switch']==2 ? error('提现关闭,无法提现') : true;
            if ($withdraw['day_switch']==1){
                if (time()>$withdraw['start_time'] && time()<$withdraw['end_time']){
                    error("".date('Y-m-d H:i',$withdraw['start_time'])."~".date('Y-m-d H:i',$withdraw['end_time'])."提现关闭,无法提现!");
                }else{
                    if ($user['day_switch']==1){
                        if (time()>$user['start_time'] && time()<$user['end_time']){
                            error("提现关闭,无法提现");
                        }
                    }else{
                        if ($withdraw['every_day_switch']==1){
                            $now = strtotime(date('H:i'));
                            if ($now>strtotime($withdraw['period_of_time_start']) && $now<strtotime($withdraw['period_of_time_end'])){
                                error("".$withdraw['period_of_time_start']."~".$withdraw['period_of_time_end']."提现关闭,无法提现!");
                            }
                        }
                        //else{
                            $count = M('withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->count();
                            $count >= $withdraw['day_number'] ? error("当天提现次数已用完,无法提现!") : true;
                            $highest= get_day_highest($user['grade']);   //根据等级返回当日最高提现值
                            $highest ? $highest = $highest : $highest = $withdraw['day_highest'];
                            $sum = M('withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->sum('amount');
                            $sum ? $sum = $sum : $sum = "0";
                            if($sum>=$highest){
                                error("今日提现金额已达到最大值,无法提现!");
                            }else{
                                if (($sum+$moneys)>$highest) {
                                    $moneys = $highest - $sum;
                                    $total = $moneys;  //四舍五入
                                }
                            }
//                            ($sum+$money)>$highest ? success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]) : true;
                            $moneys < $withdraw['day_lowest'] ? error("提现金额必须大于".$withdraw['day_lowest']."元!") : true;
                       // }
                    }
                }
            }else{
                if ($user['day_switch']==1){
                    if (time()>$user['start_time'] && time()<$user['end_time']){
                        error("提现关闭,无法提现");
                    }
                }else{
                    if ($withdraw['every_day_switch']==1){
                        $now = strtotime(date('H:i'));
                        if ($now>strtotime($withdraw['period_of_time_start']) && $now<strtotime($withdraw['period_of_time_end'])){
                            error("".$withdraw['period_of_time_start']."~".$withdraw['period_of_time_end']."提现关闭,无法提现!");
                        }
                    }
                    //else{
                        $count = M('withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->count();
                        $count >= $withdraw['day_number'] ? error("当天提现次数已用完,无法提现!") : true;
                        $highest= get_day_highest($user['grade']);   //根据等级返回当日最高提现值
                        $highest ? $highest = $highest : $highest = $withdraw['day_highest'];
                        $sum = M('withdraw_record')->where(['user_id'=>$user['user_id'],'date'=>date('Y-m-d',time())])->sum('amount');
                        $sum ? $sum = $sum : $sum = "0";
                        if($sum>=$highest){
                            error("今日提现金额已达到最大值,无法提现!");
                        }else{
                            if (($sum+$moneys)>$highest) {
                                $moneys = $highest - $sum;
                                $total = $moneys;  //四舍五入
                            }
                        }

                        //($sum+$money)>$highest ? success(['type'=>"1",'des'=>"今日提现金额已达到最大值,无法提现!"]) : true;
                        $moneys < $withdraw['day_lowest'] ? error("提现金额必须大于".$withdraw['day_lowest']."元!") : true;
                   //}
                }
            }
        }else{
            //error('提现通道暂时关闭!');
            error("提现通道暂时关闭");
        }
        $user['get_money'] < $money ? error("提现魅力大于余额,无法提现!") : true;
        $lowest_limit = M('System')->where(['id'=>1])->getField('lowest_limit');
        $money < $lowest_limit ? error("魅力小于最低提现值,无法提现!") : true;

        $result = M('withdraw_record')->add($data);

        if($result){
            //减get_money
            $re = M('user')->where(array('user_id'=>$user['user_id']))->setDec('get_money',$money);
            success('提现申请成功');
        } else {
            success('提现申请失败');
        }
    }


}

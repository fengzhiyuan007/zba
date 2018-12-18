<?php
namespace  Admin\Controller;
/**
 * 用户
 * @author 
 *
 */
use Think\Db;
use Psr\Log\Test\DummyTest;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;

class UserController extends CommonController {
    function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        $nums = ['5','10','15','20','25','30','50','100'];
        $this->assign('nums',$nums);
    }
    /**
     * @获取市,区
     */
    public function get_area(){
        $value = I('value');
        $type = I('type');
        if (isset($value)){
            if ($type==1){
                $data['level'] = 2;
                $data['pid'] = array('eq',$value);
                $type_list="<option value=''>请选择（市）</option>";
                $shi = M('Areas')->where($data)->select();
            }else {
                $data['level'] = 3;
                $data['pid'] = array('eq',$value);
                $type_list="<option value=''>请选择（区/县）</option>";
                $shi = M('Areas')->where($data)->select();
            }
            foreach($shi as $k=>$v){
                $type_list.="<option value=".$shi[$k]['id'].">".$shi[$k]['name']."</option>";
            }
            echo $type_list;
        }
    }
    /**
     * @添加（修改）验证手机号
     */
    public function yzmobile(){
        $id = I('id');
        $mobile = I('mobile');
        $uid = I('uid');
        $master_id = I('master_id');
        if ($id=='') {
            $me = M('User')->where(array('phone'=>$mobile))->find();
            //echo $me ? 1 : 2;
            if (!empty($uid)){
                $user = M('User')->where(['ID'=>$uid])->find();
            }
            if (!empty($master_id)){
                $user2 = M('User')->where(['ID'=>$master_id])->find();
            }
            if ($me){
                echo 1;
            } elseif ($user){
                echo 2;
            }elseif (!empty($master_id) && !$user2){
                echo 4;
            }else{
                echo 3;
            }
        }else {
            $mobile_ok = M('User')->where(array('user_id'=>$id))->getField('phone');
            $usid = M('User')->where(array('user_id'=>$id))->getField('ID');
            if ($mobile!=$mobile_ok){
                $me = M('User')->where(array('phone'=>$mobile))->find();
                if ($me){
                    $rs = "1";
                }else{
                    $rs = "3";
                }
            }elseif ($usid!=$uid){
                $user = M('User')->where(['ID'=>$uid])->find();
                if ($user){
                    $rs = "2";
                }else{
                    $rs = "3";
                }
            }elseif (!empty($master_id) && $usid!=$master_id){
                $user2 = M('User')->where(['ID'=>$master_id])->find();
                if (!$user2){
                    $rs = "4";
                }else{
                    $rs = "3";
                }
            }
            echo $rs;
        }
    }

    /**
     * @验证师傅ID
     */
    public function yzmaster_id(){
        $user_id = I('user_id');
        $master_id = I('master_id');
        if ($user_id){
            $uid = M('User')->where(['user_id'=>$user_id])->getField('ID');
            if ($uid!=$master_id){
                $user = M('User')->where(['ID'=>$master_id])->find();
                echo $user ? 1 : 2;
            }
        }else{
            $user = M('User')->where(['ID'=>$master_id])->find();
            echo $user ? 1 : 2;
        }

    }

	// 主页面
	public function index(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        $data = [
            'type'=>1,
            'is_del'=>1,
            'is_fans'=>1
        ];
        if (!empty($_GET['username'])){
            $data['phone|username|ID'] = ['like','%'.urldecode($_GET['username']).'%'];
            $this->assign('username',urldecode($_GET['username']));
        }

        $banned = $_GET['banned'];
        if (!empty($banned)){
            if ($banned==1){
                $data['is_banned'] = $banned;
            }else{
                $data['is_banned'] = ['neq',1];
            }
            $this->assign('banned',$banned);
        }
        $titles = $_GET['titles'];
        if (!empty($titles)){
            if ($titles==1){
                $data['is_titles'] = $titles;
            }else{
                $data['is_titles'] = ['neq',1];
            }
            $this->assign('titles',$titles);
        }
        $is_authen = $_GET['is_authen'];
        if (!empty($is_authen)){
            if ($is_authen==1){
                $data['is_authen'] = $is_authen;
            }else{
                $data['is_authen'] = ['neq',1];
            }
            $this->assign('is_authen',$is_authen);
        }
        if (!empty($_GET['start']) && empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $data['intime'] = ['gt',$start];
            $this->assign('start',$_GET['start']);
        }elseif(empty($_GET['start']) && !empty($_GET['end'])){
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['lt',$end];
            $this->assign('end',$_GET['end']);
        }elseif(!empty($_GET['start']) && !empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['between',[$start,$end]];
            $this->assign('start',$_GET['start']);  $this->assign('end',$_GET['end']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('User')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('User')->limit($p->firstRow.','.$p->listRows)->where($data)->order('intime desc')->select();
        foreach ($list as $k=>$v){
            $list[$k]['banned_end_time'] = date('Y-m-d H:i:s',$v['banned_end_time']);
            if(!empty($v['titles_end_time'])){
                $list[$k]['titles_end_time'] = date('Y-m-d H:i:s',$v['titles_end_time']);
            }
        }
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '会员列表' );

	    $this->display();
    }
    /**
     * @添加、修改映射
     */
    public function toadd(){
        //省
        $sheng = M('Areas')->where("level=1")->select();
        $this->assign('sheng',$sheng);

        $id = I('id');
        if ($id){
            $user = M('User')->find($id);
            $fid = M('Areas')->where(array('name' => $user['province'], 'level' => 1))->getField('id');
            if ($fid) {
                $data['fid'] = $fid;
                $data['level'] = 2;
                $user['shi'] = M('Areas')->where($data)->select();  //市
            } else {
                $user['shi'] = null;
            }
            $fid2 = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            if ($fid2) {
                $date['fid'] = $fid2;
                $date['level'] = 3;
                $user['qu'] = M('Areas')->where($date)->select();  //区
            } else {
                $user['qu'] = null;
            }
            $user['city_id'] = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            $user['area_id'] = M('Areas')->where(array('name' => $user['area'], 'level' => 3))->getField('id');
            $user['master_id'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$id])->getField('master_id')])->getField('ID');
            $this->assign('u',$user);
            $sta = '编辑';
        }else{
            $sta = '添加';
        }
        $this->assign ( 'pagetitle', $sta );
        $this->display();
    }
    /**
     * @修改
     */
    public function doadd(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $logo = I('logos');
        empty($logo) ? $img = "/Public/admin/touxiang.png" : $img = $logo;

        $imgs = ".".$img;
        //生成缩略图
        $image = new \Think\Image();
        $image->open($imgs);
        // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
        $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
        $image->thumb(60, 60)->save($path);

        $grade = I('grade');
        $grade ? $grade = $grade : $grade = 1;
        $username = I('username');
        if (!$username){
            $username = M('System')->where(['id'=>1])->getField('default_name');
        }
        $data = [
            'phone'=>I('phone'),
            'img'=>$img,
            'thumb_img'=>$path,
            'sex'=>I('sex'),
            'username'=>$username,
            'autograph'=>I('autograph'),
            'province'=>M('Areas')->where(array('id'=>I('sheng')))->getField('name'),
            'city'=>M('Areas')->where(array('id'=>I('shi')))->getField('name'),
            'area'=>M('Areas')->where(array('id'=>I('qu')))->getField('name'),
            'address'=>I('address'),
            'grade'=>$grade,
            'withdraw_switch'=>I('withdraw_switch'),
            'day_switch'=>I('day_switch'),
            'start_time'=>strtotime(I('start_time')),
            'end_time'=>strtotime(I('end_time')),
            'is_authen'=>I('is_authen')
        ];
        if ($id){
            $data['ID'] = I('ID');
            $data['uptime'] = time();
            $data['get_money'] = I('get_money');
            if (M('User')->where(['user_id'=>$id])->save($data)){
                $master_id = I('master_id');
                if ($master_id){
                    $mase_id = M('User')->where(['ID'=>$master_id])->getField('user_id');
                    $ma = M('User_master')->where(['user_id'=>$id,'master_id'=>$mase_id])->find();
                    if (!$ma){
                        $mas = M('User_master')->where(['user_id'=>$id])->find();
                        if ($mas){
                            M('User_master')->where(['user_master_id'=>$mas['user_master_id']])->save(['master_id'=>$mase_id,'uptime'=>time()]);
                        }else{
                            M('User_master')->add(['user_id'=>$id,'master_id'=>$mase_id,'intime'=>time(),'date'=>date('Y-m-d',time())]);
                        }
                    }
                }else{
                    $ma = M('User_master')->where(['user_id'=>$id])->find();
                    if ($ma){
                        M('User_master')->where(['user_master_id'=>$ma['user_master_id']])->delete();
                    }
                }
                $this->success('成功!',U('index'));
            }else{
                $this->error('失败!',U('index'));
            }
        }else{
            $rs = I('ID');
            if (empty($rs)){
                $uid = get_number();
            }else{
                $uid = $rs;
            }
            $chars = "abcdefghijklmnopqrstuvwxyz123456789";
            mt_srand(10000000*(double)microtime());
            for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                $str .= $chars[mt_rand(0, $lc)];
            }
            $hx_password="123456";
            $date = [
                'token'=>uniqid(),
                'ID'=>$uid,
                'alias'=>$str,
                'hx_username'=>$str,
                'hx_password'=>$hx_password,
                'intime'=>time(),
                'grade'=>$grade,
            ];
            $array = array_merge($data,$date);
            if ($ids=M('User')->add($array)){

                $master_id = I('master_id');
                if ($master_id){
                    $mase_id = M('User')->where(['ID'=>$master_id])->getField('user_id');
                    M('User_master')->add(['user_id'=>$ids,'master_id'=>$mase_id,'intime'=>time(),'date'=>date('Y-m-d',time())]);
                }

                huanxin_zhuce($str,$hx_password); //环信注册
                $url = C('IMG_PREFIX')."/index.php?m=Home&c=Public&a=index" . "&uid=" . base64_encode($id);
                M('User')->where(['user_id'=>$ids])->save(['url'=>$url,'uptime'=>time()]);
                $this->success('成功!',U('index'));
            }else{
                $this->error('失败!',U('index'));
            }
        }
    }

    /**
     * @禁播跳转
     */
    public function banned(){
        $this->assign('user_id',I('id'));
        $banned_data = M('Banned_date')->select();
        $this->assign('banned_data',$banned_data);
        $this->assign('pagetitle', '禁播');
        $this->display();
    }

    /**
     * @禁播
     */
    public function do_banned(){
        $data['user_id'] = intval(I('id'));
        $banned = I('banned');
        $banned_dis = I('banned_dis');
        if ($banned==-1){
            $data['is_banned'] = "3";
        }else{
            $data['is_banned'] = "2";
            $data['banned_start_time'] = time();
            $data['banned_end_time'] = time()+($banned*24*60*60);
        }
        $data['banned_dis'] = $banned_dis;
        $data['uptime'] = time();
        if (M('User')->save($data)){

            $live = M('Live')->field('room_id')->where(['user_id'=>intval(I('id')),'live_status'=>1])->find();
            if ($live){
                $ext = [
                    'forced_off'=>"1",
                ];
                $u = M('User')->order('intime desc')->limit(1)->find();
                if ($banned==-1){
                    $content = "已被平台永久禁播,如有问题,请联系平台!";
                }else{
                    $content = "已被平台禁播".$banned."天,如有问题,请联系平台!";
                }
                hx_send($u['hx_username'],$live['room_id'],$content,$ext);   //给聊天室发消息
            }

            $this->success('成功!',U('index'));
        }else{
            $this->error('失败!',U('index'));
        }
    }
    /**
     * @取消禁播
     */
    public function quxiao(){
        $id = I('ids');
        $user= M('User')->where(['user_id'=>$id])->field('banned_start_time,banned_dis')->find();
        $rs = M('User')->where(['user_id'=>$id])->save(['is_banned'=>1,'uptime'=>time()]);
        M('Banned_record')->add(['user_id'=>$id,'start_time'=>$user['banned_start_time'],'end_time'=>time(),'intime'=>time(),'dis'=>$user['banned_dis'],'type'=>1]);
        echo $rs ? 1 : 2;
    }

    /**
     * @封号跳转
     */
    public function titles(){
        $this->assign('user_id',I('id'));
        $banned_data = M('Banned_date')->select();
        $this->assign('banned_data',$banned_data);
        $this->assign('pagetitle', '封号');
        $this->display();
    }

    /**
     * @封号
     */
    public function do_titles(){
        $data['user_id'] = intval(I('id'));
        $banned = I('titles');
        $banned_dis = I('titles_dis');
        if ($banned==-1){
            $data['is_titles'] = "3";
        }else{
            $data['is_titles'] = "2";
            $data['titles_start_time'] = time();
            $data['titles_end_time'] = time()+($banned*24*60*60);
        }
        $data['titles_dis'] = $banned_dis;
        $data['uptime'] = time();
        if (M('User')->save($data)){
            $this->success('成功!',U('index'));
        }else{
            $this->error('失败!',U('index'));
        }
    }
    /**
     * @取消封号
     */
    public function quxiao_titles(){
        $id = I('ids');
        $user= M('User')->where(['user_id'=>$id])->field('titles_start_time,titles_dis')->find();
        $rs = M('User')->where(['user_id'=>$id])->save(['is_titles'=>1,'uptime'=>time()]);
        M('Banned_record')->add(['user_id'=>$id,'start_time'=>$user['banned_start_time'],'end_time'=>time(),'intime'=>time(),'dis'=>$user['banned_dis'],'type'=>2]);
        echo $rs ? 1 : 2;
    }

    /**
     * @充值
     */
    public function recharge(){
        $this->assign('user_id',I('id'));
        //充值列表
        $price = M('Price')->select();
        $this->assign('price',$price);
        $this->assign('pagetitle', '充值');
        $this->display();
    }

    /**
     * @充值
     */
    public function do_recharge(){
        $leixing = I('leixing');
        $type = I('type');
        $id = I('id');
        $table_recharge_record = M('Recharge_record'); $table_user = M('User'); $table_recharge_ticket = M('Recharge_ticket');
        $table_recharge_record->startTrans();  //开启事物
        $user_type = $table_user->where(['user_id'=>$id])->getField('type');
        if ($leixing==1){
            if ($type==1){
                $price_id = I('recharge');
                $price = M('Price')->find($price_id);
                $money = $price['price'];
                $meters = $price['meters']+$price['give'];
                $all_money = ($table_user->where(array('user_id'=>$id))->getField('money'))+$price['meters']+$price['give'];
            }else{
                $money = I('money');
                $meters = I('meters');
                $all_money = ($table_user->where(array('user_id'=>$id))->getField('money'))+$meters;
            }
            $data = [
                'user_id'=>$id,
                'pay_number'=>date('YmdHis').rand(100,999),
                'amount'=>$money,
                'meters'=>$meters,
                'pay_type'=>"后台",
                'pay_on'=>"admin",
                'intime'=>time()
            ];
            if ($table_recharge_record->add($data)){
                $up_user= $table_user->where(array('user_id'=>$id))->save(array('money'=>$all_money,'uptime'=>time()));  //修改会员钻石数量
                if ($up_user){
                    $table_recharge_record->commit();
                    if ($user_type==1){
                        $this->success('充值成功!',U('index'));
                    }else{
                        $this->success('充值成功!',U('live_user'));
                    }
                }else{
                    $table_recharge_record->rollback();
                    if ($user_type==1){
                        $this->error('充值失败!',U('index'));
                    }else{
                        $this->error('充值失败!',U('index'));
                    }
                }
            }else{
                $table_recharge_record->rollback();
                if ($user_type==1){
                    $this->error('充值失败!',U('index'));
                }else{
                    $this->error('充值失败!',U('index'));
                }
            }
        }else{
            $data = [
                'user_id'=>$id,
                'ticket'=>I('get_money'),
                'dis'=>I('dis'),
                'intime'=>time()
            ];
            if ($table_recharge_ticket->add($data)){
                $get_money = ($table_user->where(array('user_id'=>$id))->getField('get_money'))+I('get_money');
                $up_user= $table_user->where(array('user_id'=>$id))->save(array('get_money'=>$get_money,'uptime'=>time()));  //修改会员度票数量
                if ($up_user){
                    $table_recharge_record->commit();
                    if ($user_type==1){
                        $this->success('充值成功!',U('index'));
                    }else{
                        $this->success('充值成功!',U('live_user'));
                    }
                }else{
                    $table_recharge_record->rollback();
                    if ($user_type==1){
                        $this->error('充值失败!',U('index'));
                    }else{
                        $this->error('充值失败!',U('index'));
                    }
                }
            }else{
                $table_recharge_record->rollback();
                if ($user_type==1){
                    $this->error('充值失败!',U('index'));
                }else{
                    $this->error('充值失败!',U('index'));
                }
            }
        }

    }


    /**
     * @详情
     */
    public function details(){
        if (!M()->autoCheckToken($_POST)) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $user = M('User')->where(['user_id' => $id])->find();
        $user['xiaofei'] = M('Give_gift')->where(['user_id' => $id])->sum('jewel');
        $user['withdraw_count'] = M('Withdraw')->where(['user_id' => $id])->sum('money');
        $user['master_ID'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$id])->getField('master_id')])->getField('ID');
        $this->assign('view', $user);

        $state = I('state');
        if (!$state) {
            $state = '1';
        }
        switch ($state) {
            case 1:
                $count = M('Recharge_record')->where(['user_id' => $id,'pay_on'=>['neq','']])->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Recharge_record')->where(['user_id' => $id,'pay_on'=>['neq','']])->limit($p->firstRow . ',' . $p->listRows)->order('intime desc')->select();
                $this->assign("show", $p->show());
                $this->assign('re', $list);
                break;
//            case 2:
//                $count = M('Convert')->where(['user_id' => $id])->count();//一共有多少条记录
//                $p = getpage($count, '10');
//                $list = M('Convert')->where(['user_id' => $id])->limit($p->firstRow . ',' . $p->listRows)->order('intime desc')->select();
//                $this->assign("show", $p->show());
//                $this->assign('con', $list);
//                break;
            case 3:
                $count = M('Withdraw_record')->where(['user_id' => $id])->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Withdraw_record')->where(['user_id' => $id])->limit($p->firstRow . ',' . $p->listRows)->order('intime desc')->select();
                $this->assign("show", $p->show());
                $this->assign('w', $list);
                break;
            case 4:
                $count = M('Give_gift')
                    ->alias('a')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id=c.user_id')
                    ->join('__GIFT__ d on a.gift_id=d.gift_id')
                    ->where(['a.user_id2' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Give_gift')
                    ->alias('a')
                    ->field('a.*,b.title,c.username,d.name')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id=c.user_id')
                    ->join('__GIFT__ d on a.gift_id=d.gift_id')
                    ->where(['a.user_id2' => $id])
                    ->limit($p->firstRow . ',' . $p->listRows)
                    ->order('a.intime desc')
                    ->select();
                $this->assign("show", $p->show());
                $this->assign('g', $list);
                break;
            case 5:
                $count = M('Give_gift')
                    ->alias('a')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id=c.user_id')
                    ->join('__GIFT__ d on a.gift_id=d.gift_id')
                    ->where(['a.user_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Give_gift')
                    ->alias('a')
                    ->field('a.*,b.title,c.username,d.name')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id2=c.user_id')
                    ->join('__GIFT__ d on a.gift_id=d.gift_id')
                    ->where(['a.user_id' => $id])->limit($p->firstRow . ',' . $p->listRows)
                    ->order('a.intime desc')
                    ->select();
                $this->assign("show", $p->show());
                $this->assign('give', $list);
                break;
            case 6:
                $count = M('Follow')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id2=b.user_id')
                    ->where(['a.user_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Follow')
                    ->alias('a')
                    ->field('a.*,b.username,b.ID')
                    ->join('__USER__ b on a.user_id2=b.user_id')
                    ->where(['a.user_id' => $id])->limit($p->firstRow . ',' . $p->listRows)
                    ->order('a.intime desc')
                    ->select();
                $this->assign("show", $p->show());
                $this->assign('f', $list);
                break;
            case 7:
                $count = M('Follow')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id2' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Follow')
                    ->alias('a')
                    ->field('a.*,b.username,b.ID')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id2' => $id])->limit($p->firstRow . ',' . $p->listRows)
                    ->order('a.intime desc')
                    ->select();
                $this->assign("show", $p->show());
                $this->assign('fo', $list);
                break;
            case 8:
                $count = M('Live')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Live')
                    ->alias('a')
                    ->field('a.*,b.username,b.img,b.sex,b.phone,b.ID')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->limit($p->firstRow . ',' . $p->listRows)
                    ->where(['a.user_id' => $id])
                    ->order('a.live_status asc,a.intime desc')
                    ->select();
                foreach ($list as $k => $v) {
                    $gift_count = M('Give_gift')->where(['live_id' => $v['live_id']])->sum('jewel');
                    $gift_count ? $list[$k]['gift_count'] = $gift_count : $list[$k]['gift_count'] = '0';
                }
                $this->assign('live', $list);
                $this->assign("show", $p->show());
                break;
            case 9:
                $count = M('Live_store')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Live_store')
                    ->alias('a')
                    ->field('a.*,b.username,b.img,b.sex,b.phone,b.ID')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->limit($p->firstRow . ',' . $p->listRows)
                    ->where(['a.user_id' => $id])
                    ->order('a.intime desc')->select();
                $this->assign('live_store', $list);
                $this->assign("show", $p->show());
                break;
            case 10:
                $count = M('User_master')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.master_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('User_master')
                    ->alias('a')
                    ->field('a.*,b.username,b.img,b.sex,b.phone,b.ID')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->limit($p->firstRow . ',' . $p->listRows)
                    ->where(['a.master_id' => $id])
                    ->order('a.intime desc')->select();
                $this->assign('master', $list);
                $this->assign("show", $p->show());
                break;
            case 11:
                $count = M('Recharge_ticket')
                    ->alias('a')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id' => $id])
                    ->count();//一共有多少条记录
                $p = getpage($count, '10');
                $list = M('Recharge_ticket')
                    ->alias('a')
                    ->field('a.*,b.username,b.img,b.sex,b.phone,b.ID')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->limit($p->firstRow . ',' . $p->listRows)
                    ->where(['a.user_id' => $id])
                    ->order('a.intime desc')->select();
                $this->assign('recharge', $list);
                $this->assign("show", $p->show());
                break;
        }
        $this->assign('state', $state);
        $this->assign('pagetitle', '详情');
        $this->display();
    }

    /**
     * @查看认证信息
     */
    public function sel_authen_info(){
        $id = I('id');
        $u = M('User_alipay')->where(['user_id'=>$id])->find();
        $this->assign('l',$u);
        $this->display();
    }

    public function play(){
        $id = I('id');
        $live_store = M('Live_store')->find($id);
        $this->assign('l',$live_store);
        $this->display();
    }

    /**
     * @删除
     */
    public function del(){
        $id = I('ids');
        $rs = M('User')->where(['user_id'=>['in',$id]])->save(['is_del'=>2,'del_time'=>time()]);
        echo $rs ? 1 : 2;
    }


    /**
     * @已删除用户列表
     */
    public function del_user(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        $data = [
            'is_del'=>2
        ];
        if (!empty($_GET['username'])){
            $data['phone|username|ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        if (!empty($_GET['start']) && empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $data['intime'] = ['gt',$start];
            $this->assign('start',$_GET['start']);
        }elseif(empty($_GET['start']) && !empty($_GET['end'])){
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['lt',$end];
            $this->assign('end',$_GET['end']);
        }elseif(!empty($_GET['start']) && !empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['between',[$start,$end]];
            $this->assign('start',$_GET['start']);  $this->assign('end',$_GET['end']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('User')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('User')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '已删除用户列表' );


        $this->display();
    }

    /**
     * @恢复
     */
    public function restore(){
        $id = I('ids');
        $rs = M('User')->where(['user_id'=>['in',$id]])->save(['is_del'=>1,'uptime'=>time()]);
        echo $rs ? 1 : 2;
    }

    /**
     * @彻底删除
     */
    public function del_true(){
        $id = I('ids');
        $rs = M('User')->where(['user_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }


    /**
     * @僵尸粉列表
     */
    public function fans(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        $data = [
            'is_fans'=>2
        ];
        if (!empty($_GET['username'])){
            $data['phone|username|ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        // if (!empty($_GET['start']) && empty($_GET['end'])){
        //     $start = strtotime($_GET['start']);
        //     $data['intime'] = ['gt',$start];
        //     $this->assign('start',$_GET['start']);
        // }elseif(empty($_GET['start']) && !empty($_GET['end'])){
        //     $end = strtotime($_GET['end'])+(24*60*60-1);
        //     $data['intime'] = ['lt',$end];
        //     $this->assign('end',$_GET['end']);
        // }elseif(!empty($_GET['start']) && !empty($_GET['end'])){
        //     $start = strtotime($_GET['start']);
        //     $end = strtotime($_GET['end'])+(24*60*60-1);
        //     $data['intime'] = ['between',[$start,$end]];
        //     $this->assign('start',$_GET['start']);  $this->assign('end',$_GET['end']);
        // }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('User')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('User')->where($data)->limit($p->firstRow.','.$p->listRows)->order('intime desc')->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '僵尸粉列表' );


        $this->display();
    }

    /**
     * @添加、修改映射
     */
    public function toadd_fans(){
        //省
        $sheng = M('Areas')->where("level=1")->select();
        $this->assign('sheng',$sheng);

        $id = I('id');
        if ($id){
            $user = M('User')->find($id);
            $fid = M('Areas')->where(array('name' => $user['province'], 'level' => 1))->getField('id');
            if ($fid) {
                $data['fid'] = $fid;
                $data['level'] = 2;
                $user['shi'] = M('Areas')->where($data)->select();  //市
            } else {
                $user['shi'] = null;
            }
            $fid2 = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            if ($fid2) {
                $date['fid'] = $fid2;
                $date['level'] = 3;
                $user['qu'] = M('Areas')->where($date)->select();  //区
            } else {
                $user['qu'] = null;
            }
            $user['city_id'] = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            $user['area_id'] = M('Areas')->where(array('name' => $user['area'], 'level' => 3))->getField('id');
            $this->assign('u',$user);
            $sta = '编辑';
        }else{
            $sta = '添加';
        }
        $this->assign ( 'pagetitle', $sta );
        $this->display();
    }
    /**
     * @修改
     */
    public function doadd_fans(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $logo = I('logos');
        empty($logo) ? $img = "/Public/admin/touxiang.png" : $img = $logo;
        $imgs = ".".$img;
        //生成缩略图
        $image = new \Think\Image();
        $image->open($imgs);
        // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
        $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
        $image->thumb(60, 60)->save($path);

        $grade = I('grade');
        $grade ? $grade = $grade : $grade = 1;
        $username = I('username');
        if (!$username){
            $username = M('System')->where(['id'=>1])->getField('default_name');
        }
        $data = [
            'token'=>uniqid(),
            'phone'=>I('phone'),
            'img'=>$img,
            'thumb_img'=>$path,
            'sex'=>I('sex'),
            'username'=>$username,
            'autograph'=>I('autograph'),
            'province'=>M('Areas')->where(array('id'=>I('sheng')))->getField('name'),
            'city'=>M('Areas')->where(array('id'=>I('shi')))->getField('name'),
            'area'=>M('Areas')->where(array('id'=>I('qu')))->getField('name'),
            'address'=>I('address'),
            'is_fans'=>2,
            'grade'=>$grade,
        ];
        if ($id){
            $data['ID'] = I('ID');
            $data['uptime'] = time();
            M('User')->where(['user_id'=>$id])->save($data) ? $this->success('成功!',U('fans')) : $this->error('失败!',U('fans'));
        }else{
            $rs = I('ID');
            if (empty($rs)){
                $uid = get_number();
            }else{
                $uid = $rs;
            }
            $chars = "abcdefghijklmnopqrstuvwxyz123456789";
            mt_srand(10000000*(double)microtime());
            for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                $str .= $chars[mt_rand(0, $lc)];
            }
            $hx_password="123456";
            $date = [
                'ID'=>$uid,
                'alias'=>$str,
                'hx_username'=>$str,
                'hx_password'=>$hx_password,
                'intime'=>time(),
                'grade'=>$grade,
            ];
            $array = array_merge($data,$date);
            if ($ids=M('User')->add($array)){

                huanxin_zhuce($str,$hx_password); //环信注册
                $url = C('IMG_PREFIX')."/index.php?m=Home&c=Public&a=index" . "&uid=" . base64_encode($id);
                M('User')->where(['user_id'=>$ids])->save(['url'=>$url,'uptime'=>time()]);
                $this->success('成功!',U('fans'));
            }else{
                $this->error('失败!',U('fans'));
            }
        }
    }


    /********************************************申请列表***************************************************/

    /**
     * @申请列表
     */
    public function authen(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        $data['b.is_del'] = 1;
        if (!empty($_GET['username'])){
            $data['b.username|b.ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        if (!empty($_GET['status'])){
            $data['a.status'] = $_GET['status'];
            $this->assign('status',$_GET['status']);
        }
        if (!empty($_GET['start']) && empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $data['a.intime'] = ['gt',$start];
            $this->assign('start',$_GET['start']);
        }elseif(empty($_GET['start']) && !empty($_GET['end'])){
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['a.intime'] = ['lt',$end];
            $this->assign('end',$_GET['end']);
        }elseif(!empty($_GET['start']) && !empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['a.intime'] = ['between',[$start,$end]];
            $this->assign('start',$_GET['start']);  $this->assign('end',$_GET['end']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('User_authen')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('User_authen')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.ID')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.status desc,a.intime desc')
            ->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '申请认证列表' );

        $this->display();
    }

    /**
     * @详情
     */
    public function authen_details(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $details = M('User_authen')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.ID')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_authen_id'=>$id])
            ->find();
        $this->assign('details',$details);
        $this->assign ( 'pagetitle', '申请认证详细信息' );
        $this->display();
    }


    /**
     * @处理
     */
    public function doadd_authen_details(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $shenhe = I('shenhe');
        $authen = M('User_authen')->find($id);
        $data = [
            'user_authen_id'=>$id,
            'status'=>$shenhe,
            'why'=>I('why'),
            'uptime'=>time()
        ];
        if ($shenhe==2 || $shenhe==3){
            $data['authen_time'] = time();
        }
        if (M('User_authen')->save($data)){
            if ($shenhe==2){
                M('User')->where(['user_id'=>$authen['user_id']])->save(['type'=>2,'uptime'=>time()]);
            }
            $this->success('成功!',U('authen'));
        }else{
            $this->error('失败!',U('authen'));
        }
    }



    /*******************************************直播用户****************************************************************/


// 主页面
    public function live_user(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        $data = [
            'type'=>2,
            'is_del'=>1,
            'is_fans'=>1
        ];
        if (!empty($_GET['username'])){
            $data['phone|username|ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        $banned = $_GET['banned'];
        if (!empty($banned)){
            if ($banned==1){
                $data['is_banned'] = $banned;
            }else{
                $data['is_banned'] = ['neq',1];
            }
            $this->assign('banned',$banned);
        }
        $titles = $_GET['titles'];
        if (!empty($titles)){
            if ($titles==1){
                $data['is_titles'] = $titles;
            }else{
                $data['is_titles'] = ['neq',1];
            }
            $this->assign('titles',$titles);
        }
        if (!empty($_GET['start']) && empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $data['intime'] = ['gt',$start];
            $this->assign('start',$_GET['start']);
        }elseif(empty($_GET['start']) && !empty($_GET['end'])){
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['lt',$end];
            $this->assign('end',$_GET['end']);
        }elseif(!empty($_GET['start']) && !empty($_GET['end'])){
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end'])+(24*60*60-1);
            $data['intime'] = ['between',[$start,$end]];
            $this->assign('start',$_GET['start']);  $this->assign('end',$_GET['end']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('User')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('User')->limit($p->firstRow.','.$p->listRows)->where($data)->order('intime desc')->select();
        foreach ($list as $k=>$v){
            $list[$k]['banned_end_time'] = date('Y-m-d H:i:s',$v['banned_end_time']);
            if(!empty($v['titles_end_time'])){
                $list[$k]['titles_end_time'] = date('Y-m-d H:i:s',$v['titles_end_time']);
            }
        }
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '官方用户' );

        $this->display();
    }
    /**
     * @添加、修改映射
     */
    public function toadd_live_user(){
        //省
        $sheng = M('Areas')->where("level=1")->select();
        $this->assign('sheng',$sheng);

        $id = I('id');
        if ($id){
            $user = M('User')->find($id);
            $fid = M('Areas')->where(array('name' => $user['province'], 'level' => 1))->getField('id');
            if ($fid) {
                $data['fid'] = $fid;
                $data['level'] = 2;
                $user['shi'] = M('Areas')->where($data)->select();  //市
            } else {
                $user['shi'] = null;
            }
            $fid2 = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            if ($fid2) {
                $date['fid'] = $fid2;
                $date['level'] = 3;
                $user['qu'] = M('Areas')->where($date)->select();  //区
            } else {
                $user['qu'] = null;
            }
            $user['city_id'] = M('Areas')->where(array('name' => $user['city'], 'level' => 2))->getField('id');
            $user['area_id'] = M('Areas')->where(array('name' => $user['area'], 'level' => 3))->getField('id');

            $user['imgs'] = $user['img'];

            $user['img'] = C('IMG_PREFIX').$user['img'];

            $this->assign('u',$user);
            $sta = '编辑';
        }else{
            $sta = '添加';
        }
        $this->assign ( 'pagetitle', $sta );
        $this->display();
    }
    /**
     * @修改
     */
    public function doadd_live_user(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $logo = I('logos');
        empty($logo) ? $img = "/Public/admin/touxiang.png" :  $img = $logo;
        $imgs = ".".$img;
        //生成缩略图
        $image = new \Think\Image();
        $image->open($imgs);
        // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
        $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
        $image->thumb(60, 60)->save($path);

        $grade = I('grade');
        $grade ? $grade = $grade : $grade = 1;
        $username = I('username');
        if (!$username){
            $username = M('System')->where(['id'=>1])->getField('default_name');
        }

        $code = I('code');
        $code ? $code = $code : $code = "123456";

        $data = [
            'token'=>uniqid(),
            'phone'=>I('phone'),
            'img'=>$img,
            'thumb_img'=>$path,
            'sex'=>I('sex'),
            'type'=>2,
            'username'=>$username,
            'autograph'=>I('autograph'),
            'province'=>M('Areas')->where(array('id'=>I('sheng')))->getField('name'),
            'city'=>M('Areas')->where(array('id'=>I('shi')))->getField('name'),
            'area'=>M('Areas')->where(array('id'=>I('qu')))->getField('name'),
            'address'=>I('address'),
            'grade'=>$grade,
            'code'=>$code,
        ];
        if ($id){
            $data['ID'] = I('ID');
            $data['uptime'] = time();
            if (M('User')->where(['user_id'=>$id])->save($data)){
                $master_id = I('master_id');
                if ($master_id){
                    $mase_id = M('User')->where(['ID'=>$master_id])->getField('user_id');
                    $ma = M('User_master')->where(['user_id'=>$id,'master_id'=>$mase_id])->find();
                    if (!$ma){
                        M('User_master')->add(['user_id'=>$id,'master_id'=>$mase_id,'intime'=>time(),'date'=>date('Y-m-d',time())]);
                    }
                }
                $this->success('成功!',U('live_user'));
            }else{
                $this->error('失败!',U('live_user'));
            }
        }else{

            $rs = I('ID');
            if (empty($rs)){
                $uid = get_number();
            }else{
                $uid = $rs;
            }
            $chars = "abcdefghijklmnopqrstuvwxyz123456789";
            mt_srand(10000000*(double)microtime());
            for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < 12; $i++){
                $str .= $chars[mt_rand(0, $lc)];
            }
            $hx_password="123456";
            $date = [
                'ID'=>$uid,
                'type'=>2,
                'alias'=>$str,
                'hx_username'=>$str,
                'hx_password'=>$hx_password,
                'intime'=>time(),
                'grade'=>$grade,
            ];
            $array = array_merge($data,$date);
            if ($ids=M('User')->add($array)){

                $master_id = I('master_id');
                if ($master_id){
                    $mase_id = M('User')->where(['ID'=>$master_id])->getField('user_id');
                    M('User_master')->add(['user_id'=>$ids,'master_id'=>$mase_id,'intime'=>time(),'date'=>date('Y-m-d',time())]);
                }

                huanxin_zhuce($str,$hx_password); //环信注册
                $url = C('IMG_PREFIX')."/index.php?m=Home&c=Public&a=index" . "&uid=" . base64_encode($id);
                M('User')->where(['user_id'=>$ids])->save(['url'=>$url,'uptime'=>time()]);
                $this->success('成功!',U('live_user'));
            }else{
                $this->error('失败!',U('live_user'));
            }
        }
    }


/***************************************************举报列表**********************************************************/

    public function report(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [
            'a.type'=>2
        ];
        if (!empty($_GET['username'])){
            $data['c.username|c.ID|a.why'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Report')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__USER__ c on a.user_id2=c.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Report')
            ->alias('a')
            ->field('a.*,b.username,b.ID,b.phone,c.username as username2,c.ID as id2,c.phone,c.hx_username')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__USER__ c on a.user_id2=c.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.intime desc')
            ->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '举报管理' );

        $this->display();
    }

    public function upload(){
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

//     public function upload($dirname=''){
//         $files = $_FILES;
//         $images = array();
//         if(empty($files)){
//             error("上传文件不能空");
//         }
//         foreach($files as $file){
// //            //宽高验证
// //            $imageInfo = $file->getInfo();
// //            $imagesize = getimagesize($imageInfo['tmp_name']);
// //            if($imagesize[0] > 1002){
// //                error('请选择宽度不超过<b>1002px</b>的JPG图片...');
// //            }
// //            if($imagesize[1] > 2500){
// //                error('请选择高度不超过<b>2000px</b>的JPG图片...');
// //            }
//             //移动到框架应用根目录/public/uploads/ 目录下
//             $info = $file->validate(
//                 ['size'=>2000000,'ext'=>'png,jpg,jpeg,gif','mine'=>"image"]
//             )->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'/image'.DS.$dirname);
//             if($info){
//                 // 成功上传后 获取上传信
//                 if($dirname){
//                     $url = config('domain').'/uploads/'.DS.'/image/'.$dirname.'/'.$info->getSaveName();
//                 }else{
//                     $url = config('domain').'/uploads/'.DS.'/image/'.$info->getSaveName();
//                 }
//             }else{
//                 return error( $file->getError());
//             }
//         }

//             echo json_encode(array('error' => 0, 'url' => $url));
//             die;


//     }

    public function upload_save_thumb($dirname='',$max=''){
        $files = request()->file();
        if(empty($files)){
            error("上传文件不能空");
        }
        foreach($files as $file){
//            //宽高验证
//            $imageInfo = $file->getInfo();
//            $imagesize = getimagesize($imageInfo['tmp_name']);
//            if($imagesize[0] > 1002){
//                error('请选择宽度不超过<b>1002px</b>的JPG图片...');
//            }
//            if($imagesize[1] > 2500){
//                error('请选择高度不超过<b>2000px</b>的JPG图片...');
//            }
            //移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(
                ['size'=>2000000,'ext'=>'png,jpg,jpeg,gif','mine'=>"image"]
            )->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'image'.DS.$dirname);
            if($info){
                // 成功上传后 获取上传信
                if($dirname){
                    $url = '/uploads'.DS.'image/'.$dirname.'/';
                }else{
                    $url = '/uploads'.DS.'image/';
                }
                $array = getimagesize('.' . $url.$info->getSaveName());
                if($max){
                    if ($array[0] > $max) {
                        $image = Image::open('.' . $url . $info->getSaveName());
                        // 按照原图的比例生成一个最大为500*500的缩略图并保存为thumb.png
                        $image->thumb($max, $max, Image::THUMB_SCALING)->save('.' . $url . $info->getSaveName());
                    }
                }
                $url = config('domain').$url.$info->getSaveName();
            }else{
                return error( $file->getError());
            }
        }

        echo json_encode(array('error' => 0, 'url' => $url));
        die;
    }







	
	
}
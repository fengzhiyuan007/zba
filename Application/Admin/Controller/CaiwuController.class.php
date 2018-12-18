<?php
namespace  Admin\Controller;
/**
 * 财务管理
 * @author 
 *
 */
use Think\Db;
use Org\Util\Date;

class CaiwuController extends CommonController {
    function _initialize() {
        $nums = ['5','10','15','20','25','30','50','100'];
        $this->assign('nums',$nums);
    }

	// 充值列表
    public function index(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [
            'pay_on'=>['neq','']
        ];
        if (!empty($_GET['username'])){
            $data['b.phone|b.username|b.ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        if (!empty($_GET['pay_type'])){
            if ($_GET['pay_type']=='houtai'){
                $data['a.pay_type'] = ['eq','后台'];
            }else{
                $data['a.pay_type'] = ['eq',$_GET['pay_type']];
            }
            $this->assign('pay_type',$_GET['pay_type']);
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
        $count = M('Recharge_record')->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Recharge_record')
            ->alias('a')
            ->field('a.*,b.username,b.ID,b.img,b.phone')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.intime desc')
            ->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '充值列表' );

        //计算充值
        $count_money = M('Recharge_record')
            ->alias('a')
            ->field('a.meters')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->sum('amount');
        $meters_money = M('Recharge_record')
            ->alias('a')
            ->field('a.meters')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->sum('meters');
        $tongji = ['count_money'=>$count_money,'meters_money'=>$meters_money];
        $this->assign('tongji',$tongji);


        $this->display();
    }

    /**
     * @提现记录
     */
    public function withdraw(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        if (!empty($_GET['username'])){
            $data['b.phone|b.username|b.ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        if (!empty($_GET['status'])){
            $data['a.type'] = ['eq',$_GET['status']];
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
        $count = M('Withdraw_record')->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Withdraw_record')
            ->alias('a')
            ->field('a.*,b.username,b.ID,b.img,b.phone')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.intime desc')
            ->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '提现记录' );

        $this->display();
    }



    /**
     * @编辑
     */
    public function edit(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $details = M('Withdraw')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.phone,b.ID')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['withdraw_id'=>$id])
            ->find();
        $this->assign('d',$details);
        $this->assign ( 'pagetitle', '详情' );
        $this->display();
    }


    /**
     * @审核
     */
    public function do_eidt(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $status = I('status');
        $wi = M('Withdraw')->find($id);
        $data = [
            'withdraw_id'=>$id,
            'status'=>$status,
            'uptime'=>time()
        ];
        if ($status==3){$data['cash_time']=time();}
        if (M('Withdraw')->save($data)){
            if ($status!=1){
                if ($status==2){
                    $get_money = M('User')->where(['user_id'=>$wi['user_id']])->getField('get_money') + $wi['k'];
                    M('User')->where(['user_id'=>$wi['user_id']])->save(['get_money'=>$get_money,'uptime'=>time()]);
                    $content = "您提现的".$wi['money']."元被驳回,具体请联系平台!";
                }else{
                    $content = "您提现的".$wi['money']."元已返现,请查看!";
                }
                M('Message')->add(['type'=>1,'user_id2'=>$wi['user_id'],'content'=>$content,'intime'=>time(),'date'=>date('Y-m-d',time())]);
            }
            $this->success('成功!',U('withdraw'));
        }else{
            $this->error('失败!',U('withdraw'));
        }
    }




    /**
     * @送礼记录
     */
    public function give_gift_list(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        if (!empty($_GET['username'])){
            $data['b.username|b.ID|c.name|d.username|d.ID'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
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
        $count = M('Give_gift')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__GIFT__ c on a.gift_id=c.gift_id')
            ->join('__USER__ d on a.user_id2=d.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Give_gift')
            ->alias('a')
            ->field('a.*,b.username,b.ID,b.img,b.phone,c.name,d.username as username2,d.ID as id2')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__GIFT__ c on a.gift_id=c.gift_id')
            ->join('__USER__ d on a.user_id2=d.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.intime desc')
            ->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());

        //统计
        $sum = M('Give_gift')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__GIFT__ c on a.gift_id=c.gift_id')
            ->join('__USER__ d on a.user_id2=d.user_id')
            ->where($data)
            ->sum('jewel');
        $this->assign('sum',$sum);

        $this->assign ( 'pagetitle', '送礼记录' );
        $act=I("get.act");
        if ($act=="download"){
            $dat = M('Give_gift')
                ->alias('a')
                ->field('a.*,b.username,b.ID,b.img,b.phone,c.name,d.username as username2,d.ID as id2')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->join('__GIFT__ c on a.gift_id=c.gift_id')
                ->join('__USER__ d on a.user_id2=d.user_id')
                ->where($data)
                ->order('a.intime desc')
                ->select();
           // $str = date('YmdHis');
            $str = date('Y-m-d H:i',time());
            header("Content-type:application/vnd.ms-excel");
            header("Content-Disposition:filename={$str}.xls");
            echo "\xEF\xBB\xBF"."会员账号\t会员ID\t被送主播\t主播ID\t礼物\t价格\t经验\t时间\t日期\n";
            for($i=0;$i<count($dat);$i++){
                echo $dat[$i]["username"]."\t"
                    .$dat[$i]["id"]."\t"
                    .$dat[$i]["username2"]."\t"
                    .$dat[$i]["id2"]."\t"
                    .$dat[$i]["name"]."\t"
                    .$dat[$i]["jewel"]."\t"
                    .$dat[$i]["experience"]."\t"
                    .date("Y-m-d H-i-s",$dat[$i]["intime"])."\t"
                    .$dat[$i]["date"]
                    ."\n";
            }
        }else{
            $this->display();
        }
    }

    /**
     * @兑换比例
     */
    public function convert_scale(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Convert_scale')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Convert_scale')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '兑换比例' );
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_convert_scale(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Convert_scale')->find($id);
            $this->assign('u',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_convert_scale(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'k'=>I('k'),
            'meters'=>I('meters'),
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Convert_scale')->add($data) ? $this->success('添加成功!',U('convert_scale')) : $this->error('添加失败',U('convert_scale'));
        }else{
            $data['uptime'] = time();
            if (M('Convert_scale')->where(['convert_scale_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('convert_scale'));
            }else{
                $this->error('编辑失败',U('convert_scale'));
            }
        }
    }
    /**
     * @删除
     */
    public function del_convert_scale(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Convert_scale')->where(['convert_scale_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

    /*********************************送出统计****************************************************/

    public function send_out(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        if (!empty($_GET['account_binding'])){
            if ($_GET['account_binding']==1){
                $data['b.phone'] = ['neq',''];
            }else{
                $data['b.phone'] = ['eq',''];
            }
            $this->assign('account_binding',$_GET['account_binding']);
        }
        if (!empty($_GET['banned'])){
            if ($_GET['banned']==1){
                $data['b.is_banned'] = ['neq',1];
            }else{
                $data['b.is_banned'] = ['eq',1];
            }
            $this->assign('banned',$_GET['banned']);
        }
        if (!empty($_GET['titles'])){
            if ($_GET['titles']==1){
                $data['b.is_titles'] = ['neq',1];
            }else{
                $data['b.is_titles'] = ['eq',1];
            }
            $this->assign('titles',$_GET['titles']);
        }
        if (!empty($_GET['sex'])){
            if ($_GET['sex']==1){
                $data['b.sex'] = ['eq',1];
            }elseif($_GET['sex']==2){
                $data['b.sex'] = ['eq',2];
            }else{
                $data['b.sex'] = ['eq',0];
            }
            $this->assign('sex',$_GET['sex']);
        }
        if (!empty($_GET['certification'])){
            if ($_GET['certification']==1){
                $data['b.is_authen'] = ['eq',1];
            }else{
                $data['b.is_authen'] = ['neq',1];
            }
            $this->assign('certification',$_GET['certification']);
        }
        if (!empty($_GET['username'])){
            if ($_GET['scope']==1){
                $data['b.phone'] = ['like','%'.$_GET['username'].'%'];
            }elseif ($_GET['scope']==2){
                $data['b.ID'] = ['like','%'.$_GET['username'].'%'];
            }elseif ($_GET['scope']==3){
                $data['b.username'] = ['like','%'.$_GET['username'].'%'];
            }
            $this->assign('scope',$_GET['scope']);
            $this->assign('username',$_GET['username']);
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

        $act=I("get.act");
        if ($act=="download"){
            $dat = M('Give_gift')
                ->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade,b.phone,b.openid,b.qq_openid,b.weibo,b.ID,b.intime,b.is_authen,a.intime as give_time')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($data)
                ->order('count desc')
                ->select();
            foreach ($dat as $k=>$v){
                $dat[$k]['master_id'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$v['user_id']])->getField('master_id')])->getField('ID');
                if ($v['sex']==1){
                    $dat[$k]['sex'] = "男";
                }elseif ($v['sex']==2){
                    $dat[$k]['sex'] = "女";
                }else{
                    $dat[$k]['sex'] = "未知";
                }
                $v['is_authen'] == 1 ? $dat[$k]['is_authen'] = "未认证" : $dat[$k]['is_authen'] = "已认证";
                if ($v['phone']!=''){
                    $dat[$k]['phone'] = $v['phone'];
                }elseif($v['openid']!=''){
                    $dat[$k]['phone'] = "微信(".$v['openid'].")";
                }elseif($v['qq_openid']!=''){
                    $dat[$k]['phone'] = "QQ(".$v['qq_openid'].")";
                }elseif($v['weibo']!=''){
                    $dat[$k]['phone'] = "微博(".$v['weibo'].")";
                }
            }
            $str = date('YmdHis');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:filename={$str}.xls");
            echo "\xEF\xBB\xBF"."会员账号\t昵称\tID\t性别\t是否认证\t师傅ID\t送出合计\t等级\t送出时间\t注册日期\n";
            for($i=0;$i<count($dat);$i++){
                echo $dat[$i]["phone"]."\t"
                    .$dat[$i]["username"]."\t"
                    .$dat[$i]["id"]."\t"
                    .$dat[$i]["sex"]."\t"
                    .$dat[$i]["is_authen"]."\t"
                    .$dat[$i]["master_id"]."\t"
                    .$dat[$i]["count"]."\t"
                    .$dat[$i]["grade"]."\t"
                    .date("Y-m-d H:i:s",$dat[$i]["give_time"])
                    .date("Y-m-d H:i:s",$dat[$i]["intime"])
                    ."\n";
            }
        }else{
            $count = M('Give_gift')
                ->alias('a')
                ->field('b.user_id')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($data)
                ->select();//一共有多少条记录
            $count = count($count);
            $p = getpage($count,$nus);
            $list = M('Give_gift')
                ->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade,b.phone,b.openid,b.qq_openid,b.weibo,b.ID,b.intime,b.is_authen,a.intime as give_time')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($data)
                ->order('count desc')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();
            foreach ($list as $k=>$v){
                $list[$k]['master_id'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$v['user_id']])->getField('master_id')])->getField('ID');
            }
            $this->assign('list',$list);
            $this->assign("show",$p->show());

            //统计
            $sum = M('Give_gift')
                ->alias('a')
                ->field('jewel')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->where($data)
                ->sum('jewel');
            $this->assign('sum',$sum);

            $this->assign ( 'pagetitle', '送出统计' );
            $this->display();
        }
    }


    /*********************************收益统计****************************************************/

    public function income(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        if (!empty($_GET['account_binding'])){
            if ($_GET['account_binding']==1){
                $data['b.phone'] = ['neq',''];
            }else{
                $data['b.phone'] = ['eq',''];
            }
            $this->assign('account_binding',$_GET['account_binding']);
        }
        if (!empty($_GET['banned'])){
            if ($_GET['banned']==1){
                $data['b.is_banned'] = ['neq',1];
            }else{
                $data['b.is_banned'] = ['eq',1];
            }
            $this->assign('banned',$_GET['banned']);
        }
        if (!empty($_GET['titles'])){
            if ($_GET['titles']==1){
                $data['b.is_titles'] = ['neq',1];
            }else{
                $data['b.is_titles'] = ['eq',1];
            }
            $this->assign('titles',$_GET['titles']);
        }
        if (!empty($_GET['sex'])){
            if ($_GET['sex']==1){
                $data['b.sex'] = ['eq',1];
            }elseif($_GET['sex']==2){
                $data['b.sex'] = ['eq',2];
            }else{
                $data['b.sex'] = ['eq',0];
            }
            $this->assign('sex',$_GET['sex']);
        }
        if (!empty($_GET['certification'])){
            if ($_GET['certification']==1){
                $data['b.is_authen'] = ['eq',1];
            }else{
                $data['b.is_authen'] = ['neq',1];
            }
            $this->assign('certification',$_GET['certification']);
        }
        if (!empty($_GET['username'])){
            if ($_GET['scope']==1){
                $data['b.phone'] = ['like','%'.$_GET['username'].'%'];
            }elseif ($_GET['scope']==2){
                $data['b.ID'] = ['like','%'.$_GET['username'].'%'];
            }elseif ($_GET['scope']==3){
                $data['b.username'] = ['like','%'.$_GET['username'].'%'];
            }
            $this->assign('scope',$_GET['scope']);
            $this->assign('username',$_GET['username']);
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

        $act=I("get.act");
        if ($act=="download"){
            $dat = M('Give_gift')
                ->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade,b.phone,b.openid,b.qq_openid,b.weibo,b.ID,a.intime,b.is_authen,a.intime as give_time')
                ->join('__USER__ b on a.user_id2=b.user_id')
                ->group('a.user_id2')
                ->where($data)
                ->order('count desc')
                ->select();
            foreach ($dat as $k=>$v){
                $dat[$k]['master_id'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$v['user_id']])->getField('master_id')])->getField('ID');
                if ($v['sex']==1){
                    $dat[$k]['sex'] = "男";
                }elseif ($v['sex']==2){
                    $dat[$k]['sex'] = "女";
                }else{
                    $dat[$k]['sex'] = "未知";
                }
                $v['is_authen'] == 1 ? $dat[$k]['is_authen'] = "未认证" : $dat[$k]['is_authen'] = "已认证";
                if ($v['phone']!=''){
                    $dat[$k]['phone'] = $v['phone'];
                }elseif($v['openid']!=''){
                    $dat[$k]['phone'] = "微信(".$v['openid'].")";
                }elseif($v['qq_openid']!=''){
                    $dat[$k]['phone'] = "QQ(".$v['qq_openid'].")";
                }elseif($v['weibo']!=''){
                    $dat[$k]['phone'] = "微博(".$v['weibo'].")";
                }
            }
            $str = date('YmdHis');
            header("Content-type:application/vnd.ms-excel;charset=utf-8");
            header("Content-Disposition:filename={$str}.xls");
            echo "\xEF\xBB\xBF"."会员账号\t昵称\tID\t性别\t是否认证\t师傅ID\t收益合计\t等级\t收礼时间\t注册日期\n";
            for($i=0;$i<count($dat);$i++){
                echo $dat[$i]["phone"]."\t"
                    .$dat[$i]["username"]."\t"
                    .$dat[$i]["id"]."\t"
                    .$dat[$i]["sex"]."\t"
                    .$dat[$i]["is_authen"]."\t"
                    .$dat[$i]["master_id"]."\t"
                    .$dat[$i]["count"]."\t"
                    .$dat[$i]["grade"]."\t"
                    .date("Y-m-d H:i:s",$dat[$i]["give_time"])
                    .date("Y-m-d H:i:s",$dat[$i]["intime"])
                    ."\n";
            }
        }else{
            $count = M('Give_gift')
                ->alias('a')
                ->field('b.user_id')
                ->join('__USER__ b on a.user_id2=b.user_id')
                ->group('a.user_id2')
                ->where($data)
                ->select();//一共有多少条记录
            $count = count($count);
            $p = getpage($count,$nus);
            $list = M('Give_gift')
                ->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade,b.phone,b.openid,b.qq_openid,b.weibo,b.ID,b.intime,b.is_authen,a.intime as give_time')
                ->join('__USER__ b on a.user_id2=b.user_id')
                ->group('a.user_id2')
                ->where($data)
                ->order('count desc')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();
            foreach ($list as $k=>$v){
                $list[$k]['master_id'] = M('User')->where(['user_id'=>M('User_master')->where(['user_id'=>$v['user_id']])->getField('master_id')])->getField('ID');
            }
            $this->assign('list',$list);
            $this->assign("show",$p->show());

            //统计
            $sum = M('Give_gift')
                ->alias('a')
                ->field('jewel')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->where($data)
                ->sum('jewel');
            $this->assign('sum',$sum);

            $this->assign ( 'pagetitle', '收益统计' );
            $this->display();
        }
    }

    public function tx(){
        if(IS_AJAX) {
            $ids = I('ids');
            $data['uptime'] = time();
            $data['type'] = 2;
            $result = M('withdraw_record')->where(array('withdraw_record_id'=>$ids))->save($data);
            // $list = M('withdraw_record')->where(array('withdraw_record_id'=>$ids))->find();
            // $money = intval($list['amount']);
            // $system = M('System')
            //         ->field(['convert_scale3,convert_scale4,withdraw_switch,every_day_switch,period_of_time_start,period_of_time_end,day_switch,start_time,end_time,day_lowest,day_highest,day_number'])
            //         ->where(['id'=>1])->find();
            // $moneys = $money * ($system['convert_scale3']/$system['convert_scale4']);//实际扣的魅力值
            // //减get_money
            // $re = M('user')->where(array('user_id'=>$list['user_id']))->setDec('get_money',$moneys);
            
            if($result){
                success(['info'=>'成功']);
            }else{
                error("失败");
            }
        }
    }

	
	
}
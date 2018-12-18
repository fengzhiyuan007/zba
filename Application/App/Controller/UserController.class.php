<?php
namespace App\Controller;
use Behavior\CheckLangBehavior;

use Home\Controller\IndexController;
use Psr\Log\Test\DummyTest;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;
class UserController extends CommonController {
	/**
	 * 会员中心
	*/
    public function index(){
        $user = checklogin();
        $user['img'] = C('IMG_PREFIX').$user['img'];
        $user['get_money'] = FormatMoney($user['get_money']);
        $follow = M('Follow')
            ->alias('a')
            ->join('__USER__ b on a.user_id2=b.user_id')
            ->where(['a.user_id'=>$user['user_id'],'b.is_del'=>1])
            ->count();
        $user['follow'] = FormatMoney($follow);
        $follow_to = M('Follow')->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id2'=>$user['user_id'],'b.is_del'=>1])
            ->count();
        $user['follow_to'] = FormatMoney($follow_to);
        $live_count = M('Live_store')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user['user_id'],'a.is_del'=>1])
            ->count();
        $user['live_count'] = FormatMoney($live_count);
        $user['imgs_count'] = FormatMoney(M('User_imgs')->where(['user_id'=>$user['user_id']])->count());
        $give_count = M('Give_gift')->where(['user_id'=>$user['user_id']])->sum('jewel');
        $give_count ? $user['give_count'] = FormatMoney($give_count) : $user['give_count'] = "0";
        $get_gradeinfo = get_gradeinfo($user['grade']);
        $user['grade_img'] = $get_gradeinfo['img'];
        $user['name'] = $get_gradeinfo['name'];


        success($user);
    }
    /**
     * @录播列表
     */
    public function live_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Live_store')
            ->alias('a')
            ->field('a.*,b.img,b.sex,b.username,b.ID,b.hx_username,b.grade,b.province,b.city,b.zan,b.money,b.get_money')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user['user_id'],'a.is_del'=>1])
            ->order('a.intime desc')
            ->page($page,$pageSize)
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                if (time()-$v['intime']<(7*24*60*60)){
                    $list[$k]['intime'] = get_times($v['intime']);
                }else{
                    $list[$k]['intime'] = $v['date'];
                }
                $list[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                $list[$k]['img'] = C('IMG_PREFIX').M('User')->where(['user_id'=>$v['user_id']])->getField('img');

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $list[$k]['grade_img'] = $get_gradeinfo['img'];
                $list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$list=[];}
        success($list);
    }

    /**
     * @点击录播视频播放+1
     */
    public function play_store(){
        $user = checklogin();
        $live_store_id = I('live_store_id');
        (empty($live_store_id)) ? error('参数错误!') : true;
        if (M('Live_store')->where(['live_store_id'=>$live_store_id])->setInc('play_number')){
            success('成功!');
        }else{
            error('失败!');
        }
    }

    /**
     * @删除录播
     */
    public function del_live(){
        $user = checklogin();
        $live_store_id = I('live_store_id');
        empty($live_store_id) ? error('参数错误!') : true;
        if (M('Live_store')->where(['live_store_id'=>['in',$live_store_id]])->save(['is_del'=>2,'uptime'=>time()])){
            success('成功');
        }else{
            error('失败!');
        }
    }


    /**
     * @相册上传图片
     */
    public function upload_imgs(){
        $user = checklogin();
        $config = [
            'maxSize'	=> 500*3145728,
            'rootPath'	=> './Public/upload/user_imgs/',
            'savePath'	=> '',
            'saveName'	=> ['uniqid',''],
            'exts'		=> ['png','jpg','jpeg','git','gif'],
            'autoSub'	=> true,
            'subName'	=> '',
        ];
        $uploader = new Upload($config);
        $info = $uploader->upload();
        if ($info){
            foreach($info as $file){
                $imgs = '/Public/upload/user_imgs/'.$file["savename"];
                $dataList[] = ['user_id'=>$user['user_id'],'imgs'=>$imgs,'intime'=>time(),'date'=>date('Y-m-d',time())];    //批量写入
            }
        }else {
            error($uploader->getError());
        }
        if (M('User_imgs')->addAll($dataList)){
            success('成功!');
        }else{
            error('失败!');
        }

    }

    /**
     * @相册列表
     */
    public function imgs_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $date = M('User_imgs')->field('date')->where(['user_id'=>$user['user_id']])->order('intime desc')->group('date')->select();
        if ($date){
            $ids = array_map(function($v){ return $v['date'];},$date);
            foreach ($ids as $k=>$v) {
                if ($v == date('Y-m-d', time())) {
                    $day = '今天';
                } else {
                    $day = $v;
                }
                $list[$k]['time'] = $day;
                $imgs_list = M('User_imgs')
                    ->where(['user_id'=>$user['user_id'],'date'=>$v])
                    ->order('intime desc')
                    ->select();
                foreach ($imgs_list as $a=>$b){
                    $imgs_list[$a]['imgs'] = C('IMG_PREFIX').$b['imgs'];
                }
                $list[$k]['list'] = $imgs_list;
            }
            $list = array_slice($list,($page-1)*$pageSize,$pageSize);
        }else{$list=[];}
        $result['count'] = M('User_imgs')->where(['user_id'=>$user['user_id']])->count();
        $result['list'] = array_values($list);
        success($result);
    }

    /**
     * @删除相册
     */
    public function del_imgs(){
        checklogin();
        $imgs_id = I('user_imgs_id');
        if (M('User_imgs')->where(['user_imgs_id'=>['in',$imgs_id]])->delete()){
            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @日榜、周榜、总榜
     * $type  1:日榜   2:周榜  3:总榜
     */
    public function total_list(){
        $user = checklogin();
        $type = I('type');
        (empty($type)) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        if($page){
            $pageSize ? $pageSize : $pageSize = 10;
        }
        //$page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        switch ($type){
            case 1:
                $where = [
                    'a.user_id2'=>$user['user_id'],
                    'b.is_del'=>1,
                    'a.date'=>date('Y-m-d',time())
                ];
                break;
            case 2:
                $where = [
                    'a.user_id2'=>$user['user_id'],
                    'b.is_del'=>1,
                    'a.intime'=>['gt',time()-(7*24*60*60)]
                ];
                break;
            case 3:
                $where = [
                    'a.user_id2'=>$user['user_id'],
                    'b.is_del'=>1,
                ];
                break;
        }
        $total = M('Give_gift')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($where)
            ->sum('a.jewel');
        $total ? $list['total'] = $total : $list['total'] = "0";
        $give_list = M('Give_gift')
            ->alias('a')
            ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->group('a.user_id')
            ->where($where)
            ->order('count desc')
            ->page($page,$pageSize)
            ->select();
        if ($give_list){
            foreach ($give_list as $k=>$v){
                $give_list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                $is_follow ? $give_list[$k]['is_follow'] = "2" : $give_list[$k]['is_follow'] = "1";

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $give_list[$k]['grade_img'] = $get_gradeinfo['img'];
                $give_list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$give_list=[];}
        $list['list'] = $give_list;
        success($list);
    }



	
	/**
     * @编辑个人资料
     */
	public function edit_user(){
	    $user = checklogin();
        $key = I('key'); $username = replace_string(I('username'));  $sex = I('sex');  $birth_day = I('birth_day');  $autograph = replace_string(I('autograph'));
        $province = I('province'); $city = I('city');   $area = I('area');
//        if (!empty($username)){
//            $u = M('User')->where(['username'=>$username])->find();
//            if ($u){error('昵称已存在!');}
//        }
        if(!empty($key)){
            $config = [
                'maxSize'	=> 30*3145728,
                'rootPath'	=> './Public/admin/Uploads/touxiang/',
                'savePath'	=> '',
                'saveName'	=> ['uniqid',''],
                'exts'		=> ['png','jpg','jpeg','git','gif'],
                'autoSub'	=> true,
                'subName'	=> '',
            ];
            $uploader = new Upload($config);
            $info = $uploader->upload();
            if ($info){
                foreach($info as $file){
                    $a = '/Public/admin/Uploads/touxiang/'.$file["savename"];
                    $img= ".".$a;
                    $image = new \Think\Image();
                    $image->open($img);
                    // 按照原图的比例生成一个最大为60*60的缩略图并保存为thumb.jpg
                    $path = './Public/admin/Uploads/touxiang/thumb_img/'.time().rand(100, 999).'.jpg';
                    $image->thumb(60, 60)->save($path);
                    $data['img'] = $a;
                    $data['thumb_img'] = $path;
                }
            }else {
                error($uploader->getError());
            }
        }
        (!empty($username)) ? $data['username'] = $username : true;           //姓名
        (!empty($sex)) ? $data['sex'] = $sex : true;                         //性别
        (!empty($birth_day))? $data['birth_day'] = $birth_day : true;     //生日
        (!empty($autograph))? $data['autograph'] = $autograph : true;                 //个人简介
        (!empty($province))? $data['province'] = $province : true;                 //省
        (!empty($city))? $data['city'] = $city : true;                 //市
        (!empty($area))? $data['area'] = $area : true;                 //区
        $data['uptime'] = time();
        $old_img = M('User')->where(['user_id'=>$user['user_id']])->getField('img');
        if(M('User')->where(['user_id'=>$user['user_id']])->save($data)){
            if ($a!=$old_img){
                unlink($old_img);
            }
            $imgs = C('IMG_PREFIX').M('User')->where(['user_id'=>$user['user_id']])->getField('img');
            success($imgs);
        }else{
            error('失败!');
        }
    }


    /**
     * @提交实名认证
     */
    public function commit_authen(){
        $user = checklogin();
        $realname = I('realname'); $idcard = I('idcard'); $mobile = I('mobile');  $band_card_where = I('band_card_where');  $band_card = I('band_card');
        (empty($realname) || empty($idcard) || empty($mobile) || empty($band_card_where) || empty($band_card)) ? error('参数错误!') : true;
        $data = [
            'user_id'=>$user['user_id'],
            'realname'=>$realname,
            'idcard'=>$idcard,
            'mobile'=>$mobile,
            'band_card_where'=>$band_card_where,
            'band_card'=>$band_card,
            'intime'=>time(),
        ];
        $config = [
            'maxSize'	=> 30*3145728,
            'rootPath'	=> './Public/admin/Uploads/touxiang/',
            'savePath'	=> '',
            'saveName'	=> ['uniqid',''],
            'exts'		=> ['png','jpg','jpeg','git','gif'],
            'autoSub'	=> true,
            'subName'	=> '',
        ];
        $uploader = new Upload($config);
        $info = $uploader->upload();
        if ($info){
            foreach($info as $file){
                $data['idcard_img'] = '/Public/admin/Uploads/touxiang/'.$file["savename"];
            }
        }else {
            error($uploader->getError());
        }
        if (M('User_authen')->add($data)){
            success('提交成功!');
        }else{
            error('失败!');
        }
    }

    /**
     * @编辑认证信息
     */
    public function edit_authen(){
        $user = checklogin();
        $user_authen_id = I('user_authen_id'); $realname = I('realname'); $idcard = I('idcard'); $idcard_img = I('idcard_img'); $mobile = I('mobile');  $band_card_where = I('band_card_where');  $band_card = I('band_card');
        (empty($user_authen_id) || empty($realname) || empty($idcard) || empty($idcard_img) || empty($mobile) || empty($band_card_where) || empty($band_card)) ? error('参数错误!') : true;
        $data = [
            'user_authen_id'=>$user_authen_id,
            'realname'=>$realname,
            'idcard'=>$idcard,
            'idcard_img'=>str_replace(C('IMG_PREFIX'),"",$idcard_img),
            'mobile'=>$mobile,
            'band_card_where'=>$band_card_where,
            'band_card'=>$band_card,
            'uptime'=>time()
        ];
        if (M('User_authen')->save($data)){
            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @获取实名认证信息
     */
    public function get_authen(){
        $user = checklogin();
        $authen = M('User_authen')->where(['user_id'=>$user['user_id']])->find();
        $authen['idcard_img'] = C('IMG_PREFIX').$authen['idcard_img'];
        success($authen);
    }


    /**
     * @粉丝列表(关注列表)
     * $type   1:粉丝列表   2:关注列表
     */
    public function follow_list(){
        $user = checklogin();
        $type = I('type');
        empty($type) ? error('参数错误!') : true; ($type==1 || $type==2) ? true : error('传值错误!');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        switch ($type){
            case 1:
                $list = M('Follow')
                    ->alias('a')
                    ->field('b.user_id,b.img,b.username,b.sex,b.autograph,b.hx_username,b.grade')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id2'=>$user['user_id'],'b.is_del'=>1])
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";

                        $get_gradeinfo = get_gradeinfo($v['grade']);
                        $list[$k]['grade_img'] = $get_gradeinfo['img'];
                        $list[$k]['name'] = $get_gradeinfo['name'];
                    }
                }else{$list=[];}
                break;
            case 2:
                $list = M('Follow')
                    ->alias('a')
                    ->field('b.user_id,b.img,b.username,b.sex,b.autograph,b.hx_username,b.grade')
                    ->join('__USER__ b on a.user_id2=b.user_id')
                    ->where(['a.user_id'=>$user['user_id'],'b.is_del'=>1])
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";

                        $get_gradeinfo = get_gradeinfo($v['grade']);
                        $list[$k]['grade_img'] = $get_gradeinfo['img'];
                        $list[$k]['name'] = $get_gradeinfo['name'];
                    }
                }else{$list=[];}
                break;
        }
        success($list);
    }

    /**
     * @视频列表
     */
    public function video_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Video')
            ->alias('a')
            ->field('a.*,b.img')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user['user_id'],'b.is_del'=>1])
            ->page($page,$pageSize)
            ->order('a.intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['video_img'] = C('IMG_PREFIX').$v['video_img'];
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        success($list);
    }

    /**
     * @删除视频
     */
    public function del_video(){
        $user = checklogin();
        $video_id = I('video_id');
        empty($video_id) ? error('参数错误!') : true;
        if (M('Video')->where(['video_id'=>['in',$video_id]])->save(['is_del'=>2,'uptime'=>time()])){
            success('成功');
        }else{
            error('失败!');
        }
    }

    /**
     * @黑名单
     */
    public function shield_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Shield')
            ->alias('a')
            ->field('a.shield_id,a.intime,b.user_id,b.username,b.img,b.company,b.duty,b.ID,b.hx_username,b.sex,b.grade,b.autograph')
            ->join('__USER__ b on a.user_id2=b.user_id')
            ->where(['a.user_id'=>$user['user_id']])
            ->order('a.intime desc')
            ->page($page,$pageSize)
            ->select();
        if ($list){
            foreach ($list as $k=>$v) {
                $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $list[$k]['grade_img'] = $get_gradeinfo['img'];
                $list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @取消黑名单
     */
    public function del_shield(){
        $user = checklogin();
        $shield_id = I('shield_id');
        empty($shield_id) ? error('参数错误!') : true;
        $shield = M('Shield')->find($shield_id);
        if (M('Shield')->where(['shield_id'=>$shield_id])->delete()){
            $hx_username = M('User')->where(['user_id'=>$shield['user_id2']])->getField('hx_username');
            deleteUserFromBlacklist($user['hx_username'],$hx_username);  //环信移除黑名单
            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @我的意见
     */
    public function feedback(){
        $user = checklogin();
        $content = I('content');
        empty($content) ? error('参数错误!') : true;
        if (M('Feedback')->add(['user_id'=>$user['user_id'],'content'=>$content,'intime'=>time()])){
            success('成功!');
        }else{
            error('失败!');
        }
    }

    /**
     * @意见列表
     */
    public function feedback_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Feedback')
            ->alias('a')
            ->field('a.*,b.img')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user['user_id']])
            ->page($page,$pageSize)
            ->order('a.intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        //if (!$list){$list = [];}
        success($list);
    }

    /**
     * @关于我们
     */
    public function about_us(){
        $about_us = M('About_us')->field('about_us_id,imgs,mobile,email,qq,wechat')->where(['about_us_id'=>1])->find();
        $about_us['imgs'] = C('IMG_PREFIX').$about_us['imgs'];
        success($about_us);
    }

    /**
     * @常见问题
     */
    public function common_problems(){
        $clause = M('About_us')->where(['about_us_id'=>2])->getField('clause');
        $this->assign('about_us',htmlspecialchars_decode($clause));
        $this->display();
    }

        /**
     * @隐私服务条款
     */
    public function clause(){
        $clause = M('About_us')->where(['about_us_id'=>1])->getField('clause');
        $this->assign('about_us',htmlspecialchars_decode($clause));
        $this->display();
    }

    /**
     * @隐私服务条款
     */
    public function xieyi(){
        $clause = M('About_us')->where(['about_us_id'=>1])->getField('xieyi');
        $this->assign('about_us',htmlspecialchars_decode($clause));
        $this->display();
    }
    /**
     * @用户充值协议
     */
    public function agreement(){
        $clause = M('About_us')->where(['about_us_id'=>1])->getField('agreement');
        $this->assign('about_us',htmlspecialchars_decode($clause));
        $this->display();
    }


    /**
     * @开播提醒
     */
    public function remind(){
        $user = checklogin();
        $page = I('page');  $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $result['is_remind'] = $user['is_remind'];
        $list = M('Follow')
            ->alias('a')
            ->field('a.follow_id,a.is_remind,b.user_id,b.img,b.username,b.sex,b.autograph,b.hx_username,b.grade')
            ->join('__USER__ b on a.user_id2=b.user_id')
            ->where(['a.user_id'=>$user['user_id']])
            ->page($page,$pageSize)
            ->order('a.intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        $result['follow_list'] = $list;
        success($result);
    }

    /**
     * @更改提醒状态
     * @type   1:更改用户状态   2:更改关注列表状态
     * @is_remind   1:开启  2:关闭
     */
    public function up_remind(){
        $user = checklogin();
        $type = I('type');  $is_remind = I('is_remind');
        (empty($type) || empty($is_remind)) ? error('参数错误!') : true;
        switch ($type){
            case 1:
                if (M('User')->where(['user_id'=>$user['user_id']])->save(['is_remind'=>$is_remind,'uptime'=>time()])){
                    success('成功!');
                }else{
                    error('失败!');
                }
                break;
            case 2:
                $follow_id = I('follow_id');
                empty($follow_id) ? error('参数错误!') : true;
                if (M('Follow')->where(['follow_id'=>$follow_id])->save(['is_remind'=>$is_remind,'uptime'=>time()])){
                    success('成功!');
                }else{
                    error('失败!');
                }
                break;
        }
    }




    /**
     * @上传图片,返回路径
     */
    public function upload(){
        $config = [
            'maxSize'	=> 500*3145728,
            'rootPath'	=> './Public/admin/Uploads/touxiang/',
            'savePath'	=> '',
            'saveName'	=> ['uniqid',''],
            'exts'		=> ['png','jpg','jpeg','git','gif'],
            'autoSub'	=> true,
            'subName'	=> '',
        ];
        $uploader = new Upload($config);
        $info = $uploader->upload();
        if ($info){
            foreach($info as $file){
                $a = C('IMG_PREFIX').'/Public/admin/Uploads/touxiang/'.$file["savename"];
            }
            success($a);
        }else {
            error($uploader->getError());
        }

    }

    /**
     * @我的钻石
     */
    public function my_money(){
        $user = checklogin();
        success($user['money']);
    }

    /**
     * @我赞过的视频
     */
    public function zan_video_list(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Video')
            ->alias('a')
            ->field('a.*,c.img,c.username,c.get_money')
            ->join('__VIDEO_ZAN__ b on a.video_id=b.video_id')
            ->join('__USER__ c on a.user_id=c.user_id')
            ->where(['b.user_id'=>$user['user_id'],'a.is_del'=>1])
            ->page($page,$pageSize)
            ->order('b.intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['video_img'] = C('IMG_PREFIX').$v['video_img'];
                $list[$k]['url'] = C('IMG_PREFIX').$v['url'];
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        success($list);
    }

    /**
     * @充值记录
     */
    public function recharge_record(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Recharge_record')->where(['user_id'=>$user['user_id'],'pay_on'=>['neq','']])->page($page,$pageSize)->order('intime desc')->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @获取版本号
     * @type  1:ios版本号   2:安卓版本号
     */
    public function get_version(){
        $type = I('type');
        empty($type) ? error('参数错误!') : true;
        switch ($type){
            case 1:
                $version = M('System')->where(['id'=>1])->getField('ios_version');
                break;
            case 2:
                $version = M('System')->where(['id'=>1])->getField('android_version');
                break;
        }
        success($version);
    }

    /**
     * @判断版本号
     */
    public function is_this(){
        $version = I('version');
        empty($version) ? error('参数错误!') : true;
        $ve = M('System')->where(['id'=>1])->getField('ios_version');
        if ($ve==$version){
            $result = "1";
        }else{
            $result = "2";
        }
        success($result);
    }



    /**
     * @我的收益
     */

    public function my_wallet(){
        $user = checklogin();
        $system = M('System')->field(['lowest_limit,convert_scale3,convert_scale4'])->where(['id'=>1])->find();
        $all_fire = $user['get_money'];
        $all_get_money = (string)floor($all_fire*($system['convert_scale4']/$system['convert_scale3']));
        $bili = ($system['convert_scale4']/$system['convert_scale3']);
        //提现说明
        $withdraw_dis = M('About_us')->where(['about_us_id'=>1])->getField('withdraw_dis');
        $result = ['lowest_limit'=>$system['lowest_limit'],'all_fire'=>$all_fire,'all_get_money'=>$all_get_money,'withdraw_dis'=>$withdraw_dis,'bili'=>$bili];
        success($result);
    }


    /**
     * @获取支付宝信息
     */
    public function get_alipay(){
        $user = checklogin();
        $alipay = M('Alipay')->where(['user_id'=>$user['user_id']])->find();
        $alipay ? $alipay = $alipay : $alipay = [];
        success($alipay);
    }

    /**
     * @绑定支付宝验证手机号
     */
    public function verify_phone(){
        $user = checklogin();
        $phone = I('phone');  $yzm = I('yzm');
        (empty($phone) || empty($yzm)) ? error('参数错误!') : true;
        $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
        if ($code) {
            $time = M('System')->getFieldById(1,'code_volidity');
            if (time()-$code['intime']>($time*60)){
                error('验证码已过期!');
            }
        }
        if ($code['code']==$yzm){
            success('成功!');
        }else{
            error('验证码不一致!');
        }
    }

    /**
     * @绑定支付宝
     */
    public function binding_alipay(){
        $user = checklogin();
        $phone = I('phone'); $alipay = I('alipay'); $relname = I('relname');
        (empty($phone) || empty($alipay) || empty($relname)) ? error('参数错误!') : true;
        $data = [
            'user_id'=>$user['user_id'],
            'phone'=>$phone,
            'alipay'=>$alipay,
            'relname'=>$relname,
            'intime'=>time()
        ];
        if (M('Alipay')->add($data)){
            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @更换支付宝账号
     */
    public function edit_alipay(){
        $user = checklogin();
        $phone = I('phone'); $alipay = I('alipay'); $relname = I('relname');
        (empty($phone) || empty($alipay) || empty($relname)) ? error('参数错误!') : true;
        $ali = M('Alipay')->where(['user_id'=>$user['user_id']])->find();
        $data = [
            'phone'=>$phone,
            'alipay'=>$alipay,
            'relname'=>$relname,
            'uptime'=>time()
        ];
        if (M('Alipay')->where(['alipay_id'=>$ali['alipay_id']])->save($data)){
            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @输入度票,返回可兑换金额
     */
    public function return_money(){
        $user = checklogin();
        $diamond = I('diamond');
        empty($diamond) ? error('参数错误!') : true;
        $system = M('System')->field(['lowest_limit,convert_scale3,convert_scale4'])->where(['id'=>1])->find();
        $get_money = (string)floor($diamond*($system['convert_scale4']/$system['convert_scale3']));
        success($get_money);
    }


    /**
     * @提现提交
     */
    public function withdraw(){
        $user = checklogin();
        $diamond = I('diamond'); $withdraw_way = I('withdraw_way'); $money = I('money');
        (empty($diamond) || empty($withdraw_way) || empty($money)) ? error('参数错误!') : true;
        $withdraw_table = M('Withdraw');  $user_table = M('User');
        $withdraw_table->startTrans();  //开启事物
        $user['get_money'] - $diamond < 0 ? error('余额不足') : true;
        $date = [
            'user_id'=>$user['user_id'],
            'k'=>$diamond,
            'money'=>$money,
            'withdraw_type'=>'支付宝',
            'withdraw_way'=>$withdraw_way,
            'intime'=>time(),
            'date'=>date('Y-m-d',time()),
        ];
        if (M('Withdraw')->add($date)){
            $get_money = $user['get_money'] - $diamond;
            $u = M('User')->where(['user_id'=>$user['user_id']])->save(['get_money'=>$get_money,'uptime'=>time()]);
            if (!$u){
                $user_table->rollback();
                error('失败!');
            }
            $user_table->commit();
            success('成功!');
        }else{
            $user_table->rollback();
            error('失败!');
        }
    }

    /**
     * @提现记录
     */
    public function withdraw_list(){
        $user = checklogin();
        $page = I('page');  $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
//        $list = M('Withdraw')
//            ->where(['user_id'=>$user['user_id']])
//            ->page($page,$pageSize)
//            ->order('intime desc')
//            ->select();
//        if ($list){
//            foreach ($list as $k=>$v){
//                $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
//            }
//        }else{$list=[];}
        $list = M('Withdraw_record')
            ->where(['user_id'=>$user['user_id']])
            ->page($page,$pageSize)
            ->order('intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @兑换比例列表
     */
    public function convert_scale(){
        $user = checklogin();
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Convert_scale')->page($page,$pageSize)->select();
        if (!$list){$list=[];}
        success($list);
    }

    /**
     * @输入度票,返回兑换的钻石
     */
    public function return_diamond(){
        $user = checklogin();
        $money = I('money');
        empty($money) ? error('参数错误!') : true;
        $system = M('System')->field(['lowest_limit,convert_scale1,convert_scale2'])->where(['id'=>1])->find();
        $get_diamond = (string)floor($money*($system['convert_scale2']/$system['convert_scale1']));
        success($get_diamond);
    }

    /**
     * @度票兑换钻石
     */
    public function convert(){
        $user = checklogin();
        $money = I('money');  $diamond = I('diamond');
        (empty($money) || empty($diamond)) ? error('参数错误!') : true;
        if ($user['get_money']<$money){error('度票不足!');}
        $data = [
            'user_id'=>$user['user_id'],
            'k'=>$money,
            'meters'=>$diamond,
            'intime'=>time(),
            'date'=>date('Y-m-d',time())
        ];
        $money2 = $user['money']+$diamond;
        $get_money = $user['get_money']-$money;
        $date = [
            'user_id'=>$user['user_id'],
            'money'=>$money2,
            'get_money'=>$get_money,
            'uptime'=>time()
        ];
        if (M('Convert')->add($data) && M('User')->save($date)){
            $content = $money."度票已成功兑换".$diamond.'钻石!';
            M('Message')->add(['type'=>1,'user_id2'=>$user['user_id'],'content'=>$content,'intime'=>time(),'date'=>date('Y-m-d',time())]);
            success('成功');
        }else{
            error('失败!');
        }
    }

    /**
     * @兑换记录
     */
    public function convert_list(){
        $user = checklogin();
        $page = I('page');  $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Convert')->where(['user_id'=>$user['user_id']])->page($page,$pageSize)->order('intime desc')->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @支付宝一键认证
     */
    public function pay_user(){
        $user = checklogin();
        $openid = I('openid');
        empty($openid) ? error('参数错误!') : true;
        $pay = M('Pay_user')->where(['openid'=>$openid])->find();
        if ($pay){
            error('支付宝已被绑定!');
        }else{
            if (M('Pay_user')->add(['user_id'=>$user['user_id'],'openid'=>$openid,'intime'=>time(),'date'=>date('Y-m-d',time())])){
                success('成功!');
            }else{
                error('失败!');
            }
        }
    }

    /**
     * @判断是否已认证
     */
    public function is_pay(){
        $user = checklogin();
        $pay = M('Pay_user')->where(['user_id'=>$user['user_id']])->find();
        $pay ? $is_pay = "2" : $is_pay = "1";
        success($is_pay);
    }


    /**
     * @我的师傅
     */
    public function my_master(){
        $user = checklogin();
        $user_id = I('user_id');
        if ($user_id){
            $where = [
                'user_id'=>$user_id
            ];
        }else{
            $where = [
                'user_id'=>$user['user_id']
            ];
        }
        $master = M('User_master')->where($where)->find();
        if ($master){
            $is_master = "2";
            $img = C('IMG_PREFIX').M('User')->where(['user_id'=>$master['master_id']])->getField('img');
            $grade = M('User')->where(['user_id'=>$master['master_id']])->getField('grade');
            $get_gradeinfo = get_gradeinfo($grade);
            $grade_img = $get_gradeinfo['img'];
            $master_id = $master['master_id'];
        }else{
            $is_master = "1";
            $img = "";
            $master_id = "0";
            $grade_img = "";
        }
        $result = ['is_master'=>$is_master,'img'=>$img,'master_id'=>$master_id,'grade_img'=>$grade_img];
        success($result);
    }

    /**
     * @填写师傅ID,绑定
     */
    public function add_master(){
        $user = checklogin();
        $id = I('ID');
        empty($id) ? error('参数错误!') : true;
        $u = M('User')->where(['ID'=>$id])->find();
        if (!$u){error('ID不存在!');}
        if ($user['id']==$id){error('不能绑定自己!');}
        if (M('User_master')->add(['user_id'=>$user['user_id'],'master_id'=>$u['user_id'],'intime'=>time(),'date'=>date('Y-m-d',time())])){
            success('成功!');
        }else{
            error('失败!');
        }
    }



    /**
     * @我的等级
     */
    public function my_grade(){
        $user = checklogin();
        $result['img'] = C('IMG_PREFIX').$user['img'];
        $result['grade'] = $user['grade'];
        $level = M('Level')->where(['level'=>$user['grade']])->find();
        $new_grade = $user['grade']+1;
        $level2 = M('Level')->where(['level'=>$new_grade])->find();
        if ($level2){
            $result['is_highest'] = "1";
            $result['between'] = (string)($level2['experience']-$level['experience']);
            $result['percentage'] = (string)round(((($user['experience']-$level['experience'])/($level2['experience']-$level['experience']))*100),2);
        }else{
            $result['is_highest'] = "2";
            $result['between'] = "0";
            $result['percentage'] = "0";
        }
        success($result);
    }


    /**
     * @账号安全
     * @判断是否绑定
     */
    public function safe_user(){
        $user = checklogin();
        $user = M('User')->where(['user_id'=>$user['user_id']])->field('user_id,token,phone,openid,qq_openid,weibo')->find();
        empty($user['phone']) ? $result['is_phone'] = "1" : $result['is_phone'] = "2";
        empty($user['openid']) ? $result['is_openid'] = "1" : $result['is_openid'] = "2";
        empty($user['qq_openid']) ? $result['is_qq_openid'] = "1" : $result['is_qq_openid'] = "2";
        empty($user['weibo']) ? $result['is_weibo'] = "1" : $result['is_weibo'] = "2";
        success($result);
    }


    /**
     * @绑定第三方
     * @type  1:微信  2:qq  3:微博
     */
    public function bound(){
        checklogin();
        $user_id = I('uid'); $type = I('type'); $openid = I('openid');
        (empty($type) || empty($openid)) ? error('参数错误!') : true;
        ($type==1 || $type==2 || $type==3) ? true : error('传值错误!');
        switch ($type){
            case 1:
                if (M('User')->where(['openid'=>$openid])->find()){
                    error('已注册!');
                }else {
                    if (M('User')->where(['user_id'=>$user_id])->save(['openid'=>$openid,'uptime'=>time()])){
                        success('成功!');
                    }else {
                        error('失败!');
                    }
                }
                break;
            case 2:
                if (M('User')->where(['qq_openid'=>$openid])->find()){
                    error('已注册!');
                }else {
                    if (M('User')->where(['user_id'=>$user_id])->save(['qq_openid'=>$openid,'uptime'=>time()])){
                        success('成功!');
                    }else {
                        error('失败!');
                    }
                }
                break;
            case 3:
                if (M('User')->where(['weibo'=>$openid])->find()){
                    error('已注册!');
                }else {
                    if (M('User')->where(['user_id'=>$user_id])->save(['weibo'=>$openid,'uptime'=>time()])){
                        success('成功!');
                    }else {
                        error('失败!');
                    }
                }
                break;
        }

    }

    /**
     * @账号管理--绑定手机号
     */
    public function bound_phone(){
        $user = checklogin();
        $phone = I('phone'); $yzm = I('yzm');
        (empty($phone) || empty($yzm)) ? error('参数错误!') : true;
        $code = M('Mobile_sms')->where(['phone'=>$phone,'state'=>1])->order('intime desc')->limit(1)->find();
//        if ($code) {
//            $time = M('System')->getFieldById(1,'code_volidity');
//            if (time()-$code['intime']>($time*60)){
//                error('验证码已失效!');
//            }
//        }
        if ($code['code']==$yzm){
            if (M('User')->where(['phone'=>$phone])->find()){
                error('手机号已注册!');
            }
            if (M('User')->where(['user_id'=>$user['user_id']])->save(['phone'=>$phone,'uptime'=>time()])){
                success('成功!');
            }else{
                error('失败!');
            }
        }else{
            error('验证码不一致!');
        }
    }

    /**
     * @账号管理--修改密码
     */
    public function up_pwd(){
        $user = checklogin();
        $yzm = I('yzm');  $pwd = I('new_pwd');
        (empty($yzm) || empty($pwd)) ? error('参数错误!') : true;
        $code = M('Mobile_sms')->where(['phone'=>$user['phone'],'state'=>1])->order('intime desc')->limit(1)->find();
        if ($code) {
            $time = M('System')->getFieldById(1,'code_volidity');
            if (time()-$code['intime']>($time*60)){
                error('验证码已失效!');
            }
        }
        if ($code['code']==$yzm){
            if (M('User')->where(['user_id'=>$user['user_id']])->save(['pwd'=>md5($pwd),'uptime'=>time()])){
                success('成功!');
            }else{
                error('失败!');
            }
        }else{
            error('验证码不一致!');
        }
    }

/**************************************************他人个人中心*******************************************************************/

    /**
     * @他人主页
     */
    public function other_center(){
        $user = checklogin();
        $user_id = I('user_id');
        empty($user_id) ? error('参数错误!') : true;
        $u = M('User')->find($user_id);
        $u['img'] = C('IMG_PREFIX').$u['img'];
        $u['get_money'] = FormatMoney($u['get_money']);
        $follow = M('Follow')
            ->alias('a')
            ->join('__USER__ b on a.user_id2=b.user_id')
            ->where(['a.user_id'=>$user_id,'b.is_del'=>1])
            ->count();
        $u['follow'] = FormatMoney($follow);
        $follow_to = M('Follow')->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id2'=>$user_id,'b.is_del'=>1])
            ->count();
        $u['follow_to'] = FormatMoney($follow_to);
        $u['collection'] = M('Collection')->where(['user_id'=>$user_id])->count();
        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$user_id])->find();
        $is_follow ? $u['is_follow'] = "2" : $u['is_follow'] = "1";
        //$u['video_count'] = M('Video')->where(['user_id'=>$user_id,'is_del'=>1])->count();    //视频数量
        $u['imgs_count'] = FormatMoney(M('User_imgs')->where(['user_id'=>$user_id])->count());
        $give_count = M('Give_gift')->where(['user_id'=>$user_id])->sum('jewel');
        $give_count ? $u['give_count'] = FormatMoney($give_count) : $u['give_count'] = "0";
        $live_count = M('Live_store')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user_id,'a.is_del'=>1])
            ->count();
        $u['live_count'] = FormatMoney($live_count);

        $get_gradeinfo = get_gradeinfo($u['grade']);
        $u['grade_img'] = $get_gradeinfo['img'];
        $u['name'] = $get_gradeinfo['name'];



        //判断是否正在直播
        $live = M('Live')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.grade,b.hx_username,b.ID,b.sex,b.province,b.city,b.money,b.get_money,b.type')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user_id,'a.live_status'=>1])
            ->find();
        if ($live){
            $u['is_live'] = "2";
            $live['img'] = C('IMG_PREFIX').$live['img'];
            $live['play_img'] = C('IMG_PREFIX').$live['play_img'];
            $live['qrcode_path'] = C('IMG_PREFIX').$live['qrcode_path'];
            $live['url'] = C('IMG_PREFIX')."/App/Index/share_live/live_id/" . base64_encode($live['live_id']);

            $get_gradeinfo = get_gradeinfo($live['grade']);
            $live['grade_img'] = $get_gradeinfo['img'];
            $live['name'] = $get_gradeinfo['name'];

            $u['live'] = $live;
        }else{
            $u['is_live'] = "1";
        }
        success($u);
    }

    /**
     * @相册列表
     */
    public function other_imgs_list(){
        checklogin();
        $user_id = I('user_id');
        empty($user_id) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $date = M('User_imgs')->field('date')->where(['user_id'=>$user_id])->order('intime desc')->group('date')->select();
        if ($date){
            $ids = array_map(function($v){ return $v['date'];},$date);
            foreach ($ids as $k=>$v) {
                if ($v == date('Y-m-d', time())) {
                    $day = '今天';
                } else {
                    $day = $v;
                }
                $list[$k]['time'] = $day;
                $imgs_list = M('User_imgs')
                    ->where(['user_id'=>$user_id,'date'=>$v])
                    ->order('intime desc')
                    ->select();
                foreach ($imgs_list as $a=>$b){
                    $imgs_list[$a]['imgs'] = C('IMG_PREFIX').$b['imgs'];
                }
                $list[$k]['list'] = $imgs_list;
            }
            $list = array_slice($list,($page-1)*$pageSize,$pageSize);
        }else{$list=[];}
        $result['count'] = M('User_imgs')->where(['user_id'=>$user_id])->count();
        $result['list'] = array_values($list);
        success($result);
    }


    /**
     * @他人粉丝列表
     * $type   1:粉丝列表   2:关注列表
     */
    public function other_follow_list(){
        $user = checklogin();
        $user_id = I('user_id');
        $type = I('type');
        (empty($type) || empty($user_id)) ? error('参数错误!') : true; ($type==1 || $type==2) ? true : error('传值错误!');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        switch ($type){
            case 1:
                $list = M('Follow')
                    ->alias('a')
                    ->field('b.user_id,b.img,b.username,b.sex,b.autograph,b.hx_username,b.grade')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id2'=>$user_id,'b.is_del'=>1])
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";

                        $get_gradeinfo = get_gradeinfo($v['grade']);
                        $list[$k]['grade_img'] = $get_gradeinfo['img'];
                        $list[$k]['name'] = $get_gradeinfo['name'];
                    }
                }else{$list=[];}
                break;
            case 2:
                $list = M('Follow')
                    ->alias('a')
                    ->field('b.user_id,b.img,b.username,b.sex,b.autograph,b.hx_username,b.grade')
                    ->join('__USER__ b on a.user_id2=b.user_id')
                    ->where(['a.user_id'=>$user_id,'b.is_del'=>1])
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";

                        $get_gradeinfo = get_gradeinfo($v['grade']);
                        $list[$k]['grade_img'] = $get_gradeinfo['img'];
                        $list[$k]['name'] = $get_gradeinfo['name'];
                    }
                }else{$list=[];}
                break;
        }
        success($list);
    }


    /**
     * @日榜、周榜、总榜
     * $type  1:日榜   2:周榜  3:总榜
     */
    public function other_total_list(){
        // $user = checklogin();
        $user_id = I('user_id');
        $type = I('type');
        (empty($type) || empty($user_id)) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        //$pageSize ? $pageSize : $pageSize = 10;
        if($page){
            $pageSize ? $pageSize : $pageSize = 10;
        }
        switch ($type){
            case 1:
                $where = [
                    'a.user_id2'=>$user_id,
                    'b.is_del'=>1,
                    'a.date'=>date('Y-m-d',time())
                ];
                break;
            case 2:
                $where = [
                    'a.user_id2'=>$user_id,
                    'b.is_del'=>1,
                    'a.intime'=>['gt',time()-(7*24*60*60)]
                ];
                break;
            case 3:
                $where = [
                    'a.user_id2'=>$user_id,
                    'b.is_del'=>1,
                ];
                break;
        }
        $total = M('Give_gift')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($where)
            ->sum('a.jewel');
        $total ? $list['total'] = $total : $list['total'] = "0";
        if($page){
            $give_list = M('Give_gift')->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($where)->order('count desc')
                ->page($page,$pageSize)
                ->select();
        }else{
            $give_list = M('Give_gift')->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($where)->order('count desc')
                ->select();
        }
        if ($give_list){
            foreach ($give_list as $k=>$v){
                $give_list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                $is_follow ? $give_list[$k]['is_follow'] = "2" : $give_list[$k]['is_follow'] = "1";

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $give_list[$k]['grade_img'] = $get_gradeinfo['img'];
                $give_list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$give_list=[];}
        $list['list'] = $give_list;
        success($list);
    }

    /*
     * @小时榜，日榜、周榜、月榜，总榜
     * $type  1:小时榜   2:日榜  3:周榜  4:月榜  5:总榜
     */
    public function charts(){
        // $user = checklogin();
        $uid = I('uid');
        $type = I('type');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;
        $pageSize ? $pageSize : $pageSize = 1000;
        switch ($type){
            case 1:
                $where = [
                    'b.is_del'=>1,
                    'a.intime'=>['gt',time()-(60*60)]
                ];
                break;
            case 2:
                $where = [
                    'b.is_del'=>1,
                    'a.date'=>date('Y-m-d',time())
                ];
                break;
            case 3:
                $where = [
                    'b.is_del'=>1,
                    'a.intime'=>['gt',time()-(7*24*60*60)]
                ];
                break;
            case 4:
                $where = [
                    'b.is_del'=>1,
                    'a.intime'=>['gt',time()-(30*24*60*60)]
                ];
                break;
            case 5:
                $where = [
                    'b.is_del'=>1,
                ];
                break;
        }

        $give_list = M('Give_gift')->alias('a')
                ->field('b.user_id,b.img,b.username,b.hx_username,sum(jewel) as count,b.sex,b.grade')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->group('a.user_id')
                ->where($where)
                ->order('count desc')
                ->page($page,$pageSize)
                ->select();
        // echo M('Give_gift')->getLastSql();exit;
        if ($give_list){
            foreach ($give_list as $k=>$v){
                $give_list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                $is_follow = M('Follow')->where(['user_id'=>$uid,'user_id2'=>$v['user_id']])->find();
                $is_follow ? $give_list[$k]['is_follow'] = "2" : $give_list[$k]['is_follow'] = "1";

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $give_list[$k]['grade_img'] = $get_gradeinfo['img'];
                $give_list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$give_list=[];}
        success($give_list);
    }


    /**
     * @他人视频列表
     */
    public function other_video_list(){
        $user = checklogin();
        $user_id = I('user_id');
        empty($user_id) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Video')
            ->alias('a')
            ->field('a.*,b.img,b.username,b.get_money')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user_id,'a.is_del'=>1])
            ->order('a.intime desc')
            ->page($page,$pageSize)
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                $list[$k]['video_img'] = C('IMG_PREFIX').$v['video_img'];
                $list[$k]['url'] = C('IMG_PREFIX').$v['url'];
            }
        }else{$list=[];}
        success($list);
    }

    /**
     * @他人直播列表
     */
    public function other_live_list(){
        checklogin();
        $user_id = I('user_id');
        empty($user_id) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        $list = M('Live_store')
            ->alias('a')
            ->field('a.*,b.img,b.sex,b.username,b.ID,b.hx_username,b.grade,b.province,b.city,b.zan,b.money,b.get_money')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.user_id'=>$user_id,'a.is_del'=>1])
            ->order('a.intime desc')
            ->page($page,$pageSize)
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                if (time()-$v['intime']<(7*24*60*60)){
                    $list[$k]['intime'] = get_times($v['intime']);
                }else{
                    $list[$k]['intime'] = $v['date'];
                }
                $list[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                $list[$k]['img'] = C('IMG_PREFIX').M('User')->where(['user_id'=>$v['user_id']])->getField('img');

                $get_gradeinfo = get_gradeinfo($v['grade']);
                $list[$k]['grade_img'] = $get_gradeinfo['img'];
                $list[$k]['name'] = $get_gradeinfo['name'];
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @举报视频
     */
    public function report_live_store(){
        $user = checklogin();
        $live_store_id = intval(I('live_store_id'));
        empty($live_store_id) ? error('参数错误') : true;
        $data = [
            'user_id'=>$user['user_id'],
            'live_store_id'=>$live_store_id,
            'intime'=>time(),
            'date'=>date('Y-m-d',time())
        ];
        if (M('Live_store_report')->add($data)){
            success('成功');
        }else{
            error('失败');
        }
    }



    /**
     * @举报
     */
    public function report(){
        $user = checklogin();
        $user_id = I('user_id'); $why = I('why');
        (empty($user_id) || empty($why)) ? error('参数错误!') : true;
        if (M('Report')->add(['user_id'=>$user['user_id'],'user_id2'=>$user_id,'why'=>$why,'intime'=>time(),'type'=>2])){
            success('成功!');
        }else{
            error('失败!');
        }
    }

    /**
     * @判断是否绑定手机号
     */
    public function is_bound_phone(){
        $user = checklogin();
        $user['phone'] ? $phone = "2" : $phone = "1";
        success($phone);
    }


    /**
     * @获取微信appsecret
     */
    public function get_secret(){
        $system = M('System')->field('appsecret')->where(['id'=>1])->find();
        success($system);
    }


/**************************************支付宝认证**********************************************************/

    /**
     * @返回生成的签名
     */
    public function get_sign(){
        $params = $_POST['params'];
        $get_sign = sign($params);
        success(rawurlencode($get_sign));
    }

    /**
     * @支付宝认证信息
     */
    public function alipay_approve(){
        $user = checklogin();
        $code = trim(I('code'));
        empty($code) ? error('参数错误!') : true;
        $system = M('System')->field('alipay_appid,alipay_privatekey,alipay_publickey')->where(['id'=>1])->find();
        import('Vendor.aop.AopClient');
        $c = new \AopClient;
        $c->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $c->appId = $system['alipay_appid'];
        $c->rsaPrivateKey = $system['alipay_privatekey'];
        $c->signType= "RSA2";
        $c->alipayrsaPublicKey = $system['alipay_publickey'];

        $request= new \AlipaySystemOauthTokenRequest();
        $request->setCode($code);
        $request->setGrantType("authorization_code");
        $response= $c->execute($request);
        $rs = json_decode($response,true);
        if ($rs['error_response']){
            error('未知错误');
        }else{
            $request2= new \AlipayUserUserinfoShareRequest();
            $response2= $c->execute($request2,$rs['alipay_system_oauth_token_response']['access_token']);
            $rss = json_decode($response2,true);
            if ($rss['error_response']){
                error('未知错误');
            }else{
                if ($rss['alipay_user_userinfo_share_response']['is_certified']=='F'){
                    error('支付宝未实名认证!');
                }elseif($rss['alipay_user_userinfo_share_response']['is_certified']=='T'){
                    $alipay = M('User_alipay')->where(['user_id'=>$user['user_id']])->find();
                    $alipay ? error('已认证过') : true;
                    $result = $rss['alipay_user_userinfo_share_response'];
                    $date = [
                        'user_id'=>$user['user_id'],
                        'avatar'=>$result['avatar'],
                        'nick_name'=>$result['nick_name'],
                        'province'=>$result['province'],
                        'city'=>$result['city'],
                        'gender'=>$result['gender'],
                        'alipay_user_id'=>$result['alipay_user_id'],
                        'user_type'=>$result['user_type'],
                        'user_status'=>$result['user_status'],
                        'is_certified'=>$result['is_certified'],
                        'is_student_certified'=>$result['is_student_certified'],
                        'intime'=>time()
                    ];
                    if (M('User_alipay')->add($date)){
                        M('User')->where(['user_id'=>$user['user_id']])->save(['is_authen'=>2,'uptime'=>time()]);
                        success('认证成功!');
                    }else{
                        error('认证失败!');
                    }
                }else{
                    error('未知错误');
                }
            }
        }
    }



    /**
     * @支付宝支付
     */
    public function get_pay_sign(){
        $user = checklogin();
        $params = $_POST['params'];
        $pay_number = I('order_number');
        $price_id = I('price_id');
        (empty($price_id) || empty($order_number) || empty($params)) ? error('参数错误!') : true;
        $price = M('Price')->find($price_id);
        $amount = $price['price'];  //金额
        $meters = $price['meters'];  //福分
        $type = 'alipay';
        M('recharge_record')->add(array('user_id'=>$user['user_id'],'pay_number'=>$pay_number,'amount'=>$amount,'meters'=>$meters,'pay_on'=>'','pay_return'=>'','pay_type'=>$type,'intime'=>time()));

        $get_sign = sign($params);
        success(rawurlencode($get_sign));
    }


    /**
     * @支付成功
     */
    public function pay_success(){
        $user = checklogin();
        $pay_number = I('order_number');
        empty($pay_number) ? error('参数错误!') : true;
        $data = array(
            "pay_on"=>$pay_number
        );
        $rec = M('recharge_record')->where(array('pay_number'=>$pay_number))->find();
        M('recharge_record')->where(array('recharge_record_id'=>$rec['recharge_record_id']))->save($data); //支付成功!
        $money = (M('User')->where(array('user_id'=>$rec['user_id']))->getField('money'))+$rec['meters'];
        M('User')->where(array('user_id'=>$rec['user_id']))->save(array('money'=>$money,'uptime'=>time()));  //修改会员钻石数量
        success('成功!');
    }

    /**
     * @支付宝支付回调
     */
    public function pay_callback(){
        $result = I();
        print_r($result);
    }


    /**
     * @提现记录
     */
    public function tx_list(){
        $user = checklogin();
        $list = M('withdraw_record')->where(array('user_id'=>$user['user_id']))->field('pay_return',true)->order('intime desc')->select();
        if(empty($list)){
            $list = [];
        }
        success($list);


    }








}
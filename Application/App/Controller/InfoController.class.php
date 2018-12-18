<?php
namespace App\Controller;
use Behavior\CheckLangBehavior;

use Home\Controller\IndexController;
use Psr\Log\Test\DummyTest;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;
class InfoController extends CommonController {
    /**
     * @资讯列表
     */
    public function index(){
        $user = checklogin();
        $date = I('date');  empty($date) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        //资讯
        $info = M('Information')->where(['date'=>$date])->select();
        if ($info){
            foreach ($info as $k=>$v){
                $info[$k]['img'] ? $info[$k]['img'] = C('IMG_PREFIX').$v['img'] : $info[$k]['img'] = "";
                $info[$k]['is_type'] = "1";
                $info[$k]['times'] = get_times($v['intime']);
                $info[$k]['url'] = C('IMG_PREFIX')."/App/Info/details/id/".base64_encode($v['information_id']);
            }
        }else{$info=[];}
        //预告
        $pre = M('Prevue')
            ->alias('a')
            ->field('a.*,b.img as user_img,b.username,b.company,b.duty,b.autograph')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.date'=>$date])
            ->select();
        if ($pre){
            foreach ($pre as $k=>$v){
                $sign_up = M('Prevue_sign_up')
                    ->alias('a')
                    ->field('a.*,b.username,b.img')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.prevue_id'=>$v['prevue_id']])
                    ->select();
                if ($sign_up){
                    foreach ($sign_up as $a=>$b){
                        $sign_up[$a]['img'] = C('IMG_PREFIX').$b['img'];
                    }
                }else{$sign_up=[];}
                $pre[$k]['user_img'] = C('IMG_PREFIX').$v['user_img'];
                $pre[$k]['sign_up'] = $sign_up;
                $pre[$k]['is_type'] = "2";
                $is_sign_up = M('Prevue_sign_up')->where(['user_id'=>$user['user_id'],'prevue_id'=>$v['prevue_id']])->find();
                $is_sign_up ? $pre[$k]['is_sign_up'] = "2" : $pre[$k]['is_sign_up'] = "1";
            }
        }else{$pre=[];}
        //视频
        $video = M('Live_store')
            ->alias('a')
            ->field('a.*,b.nums,b.watch_nums,b.light_up_count,c.username,c.company,c.duty,c.autograph')
            ->join('__LIVE__ b on a.live_id=b.live_id')
            ->join('__USER__ c on a.user_id=c.user_id')
            ->where(['a.is_tuijian'=>2,'a.date'=>$date])
            ->select();
        if ($video){
            foreach ($video as $k=>$v){
                $video[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                $video[$k]['is_type'] = "3";
            }
        }else{$video=[];}
        $list = array_merge($info,$pre,$video);
        foreach ($list as $key => $row ){
            $num1[$key] = $row ['intime'];
        }
        array_multisort($num1, SORT_DESC, $list);
        $list = array_slice($list,($page-1)*$pageSize,$pageSize);

        if($page==1){
            //推荐列表(插入10个)
            $foll = M('Follow')->where(['user_id'=>$user['user_id']])->select();
            if ($foll){
                $ids = array_map(function($v){ return $v['user_id2'];},$foll);
                array_push($ids, $user['user_id']);
                $dat['user_id'] = ['not in',$ids];
                $dat['is_fans'] = 1;
                $dat['is_del'] = 1;
                $tuijian = M('User')
                    ->field('user_id,phone,company,duty,img,sex,username,autograph,ID,hx_username,province,city,zan,money,url')
                    ->where($dat)
                    ->order('get_money desc')
                    ->limit(10)
                    ->select();
                if ($tuijian){
                    foreach ($tuijian as $k=>$v){
                        $tuijian[$k]['img'] = C('IMG_PREFIX').$v['img'];
                    }
                    $tj = ['tuijian'=>['is_type'=>"4",'tuijian'=>$tuijian]];
                    array_splice($list,4,0,$tj);    //把推荐列表插入到数组第四个位置,如果前面没有,自动推前
                }
                //else{$tuijian=[];}
            }//else{$tuijian=[];}
        }
        success($list);
    }


    /**
     * @资讯详情
     */
    public function info_details(){
        $user = checklogin();
        $information_id = I('information_id');
        empty($information_id) ? error('参数错误!') : true;
        M('Information')->comment('点击数加1')->where(['information_id'=>$information_id])->setInc('watch_nums');
        $info = M('Information')->where(['information_id'=>$information_id])->find();
        $info['times'] = get_times($info['intime']);
        $is_comments = M('Information_comments')->where(['information_id'=>$information_id,'user_id'=>$user['user_id'],'type'=>1])->find();
        $is_comments ? $info['is_comments'] = "2" : $info['is_comments'] = "1";
        $is_collection = M('Collection')->where(['about_id'=>$information_id,'user_id'=>$user['user_id'],'type'=>1])->find();
        $is_collection ? $info['is_collection'] = "2" : $info['is_collection'] = "1";
        $info['url'] = C('IMG_PREFIX')."/App/Info/details/id/".base64_encode($information_id);
        success($info);
    }

    /**
     * @资讯详情页面html
     */
    public function details(){
        $id = base64_decode(I('id'));
        $details = M('Information')->find($id);
        $this->assign('details',$details);
        $this->display();
    }

    /**
     * @评论资讯
     */
    public function comment_info(){
        $user = checklogin();
        $information_id = I('information_id');  $content = I('content');
        (empty($information_id) || empty($content)) ? error('参数错误!') : true;
        if (M('information_comments')->add(['information_id'=>$information_id,'user_id'=>$user['user_id'],'type'=>1,'intime'=>time(),'content'=>$content])){
            M('Information')->comment('评论数加1')->where(['information_id'=>$information_id])->setInc('comments');
            success('成功!');
        }else{
            error('失败!');
        }
    }

    /**
     * @收藏(取消收藏)
     * $type  1:资讯  2:视频
     * $state  1:收藏   2:取消收藏
     */
    public function collection(){
        $user = checklogin();
        $type = I('type');  $state = I('state'); $about_id = I('about_id');
        (empty($type) || empty($state) || empty($about_id)) ? error('参数错误!') : true;
        ($type==1 || $type==2 || $state==1 || $state==2) ? true : error('传值错误!');
        $collection = M('Collection')->where(['user_id'=>$user['user_id'],'type'=>$type,'about_id'=>$about_id])->find();
        switch ($type){
            case 1:
                switch ($state){
                    case 1:
                        if ($collection){
                            error('已收藏过!');
                        }else{
                            if (M('Collection')->add(['user_id'=>$user['user_id'],'type'=>$type,'about_id'=>$about_id,'intime'=>time()])){
                                M('Information')->comment('收藏数加1')->where(['information_id'=>$about_id])->setInc('collection');
                                success('成功!');
                            }else{
                                error('失败!');
                            }
                        }
                        break;
                    case 2:
                        if ($collection){
                            if (M('Collection')->where(['collection_id'=>$collection['collection_id']])->delete()){
                                M('Information')->comment('收藏数减1')->where(['information_id'=>$about_id])->setDec('collection');
                                success('成功!');
                            }else{
                                error('失败!');
                            }
                        }else{
                            error('未收藏过!');
                        }
                        break;
                }
                break;
            case 2:
                switch ($state){
                    case 1:
                        if ($collection){
                            error('已收藏过!');
                        }else{
                            if (M('Collection')->add(['user_id'=>$user['user_id'],'type'=>$type,'about_id'=>$about_id,'intime'=>time()])){
                                M('Live_store')->comment('收藏数加1')->where(['live_store_id'=>$about_id])->setInc('collection');
                                success('成功!');
                            }else{
                                error('失败!');
                            }
                        }
                        break;
                    case 2:
                        if ($collection){
                            if (M('Collection')->where(['collection_id'=>$collection['collection_id']])->delete()){
                                M('Live_store')->comment('收藏数减1')->where(['live_store_id'=>$about_id])->setDec('collection');
                                success('成功!');
                            }else{
                                error('失败!');
                            }
                        }else{
                            error('未收藏过!');
                        }
                        break;
                }
                break;
        }
    }


    /**
     * @评论列表
     */
    public function comment_list(){
        $user = checklogin();
        $information_id = I('information_id');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        empty($information_id) ? error('参数错误!') : true;
        $list = M('Information_comments')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.company,b.duty')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.information_id'=>$information_id,'a.type'=>1])
            ->page($page,$pageSize)
            ->order('a.intime desc')
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                $list[$k]['intime'] = date('Y-m-d H:i',$v['intime']);
                $is_zan = M('Information_comments_zan')->comment('查询是否点赞')->where(['information_comments_id'=>$v['information_comments_id']])->find();
                $is_zan ? $list[$k]['is_zan'] = "2" : $list[$k]['is_zan'] = "1";
                $rs = M('Information_comments')->where(['type'=>2,'fid'=>$v['information_comments_id']])->select();
                if ($rs){
                    foreach ($rs as $a=>$b) {
                        $rs[$a]['username'] = M('User')->getFieldByUser_id($b['user_id'],'username');
                        $user_id = M('Information_comments')->where(['information_comments_id'=>$b['fid']])->getField('user_id');
                        $rs[$a]['username2'] = M('User')->getFieldByUser_id($user_id,'username');
                        $rs[$a]['intime'] = date('Y-m-d H:i',$b['intime']);
                    }
                }else{$rs=[];}
                $list[$k]['rs'] = $rs;
            }
        }else{$list=[];}
        success($list);
    }

    /**
     * @评论点赞
     * @state 1:赞  2:取消赞
     */
    public function comment_zan(){
        $user = checklogin();
        $state = I('state');  $information_comments_id = I('information_comments_id');
        (empty($information_comments_id) || empty($state)) ? error('参数错误!') : true;
        ($state==1 || $state==2) ? true : error('传值错误!');
        $zan = M('Information_comments_zan')->where(['information_comments_id'=>$information_comments_id,'user_id'=>$user['user_id']])->find();
        switch ($state){
            case 1:
                if ($zan){
                    error('已赞过!');
                }else{
                    if (M('Information_comments_zan')->add(['information_comments_id'=>$information_comments_id,'user_id'=>$user['user_id'],'intime'=>time()])){
                        M('Information_comments')->comment('赞加1')->where(['information_comments_id'=>$information_comments_id])->setInc('zan');

                       //添加消息
                        $information__id = M('Information_comments')->where(['information_comments_id'=>$information_comments_id])->getField('information_id');
                        $title = M('Information')->where(['information_id'=>$information__id])->getField('title');
                        $content = '我赞了你在'.$title.'中的评论';
                        M('Message')->add(['type'=>3,'user_id'=>$user['user_id'],'user_id'=>M('Information_comments')->where(['information_comments_id'=>$information_comments_id])->getField('user_id'),'content'=>$content,'state'=>1,'intime'=>time(),'date'=>date('Y-m-d',time()),'information_id'=>$information__id,'stats'=>1]);

                        success('成功!');
                    }else{
                        error('失败!');
                    }
                }
                break;
            case 2:
                if ($zan){
                    if (M('Information_comments_zan')->where(['information_comments_zan_id'=>$zan['information_comments_zan_id']])->delete()){
                        M('Information_comments')->comment('赞减1')->where(['information_comments_id'=>$information_comments_id])->setDec('zan');
                        success('成功!');
                    }else{
                        error('失败!');
                    }
                }else{
                    error('未赞过!');
                }
                break;
        }
    }

    /**
     * @回复评论
     */
    public function reply_comments(){
        $user = checklogin();
        $information_comments_id = I('information_comments_id');  $content = I('content');
        (empty($information_comments_id) || empty($content)) ? error('参数错误!') : true;
        $information_id = M('Information_comments')->where(['information_comments_id'=>$information_comments_id])->getField('information_id');
        if (M('Information_comments')->add(['information_id'=>$information_id,'user_id'=>$user['user_id'],'fid'=>$information_comments_id,'type'=>2,'intime'=>time(),'content'=>$content])){

            $user_id = M('Information_comments')->where(['information_comments_id'=>$information_comments_id])->getField('user_id');
           // $alias = M('User')->where(['user_id'=>$user_id])->getField('alias');
//            $contents = M('User')->where(['user_id'=>$user['user_id']])->getField('username').'回复了你:'.$content;
            //添加消息
            M('Message')->add(['type'=>2,'user_id'=>$user['user_id'],'user_id2'=>$user_id,'content'=>$content,'intime'=>time(),'date'=>date('Y-m-d',time()),'information_id'=>$information_id]);
//            //极光推送
//            push5($user_id,$contents,$alias,2);

            success('成功!');
        }else{
            error('失败!');
        }
    }


    /**
     * @资讯分享
     */
    public function share(){
        checklogin();
        $information_id = I('information_id');
        empty($information_id) ? error('参数错误!') : true;
        if (M('Information')->where(['information_id'=>$information_id])->setInc('share')){
            success('成功!');
        }else{
            error('失败!');
        }
    }



    /**
     * @预告详情
     */
    public function prevue_details(){
        $user = checklogin();
        $prevue_id = I('prevue_id');
        empty($prevue_id) ? error('参数错误!') : true;
        $prevue = M('Prevue')
            ->alias('a')
            ->field('a.*,b.username,b.img as user_img,b.company,b.duty,b.autograph,b.ID,b.hx_username')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['a.prevue_id'=>$prevue_id])
            ->find();
        $prevue['img'] ? $prevue['img'] = C('IMG_PREFIX').$prevue['img'] : $prevue['img'] ="";
        $prevue['user_img'] = C('IMG_PREFIX').$prevue['user_img'];
        $is_sign_up = M('Prevue_sign_up')->where(['user_id'=>$user['user_id'],'prevue_id'=>$prevue_id])->find();
        $is_sign_up ? $prevue['is_sign_up'] = "2" : $prevue['is_sign_up'] = "1";
        $list = M('Prevue_sign_up')
            ->alias('a')
            ->field('a.*,b.img,b.username')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['prevue_id'=>$prevue_id])
            ->order('intime desc')
            ->limit(5)
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        $prevue['list'] = $list;
        success($prevue);
    }

    /**
     * @报名
     */
    public function sign_up(){
        $user = checklogin();
        $prevue_id = I('prevue_id');
        empty($prevue_id) ? error('参数错误!') : true;
        $sign = M('Prevue_sign_up')->where(['user_id'=>$user['user_id'],'prevue_id'=>$prevue_id])->find();
        if ($sign){
            error('已报名');
        }else{
            if (M('Prevue_sign_up')->add(['user_id'=>$user['user_id'],'prevue_id'=>$prevue_id])){
                M('Prevue')->where(['prevue_id'=>$prevue_id])->setInc('sign_up_count');
                success('成功!');
            }else{
                error('失败!');
            }
        }
    }

    /**
     * @报名列表
     */
    public function sign_up_list(){
        $user = checklogin();
        $prevue_id = I('prevue_id');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        empty($prevue_id) ? error('参数错误!') : true;
        $list = M('Prevue_sign_up')
            ->alias('a')
            ->field('a.*,b.img,b.username,b.company,b.duty')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where(['prevue_id'=>$prevue_id])
            ->order('a.intime desc')
            ->page($page,$pageSize)
            ->select();
        if ($list){
            foreach ($list as $k=>$v) {
                $list[$k]['img'] =  C('IMG_PREFIX').$v['img'];
                $list[$k]['intime'] = get_times($v['intime']);
            }
        }else{$list=[];}
        success($list);
    }


    /**
     * @视频详情
     */
    public function video_details(){
        $user = checklogin();
        $live_store_id = I('live_store_id');
        empty($live_store_id) ? error('参数错误') : true;
        $live = M('Live_store')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.duty,b.autograph,b.ID,b.hx_username,c.content,c.start_time,c.watch_nums')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->join('__LIVE__ c on a.live_id=c.live_id')
            ->where(['live_store_id'=>$live_store_id])
            ->find();
        $live['play_img'] = C('IMG_PREFIX').$live['play_img'];
        $live['img'] = C('IMG_PREFIX').$live['img'];
        $live['comment_info'] = date('Y-m-d H:i',$live['start_time']);
        $list = M('Live_number')
            ->alias('a')
            ->field('a.*,b.username,b.img,b.company,b.duty')
            ->join('__USER__ b on a.user_id2=b.user_id')
            ->where(['live_id'=>$live['live_id']])
            ->limit(5)
            ->select();
        if ($list){
            foreach ($list as $k=>$v){
                $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
            }
        }else{$list=[];}
        $live['list'] = $list;
        success($live);
    }


    /**
     * @搜索
     * @typpe  1:资讯  2:视频   3:直播   4:用户
     */
    public function search(){
        $user = checklogin();
        $keywords = I('keywords');  $type = I('type');
        (empty($keywords) || empty($type)) ?  error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;

        M('Search')->add(['user_id'=>$user['user_id'],'keywords'=>$keywords,'intime'=>time(),'date'=>date('Y-m-d',time())]);

        switch ($type){
            case 1:
                //资讯
                $data['title'] = ['like','%'.$keywords.'%'];
                $list = M('Information')->where($data)->order('intime desc')->page($page,$pageSize)->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] ? $list[$k]['img'] = C('IMG_PREFIX').$v['img'] : $list[$k]['img'] = "";
                        $list[$k]['times'] = get_times($v['intime']);
                        $list[$k]['url'] = C('IMG_PREFIX')."/App/Info/details/id/".base64_encode($v['information_id']);
                    }
                }else{$list=[];}
                break;
            case 2:
                //视频
                $data['a.title|c.username'] = ['like','%'.$keywords.'%'];
                $list = M('Live_store')
                    ->alias('a')
                    ->field('a.*,b.nums,b.watch_nums,b.light_up_count,c.username,c.company,c.duty,c.autograph')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id=c.user_id')
                    ->where($data)
                    ->page($page,$pageSize)
                    ->order('a.intime desc')
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                    }
                }else{$list=[];}
                break;
            case 3:
                //直播
                $data = [
                    'a.live_status'=>1,
                    'a.title|b.username'=>['like','%'.$keywords.'%']
                ];
                $list = M('Live')
                    ->alias('a')
                    ->field('a.*,b.phone,b.img,b.company,b.duty,b.sex,b.username,b.ID,b.hx_username,b.hx_password,b.province,b.city,b.area,b.zan,b.money,b.get_money,b.url')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where($data)
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                    }
                }else{$list=[];}
                break;
            case 4:
                //用户
                $data = [
                    'is_del'=>1,
                    'username|duty|autograph'=>['like','%'.$keywords.'%']
                ];
                $list = M('User')
                    ->field('user_id,img,sex,username,type,company,duty,autograph,ID,hx_username,province,city,zan,money,get_money')
                    ->where($data)
                    ->page($page,$pageSize)
                    ->order('get_money desc')
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                    }
                }else{$list=[];}
                break;
        }
        success($list);
    }


    /**
     * @按标签查询
     * @type   1:资讯  2:直播
     */
    public function sel_lebel_list(){
        $user = checklogin();
        $lebel = I('lebel');   $type = I('type');
        (empty($lebel) || empty($type)) ? error('参数错误!') : true;   ($type==1 || $type==2) ? true : error('传值错误!');
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        switch ($type){
            case 1:
                //资讯
                $info = M('Information')->where(['lebel'=>$lebel])->select();
                if ($info){
                    foreach ($info as $k=>$v){
                        $info[$k]['img'] ? $info[$k]['img'] = C('IMG_PREFIX').$v['img'] : $info[$k]['img'] = "";
                        $info[$k]['is_type'] = "1";
                        $info[$k]['times'] = get_times($v['intime']);
                        $info[$k]['url'] = C('IMG_PREFIX')."/App/Info/details/id/".base64_encode($v['information_id']);
                    }
                }else{$info=[];}
                //预告
                $pre = M('Prevue')
                    ->alias('a')
                    ->field('a.*,b.img as user_img,b.username,b.company,b.duty,b.autograph')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.lebel'=>$lebel])
                    ->select();
                if ($pre){
                    foreach ($pre as $k=>$v){
                        $sign_up = M('Prevue_sign_up')
                            ->alias('a')
                            ->field('a.*,b.username,b.img')
                            ->join('__USER__ b on a.user_id=b.user_id')
                            ->where(['a.prevue_id'=>$v['prevue_id']])
                            ->select();
                        if ($sign_up){
                            foreach ($sign_up as $a=>$b){
                                $sign_up[$a]['img'] = C('IMG_PREFIX').$b['img'];
                            }
                        }else{$sign_up=[];}
                        $pre[$k]['user_img'] = C('IMG_PREFIX').$v['user_img'];
                        $pre[$k]['sign_up'] = $sign_up;
                        $pre[$k]['is_type'] = "2";
                        $is_sign_up = M('Prevue_sign_up')->where(['user_id'=>$user['user_id'],'prevue_id'=>$v['prevue_id']])->find();
                        $is_sign_up ? $pre[$k]['is_sign_up'] = "2" : $pre[$k]['is_sign_up'] = "1";
                    }
                }else{$pre=[];}
                //视频
                $video = M('Live_store')
                    ->alias('a')
                    ->field('a.*,b.nums,b.watch_nums,b.light_up_count,c.username,c.company,c.duty,c.autograph')
                    ->join('__LIVE__ b on a.live_id=b.live_id')
                    ->join('__USER__ c on a.user_id=c.user_id')
                    ->where(['a.lebel'=>$lebel])
                    ->select();
                if ($video){
                    foreach ($video as $k=>$v){
                        $video[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                        $video[$k]['is_type'] = "3";
                    }
                }else{$video=[];}
                $list = array_merge($info,$pre,$video);
                foreach ($list as $key => $row ){
                    $num1[$key] = $row ['intime'];
                }
                array_multisort($num1, SORT_DESC, $list);
                $list = array_slice($list,($page-1)*$pageSize,$pageSize);
                break;
            case 2:
                $data = [
                    'a.live_status'=>1,
                    'a.lebel'=>$lebel
                ];
                $list = M('Live')
                    ->alias('a')
                    ->field('a.*,b.phone,b.img,b.company,b.duty,b.sex,b.username,b.ID,b.hx_username,b.hx_password,b.province,b.city,b.area,b.zan,b.money,b.get_money,b.url')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where($data)
                    ->order('a.intime desc')
                    ->page($page,$pageSize)
                    ->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        $list[$k]['play_img'] = C('IMG_PREFIX').$v['play_img'];
                        $list[$k]['img'] = C('IMG_PREFIX').$v['img'];
                    }
                }else{$list=[];}
                break;
        }

        success($list);
    }














}
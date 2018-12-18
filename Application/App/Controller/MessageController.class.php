<?php
namespace App\Controller;
use Behavior\CheckLangBehavior;

use Home\Controller\IndexController;
use Psr\Log\Test\DummyTest;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;
class MessageController extends CommonController {

    /**
     * @右上角是否有消息
     */
    public function is_message(){
        $user = checklogin();
        $system = M('Message')->where(['state'=>1,'user_id2'=>$user['user_id']])->select();
        $system ? success('2') : success('1');
    }

    /**
     * @点击消息
     */
    public function index(){
        $user = checklogin();
        $system = M('Message')->where(['type'=>1,'state'=>1,'user_id2'=>$user['user_id']])->select();
        $system ? $result['system'] = "2" : $result['system'] ="1";
        $comment = M('Message')->where(['type'=>2,'state'=>1,'user_id2'=>$user['user_id']])->select();
        $comment ? $result['comment'] = "2" : $result['comment'] ="1";
        $zan = M('Message')->where(['type'=>3,'state'=>1,'user_id2'=>$user['user_id']])->select();
        $zan ? $result['zan'] = "2" : $result['zan'] ="1";
        success($result);
    }


    /**
     * @消息列表
     * @type  1:系统消息   2:评论消息  3:点赞消息
     */
    public function message_list(){
        $user = checklogin();
        $type = I('type');
        empty($type) ? error('参数错误!') : true;
        $page = I('page');
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  $pageSize ? $pageSize : $pageSize = 10;
        switch ($type){
            case 1:
                $list = M('Message')->where(['type'=>$type,'user_id2'=>$user['user_id']])->order('intime desc')->page($page,$pageSize)->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        if ($v['state']==1){
                            M('Message')->where(['message_id'=>$v['message_id']])->save(['state'=>2,'uptime'=>time()]);
                        }
                        $list[$k]['intime'] = date('Y-m-d H:i:s',$v['intime']);
                    }
                }else{$list=[];}
                break;
            case 2:
                $list = M('Message')->where(['type'=>$type,'user_id2'=>$user['user_id']])->order('intime desc')->page($page,$pageSize)->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        if ($v['state']==1){
                            M('Message')->where(['message_id'=>$v['message_id']])->save(['state'=>2,'uptime'=>time()]);
                        }
                        $list[$k]['intime'] = date('m-d H:i',$v['intime']);
                        $list[$k]['img'] = C('IMG_PREFIX').M('User')->where(['user_id'=>$v['user_id']])->getField('img');
                        $list[$k]['username'] = M('User')->where(['user_id'=>$v['user_id']])->getField('username');
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";
                    }
                }else{$list=[];}
                break;
            case 3:
                $list = M('Message')->where(['type'=>$type,'user_id2'=>$user['user_id']])->order('intime desc')->page($page,$pageSize)->select();
                if ($list){
                    foreach ($list as $k=>$v){
                        if ($v['state']==1){
                            M('Message')->where(['message_id'=>$v['message_id']])->save(['state'=>2,'uptime'=>time()]);
                        }
                        $list[$k]['intime'] = date('m-d H:i',$v['intime']);
                        $list[$k]['img'] = C('IMG_PREFIX').M('User')->where(['user_id'=>$v['user_id']])->getField('img');
                        $list[$k]['username'] = M('User')->where(['user_id'=>$v['user_id']])->getField('username');
                        $is_follow = M('Follow')->where(['user_id'=>$user['user_id'],'user_id2'=>$v['user_id']])->find();
                        $is_follow ? $list[$k]['is_follow'] = "2" : $list[$k]['is_follow'] = "1";
                        $title = M('Video')->where(['video_id'=>$v['video_id']])->getField('title');
                        $title ? $list[$k]['title'] = $title : $list[$k]['title'] = "";
                    }
                }else{$list=[];}
                break;
        }
        $list = str_replace(null, "", $list);
        success($list);
    }









}
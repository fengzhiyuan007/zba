<?php
namespace Admin\Controller;
use Org\Util\Date;

use Psr\Log\Test\DummyTest;

use Think\Controller;
use Think\Auth;
class CliController extends Controller{
	/**
	 * @没三分钟,判断按home键退出的直播,更改为下线状态
	 */
	public function check_live(){
        for ($i=1;$i<50;$i++){
            set_time_limit(0);
            $live = M('Live')->where(['live_status' => '1','live_time' =>['neq','0']])->page($i)->limit(50)->select();
            if (empty($live)) break;
            foreach ($live as $k=>$v){
                if (time() - $v['live_time'] > 3 * 60) {
                    M('Live')->where(['live_id'=>$v['live_id']])->save(['live_status'=>2,'is_normal_exit'=>2,'end_time'=>time(),'uptime'=>time()]);
                    //保存视频
                    import('Vendor.Qiniu.Pili');
                    $system = M('System')->where(['id' => 1])->find();
                    $ak = $system['ak'];
                    $sk = $system['sk'];
                    $hubName = $system['hubname'];
                    $mac = new \Qiniu\Pili\Mac($ak, $sk);
                    $client = new \Qiniu\Pili\Client($mac);
                    $hub = $client->hub($hubName);
                    //获取stream
                    $streamKey = $live['stream_key'];
                    $stream = $hub->stream($streamKey);
                    //保存直播数据
                    /**
                    $fname = $stream->save(0, 0);
                    if ($fname['fname']) {
                        $data = [
                            'live_id' => $live['live_id'],
                            'user_id' => $live['user_id'],
                            'play_img' => $live['play_img'],
                            'title' => $live['title'],
                            // 'url' => 'http://dlzb-media.duluozb.com/' . $fname['fname'],
                            'url' => 'http://pili-vod.speed.zhiboxiaoka.com/' . $fname['fname'],
                            'intime' => time(),
                            'room_id' => $live['room_id'],
                            'date'=>date('Y-m-d',time())
                        ];
                        M('Live_store')->add($data);
                    }
                    **/
                    $result = $stream->disable();
                }
            }
        }
	}

    /**
     *@列出七牛正在直播的流，不在里面则改变直播状态。
     */
    public function check_online(){
        // error_reporting(E_ALL);
        import('Vendor.Qiniu.Pili');
        $system = M('System')->where(['id' => 1])->find();
        $ak = $system['ak'];
        $sk = $system['sk'];
        $hubName = $system['hubname'];
        $mac = new \Qiniu\Pili\Mac($ak, $sk);
        $client = new \Qiniu\Pili\Client($mac);
        $hub = $client->hub($hubName);
        $resp = $hub->listLiveStreams("php-sdk-test", 100000, "");
        $resp = $resp[keys];

        for ($i = 0; $i < 50; $i++) {
            $live = M('Live')->where(['live_status' => '1'])->page($i)->limit(50)->select();
            if (empty($live)) break;
            foreach ($live as $k => $v) {
                if(!in_array($v['stream_key'],$resp)){
                    M('Live')->where(['live_id'=>$v['live_id']])->save(['live_status'=>2,'is_normal_exit'=>2,'end_time'=>time(),'uptime'=>time()]);
                    //保存视频
                    import('Vendor.Qiniu.Pili');
                    $system = M('System')->where(['id' => 1])->find();
                    $ak = $system['ak'];
                    $sk = $system['sk'];
                    $hubName =$system['hubname'];
                    $mac = new \Qiniu\Pili\Mac($ak, $sk);
                    $client = new \Qiniu\Pili\Client($mac);
                    $hub = $client->hub($hubName);
                    //获取stream
                    $streamKey = $v['stream_key'];
                    $stream = $hub->stream($streamKey);
                    //保存直播数据
                    /**
                    $fname = $stream->save(0, 0);
                    if ($fname['fname']) {
                        $data = [
                            'live_id' => $v['live_id'],
                            'user_id' => $v['user_id'],
                            'play_img' => $v['play_img'],
                            'title' => $v['title'],
                            // 'url' => 'http://dlzb-media.duluozb.com/' . $fname['fname'],
                            'url' => 'http://pili-vod.speed.zhiboxiaoka.com/' . $fname['fname'],
                            'intime' => time(),
                            'room_id' => $v['room_id'],
                            'date'=>date('Y-m-d',time())
                        ];
                        M('Live_store')->add($data);
                    }
                    **/
                }
            }
            set_time_limit(0);
        }
    }
//    /**
//     *@每过一分钟一个僵尸粉,如果直播间人数超过10人,则不加僵尸粉
//     */
//    public function set_fans(){
//        for ($i = 0; $i < 50; $i++) {
//            $live = M('Live')->where(['live_status' => '1'])->page($i)->limit(50)->select();
//            if (empty($live)) break;
//            foreach($live as $k=>$v){
//                $count = M('Live_number')->where(['live_id'=>$v['live_id']])->count();
//                if($count<11){
//                    $live_number = M('Live_number')->where(['live_id'=>$v['live_id']])->select();
//                    if ($live_number){
//                        $user_ids = array_map(function($v){ return $v['user_id2'];},$live_number);  //观众id集合
//                        $fans = M('User')->field('user_id')->where(['is_fans'=>2,'user_id'=>['not in',$user_ids]])->select();
//                        if ($fans){
//                            $fans_ids = array_map(function($v){ return $v['user_id'];},$fans);  //僵尸粉id集合
//                            $rand = array_rand($fans_ids,1);
//                            M('Live_number')->add(['live_id'=>$v['live_id'],'user_id'=>$v['user_id'],'user_id2'=>$fans_ids[$rand],'intime'=>time()]);
//                            M('Live')->where(['live_id'=>$v['live_id']])->setInc('nums');
//                            M('Live')->where(['live_id'=>$v['live_id']])->setInc('watch_nums');
//                        }
//                    }else{
//                        $fans = M('User')->field('user_id')->where(['is_fans'=>2])->select();
//                        if ($fans){
//                            $fans_ids = array_map(function($v){ return $v['user_id'];},$fans);  //僵尸粉id集合
//                            $rand = array_rand($fans_ids,1);
//                            M('Live_number')->add(['live_id'=>$v['live_id'],'user_id'=>$v['user_id'],'user_id2'=>$fans_ids[$rand],'intime'=>time()]);
//                            M('Live')->where(['live_id'=>$v['live_id']])->setInc('nums');
//                            M('Live')->where(['live_id'=>$v['live_id']])->setInc('watch_nums');
//                        }
//                    }
//                }
//            }
//
//        }
//    }

    /**
     *@每过一分钟X僵尸粉,如果直播间人数超过XX人,则不加僵尸粉
     */
//    public function set_fans(){
//        $system = M('System')->where(['id'=>1])->find();
//        for ($i = 0; $i < 50; $i++) {
//            set_time_limit(0);
//            $live = M('Live')->where(['live_status' => '1'])->page($i)->limit(50)->select();
//            $live_ids = M('Live')->where(['live_status' => '1'])->getField('live_id',true);
//            if (!$live) break;
//            foreach($live as $k=>$v){
//                $count = M('Live_number')->where(['live_id'=>$v['live_id']])->count();
//                if($count<$system['live_most_num']){
//                    $live_number = M('Live_number')->where(['live_id'=>$v['live_id']])->select();
//                    if ($live_number){
//                        $user_ids = array_map(function($v){ return $v['user_id2'];},$live_number);  //观众id集合
//                        $fans = M('User')->where(['is_fans'=>2,'user_id'=>['not in',$user_ids]])->order('rand()')->limit($system['one_minutes_num'])->select();
//                        if ($fans){
//                            foreach ($fans as $a=>$b){
//                                M('Live_number')->where(['user_id2'=>$b['user_id'],'live_id'=>['not in',$live_ids]])->delete();
//                                echo 1;
//                                M('Live_number')->add(['live_id'=>$v['live_id'],'user_id'=>$v['user_id'],'user_id2'=>$b['user_id'],'intime'=>time()]);
//                                M('Live')->where(['live_id'=>$v['live_id']])->setInc('nums');
//                                M('Live')->where(['live_id'=>$v['live_id']])->setInc('watch_nums');
//                            }
//                            //adduserChatRoom($fans[0]['hx_username'],$v['room_id']); //其中一个加入聊天室
//                            $get_gradeinfo = get_gradeinfo($fans[0]['grade']);
//                            $ext = [
//                                'user_id'=>$fans[0]['user_id'],
//                                'username'=>$fans[0]['username'],
//                                'userimg'=>C('IMG_PREFIX').$v['img'],
//                                'intoroom'=>"1",
//                                'usergrade'=>$fans[0]['grade'],
//                                'authName'=>$get_gradeinfo['name']
//                            ];
//                            hx_send($fans[0]['hx_username'],$v['room_id'],"进入了直播间",$ext);   //给聊天室发消息
//                        }
//                    }else{
//                        $fans = M('User')->where(['is_fans'=>2])->order('rand()')->limit($system['one_minutes_num'])->select();
//                        if ($fans){
//                            foreach ($fans as $a=>$b){
//                                M('Live_number')->where(['user_id2'=>$b['user_id'],'live_id'=>['not in',$live_ids]])->delete();
//                                M('Live_number')->add(['live_id'=>$v['live_id'],'user_id'=>$v['user_id'],'user_id2'=>$b['user_id'],'intime'=>time()]);
//                                M('Live')->where(['live_id'=>$v['live_id']])->setInc('nums');
//                                M('Live')->where(['live_id'=>$v['live_id']])->setInc('watch_nums');
//                            }
//                            //adduserChatRoom($fans[0]['hx_username'],$v['room_id']); //其中一个加入聊天室
//                            $get_gradeinfo = get_gradeinfo($fans[0]['grade']);
//                            $ext = [
//                                'user_id'=>$fans[0]['user_id'],
//                                'username'=>$fans[0]['username'],
//                                'userimg'=>C('IMG_PREFIX').$v['img'],
//                                'intoroom'=>"1",
//                                'usergrade'=>$fans[0]['grade'],
//                                'authName'=>$get_gradeinfo['name']
//                            ];
//                            hx_send($fans[0]['hx_username'],$v['room_id'],"进入了直播间",$ext);   //给聊天室发消息
//                        }
//                    }
//                }
//            }
//
//        }
//    }

    public function set_fans(){
        $system = M('System')->where(['id'=>1])->find();
        $live_ids = M('Live')->where(['live_status' => '1'])->getField('live_id',true);
        $count = M('Live')->where(['live_status' => '1'])->count();
        $pagesize = 50;
        $page = ceil($count/$pagesize);
        for ($i = 0; $i < $page; $i++) {
            set_time_limit(0);
            $live = M('Live')->where(['live_status' => '1'])->limit($i*$pagesize,$pagesize)->select();
            if (!$live) break;
            foreach($live as $k=>$v){
                $count = M('Live_number')->where(['live_id'=>$v['live_id']])->count();
                if($count<$system['live_most_num']){
                    $live_number = M('Live_number')->where(['live_id'=>$v['live_id']])->select();
                    if ($live_number){
                        $user_ids = array_map(function($v){ return $v['user_id2'];},$live_number);  //观众id集合
                        $fans = M('User')->where(['is_fans'=>2,'user_id'=>['not in',$user_ids]])->order('rand()')->limit($system['one_minutes_num'])->select();
                        if ($fans){
                            foreach ($fans as $a=>$b){
                                $not_fans[] = $b['user_id'];
                                $live_num[] = [
                                    'live_id'=>$v['live_id'],
                                    'user_id'=>$v['user_id'],
                                    'user_id2'=>$b['user_id'],
                                    'intime'=>time()
                                ];
                                $liveIds[] = $v['live_id'];
                            }
                            //adduserChatRoom($fans[0]['hx_username'],$v['room_id']); //其中一个加入聊天室
                            $get_gradeinfo = get_gradeinfo($fans[0]['grade']);
                            $ext = [
                                'user_id'=>$fans[0]['user_id'],
                                'username'=>$fans[0]['username'],
                                'userimg'=>C('IMG_PREFIX').$v['img'],
                                'intoroom'=>"1",
                                'usergrade'=>$fans[0]['grade'],
                                'authName'=>$get_gradeinfo['name']
                            ];
                            hx_send($fans[0]['hx_username'],$v['room_id'],"进入了直播间",$ext);   //给聊天室发消息
                        }
                    }else{
                        $fans = M('User')->where(['is_fans'=>2])->order('rand()')->limit($system['one_minutes_num'])->select();
                        if ($fans){
                            foreach ($fans as $a=>$b){
                                $not_fans[] = $b['user_id'];
                                $live_num[] = [
                                    'live_id'=>$v['live_id'],
                                    'user_id'=>$v['user_id'],
                                    'user_id2'=>$b['user_id'],
                                    'intime'=>time()
                                ];
                                $liveIds[] = $v['live_id'];
                            }
                            //adduserChatRoom($fans[0]['hx_username'],$v['room_id']); //其中一个加入聊天室
                            $get_gradeinfo = get_gradeinfo($fans[0]['grade']);
                            $ext = [
                                'user_id'=>$fans[0]['user_id'],
                                'username'=>$fans[0]['username'],
                                'userimg'=>C('IMG_PREFIX').$v['img'],
                                'intoroom'=>"1",
                                'usergrade'=>$fans[0]['grade'],
                                'authName'=>$get_gradeinfo['name']
                            ];
                            hx_send($fans[0]['hx_username'],$v['room_id'],"进入了直播间",$ext);   //给聊天室发消息
                        }
                    }
                }
            }

        }
        if(!empty($liveIds)){
            M('Live')->where(['live_id'=>['in',$liveIds]])->setInc('nums');
            M('Live')->where(['live_id'=>['in',$liveIds]])->setInc('watch_nums');
        }
        if(!empty($live_num)){
            M('live_number')->addAll($live_num);
        }
        if(!empty($not_fans)){
            M('Live_number')->where(['user_id2'=>['in',$not_fans],'live_id'=>['not in',$live_ids]])->delete();
        }
    }


    /**
     * @三分钟推送一次(开播提醒)
     */
    public function push_user(){
        for ($i = 0; $i < 500; $i++) {
            set_time_limit(0);
            $live = M('Live')->alias('a')
                ->field('a.live_id,a.user_id,a.play_address,a.play_img,a.qiniu_room_id,a.room_id,a.qiniu_room_name,a.qiniu_token,b.img,b.sex,b.username,b.ID,b.hx_username,b.grade,b.get_money,b.type')
                ->join('__USER__ b on a.user_id=b.user_id')
                ->where(['a.live_status' => '1','b.is_del'=>1])
                ->page($i)
                ->limit(50)
                ->select();
            if (empty($live)) break;
            foreach ($live as $s=>$n){
                $live[$s]['img'] = C('IMG_PREFIX').$n['img'];
                $live[$s]['play_img'] = C('IMG_PREFIX').$n['play_img'];
                $live[$s]['url'] = C('IMG_PREFIX')."/App/Index/share_live/live_id/" . base64_encode($n['live_id']);
            }
            foreach ($live as $k=>$v){
                $list = M('Follow')->alias('a')
                    ->field('a.*,b.is_remind,b.user_id,b.alias')
                    ->join('__USER__ b on a.user_id=b.user_id')
                    ->where(['a.user_id2'=>$v['user_id'],'b.is_remind'=>1,'a.is_remind'=>1])
                    ->select();
                $alias =[];
                $dataList = [];
                foreach ($list as $a=>$b){
                    $pu = M('Push_record')->where(['live_id'=>$v['live_id'],'user_id'=>$b['user_id']])->find();
                    if (!$pu){
                        $alias[] = $b['alias'];
                        $dataList[] = ['live_id'=>$v['live_id'],'user_id'=>$b['user_id'],'intime'=>time()];
//                        $content = "您关注的".$v['username']."开播啦，一起来，涨姿势，享健康~";
//                        push5($b['user_id'],$content,$b['alias'],json_encode($live[$k]));
//                        M('Push_record')->add(['live_id'=>$v['live_id'],'user_id'=>$b['user_id']]);
                    }
                }
               // print_r($alias);die;
                //$alias = array_slice($alias, 0, 20);
                $content = "您关注的".$v['username']."开播啦，一起来，涨姿势，享健康~";
                $count = count($alias);
                if ($count>1000){
                    $countpage=ceil($count/1000); #计算总页面数
                    for ($i=1;$i<=$countpage;$i++){
                        $alias2 = page_array(1000,$i,$alias,0);
                        push5($v['user_id'],$content,$alias2,json_encode($live[$k]));
                    }
                }else{
                    push5($v['user_id'],$content,$alias,json_encode($live[$k]));
                }
                M('Push_record')->addAll($dataList);
            }
        }
    }

    /**
     * @每三分钟执行,禁播、封号的用户时间判断
     */
    public function remove_banned(){
        for ($i = 0; $i < 500; $i++) {
            set_time_limit(0);
            $data['is_banned'] = 2;
            $data['is_titles'] = 2;;
            $data['_logic'] = 'OR';
            $list = M('User')->where($data)->page($i)->limit(50)->select();
            if (empty($list)) break;
            foreach ($list as $k=>$v){
                if ($v['banned_end_time']<time()){
                    M('User')->where(['user_id'=>$v['user_id']])->save(['is_banned'=>1,'uptime'=>time()]);
                    M('Banned_record')->add(['user_id'=>$v['user_id'],'start_time'=>$v['banned_start_time'],'end_time'=>time(),'intime'=>time(),'dis'=>$v['banned_dis'],'type'=>1]);
                }
                if ($v['titles_end_time']<time()){
                    M('User')->where(['user_id'=>$v['user_id']])->save(['is_titles'=>1,'uptime'=>time()]);
                    M('Banned_record')->add(['user_id'=>$v['user_id'],'start_time'=>$v['banned_start_time'],'end_time'=>time(),'intime'=>time(),'dis'=>$v['banned_dis'],'type'=>2]);
                }
            }
        }
    }

    /**
     * @每天半夜11:30备份数据库
     */
    public function backup_database(){
        $database=C('DB_NAME');//数据库名
        $name = "back_".date('Y-m-d',time());
        $options=array(
            'hostname' => C('DB_HOST'),//ip地址
            'charset'  => C('DB_CHARSET'),//编码
            'filename' => $name.'.sql',//文件名
            'username' => C('DB_USER'),
            'password' => C('DB_PWD')       //密码
        );
        mysql_connect($options['hostname'],$options['username'],$options['password'])or die("不能连接数据库!");
        mysql_select_db($database) or die("数据库名称错误!");
        mysql_query("SET NAMES '{$options['charset']}'");

        $tables = list_tables($database);
        $filename = sprintf($options['filename'],$database);
        $fp = fopen('./sql/'.$filename, 'w');
        foreach ($tables as $table) {
            dump_table($table, $fp);
        }
        fclose($fp);
        $file_name=$options['filename'];
        Header("Content-type:application/octet-stream");
        Header("Content-Disposition:attachment;filename=".$file_name);
        readfile($file_name);
        exit;
    }


    /**
     * @每天半夜11:50更新公众号用户
     */
    public function save_withdraw_wechat(){
        $appid = "wx46e268c7797cae0b";
        $secret = "f9f95e49f91333257696b50257a2535d";
        //$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $get_token_url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$secret.'';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res,true);
        $list = $this->get_list($json_obj['access_token']);
        foreach ($list as $k=>$v){
            set_time_limit(0);
            $w = M('Withdraw_wechat')->where(['unionid'=>$v['unionid']])->find();
            if (!$w){
                M('Withdraw_wechat')->add(['openid'=>$v['openid'],'nickname'=>$v['nickname'],'sex'=>$v['sex'],'province'=>$v['province'],'city'=>$v['city'],'hearimgurl'=>$v['headimgurl'],'unionid'=>$v['unionid'],'intime'=>time()]);
            }
        }
        //print_r($list);
    }

    public function get_list($access_token){
        $next_openid = '';
        $get_token_url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$access_token.'&next_openid='.$next_openid.'';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $res = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($res,true);
        $arr = $json_obj['data']['openid'];
        $info = [];
        foreach ($arr as $k=>$v){
            $get_token_url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$v.'&lang=zh_CN';
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$get_token_url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            $res = curl_exec($ch);
            curl_close($ch);
            $jsons = json_decode($res,true);
            $info[] = $jsons;
        }
        return $info;
    }









	}

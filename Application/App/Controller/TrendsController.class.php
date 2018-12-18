<?php
namespace App\Controller;
use Org\Util\Date;
use Think\Upload;
use Think\Controller;
Vendor('php-qiniu-sdk.autoload');
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
class TrendsController extends CommonController {
	private $accessKey = 'V2fTXsUpBvftpGPTHFlBuR0SaTrPX9t7-yu10Ktc';
    private $secretKey = 'mTWoKmA67XgN3dLBV4leH4jUm6B8RkquH-s5aG1x';
    private $bucket = 'zhibo-a';
	// /**
 //     * @相册上传图片
 //     */
 //    public function upload_imgs(){
 //        $user = checklogin();
 //        $config = [
 //            'maxSize'	=> 500*3145728,
 //            'rootPath'	=> './Public/upload/user_imgs/',
 //            'savePath'	=> '',
 //            'saveName'	=> ['uniqid',''],
 //            'exts'		=> ['png','jpg','jpeg','git','gif'],
 //            'autoSub'	=> true,
 //            'subName'	=> '',
 //        ];
 //        $uploader = new Upload($config);
 //        $info = $uploader->upload();
 //        if ($info){
 //            foreach($info as $file){
 //                $imgs = '/Public/upload/user_imgs/'.$file["savename"];
 //                $dataList[] = ['user_id'=>$user['user_id'],'imgs'=>$imgs,'intime'=>time(),'date'=>date('Y-m-d',time())];    //批量写入
 //            }
 //        }else {
 //            error($uploader->getError());
 //        }
 //        if (M('User_imgs')->addAll($dataList)){
 //            success('成功!');
 //        }else{
 //            error('失败!');
 //        }

 //    }

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
                $a = '/Public/admin/Uploads/touxiang/'.$file["savename"];//C('IMG_PREFIX')
            }
            success($a);
        }else {
            error($uploader->getError());
        }

    }

    /*
    上传视频
     */
    public function upload_video(){
        $file = $_FILES;
        // print_r($file);exit;
        $v = explode('/', $_FILES['file']['type']);
        if($v[0] == 'video'){
            // 构建鉴权对象
            $auth = new Auth($this->accessKey, $this->secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($this->bucket);
            // 要上传文件的本地路径
            $filePath = $_FILES['file']['tmp_name'];
            // print_r($_FILES['file']['tmp_name']);exit;
            // 上传到七牛后保存的文件名
            $key = $_FILES['file']['name'];
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();

            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            // echo "\n====> putFile result: \n";
            
            // if ($err !== null || $err !== '' || $err !== False) {
            //     echo json_encode($err);
            // } else {
            //     $ret['link'] = 'play.100ytv.com/'.$ret['key'];
            //     success($ret);
              
            // }
            if($ret){
                $ret['link'] = 'http://speed.zhiboxiaoka.com/'.$ret['key'];
                success($ret);
            }else{
                error($err);
            }
        }
    }

    /*
    添加标签
     */
    public function add_tag(){
    	$user = checklogin();
    	$data['name']    = I('name');
        //判断标签表里是否存在
        $re = M('tag')->where(array('name'=>$data['name']))->find();
        if($re){
            $result = M('tag')->where(array('name'=>$data['name']))->setInc('count');
        }else{
            $result = M('tag')->add($data);
        }

    	if($result){
    		success('添加标签成功');
    	}else{
    		error('添加标签失败');
    	}
    }
    /*
    查询标签
     */
    public function search_tag(){
    	$user = checklogin();
    	$list = M('tag')
                ->field('id,name,(count+counts) count')
                ->order('count desc')
                ->select();
    	if($list){
    		success($list);
    	}
    	
    }
	/**
	 * 发布动态
	 */
	public function add_trends(){
		$user = checklogin();
        $type = I('type');
        switch ($type) {
        	case '1':
        		//发布图片
        		$data['user_id'] = I('uid');
        		$data['url'] = I('url');
        		$data['content'] = I('content');
        		$data['tag']     = I('tag');
                $data['address'] = I('address');
                $data['log'] = I('log');
        		$data['lag'] = I('lag');
		        $data['creatime']= time();
        		$data['type']= 1;
        		if(M('trends')->add($data)){
        			success('发布动态成功');
        		}else{
        			error('发布动态失败');
        		}

        		break;
        	case '2':
     //            $file = $_FILES;
     //            // print_r($file);exit;
     //    		$v = explode('/', $_FILES['file']['type']);
     //    		if($v[0] == 'video'){
     //    			// 构建鉴权对象
					// $auth = new Auth($this->accessKey, $this->secretKey);
					// // 生成上传 Token
					// $token = $auth->uploadToken($this->bucket);
					// // 要上传文件的本地路径
					// $filePath = $_FILES['file']['tmp_name'];
					// // print_r($_FILES['file']['tmp_name']);exit;
					// // 上传到七牛后保存的文件名
					// $key = $_FILES['file']['name'];
					// // 初始化 UploadManager 对象并进行文件的上传。
					// $uploadMgr = new UploadManager();

					// // 调用 UploadManager 的 putFile 方法进行文件的上传。
					// list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
					// // echo "\n====> putFile result: \n";
					// if ($err !== null) {
					//     error($err);
					// } else {
					// 	//入库
		   //      		$ret['link'] = 'play.100ytv.com/'.$ret['key'];
						
					// 	$data['user_id'] = I('uid');
		   //      		$data['url'] = $ret['link'];
		   //      		$data['content'] = I('content');
		   //      		$data['tag']     = I('tag');
		   //      		$data['address'] = I('address');
     //                    $data['log'] = I('log');
     //                    $data['lag'] = I('lag');
		   //      		$data['creatime']= time();
     //    				$data['type']= 2;
     //                    $data['img_url']= I('img_url');


     //                    $result = M('trends')->add($data);
		   //      		if($result){
     //                        $ret['id'] = $result;
					//     	success($ret);
		   //      		}else{
		   //      			error('发布动态失败');
		   //      		}

						
					// }
     //    		}
				//入库
                
                $data['user_id'] = I('uid');
                $data['url'] = I('link');
                $data['content'] = I('content');
                $data['tag']     = I('tag');
                $data['address'] = I('address');
                $data['log'] = I('log');
                $data['lag'] = I('lag');
                $data['creatime']= time();
                $data['type']= 2;
                $data['img_url']= I('img_url');


                $result = M('trends')->add($data);
                if($result){
                    success('发布动态成功');
                }else{
                    error('发布动态失败');
                }
        		
        		break;
        	case '3':
        		//发布录播短片
                //查询录播视频的封面及视频链接
                $live_store_id = I('live_store_id');
                $list = M('live_store')->where(array('live_store_id'=>$live_store_id))->field('play_img,url,play_number')->find();
                //入库
                $data['user_id'] = I('uid');
                $data['url'] = $list['url'];
                $data['content'] = I('content');
                $data['tag']     = I('tag');
                $data['address'] = I('address');
                $data['log'] = I('log');
                $data['lag'] = I('lag');
                $data['creatime']= time();
                $data['type']= 3;
                $data['img_url']= $list['play_img'];
                $data['play_number']= $list['play_number'];
                $data['live_store_id']= I('live_store_id');

                $result = M('trends')->add($data);
                if($result){
                    success('发布动态成功');
                }else{
                    error('发布动态失败');
                }

        		break;
        	default:
        		# code...
        		break;
        }
	}

    //动态列表
    public function trends_list(){
        //查看关注人的动态
        // $user = checklogin();
        $user_id = I('uid');  //关注人ID
        $type = I('type');
        $page = I('page'); 
        $pageSize = I('pagesize');
        $page ? $page : $page = 1;  
        $pageSize ? $pageSize : $pageSize = 10;
        switch ($type) {
            case '1':         //关注人的动态
                //查出被关注人的ID
                // $list = M('follow')->where(array('user_id'=>$user_id))->field('user_id2')->select();
                //查出自己的动态
                $own = M('trends')
                       ->alias('t')
                       ->join('__USER__ u ON t.user_id = u.user_id')
                       ->where(array('t.user_id'=>$user_id))
                       ->field('t.id,t.user_id,t.url,t.content,t.tag,t.address,FROM_UNIXTIME(t.creatime) AS creatime,t.type,t.img_url,t.log,t.lag,t.play_number,t.live_store_id,u.username,u.img header_img')
                       ->select();
                if(!$own){
                   $own = [];
                }

                $list = M('follow')
                        ->alias('f')
                        ->join('__TRENDS__ t ON f.user_id2 = t.user_id')
                        ->join('__USER__ u ON f.user_id2 = u.user_id')
                        ->where(array('f.user_id'=>$user_id))
                        ->field('t.id,t.user_id,t.url,t.content,t.tag,t.address,FROM_UNIXTIME(t.creatime) AS creatime,t.type,t.img_url,t.log,t.lag,t.play_number,t.live_store_id,u.username,u.img header_img')
                        // ->page($page,$pageSize)
                        ->select();
                if(!$list){
                    $list = [];
                }
                $list = array_merge($own,$list);
                $list = $this->two_sort($list,'creatime','SORT_DESC');
                
                $list = array_slice($list,($page-1)*$pageSize,$pageSize);
                foreach ($list as $k => $v) {
                    if($list[$k]['type'] == 1){
                        $list[$k]['url'] = C('IMG_PREFIX').$list[$k]['url'];
                    }
                    $list[$k]['img_url'] ? $list[$k]['img_url']=C('IMG_PREFIX').$list[$k]['img_url']:"";
                    $list[$k]['header_img'] ? $list[$k]['header_img']=C('IMG_PREFIX').$list[$k]['header_img']:"";
                    //赞的人数，赞的状态，评论人数
                    $list[$k]['zan'] = M('trends_zan')->where(array('trends_id'=>$list[$k]['id'],'status'=>'1'))->count();
                    $list[$k]['zan_status'] = M('trends_zan')->where(array('trends_id'=>$list[$k]['id']))->getField('status');
                    if($list[$k]['zan_status'] == null){
                            $list[$k]['zan_status'] = '2';
                    }
                    $list[$k]['comments'] = M('trends_comments')->where(array('trends_id'=>$list[$k]['id']))->count();
                }
                if(empty($list)){
                    $list = [];
                }

                success($list);
                break;
            case '2':         //附近的人的动态
                $log = I('log');
                $lag = I('lag');
                $list = M('trends')
                        ->alias('t')
                        ->join('__USER__ u ON t.user_id = u.user_id')
                        ->field('t.id,t.user_id,t.url,t.content,t.tag,t.address,FROM_UNIXTIME(t.creatime) AS creatime,t.type,t.img_url,t.log,t.lag,t.play_number,t.live_store_id,u.username,u.img header_img')
                        ->select();

                if ($log && $lag){
                    foreach ($list as $k=>$v){
                        $list[$k]['distance'] = (string)round(getDistance($lag,$log,$v['lag'],$v['log'])/1000,2);   //算出距离（公里），保留两位小数点(四舍五入)
                    }
                    foreach ($list as $k=>$v){
                        if($list[$k]['type'] == 1){
                            $list[$k]['url'] = C('IMG_PREFIX').$list[$k]['url'];
                        }
                        $list[$k]['img_url'] ? $list[$k]['img_url']=C('IMG_PREFIX').$list[$k]['img_url']:"";
                        $list[$k]['header_img'] ? $list[$k]['header_img']=C('IMG_PREFIX').$list[$k]['header_img']:"";

                        //赞的人数，赞的状态，评论人数
                        $list[$k]['zan'] = M('trends_zan')->where(array('trends_id'=>$list[$k]['id'],'status'=>'1'))->count();
                        $list[$k]['zan_status'] = M('trends_zan')->where(array('trends_id'=>$list[$k]['id']))->getField('status');
                        if($list[$k]['zan_status'] == null){
                            $list[$k]['zan_status'] = '2';
                        }
                        $list[$k]['comments'] = M('trends_comments')->where(array('trends_id'=>$list[$k]['id']))->count();
                        
                        if ($list[$k]['distance']>200){
                            unset($list[$k]);
                        }


                    }
                }
                $list = array_values($list);

                $list = $this->two_sort($list,'distance','SORT_ASC');

                if(empty($list)){
                    $list = [];
                }
                success($list);

                break;
            default:
                
                break;
        }
        


    }

    //动态详情
    public function trends(){
        $id = I('id');

        $trends = M('trends')
            ->alias('t')
            ->join('__USER__ u ON t.user_id = u.user_id')
            ->where(array('t.id'=>$id))
            ->field('t.id,t.user_id,t.url,t.content,t.tag,t.address,FROM_UNIXTIME(t.creatime) AS creatime,t.type,t.img_url,t.log,t.lag,t.play_number,t.live_store_id,u.username,u.img header_img')
            ->find();

        // if($trends['type'] == 2){
        //     $trends['img_url'] = C('IMG_PREFIX').$trends['img_url'];
        // }
        if($trends['type'] == 1){
            $trends['url'] = C('IMG_PREFIX').$trends['url'];
        }
        $trends['img_url'] ? $trends['img_url']=C('IMG_PREFIX').$trends['img_url']:$trends['img_url'] = "";
        $trends['header_img'] ? $trends['header_img']=C('IMG_PREFIX').$trends['header_img']:$trends['header_img'] = "";
        //赞的人数，赞的状态，评论人数
        $trends['zan'] = M('trends_zan')->where(array('trends_id'=>$trends['id'],'status'=>'1'))->count();
        $trends['zan_status'] = M('trends_zan')->where(array('user_id'=>I('uid'),'trends_id'=>$trends['id']))->getField('status');
        if($trends['zan_status'] == null){
            $trends['zan_status'] = '2';
        }
        $trends['comments'] = M('trends_comments')->where(array('trends_id'=>$trends['id']))->count();
        //评论列表
        $comments = M('trends_comments')->where(array('trends_id'=>$id))->select();
        foreach ($comments as $k => &$v) {
            $user = M('user')->where(array('user_id'=>$v['user_id']))->field('username,img,grade')->find();
            $v['creatime'] = date('Y-m-d H:i:s',$v['creatime']);
            $v['username'] = $user['username'];
            $v['header_img'] = C('IMG_PREFIX').$user['img'];

            $get_gradeinfo = get_gradeinfo($user['grade']);
            $v['grade_img'] = $get_gradeinfo['img'];
            $v['name'] = $get_gradeinfo['name'];

            if($v['type'] == 2){
                //获取被回复人的用户名
                $uid = M('trends_comments')->where(array('id'=>$v['fid']))->getField('user_id');
                $v['username2'] = M('user')->where(array('user_id'=>$uid))->getField('username');
            }
        }
        if(empty($comments)){
            $comments = [];
        }
        success(['trends'=>$trends,'comments'=>$comments]);
    }

    //动态点赞
    public function like(){
        $user = checklogin();
        $data['user_id'] = I('uid');
        $data['trends_id'] = I('trends_id');
        $data['creatime'] = time();
        //查询动态点赞表
        $result = M('trends_zan')->where(array('user_id'=>$data['user_id'],'trends_id'=>$data['trends_id']))->find();
        if($result){
            if($result['status'] == 1){
                //已经点赞过，点击取消赞
                $list = M('trends_zan')->where(array('id'=>$result['id']))->setInc('status');
                if($list){
                    success('取消赞成功');
                }else{
                    error('取消赞失败');
                }
                
            }else{
                //已经取消赞，点击点赞
                $list = M('trends_zan')->where(array('id'=>$result['id']))->setDec('status');
                if($list){
                    success('点赞成功');
                }else{
                    error('点赞失败');
                }
            }
        }else{
            //第一次点赞，入库
            $list = M('trends_zan')->add($data);
            if($list){
                success('点赞成功');
            }else{
                error('点赞失败');
            }
        }

    }


    /*
    评论动态
     */
    public function comments(){
        $user = checklogin();
        $type = I('type');

        $data = [
            'trends_id'=>I('trends_id'),
            'user_id'=>I('uid'),
            'content'=>I('content'),
            'creatime'=>time(),
            'type'=>$type,
        ];
        switch ($type){
            case 1:
                $data['fid'] = "0";
                break;
            case 2:
                $data['fid'] = I('id');
                break;
        }
        $list = M('trends_comments')->add($data);
        if ($list){
            success('成功!');
        }else{
            error('失败!');
        }


    }

    //删除动态
    public function del_comments(){
        $user = checklogin();
        $id = I('id');
        $result = M('trends_comments')->where(array('id'=>$id))->delete();
        if($result){
            success('删除评论成功');
        }else{
            error('删除评论失败');
        }

    }

    

    public function comments_list(){
        $id = I('id');
        //评论列表
        $comments = M('trends_comments')->where(array('trends_id'=>$id))->select();
        foreach ($comments as $k => &$v) {
            $user = M('user')->where(array('user_id'=>$v['user_id']))->field('username,img,grade')->find();
            $v['creatime'] = date('Y-m-d H:i:s',$v['creatime']);
            $v['username'] = $user['username'];
            $v['header_img'] = C('IMG_PREFIX').$user['img'];

            $get_gradeinfo = get_gradeinfo($user['grade']);
            $v['grade_img'] = $get_gradeinfo['img'];
            $v['name'] = $get_gradeinfo['name'];

            if($v['type'] == 2){
                //获取被回复人的用户名
                $uid = M('trends_comments')->where(array('id'=>$v['fid']))->getField('user_id');
                $v['username2'] = M('user')->where(array('user_id'=>$uid))->getField('username');
            }
        }
        success(['comments'=>$comments]);
    }

    /*
    删除动态
     */
    public function del_trends(){
        $user = checklogin();
        $id = I('id');
        $result = M('trends')->where(array('id'=>$id))->delete();
        if($result){
            //删除评论
            $re = M('trends_comments')->where(array('trends_id'=>$id))->delete();
            success('删除动态成功');
        }else{
            error('删除动态失败');
        }
    }

    /*
    举报动态
     */
    public function report(){
        $user = checklogin();
        $id = I('id');
        $result = M('trends')->where(array('id'=>$id))->setInc('report');
        if($result){
            success('举报成功');
        }else{
            error('举报失败');
        }
    }

    /*
    推荐关注主播
     */
    public function follow(){
        $user = checklogin();
        $list = M('user')
                ->where(array('is_del'=>1,'is_banned'=>1,'user_id'=>array('neq',I('uid'))))
                ->field('user_id,img,sex,username,autograph,money,experience,grade')
                ->order('rand()')
                ->limit(9)
                ->select();
        foreach ($list as $k => &$v) {
            $v['img'] = C('IMG_PREFIX').$v['img'];
            //查看是否关注
            $v['follow'] = M('follow')->where(array('user_id'=>$v['user_id']))->count();
        }
        success($list);

    }

    //批量关注
    public function get_follow(){
        $user = checklogin();
        $ids = I('ids');
        $u = explode(',', $ids);

        $data['user_id'] = I('uid');
        foreach ($u as $k => &$v) {
            //插入关注表
            $data['user_id2'] = $v;
            $data['intime'] = time();

            $res = M('follow')->where(array('user_id'=>I('uid'),'user_id2'=>$v))->find();
            if($res){
                $result = 1;
            }else{
                $result = M('follow')->add($data);
            }

        }
        if($result){
            success('关注成功');
        }else{
            error('关注失败');
        }

    }

    //已经关注的主播列表
    public function follow_anchor(){
        $user = checklogin();
        $list = M('follow')
              ->alias('f')
              ->join('__USER__ u ON f.user_id2 = u.user_id')
              ->where(array('f.user_id'=>I('uid')))
              ->field('f.follow_id,u.img,u.sex,u.username,u.autograph')
              ->select();
        success($list);

    }


	/**
     *七牛token
     */
    public function get_qiniu_token(){
        $accessKey = $this->accessKey;
        $secretKey = $this->secretKey;
        $bucket = $this->bucket;
        // 初始化Auth状态

        $auth = new Auth($accessKey, $secretKey);
        $expires = 3600;
        $policy = null;
        $upToken = $auth->uploadToken($bucket, null, $expires, $policy, true);
        echo json_encode(['uptoken'=>$upToken]);
    }
}
<?php
namespace  Admin\Controller;
/**
 * 友情链接
 * @author 
 *
 */
use Think\Db;
import('Vendor.Redis.RedisServer');
class BaseController extends CommonController {
    function _initialize() {
        $nums = ['5','10','15','20','25','30','50','100'];
        $this->assign('nums',$nums);
    }
	// 主页面
	// 获得数据
	function index() {
		$Base = D ( "Base" );
		$data = $Base->order ( "paixu" )->select ();
		$this->assign ( "base", $data );
		$this->assign ( 'pagetitle', '站点设置' );
		$this->display ();
	}
	
	// 跳转到修改页面
	function toupdate() {
		$id = I ( "id", 0 );
		$B = D ( "System" );
		$base = $B->find ();	
		$this->assign ( "base", $base );
		$this->assign ( 'pagetitle', '站点设置-修改' );
		$this->display ();
	}
	
	// 执行修改
	function doupdate() {
		//dump($_POST);exit;
		$B = D ( 'System' );
		$B->create ();
		$B->updatetime = getCurrentTime ();
		$r = $B->save ();
		if ($r) {
			$this->success ( '修改成功！',U('toupdate')  );
		} else {
			$this->error ( "修改失败:" . $B->getDbError () );
		}
	}
	//banner
	function banner(){
		$count = M('Banner')->count();//一共有多少条记录
		$p = getpage($count,10);
		$ban = M('Banner')->limit($p->firstRow.','.$p->listRows)->select();
        foreach ($ban as $k=>$v){
            $ban[$k]['user'] = M('User')->field('user_id,username,ID')->find($v['user_id']);
        }
		$this->assign('ban',$ban);
		$this->assign("show",$p->show());
		$this->assign ( 'pagetitle', 'Banner' );
	    $this->display();
	}
	//添加
	function toadd(){
	   $this->assign ( 'pagetitle', 'banner添加' );
	   $this->display('add'); 
	}
	//添加实现
	function doadd(){
	    $data['b_img']    = I('logos');
        //$data['b_type']   = I('type');
	    $data['b_intime'] = time();
        //$data['user_id']  = M('User')->where(['ID'=>I('uid')])->getField('user_id');
        //$data['live_id']  = I('live_id');
        //$data['url']      = I('url');
        $data['remark']   = I('remark');
        $data['title']   = I('title');
        $data['content']   = I('content');
	    if (M('Banner')->add($data)){
	       $this->success('添加成功',U('banner'));
	    }else {
	       $this->error('添加失败',U('banner'));
	    }
	}
	/**
	 * @删除（单个）
	 */
	public function del(){
	    if (isset($_GET['b_id'])){
			if (M('Banner')->delete(intval($_GET['b_id']))){
				$this->success('删除成功！',U('banner'));
			}else {
				$this->success('删除失败！',U('banner'));
			}
			
		 }
	}
	/**
	 * @删除(多个)
	 */
    public function delete(){
		if (!empty($_POST['chois'])){
			$id = implode(',', $_POST['chois']);
			if (M('Banner')->delete($id)){
				$this->success('删除成功！',U('banner'));
			}else {
				$this->success('删除失败！',U('banner'));
			}
		}
	 } 
	/**
	 * @修改
	 */
    public function upbanner(){
		if (!empty($_GET['b_id'])){
		   $hots = M('Banner')->find(intval($_GET['b_id']));
           $hots['id'] = M('User')->where(['user_id'=>$hots['user_id']])->getField('ID');
		   $this->assign('hots',$hots);
		   $this->assign ( 'pagetitle', '修改' );
		   $this->display('upbanner');
		}
	 }
	/**
	 * @实现修改
	 */
    public function doupbanner(){
		header("Content-Type:text/html; charset=utf-8");
		if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
	    unset($_POST['__hash__']);
	    if(IS_POST){
	    	$data['b_id']     =  intval($_POST['id']);
            $data['b_img']    = I('logos');
//            $data['b_type']   = I('type');
            $data['b_intime'] = time();
//            $data['user_id']  = M('User')->where(['ID'=>I('uid')])->getField('user_id');
//            $data['live_id']  = I('live_id');
//            $data['url']      = I('url');
            $data['remark']   = I('remark');
            $data['title']   = I('title');
            $data['content']   = I('content');
	        if (M('Banner')->save($data)){
	    		$this->success('修改成功！',U('banner'));
	    	}else {
	    		$this->error('修改失败！',U('banner'));
	    	}
	    }
	 } 
	/**
	 * @数据库备份
	 */
	 public function backup(){
	     $this->assign ( 'pagetitle', '数据库备份' );
		 $this->display();
	 }
     function backup_do(){
		$database=C('DB_NAME');//数据库名
		$options=array(
				'hostname' => C('DB_HOST'),//ip地址
				'charset'  => C('DB_CHARSET'),//编码
				'filename' => $_POST['name'].'.sql',//文件名
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
     * @礼物列表
     */
	public function gift_list(){
	    $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Gift')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Gift')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '礼物列表' );
	    $this->display();
    }
    /**
     * @添加(修改映射)
     */
    public function toadd_gift(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $p = M('Gift')->find($id);
            $this->assign('p',$p);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_gift(){
        $id = I('id');
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [
            'name'=>I('name'),
            'img'=>I('logos'),
            'price'=>I('price'),
            'experience'=>I('experience'),
            'is_running'=>I('is_running'),
            'is_special'=>I('is_special')
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Gift')->add($data) ? $this->success('添加成功!',U('gift_list')) : $this->error('添加失败!',U('gift_list')) ;
        }else{
            $data['uptime'] = time();
            M('Gift')->where(['gift_id'=>$id])->save($data) ? $this->success('编辑成功!',U('gift_list')) : $this->error('编辑失败!',U('gift_list')) ;
        }
    }
    /**
     * @删除
     */
    public function del_gift(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Gift')->where(['gift_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }


    /**
     * @资料管理(星座、地区、学历、职业、兴趣、性格、年龄)
     */
    public function unify(){
        $type = ['1'=>'星座','2'=>'地区','3'=>'学历','4'=>'职业','5'=>'兴趣','6'=>'性格','7'=>'年龄'];
        $this->assign('type',$type);

        $data = [];
        if (!empty($_GET['type'])){
            $data['type'] = $_GET['type'];
            $this->assign('lei',$_GET['type']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Unify')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Unify')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '资料管理' );
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_unify(){
        $type = ['1'=>'星座','2'=>'地区','3'=>'学历','4'=>'职业','5'=>'兴趣','6'=>'性格','7'=>'年龄'];
        $this->assign('type',$type);

        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Unify')->find($id);
            $this->assign('u',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_unify(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'name'=>I('name'),
            'img'=>I('logo'),
            'type'=>I('leixing'),
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Unify')->add($data) ? $this->success('添加成功!',U('unify')) : $this->error('添加失败',U('unify'));
        }else{
            $data['uptime'] = time();
            $un = M('Unify')->find($id);
            if (M('Unify')->where(['unify_id'=>$id])->save($data)){
                if ($un['img']!=I('logo')){
                    unlink($un['img']);
                }
                $this->success('编辑成功!',U('unify'));
            }else{
                $this->error('编辑失败',U('unify'));
            }
        }
    }
    /**
     * @删除
     */
    public function del_unify(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Unify')->where(['unify_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

    /**
     * @敏感词
     */
    public function sensitive_word(){
        $this->assign ( 'pagetitle', '敏感词' );
        $id = I('id');
        if (empty($id)){
            $s = M('System')->field('id,sensitive_word')->where(['id'=>1])->find();
            $this->assign('s',$s);
            $this->display();
        }else{
            M('System')->where(['id'=>$id])->save(['sensitive_word'=>I('sensitive_word'),'uptime'=>time()]) ? $this->success('成功!',U('sensitive_word')) : $this->error('失败!',U('sensitive_word')) ;
        }
    }
    /**
     * @关于我们
     */
    public function about_us(){
        $this->assign ( 'pagetitle', '关于我们' );
        $id = I('id');
        if (empty($id)){
            $s = M('About_us')->where(['about_us_id'=>1])->find();
            $this->assign('a',$s);
            $this->display();
        }else{
            M('About_us')->where(['about_us_id'=>$id])->save(['imgs'=>I('logos'),'mobile'=>I('mobile'),'email'=>I('email'),'qq'=>I('qq'),'wechat'=>I('wechat'),'clause'=>I('content'),'xieyi'=>I('xieyi'),'intime'=>time()]) ? $this->success('成功!',U('about_us')) : $this->error('失败!',U('about_us')) ;
        }
    }
    /**
     * @举报类型
     */
    public function report_type(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Report_type')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Report_type')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '举报类型' );
        $this->display();
    }

    /**
     * @标签
     */
    public function lebel(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Lebel')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Lebel')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '标签列表' );
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_lebel(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Lebel')->find($id);
            $this->assign('u',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_lebel(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'name'=>I('name'),
            'type'=>I('type')
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Lebel')->add($data) ? $this->success('添加成功!',U('lebel')) : $this->error('添加失败',U('lebel'));
        }else{
            $data['uptime'] = time();
            if (M('Lebel')->where(['lebel_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('lebel'));
            }else{
                $this->error('编辑失败',U('lebel'));
            }
        }
    }
    /**
     * @删除
     */
    public function del_lebel(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Lebel')->where(['lebel_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

    /*********************************价格列表********************************************/
    public function price_list(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Price')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Price')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '价格列表' );
        $this->assign("show",$p->show());
        $this->display();
    }
    /**
     * @添加(修改)映射
     */
    public function toadd_price(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Price')->find($id);
            $this->assign('u',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_price(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'price'=>I('price'),
            'meters'=>I('meters'),
            'give'=>I('give'),
            'apple_id'=>I('apple_id')
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Price')->add($data) ? $this->success('添加成功!',U('price_list')) : $this->error('添加失败',U('price_list'));
        }else{
            $data['uptime'] = time();
            if (M('Price')->where(['price_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('price_list'));
            }else{
                $this->error('编辑失败',U('price_list'));
            }
        }
    }
    /**
     * @删除
     */
    public function del_price(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Price')->where(['price_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

    /**
     * @系统消息
     */
    public function message(){
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $data = ['is_del'=>1];
        $this->assign("nus",$nus);
        $count = M('Notice')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Notice')->limit($p->firstRow.','.$p->listRows)->where($data)->order('ctime desc')->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '系统消息' );
        $this->display();
    }
    /**
     * @添加(修改)映射
     */
    public function toadd_message(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Notice')->find($id);
            $this->assign('u',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_message(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'content'=>I('content'),
            'state'=>I('state'),
            'ctime'=>time()
        ];
        if (empty($id)){
            M('Notice')->add($data) ? $this->success('添加成功!',U('message')) : $this->error('添加失败',U('message'));
        }else{
            if (M('Notice')->where(['id'=>$id])->save($data)){
                $this->success('编辑成功!',U('message'));
            }else{
                $this->error('编辑失败',U('message'));
            }
        }
    }
    /**
     * @发送
     */
    public function send(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $no = M('Notice')->where(['id'=>$id])->find();
        $data = [
            'is_fans'=>1,
            'is_del'=>1
        ];
        $user = M('User')->where($data)->select();
        if (M('Notice')->where(['id'=>$id])->save(['status'=>2,'stime'=>time()])){
            foreach ($user as $k=>$v){
                M('Message')->add(['type'=>1,'user_id2'=>$v['user_id'],'content'=>$no['content'],'intime'=>time(),'date'=>date('Y-m-d',time())]);

                //极光推送
                push5($v['user_id'],$no['content'],$v['alias'],'',1);

                set_time_limit(0);
            }
            $this->success('发送成功!',U('message'));
        }else{
            $this->error('发送失败',U('message'));
        }
    }
    /**
     * @删除
     */
    public function del_message(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Notice')->where(['id'=>['in',$id]])->save(['is_del'=>2]);
        echo $rs ? 1 : 2;
    }


    /**
     * @开户行列表
     */
    public function band_card_where_list(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Band_card_where')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Band_card_where')->limit($p->firstRow.','.$p->listRows)->where($data)->order('remark desc')->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '开户行列表' );
        $this->assign("show",$p->show());
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_band_card_where(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Band_card_where')->find($id);
            $this->assign('p',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_band_card_where(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'name'=>I('name'),
            'remark'=>I('remark')
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Band_card_where')->add($data) ? $this->success('添加成功!',U('band_card_where_list')) : $this->error('添加失败',U('band_card_where_list'));
        }else{
            $data['uptime'] = time();
            if (M('Band_card_where')->where(['band_card_where_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('band_card_where_list'));
            }else{
                $this->error('编辑失败',U('band_card_where_list'));
            }
        }
    }
    /**
     * @删除
     */
    public function del_band_card_where(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Band_card_where')->where(['band_card_where_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }


    /**
     * @常见问题
     */
    public function common_problems(){
        $this->assign ( 'pagetitle', '常见问题' );
        $id = I('id');
        if (empty($id)){
            $s = M('About_us')->where(['about_us_id'=>2])->find();
            $this->assign('a',$s);
            $this->display();
        }else{
            M('About_us')->where(['about_us_id'=>$id])->save(['clause'=>I('content'),'intime'=>time()]) ? $this->success('成功!',U('common_problems')) : $this->error('失败!',U('common_problems')) ;
        }
    }



    /**
     * @反馈列表
     */
    public function feedback(){
        $data = [];
        if (!empty($_GET['username'])){
            $data['b.username|b.ID|a.content'] = ['like','%'.$_GET['username'].'%'];
            $this->assign('username',$_GET['username']);
        }
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Feedback')
            ->alias('a')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->where($data)
            ->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Feedback')
            ->alias('a')
            ->field('a.*,b.username,b.phone,b.ID,b.hx_username')
            ->join('__USER__ b on a.user_id=b.user_id')
            ->limit($p->firstRow.','.$p->listRows)
            ->where($data)
            ->order('a.intime desc')->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '反馈列表' );
        $this->assign("show",$p->show());
        $this->display();
    }

    /**
     * @删除反馈
     */
    public function del_feedback(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Feedback')->where(['feedback_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }




    /**
     * @等级经验表
     */
    public function level(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 20;
        }
        $this->assign("nus",$nus);
        $count = M('Level')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Level')->limit($p->firstRow.','.$p->listRows)->where($data)->order('level asc')->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '等级经验' );
        $this->assign("show",$p->show());
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_level(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Level')->find($id);
            $this->assign('p',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_level(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'level'=>I('level'),
            'experience'=>I('experience')
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Level')->add($data) ? $this->success('添加成功!',U('level')) : $this->error('添加失败',U('level'));
        }else{
            $data['uptime'] = time();
            if (M('Level')->where(['level_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('level'));
            }else{
                $this->error('编辑失败',U('level'));
            }
        }
    }

    /**
     * @删除
     */
    public function del_level(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Level')->where(['level_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }






    /**
     * @认证规则
     */
    public function approve_rule(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Approve_rule')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Approve_rule')->limit($p->firstRow.','.$p->listRows)->where($data)->order('intime asc')->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '认证规则' );
        $this->assign("show",$p->show());
        $this->display();
    }


    /**
     * @映射
     */
    public function toadd_approve_rule(){
        $id = I('id');
        if (!$id){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Approve_rule')->find($id);
            $this->assign('p',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_approve_rule(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'grade_start'=>I('grade_start'),
            'grade_end'=>I('grade_end'),
            'name'=>I('name'),
            'grade_img'=>I('logos'),
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Approve_rule')->add($data) ? $this->success('添加成功!',U('approve_rule')) : $this->error('添加失败',U('approve_rule'));
        }else{
            $data['uptime'] = time();
            if (M('Approve_rule')->where(['approve_rule_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('approve_rule'));
            }else{
                $this->error('编辑失败',U('approve_rule'));
            }
        }
    }

    /**
     * @删除
     */
    public function del_approve_rule(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Approve_rule')->where(['approve_rule_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }


    /**
     * @提现说明
     */
    public function withdraw_dis(){
        $this->assign ( 'pagetitle', '提现说明' );
        $id = I('id');
        if (empty($id)){
            $s = M('About_us')->where(['about_us_id'=>1])->find();
            $this->assign('a',$s);
            $this->display();
        }else{
            M('About_us')->where(['about_us_id'=>$id])->save(['withdraw_dis'=>I('content'),'intime'=>time()]) ? $this->success('成功!',U('withdraw_dis')) : $this->error('失败!',U('withdraw_dis')) ;
        }
    }

    /**
     * @提现控制
     */
    public function cash_control(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = intval(I('id'));
        if ($id){
            $data = [
                'id'=>$id,
                'withdraw_switch'=>I('withdraw_switch'),
                'every_day_switch'=>I('every_day_switch'),
                'period_of_time_start'=>I('period_of_time_start'),
                'period_of_time_end'=>I('period_of_time_end'),
                'day_switch'=>I('day_switch'),
                'start_time'=>strtotime(I('start_time')),
                'end_time'=>strtotime(I('end_time')),
                'day_lowest'=>I('day_lowest'),
                'day_highest'=>I('day_highest'),
                'day_number'=>I('day_number'),
                'uptime'=>time()
            ];
            if (M('System')->save($data)){
                $this->success('修改成功!',U('cash_control'));
            }else{
                $this->error('修改失败',U('cash_control'));
            }
        }else{
            $B = D ( "System" );
            $base = $B->find ();
            $this->assign ( "base", $base );
            $this->assign ( 'pagetitle', '提现控制' );
            $this->display ();
        }
    }




    /**
     * @提现等级控制列表
     */
    public function level_control(){
        $data = [];
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Level_control')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Level_control')->limit($p->firstRow.','.$p->listRows)->where($data)->order('intime asc')->select();
        $this->assign('list',$list);
        $this->assign ( 'pagetitle', '提现等级控制列表' );
        $this->assign("show",$p->show());
        $this->display();
    }

    /**
     * @添加(修改)映射
     */
    public function toadd_level_control(){
        $id = I('id');
        if (empty($id)){
            $this->assign ( 'pagetitle', '添加' );
        }else{
            $u = M('Level_control')->find($id);
            $this->assign('p',$u);
            $this->assign ( 'pagetitle', '编辑' );
        }
        $this->display();
    }
    /**
     * @添加(修改)
     */
    public function doadd_level_control(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('id');
        $data = [
            'grade_start'=>I('grade_start'),
            'grade_end'=>I('grade_end'),
            'day_highest'=>I('day_highest'),
        ];
        if (empty($id)){
            $data['intime'] = time();
            M('Level_control')->add($data) ? $this->success('添加成功!',U('level_control')) : $this->error('添加失败',U('level_control'));
        }else{
            $data['uptime'] = time();
            if (M('Level_control')->where(['level_control_id'=>$id])->save($data)){
                $this->success('编辑成功!',U('level_control'));
            }else{
                $this->error('编辑失败',U('level_control'));
            }
        }
    }

    /**
     * @删除
     */
    public function del_level_control(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $id = I('ids');
        $rs = M('Level_control')->where(['level_control_id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

     /**
     * @平台认证QQ
     */
    public function qq_auth(){
        $this->assign ( 'pagetitle', '平台认证QQ' );
        $id = I('id');
        if (empty($id)){
            $base = M('Qq')->field('id,qq')->where(['id'=>1])->find();
            $this->assign('base',$base);
            $this->display();
        }else{
            M('Qq')->where(['id'=>$id])->save(['qq'=>I('qq')]) ? $this->success('成功!',U('qq_auth')) : $this->error('失败!',U('qq_auth')) ;
        }
    } 
    //******************************************************redis的使用********************************************//
    //获取数据redis_set
    public function redis_server(){
    # 将数据一一加入到列表中
    $redis  = new \RedisServer();
    $redis->lpush('today_cost', 30);
    $redis->lpush('today_cost', 1.5);
    $redis->lpush('today_cost', 10);
    $redis->lpush('today_cost', 8);
    # 将数据一一加入到列表中
    return $redis->sort('today_cost');



}
    //获取数据redis_get

















}
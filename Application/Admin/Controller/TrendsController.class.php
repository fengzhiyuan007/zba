<?php
namespace  Admin\Controller;
use Think\Db;
use Psr\Log\Test\DummyTest;

use Org\Util\Date;
use Think\Upload;
use Think\Controller;

class TrendsController extends CommonController {
	function _initialize() {
        header("Content-Type:text/html; charset=utf-8");
        $nums = ['5','10','15','20','25','30','50','100'];
        $this->assign('nums',$nums);
    }
	/**
     * @标签列表
     */
    public function tag_list(){
        if( !M()->autoCheckToken($_POST) ) $this->error('禁止站外提交！');
        unset($_POST['__hash__']);
        $data = [];
        if (!empty($_GET['name'])){
            $data['name'] = ['like','%'.$_GET['name'].'%'];
            $this->assign('name',$_GET['name']);
        }
        
        //每页显示几条
        if (isset($_GET['nums'])){
            $nus  = intval($_GET['nums']);
        }else {
            $nus  = 10;
        }
        $this->assign("nus",$nus);
        $count = M('Tag')->where($data)->count();//一共有多少条记录
        $p = getpage($count,$nus);
        $list =  M('Tag')->limit($p->firstRow.','.$p->listRows)->where($data)->select();
        $this->assign('list',$list);
        $this->assign("show",$p->show());
        $this->assign ( 'pagetitle', '标签列表' );


        $this->display();
    }

	/**
     * @添加、修改映射
     */
    public function toadd_tag(){

        $id = I('id');
        if ($id){
            $user = M('Tag')->find($id);
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

        $counts = I('counts');
        $counts ? $counts = $counts : $counts = 1;
        $name = I('name');
        
        $data = [
            'name'=>$name,
            'counts'=>$counts,
        ];
        if ($id){
            M('Tag')->where(['id'=>$id])->save($data) ? $this->success('成功!',U('tag_list')) : $this->error('失败!',U('tag_list'));
        }else{
            if ($ids=M('Tag')->add($data)){

                $this->success('成功!',U('tag_list'));
            }else{
                $this->error('失败!',U('tag_list'));
            }
        }
    }

    /**
     * @彻底删除
     */
    public function del_true(){
        $id = I('ids');
        $rs = M('Tag')->where(['id'=>['in',$id]])->delete();
        echo $rs ? 1 : 2;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: ljy
 * Date: 17/8/30
 * Time: 下午2:10
 */

namespace Admin\Controller;


use Think\Controller;

class TestController extends Controller
{
    public function test(){
        $user = M('User')->where(['grade'=>'1'])->select();
        foreach ($user as $v){
            $experience = $v['experience'];
            $level = M('level')->where(['experience'=>['ELT',$experience]])->order("level_id desc")->limit(1)->find();
            M('User')->where(['user_id'=>$v['user_id']])->save(['grade'=>$level['level']]);
        }
    }
}
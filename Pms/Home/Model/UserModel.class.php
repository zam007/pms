<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';

     protected $_validate = array(
     array('verify','require','验证码必须！'), //默认情况下用正则进行验证
     array('userName','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
     //array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
     array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
     array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
     //array('email','email','email格式错误'),
   );

    public function getUser($info,$filed = '*') {
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where($info)->find();
        return $userInfo;
    }

    public function getUserField($userId,$filed = '*'){
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where('user_id='.$userId)->getField($filed);
        return $userInfo;
    }

    /**
     * 修改用户
     * @param type $userId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $userId,array $update){
        $user = M("User"); // 实例化User对象
        $update["update"] = date("Y-m-d H:i:s", time());
        return $user->where('user_id='.$userId)->save($update);
    }

    public function addUser($data){
        $user = M("User");//实例化User对象
        return $user->add($data);
    }
}
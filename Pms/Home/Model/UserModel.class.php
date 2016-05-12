<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';

    
    public function getUser($user,$filed = '*') {
        $User = M("User"); // 实例化User对象
        $userInfo = $User->where($user)->find();
        return $userInfo;
    }

    public function getUserField($userId,$filed = '*'){
        $User = M("User"); // 实例化User对象
        $userInfo = $User->where('user_id='.$userId)->getField($filed);
        return $userInfo;
    }

    /**
     * 修改用户
     * @param type $userId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $userId,array $update){
        $User = M("User"); // 实例化User对象
        $update["update"] = date("Y-m-d H:i:s", time());
        return $User->where('user_id='.$userid)->save($update);
    }

    public function addUser($data){
        $User = M("User");//实例化User对象
        $User->add($data);
    }
}
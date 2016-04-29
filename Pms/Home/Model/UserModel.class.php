<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';

    public function getUser($user) {
        $User = M("User"); // 实例化User对象
        $userInfo = $User->where($user)->find();
        return $userInfo;
    }

    public function getUserField($user,$filed){
        $User = M("User"); // 实例化User对象
        $userInfo = $User->where($user)->getField($filed);
        return $userInfo;
    }

    /**
     * 修改用户
     * @param array $where  修改条件
     * @param array $update 修改结果
     * @return type
     */
    public function modify(array $where,array $update){
        $User = M("User"); // 实例化User对象
        $update["update"] = date("Y-m-d H:i:s", time());
        return $User->where($where)->save($update);
    }

    public function addUser($user){
        $User = M("User");//实例化User对象
        $data["user_id"] = $user["userAccount"];
        $data["password"] = $user["password"];
        $data["reg_time"] = $user["regtime"];
        $data["status"] =  $user["status"];
        $data["flag"] = $user["flag"];
        $User->add($data);
    }
}
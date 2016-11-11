<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    public function getUser($info,$filed = '*') {
        $user = M("User"); // 实例化User对象
        $userInfo = $user->field($filed)->where($info)->find();
        return $userInfo;
    }

    public function getUserField($userId,$filed = '*'){
        $user = M("User"); // 实例化User对象
        $userInfo = $user->field($filed)->where('user_id='.$userId)->find();
        return $userInfo;
    }

    /**
     * 修改用户
     * @param type $userId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $userId,$update){
        $user = M("User"); // 实例化User对象
        $update["update_time"] = date("Y-m-d H:i:s", time());
        return $user->where('user_id='.$userId)->save($update);
    }
    /**
     * 修改密码
     * @param type $info 用户account
     * @param array $password 修改参数
     * @return type
     */
    public function updatePwd( $info,$password){
        $user = M("User"); // 实例化User对象
        $update["update_time"] = date("Y-m-d H:i:s", time());
        $update["password"] = $password;
        $user->where($info)->save($update);return $this->getlastsql();
    }

    public function addUser($data){
        $user = M("User");//实例化User对象
        return $user->add($data);
    }

    public function getWrok($workId,$filed){
        $work = M("Work");
        return $work->field($filed)->where('work_id='.$workId)->find();
    }
}
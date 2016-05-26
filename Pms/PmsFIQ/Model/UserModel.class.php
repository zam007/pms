<?php
namespace PmsFIQ\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    
    public function getUser($info,$filed = '*') {
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where($info)->select();
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
        return $user->where('user_id='.$userId)->save($update);
    }

}
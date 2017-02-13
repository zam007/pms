<?php
namespace Admin\Model;
use Think\Model;
class CompanyModel extends Model {
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


}
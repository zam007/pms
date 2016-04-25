<?php
namespace Home\Model;
use Think\Model;
class IndexModel extends Model {
    public function login($user) {
        $User = M("User"); // 实例化User对象
        $User->where($user)->find(); 
    }
}
<?php
namespace Home\Model;
use Think\Model;
class LavelModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getLavel($info,$filed = '*') {
        $user = M("Lavel");
        $userInfo = $user->where($info)->find();
        return $userInfo;
    }

}
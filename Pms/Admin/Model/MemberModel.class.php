<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    private function getModel(){
        return M("Member");
    }
    public function getMember($info,$filed = '*') {
        $model = $this->getModel(); 
        return $model->where($info)->find();
        
    }

    public function getUserField($userId,$filed = '*'){
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where('user_id='.$userId)->getField($filed);
        return $userInfo;
    }

}
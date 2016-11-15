<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends Controller {
    
    public function userList(){
        $user = D("User");
        $where = array(
            'flag' => 1,
            );
        $show = $user->userList($where);
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
}
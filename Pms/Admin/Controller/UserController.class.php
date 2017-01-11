<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
class UserController extends BaseController {
    public function userList() {
        $m = M('User');      
        $where = array(
            'flag' => 1,
            );
        $count = $m->where($where)->count();
        $p = getpage($count);
        $list = $m->field(true)->where($where)->limit($p->firstRow, $p->listRows)->select();
        $this->assign('select', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display();
    }
}
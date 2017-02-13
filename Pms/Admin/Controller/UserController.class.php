<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
class UserController extends BaseController {
    public function userList() {
        $model = M('User');
        $where = array(
            'flag' => 1,
            );
        $count = $model->where($where)->count();
        $p = getpage($count);
        $list = $model->field(true)->where($where)->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display();
        die();
    }
}
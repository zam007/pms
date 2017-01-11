<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
class UserController extends BaseController {
    
    public function userList1(){
        $user = D("User");
        $where = array(
            'flag' => 1,
            );
        import('ORG.Util.Page');// 导入分页类
        $show = $user->getUser($where);
        $this->assign('page',$show);// 赋值分页输出
        $this->display(); // 输出模板
    }
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
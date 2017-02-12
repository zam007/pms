<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
class CompanyController extends BaseController {
    public function companyList() {
        $m = M('Team');
        $where = array(
            'flag' => 1,
            );
        $count = $m->where($where)->count();
        $p = getpage($count);
        $list = $m->field(true)->where($where)->limit($p->firstRow, $p->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display("danweiguanli");
    }
}
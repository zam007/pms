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
        $team = array_column($list,'team_user');
        $userModel = D('user');
        if(empty($team)){
            $userIds = join(',',$team);
            $userList = $userModel->getList($userIds);
            $userList = array_column($userList,null,'user_id');
        }
        $sql = "select team_id,(select count(*) from team_user where team_user.team_id = team.team_id) num from team";
        $num = $m->query($sql);
        $num = array_column($num,null,'team_id');
        $this->assign('num', $num);
        $this->assign('userList', $userList);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display("danweiguanli");
    }
}
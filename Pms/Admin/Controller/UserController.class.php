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

        // $ids = array_column($list,'user_id');
        // $ids = join($ids,',');
        $userModel = D('User');
        $work = $userModel->getWork();
        $work = array_column($work,null,'work_id');
        $this->assign('work', $work);
        $this->display('user');
        die();
    }

    public function disable(){
        $id = (int)I('id');
        if($id <= 0){
            $this->error('用户不存在');
        }
        $userModel = D('User');
        $cond = [
            'status'=>9,
            'update_time'=>date('Y-m-d H:i:s',time()),
        ];
        if($userModel->modify($id,$cond) === false){
            $this->error('禁用用户失败！');
        }
        $this->success('禁用用户成功！', '/Admin/User/userList');
    }

    public function recovery(){
        $id = (int)I('id');
        if($id <= 0){
            $this->error('用户不存在');
        }
        $userModel = D('User');
        $cond = [
            'status'=>9,
            'update_time'=>date('Y-m-d H:i:s',time()),
        ];
        if($userModel->modify($id,$cond) === false){
            $this->error('解禁用户失败');
        }
        $this->success('解禁用户成功！', '/Admin/User/userList');
    }
}
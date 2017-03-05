<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    
    public function getUser($info,$filed = '*') {
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where($info)->select();
        return $userInfo;
    }

    public function getUserField($userId,$filed = '*'){
        $user = M("User"); // 实例化User对象
        $userInfo = $user->where('user_id='.$userId)->getField($filed);
        return $userInfo;
    }

    public function getList($ids){
        $user = M("User"); // 实例化User对象
        return $user->where('user_id in ('.$ids.')')->select();
    }
    /**
     * 修改用户
     * @param type $userId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $userId,array $update){
        $user = M("User"); // 实例化User对象
        return $user->where('user_id='.$userId)->save($update);
    }

    public function userList($info, $page = 1, $size = 20){
        $User = M('User'); // 实例化User对象
        
        // 进行分页数据查询 
        $list = $User->where($info)->page($page.','.$size)->select();
        $this->assign('list',$list);// 赋值数据集
        $count      = $User->where($info)->count();// 查询满足要求的总记录数
        $ThinkPage       = new \Think\Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = $ThinkPage->show();// 分页显示输出
        return $show;
    }

    public function getWork(){
        $work = M('work');
        $cond['flag'] = 1;
        return $work->where($cond)->select();
    }

}
<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends BaseController {
    public function improveHtml(){
        $this->display("register_1");
    }
    /**
     * 跳转
     */
    public function personal_info(){
        $this->display("personal_info");
    }
    public function account_bind(){
        $this->display("account_bind");
    }
    public function change_pwd(){
        $this->display("change_pwd");
    }
    public function test_record(){
        $this->display("test_record");
    }
    /**
     * 个人用户完善资料
     */
    public function register_2(){
        if (IS_POST) {
            //检测验用户名是手机号码或者邮箱
            $index = D("user");
            //检测之前输入的是邮箱还是手机号码
            $userId = $this->userId;
            $info = $index->getUserField($userId,'mobile,email');
            $userInfo = $index->modify($userId,$user);
            //密码合法验证
            $rules = array(
                array('repassword','password','确认密码不正确',0,'confirm'),
                array('password','/^[a-z]\w{6,20}$/i','密码不符合要求！'),
            );
            if (!$index->validate($rules)->create()) {
                //密码检验不通过，输出错误信息
                exit($this->ajaxReturn($index->getError()));
            }
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $index->modify($userId,$user);
            if(I('session.user_type') == 0){
                $this->display("User/register_2");
            }else{
                $this->display("User/register_group");
            }
        }
    }
    /**
     * 补全资料
     */
    public function completion(){
        // print_r($_POST);exit;
            $userId = $this->userId;
            $user["name"] = I("name");
            $user["sex"] = I("sex");
            $user["birth"] = I("birth");
            $user["work_id"] = I("work_id");
            $user["company_id"] = I("company_id");
            $user["invite_id"] = I("invite_id");
            $user["from_add"] = I("from_add");
            $user["weixin"] = I("weixin");
            $user["qq"] = I("qq");
            $user["status"] = 1;
            $user["update_time"] = date('Y-m-d H:i:s',time());

            $index = D("user");
            //资料完善合法验证
            $rules = array(
                 array('name','require','标题不能为空。'),
            );

            if (!$index->validate($rules)->create($user)){
                //验证失败
                $this->ajaxReturn($index->getError());
            }else{
                //验证通过
            if (!$index->modify($userId,$user)) {
                $this->error('完善资料失败，请重新填写','../User/completion');
            }
            $name = 'name';
            $userInfo = $index->getUserField($userId,$name);
            SESSION("user_name",$userInfo['name']);
            $this->assign('name',$userInfo['name']);
            $this->display("Index/index");
            }
    }
}
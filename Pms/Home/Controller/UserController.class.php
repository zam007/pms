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
        $userId = $this->userId;
        $userMode = D('user');
        $user = $userMode->getUserField($userId);
        $this->assign('user',$user);
        $this->display("personal_info");
    }
    public function account_bind(){
        $userId = $this->userId;
        $userMode = D('user');
        $user = $userMode->getUserField($userId,"mobile,email");
        $this->assign('user',$user);
        $this->display("account_bind");
    }
    public function change_pwd(){
        $this->display("change_pwd");
    }
    public function test_record(){
        $userId = $this->userId;
        $order = D('order');
        $userExam = $order->getList($userId,1);
        $teamExam = $order->getList($userId,2);
        $this->assign('user_exam',$userExam);
        $this->assign('team_exam',$teamExam);
        $this->display("test_record");
    }
    /**
     * 发送测试报告
     */
    public function sendMile(){
        $userId = $this->userId;
        $userMode = D('user');
        $userEamil = $userMode->getUserField($userId,'email');
        if(!$userEamil['email']){
            echo "请完善邮箱";die();
        }
        $anwerSheetId = I('answer_sheet_id');
    }
    /**
     * 个人用户完善资料
     */
    public function registerTwo(){
        if (IS_POST) {
            $index = D("user");
            $userId = $this->userId;
//            $info = $index->getUserField($userId,'mobile,email');
//            $userInfo = $index->modify($userId,$user);
            //密码合法验证
            $rules = array(
                array('password','/^[a-z]\w{6,20}$/i','请输入8位带大小写字母组合的密码'),
                array('repassword','password','两次输入的密码不一致',0,'confirm'),
            );
            if (!$index->validate($rules)->create()) {
                //密码检验不通过，输出错误信息
                $this->ajaxReturn($index->getError());
            }
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $index->modify($userId,$user);
            if(I('session.user_type') == 0){
                $msg = array(
                'info' => 'ok',
                'callback' => U('user/register_2')
                );
                $this->ajaxReturn($msg);
            }else{
                $msg = array(
                'info' => 'ok',
                'callback' => U('User/register_group')
                );
                $this->ajaxReturn($msg);
            }
        }
    }

    /**
     * 修改密码
     */
    public function updatePwd(){
//        if (IS_POST) {
            $index = D("user");
            $userId = $this->userId;
            //密码合法验证
            $rules = array(
                array('password','/^[a-z]\w{6,20}$/i','请输入8位带大小写字母组合的密码'),
                array('repassword','password','两次输入的密码不一致',0,'confirm'),
            );
            if (!$index->validate($rules)->create()) {
                //密码检验不通过，输出错误信息
                $this->ajaxReturn($index->getError());
            }
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $index->modify($userId,$user);
            if(I('session.user_type') == 0){
                $msg = array(
                'info' => 'ok',
                'callback' => U('index/index')
                );
                $this->ajaxReturn($msg);
            }else{
                $msg = array(
                'info' => 'ok',
                'callback' => U('index/index')
                );
                $this->ajaxReturn($msg);
            }
//        }
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
    /**
     * 修改密码
     */
    public function changePwd(){
        
    }
}
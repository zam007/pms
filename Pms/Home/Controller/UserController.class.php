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
        $index = D("user");
        $userId = $this->userId;
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

    /**
     * 修改密码
     */
    public function updatePwd(){
        $index = D("user");
        $userId = $this->userId;
        //修改成功后ajax返回信息
        $msg = array(
            'info' => '',
            'callback' => U('User/change_pwd')
            );
        //验证原始密码是否正确
        $info["user_id"] = $this->userId;
        $userInfo = $index->getUser($info);
        if (md5(I("oldpassword").C("PWD_KEY")) != $userInfo["password"]) {
            $msg['info'] = 'no';
            $msg['pwd_err'] = "旧密码错误，请输入正确的旧密码";
            $this->ajaxReturn($msg);
        }
        //新密码合法验证
        $newinfo['password'] = I("password");
        $newinfo['repassword'] = I("repassword");
        $rules = array(
            array('password','/^[a-z]\w{6,20}$/i','请输入8位带大小写字母组合的密码'),
            array('repassword','password','两次输入的密码不一致',0,'confirm'),
        );
        if (!$index->validate($rules)->create($newinfo)) {
            //密码检验不通过，输出错误信息
            $this->ajaxReturn($index->getError());
        }
        $user["password"] = md5(I("password").C("PWD_KEY"));
        $index->modify($userId,$user);
        $msg['info'] = 'ok';
        $this->ajaxReturn($msg);
    }

    /**
     * 个人补全资料
     */
    public function completion(){
        // print_r($_POST);exit;
            $userId = $this->userId;
            $user["name"] = I("name");
            $user["sex"] = I("sex");
            $user["birth"] = date("Y-m-d", strtotime(I("datetimepicker")));
            $user["work_id"] = I("work_id");
            $user["company_id"] = I("company_id");
            $user["team_id"] = I("team_id");
            $user["from_add"] = I("from_add");
            $user["weixin"] = I("weixin");
            $user["qq"] = I("qq");
            $user["status"] = 1;
            $user["update_time"] = date('Y-m-d H:i:s',time());

            //资料完善合法验证
            $index = D("user");
            $rules = array(
                 array('name','require','请输出姓名'),
                 array('sex','require','请选择性别'),
                 array('birth','require','请补充生日信息'),
                 array('work_id','require','请补充职业信息'),
                 array('from_add','require','请补充出生地信息'),
            );

            if (!$index->validate($rules)->create($user)){
                //验证失败
                $this->ajaxReturn($index->getError());
            }else{
                //验证通过
            if (!$index->modify($userId,$user)) {
                 $msg = array(
                'statu' => 'no',
                'info' => '完成个人信息失败，请重试'
                );
                $this->ajaxReturn($msg);
            }
            $name = 'name';
            $userInfo = $index->getUserField($userId,$name);
            SESSION("user_name",$userInfo['name']);
            $this->assign('name',$userInfo['name']);
             $msg = array(
            'statu' => 'ok',
            'callback' => U('Index/index')
            );
            $this->ajaxReturn($msg);
            }
    }
    /**
     * 团队补全资料
     */
    public function groupCompletion(){
        //个人信息获取
        $userId = $this->userId;
        $teamId = I('session.team_id');
        $user["name"] = I("name");
        $user["sex"] = I("sex");
        $user["birth"] = date("Y-m-d", strtotime(I("datetimepicker")));
        $user["work_id"] = I("work_id");
        $user["team_id"] = I("team_id");
        $user["from_add"] = I("from_add");
        $user["weixin"] = I("weixin");
        $user["qq"] = I("qq");
        $user["status"] = 1;
        $user["update_time"] = date('Y-m-d H:i:s',time());
        //团体信息获取
        $teaminfo["team_name"] = I("team_name");
        $teaminfo["nature"] = I("team_nature");
        $teaminfo["attribute"] = I("team_attribute");
        $teaminfo["team_user"] = $userId ;
        //个人资料完善合法验证
        $index = D("user");
        $rules = array(
             array('name','require','请输出姓名'),
             array('sex','require','请选择性别'),
             array('birth','require','请补充生日信息'),
             array('work_id','require','请补充职业信息'),
             array('from_add','require','请补充出生地信息'),
        );
        if (!$index->validate($rules)->create($user)){
            $this->ajaxReturn($index->getError());
        }
        //团队资料合法验证
        $team = D("team");
        $teamrules = array(
             array('team_name','require','请输入团体名称'),
             array('nature','require','请输入团体性质'),
             array('attribute','require','请输入团体属性'),
        );
        if (!$team->validate($teamrules)->create($teaminfo)){
            $this->ajaxReturn($index->getError());
        }
        //用户数据插入
        if (!$index->modify($userId,$user)) {
             $msg = array(
            'statu' => 'no',
            'info' => '完善个人信息失败，请重试'
            );
            $this->ajaxReturn($msg);
        }
        //团队信息插入
        if (!$team->modify($teamId,$teaminfo)) {
             $msg = array(
            'statu' => 'no',
            'info' => '完善团退信息失败，请重试'
            );
            $this->ajaxReturn($msg);
        }
        //获取用户信息，存入session前端展示
        $name = 'name';
        $userInfo = $index->getUserField($userId,$name);
        SESSION("user_name",$userInfo['name']);
        $this->assign('name',$userInfo['name']);
        //获取团队信息，存入session前端展示
        $teamdisplay = 'team_name';
        $team_Info = $team->getTeamField($teamId,$teamdisplay);
        SESSION("team_name",$team_Info['team_name']);
        $this->assign('team_name',$team_Info['team_name']);
        //ajax正确返回
         $msg = array(
        'statu' => 'ok',
        'callback' => U('Index/index')
        );
        $this->ajaxReturn($msg);
    }
}
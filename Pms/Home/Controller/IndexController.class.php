<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function reg(){
            $this->display("register");
    }
    public function log(){
            $this->display("login");
    }

	/**
	 *登陆
	 */
	public function login(){
        if (IS_POST) {

            //检测验用户输入账户类型
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $value = '手机号';
            }else {
                $user['mobile'] = I("username");
                $value = '邮箱';
            }

            //用户登陆合法验证
            $rules = array(
                 array('mobile', '/^1[34578]\d{9}$/', '请输入正确的手机号', 0),
                 array('email', 'email', '请输入正确的邮箱号'),
            );

            $index = D("user");
            if (!$index->validate($rules)->create($user)){
                //验证失败
                $this->ajaxReturn($index->getError());
            }else{
                //判断用户登录错误次数
                $info["flag"] = 1;
                $error = $index->getUser($info);
                if($error['login_err'] >= 3){
                   $code = I("verify");
                   if(!check_verify($code)){
                      $this->error('验证码错误','index');
               }
            }
            $user['user_id'] = $error['user_id'];
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $user["flag"] = 1;
            $userInfo = $index->getUser($user);
            if($userInfo){
                $update["login_err"] = 0;
                $index->modify($userInfo["user_id"],$update);//清空登录错误次数
                SESSION("user_id",$userInfo["user_id"]);
                if($userInfo['name']){
                    $userNmae = $userInfo['name'];
                }
                SESSION("user_name",$userNmae);
                if($userInfo['status'] == 0){
                    $this->display("User/completion");exit;
                }
                if($userInfo['status'] == 9){
                  $this->error('用户被冻结','index');
                }
                 $this->success('登陆成功', 'index');
            }else{
                if($error){
                    $update["login_err"] = $error['login_err']+1;
                    $index->modify($error['user_id'],$update);
                }
                $this->error('用户名或密码错误','index');
             }
            }
        }
    }

    /**
     * 图片验证码
     */
	public function verify(){
		ob_clean();//清除缓存
        $Verify = new \Think\Verify();
        $Verify->fontSize = 18;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
        $Verify->imageW = 130;
        $Verify->imageH = 50;
        //$Verify->expire = 600;
        $Verify->entry();
	}

	/**
	 * 注册
	 */
	public function register(){
        if (IS_POST) {

            //检测验用户输入账户类型
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $value = '手机号';
            }else {
                $user['mobile'] = I("username");
                $value = '邮箱';
            }

            //注册账号合法验证
            $rules = array(
                 array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0),
                 array('email', 'email', '邮箱格式不正确'),
                 array('mobile','','该手机号已经被注册！',0,'unique',1),
                 array('email','','该邮箱已经被注册！',0,'unique',1),
            );
            //验证团体注册或者个人注册
            if (I("register_property") == 2) {
                $index_user = D("user");
                $index_team = D("team");
                $index_team_user = D("teamUser");
                if (!$index_user->validate($rules)->create($user)){
                    //验证失败
                    $this->ajaxReturn($index->getError());
                }else{
                    //验证通过
                    $team["code"] = rand(100000,999999);
                    $teamId = $index_team->addTeam($team);
                    //
                    $user["reg_time"] = date('Y-m-d H:i:s',time());
                    $user["team_id"] = $teamId;
                    $userId = $index_user->addUser($user);
                    //
                    $team_user["team_id"] = $teamId;
                    $team_user["user_id"] = $userId;
                    $team_user["created"] = $user["reg_time"];
                    $team_userId = $index_team_user->addteam($team_user);
                    SESSION("user_id",$userId);
                    SESSION("team_id",$teamId);
                    SESSION("last_session_id",session_id());
                    $this->assign('value',$value);
                    $this->display("user/improve");
                }
            }
            else {
                $index = D("user");
                if (!$index->validate($rules)->create($user)){
                    //验证失败
                    $this->ajaxReturn($index->getError());
                }else{
                    //验证通过
                    $user["reg_time"] = date('Y-m-d H:i:s',time());
                    $userInfo = $index->addUser($user);
                    SESSION("user_id",$userInfo);
                    SESSION("last_session_id",session_id());
                    $this->assign('value',$value);
                    $this->display("user/improve");
                }
            }
        }
    }

    /**
     * 发送邮件
     */
    public function send(){
    //        $email = I('email');
            $email = '1435626505@qq.com';
            $title = '标题';
            $content = '内容';
            SendMail($email,$title,$content);
    }

    /**
     * 用户退出
     */
    public function logout(){
            SESSION("user_id",0);
            SESSION("team_id",0);
            $this->success('成功退出','index');
    }
}
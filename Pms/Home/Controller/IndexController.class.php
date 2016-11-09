<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    public function reg(){
            $this->display("register");
    }
    public function log(){
            $this->display("login");

    }public function test(){echo 3;exit;}

	/**
	 *登陆
	 */
	public function login(){
        if (IS_POST) {
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $value = '手机号';
                $info["email"] = I("username");
            }else {
                $user['mobile'] = I("username");
                $value = '邮箱';
                $info["mobile"] = I("username");
            }
            //用户登陆合法验证
            $user['verify'] = I("verify");
            $rules = array(
                 array('mobile', '/^1[34578]\d{9}$/', '请输入正确的11位数手机号码', 0),
                 array('email', 'email', '请输入正确的邮箱号'),
                 array('verify','require','验证码必须！'),
            );

            $index = D("user");
            if (!$index->validate($rules)->create($user)){
                //验证失败
                $this->ajaxReturn($index->getError());
            }else{
                //判断用户登录错误次数
                $info["flag"] = 1;
                $userInfo = $index->getUser($info);
            //     if($error['login_err'] >= 3){
            //        $code = I("verify");
            //        if(!check_verify($code)){
            //           $this->error('验证码错误'.$code,'index');
            //    }
            // }
                $verify = I("verify");
                if(!check_verify($user['verify'])){
                    $this->ajaxReturn(array('info' => 'no',
                                            'verify'=> '请输入正确的验证码'
                     ));
                }
                if(md5(I("password").C("PWD_KEY")) === $userInfo["password"]){
                    $update["login_err"] = 0;
                    $index->modify($userInfo["user_id"],$update);//清空登录错误次数
                    SESSION("user_id",$userInfo["user_id"]);
                    if($userInfo['name']){
                        $userNmae = $userInfo['name'];
                    }
                    SESSION("user_name",$userNmae);
                    $msg = array(
                    'info' => 'ok',
                    'callback' => U('Index/index')
                    );
                    if($userInfo['status'] == 0){
                        $msg = array(
                        'callback' => U('User/completion')
                        );
                    }
                    if($userInfo['status'] == 9){
                        $msg = array(
                            'info' => 'no',
                            'error'=> '账户被禁用,请联系管理员接触禁用',
                        'callback' => U('Index/index')
                        );
                    }
                    $this->ajaxReturn($msg);
                }else{
                    if($userInfo){
                        $update["login_err"] = $userInfo['login_err']+1;
                        $index->modify($userInfo['user_id'],$update);
                    }
                    // $this->error('用户名或密码错误','index');
                        $msg = array(
                            'info' => 'no',
                            'error'=> '用户名或密码错误',
                        'callback' => U('Index/index')
                        );
                        $this->ajaxReturn($msg);
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
	public function registerOne(){
        if (IS_POST) {
            //检测验用户输入账户类型
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $verify = I("verify");
                $value = '手机号';
                $account = I("username");
                SESSION("email",$account);
            }else {
                $user['mobile'] = I("username");
                $verify = I("verify");
                $value = '邮箱';
                $account = I("username");
                SESSION("mobile",$account);
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
                    $this->ajaxReturn($index_user->getError());
                }else{
                	//验证码匹配
                	if (!verifyCode($account,$verify)) {
	                   $msg = array(
	                    'staut' => 'no',
	                    'info' => '验证码错误，请输入正确的验证码'
	                    );
                		$this->ajaxReturn($msg);
                	}
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
                    SESSION("user_account",$account);
                    SESSION("user_type",1);
                    $this->assign('value',$value);
                    // $this->display("user/register_1");
                    $msg = array(
                    'info' => 'ok',
                    'callback' => U('user/register_1')
                    );
                    $this->ajaxReturn($msg);
                }
            }else {
                $index = D("user");
                if (!$index->validate($rules)->create($user)){
                    //验证失败
                    $this->ajaxReturn($index->getError());
                }else{
                	//验证码匹配
                	if (!verifyCode($account,$verify)) {
	                   $msg = array(
	                    'staut' => 'no',
	                    'info' => '验证码错误，请输入正确的验证码'
	                    );
                		$this->ajaxReturn($msg);
                	}
                    //验证通过
                    $user["reg_time"] = date('Y-m-d H:i:s',time());
                    $userId = $index->addUser($user);
                    SESSION("user_id",$userId);
                    SESSION("last_session_id",session_id());
                    SESSION("user_account",$account);
                    SESSION("user_type",0);
                    $this->assign('value',$value);
                    // $this->display("user/register_1");
                    $msg = array(
                    'info' => 'ok',
                    'callback' => U('User/register_1')
                    );
                    $this->ajaxReturn($msg);
                }
            }
        }
    }
    /**
     * 发送注册邮件或者短信
     */
    public function sendMsg(){
        //账号合法验证
        $rules = array(
             array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0),
             array('email', 'email', '邮箱格式不正确'),
             array('mobile','','该手机号已经被注册，请直接登陆！',0,'unique',1),
             array('email','','该邮箱已经被注册，请直接登陆！',0,'unique',1),
        );
        if(strstr(I("username"), '@')){
            $user['email'] = I("username");
            $index_user = D("user");
            if (!$index_user->validate($rules)->create($user)){
                //邮箱不合法或者已经存在
                $this->ajaxReturn($index_user->getError());
            }else{
	            $email = $user['email'];
	            if (mailCode($email)) {
                    $msg = array(
                    'staut' => 'ok',
                    'info' => '邮件发送成功，请注意查收邮件'
                    );
                    $this->ajaxReturn($msg);
	            }else{
                    $msg = array(
                    'staut' => 'no',
                    'info' => '邮件发送失败，请稍后重试'
                    );
                    $this->ajaxReturn($msg);
	            }
            }
        }else {
            $user['mobile'] = I("username");
            $index_user = D("user");
            if (!$index_user->validate($rules)->create($user)){
                //电话号码不合法或者已经存在
                $this->ajaxReturn($index_user->getError());
            }else{
	            $mobile = I('username');
	            if(mobileCode($mobile)){
                    $msg = array(
                    'staut' => 'ok',
                    'info' => '短信发送成功，请注意查看手机短信'
                    );
                    $this->ajaxReturn($msg);
	            }else{
                    $msg = array(
                    'staut' => 'no',
                    'info' => '短信发送失败，请稍后重试'
                    );
                    $this->ajaxReturn($msg);
	            }
            }
        }
    }
    /**
     * 发送找回密码的验证码
     */
    public function sendMsgfindnpwd(){
        //账号合法验证
        $rules = array(
             array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0),
             array('email', 'email', '邮箱格式不正确'),
        );
        if(strstr(I("username"), '@')){
            $user['email'] = I("username");
            $index_user = D("user");
            if (!$index_user->validate($rules)->create($user)){
                $this->ajaxReturn($index_user->getError());
            }else{
                // 检查邮箱账户是否存在
                $user["flag"] = 1;
                if (!$index_user->getUser($user)) {
                    $msg = array(
                    'staut' => 'no',
                    'info' => '邮箱账户不存在，请先注册账户'
                    );
                    $this->ajaxReturn($msg);
                }
                //检查验证码
                $user['verify'] = I("verify");
                if(!check_verify($user['verify'])){
                    $this->ajaxReturn(array('staut' => 'no',
                                            'info'=> '请输入正确的验证码'
                     ));
                }
                //发送验证码
                $email = $user['email'];
                if (mailCode($email)) {
                    $msg = array(
                    'staut' => 'ok',
                    'info' => '邮件发送成功，请注意查收邮件'
                    );
                    $this->ajaxReturn($msg);
                }else{
                    $msg = array(
                    'staut' => 'no',
                    'info' => '邮件发送失败，请稍后重试'
                    );
                    $this->ajaxReturn($msg);
                }
            }
        }else {
            $user['mobile'] = I("username");
            $index_user = D("user");
            if (!$index_user->validate($rules)->create($user)){
                //电话号码不合法或者已经存在
                $this->ajaxReturn($index_user->getError());
            }else{
                // 检查手机账户是否存在
                $index = D("user");
                $user["flag"] = 1;
                if (!$index->getUser($user)) {
                    $msg = array(
                    'staut' => 'no',
                    'info' => '手机账户不存在，请先注册账户'
                    );
                    $this->ajaxReturn($msg);
                }
                //检查验证码
                $user['verify'] = I("verify");
                if(!check_verify($user['verify'])){
                    $this->ajaxReturn(array('staut' => 'no',
                                            'info'=> '请输入正确的验证码'
                     ));
                }
                $mobile = I('username');
                if(mobileCode($mobile)){
                    $msg = array(
                    'staut' => 'ok',
                    'info' => '短信发送成功，请注意查看手机短信'
                    );
                    $this->ajaxReturn($msg);
                }else{
                    $msg = array(
                    'staut' => 'no',
                    'info' => '短信发送失败，请稍后重试'
                    );
                    $this->ajaxReturn($msg);
                }
            }
        }
    }
    /**
     * 发送邮件
     */
    public function send(){
            $email = I('email');
            $title = '标题';
            $content = '内容';
            echo SendMail($email,$title,$content);
    }
    /**
     * 用户退出
     */
    public function logout(){
            SESSION("user_id",0);
            SESSION("team_id",0);
            SESSION("user_accout",0);
            $this->success('成功退出','index');
    }
    /**
     * 找回密码
     */
     public function findpasswdstepone(){
        $this->display("fundpwd_1");
    }

     public function findpasswdsteptwo(){
        //检测验用户输入账户类型
        if(strstr(I("username"), '@')){
            $user['email'] = I("username");
            $verify = I("code");
            $value = '手机号';
            $account = I("username");
            SESSION("email",$account);
        }else {
            $user['mobile'] = I("username");
            $verify = I("code");
            $value = '邮箱';
            $account = I("username");
            SESSION("mobile",$account);
        }
        //注册账号合法验证
        $rules = array(
             array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0),
             array('email', 'email', '邮箱格式不正确'),
        );
        //短信、邮箱验证码匹配
        if (!verifyCode($account,$verify)) {
           $msg = array(
            'staut' => 'no',
            'info' => '请输入正确的短信/邮箱验证码'
            );
            $this->ajaxReturn($msg);
        }
        //验证通过
        $index = D("user");
        if (!$index->validate($rules)->create($user)){
            $this->ajaxReturn($index->getError());
        }else{
            //短信、邮箱验证码匹配
            if (!verifyCode($account,$verify)) {
               $msg = array(
                'staut' => 'no',
                'info' => '请输入正确的短信/邮箱验证码'
                );
                $this->ajaxReturn($msg);
            }
            //验证通过
            $userId = $index->addUser($user);
            SESSION("user_account",$account);
            $this->assign('value',$value);
            $msg = array(
            'staut' => 'ok',
            'callback' => U('Index/fundpwd_2')
            );
            $this->ajaxReturn($msg);
        }
    }
    /**
     * 短信找回密码后修改
     */
    public function findPwd(){
        //修改成功后ajax返回信息
        $msg = array(
            'info' => 'default',
            'callback' => U('Index/index')
            );
        //新密码合法验证
        $index = D("user");
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
        if (I('session.email')) {
            $accountinfo["email"]=I('session.email');
        }else{
            $accountinfo["mobile"]=I('session.mobile');
        }
        $password = md5(I("password").C("PWD_KEY"));
        if ($index->updatePwd($accountinfo,$password)) {
            $msg['info'] = 'ok';
            $this->ajaxReturn($msg);
        }else{
            $msg['info'] = 'no';
            $this->ajaxReturn($msg);
        }
    }

    /**
     * 发送手机验证码
     * @return boolean
     */
    public function sendMobile(){

        $mobile=I('mobile') ;//发送号码用逗号分隔
        $code = sendMobile($mobile);
        if($code == "000"){
            return true;
        }
        return false;
    }
    /**
     * 实例
     * 通过手机或者邮箱获取验证码
     * return Array ( [code] => 182177 [time] => 1474376466 )  code:验证码，time:发送时间(时间戳)
     */
    public function getCode(){
        $key = I('key');
        $arr = getCode($key);
        $code = key($arr);
        $time = $arr[$code];
        $data = array(
            'code'=>$code,
            'time'=>$time
        );
        return $data;
    }
    /**
     * ajax异步验证
     */
    public function checkName(){
        if (IS_POST) {
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $value = '手机号';
            }else {
                $user['mobile'] = I("username");
                $value = '邮箱';
            }
            $rules = array(
                 array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0),
                 array('email', 'email', '邮箱格式不正确'),
                 array('mobile','','该手机号已经被注册！',0,'unique',1),
                 array('email','','该邮箱已经被注册！',0,'unique',1),
            );
            $user = D("user");
            if (!$user->validate($rules)->create($user)){
                //验证失败
                $this->ajaxReturn($user->getError());
                die();
            }else{
                //验证通过
                $msg = array(
                'mobile' => 'err',
                'callback' => U('Index/register_1')
                );
                $this->ajaxReturn($msg);
                die();
            }
        }
    }
}
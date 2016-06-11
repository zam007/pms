<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
         $this->display("home");
     }
    public function log(){
        //重定向到指定的URL地址
        $this->display("login");
    }
	/**
	 *登陆
	 */
	public function login($username = '',$password = '',$verify = ''){
        if (IS_POST) {

            $index = D("user");
            //判断用户名
            if(!I("username")){
                echo 'empty';exit;
            }
            $userName = I("username");
            if(strstr(I("username"), '@')){
                $info['email'] = I("username");
            }else if(strlen(I("userName")) == 11){
                $info['mobile'] = I("username");
            }else{
                echo "请输入正确的手机或邮箱！";exit;
            }
            //判断用户登录错误次数
            $error = $index->getUser($info);
            if($error['login_err'] >= 3){
               $code = I("verify");
               if(!check_verify($code)){
                  echo "验证码错误";exit;
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
                if(!$userInfo['mobile'] or !$userInfo['email']){
                    if(!$userInfo['mobile']){
                        $value = '手机号';
                    }else{
                        $value = '邮箱';
                    }
                    $this->assign('value',$value);
                    $this->display('User/improve');exit;
                }
                if($userInfo['status'] == 0){
                    echo '完善资料';exit;
                }
                if($userInfo['status'] == 9){
                    echo "用户被冻结";exit;
                }
                echo "登录成功";exit;
       //             $this->display();
            }else{
                if($error){
                    $update["login_err"] = $error['login_err']+1;
                    $index->modify($error['user_id'],$update);
                }
                echo "用户名或密码错误";exit;
            }
        }

        }

        /**
         * 图片验证码
         */
	public function verify(){
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

	public function reg(){
		    $this->display("register");
	}

	/**
	 * 注册
	 */
	public function register($username = '',$verify = ''){
        if (IS_POST) {

            /* 检测验证码 */
            // if(!check_verify($verify)){
            //     $this->error('验证码输入错误！');
            // }

            /* 检测验用户名是手机号码或者邮箱 */
            $index = D("user");
            if(strstr(I("username"), '@')){
                $user['email'] = I("username");
                $value = '手机号';
            }else if(strlen(I("username")) == 11){
                $user['mobile'] = I("username");
                $value = '邮箱';
            }else{
                echo "请输入正确的手机或邮箱！";exit;
            }
            $user["reg_time"] = date('Y-m-d H:i:s',time());
            $userInfo = $index->addUser($user);
            SESSION("user_id",$userInfo);
            SESSION("last_session_id",session_id());
            $this->assign('value',$value);
            $this->display("user/improve");
        }
    //      //动态自动验证表单信息
    //     $rules = array(
    //          array('verify','require','验证码必须！'), //默认情况下用正则进行验证
    //          array('userName','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
    //          //array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
    //          array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
    //          array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    //     );

    //     $index = D("user");
    //     if (!$index->validate($rules)->create()){
    //              // 如果创建失败 表示验证没有通过 输出错误提示信息
    //         exit($this->ajaxReturn($index->getError()));
    //         }else{
    //             // 验证通过 可以进行其他数据操作
    //         $userName = I("userName");
    //         if(strstr(I("userName"), '@')){
    //             $user['email'] = I("userName");
    //             $value = '手机号';
    //         }else if(strlen(I("userName")) == 11){
    //             $user['mobile'] = I("userName");
    //             $value = '邮箱';
    //         }else{
    //             echo "请输入正确的手机或邮箱！";exit;
    //         }
    //         $user["password"] = md5(I("password").C("PWD_KEY"));
    //         $user["regtime"] = date('Y-m-d H:i:s',time());
    //         $userInfo = $index->addUser($user);
    //         SESSION("user_id",$userInfo);
    //         $this->assign('value',$value);
    //         $this->display("user/improve");
    // 	}

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
}
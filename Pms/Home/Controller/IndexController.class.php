<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {

    public function index(){
         $this->display("login");
     }
	/**
	 *登陆
	 */
	public function login(){
            
            $index = D("user");
            $user['email'] = I("userName");
            $user['mobile'] = I("userName");
            $user['_logic'] = 'OR';
//            $filed = "login_err";
            //判断用户登录错误次数
            $error = $index->getUser($user);
            if($error['login_err'] >= 3){
               $code = I("code");
               if(!check_verify($code)){
                  echo "验证码错误";exit;
               }
            }
            echo 11;exit;
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $user["status"] = 1;//用户状态
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
	/**
	 * 注册
	 */
	public function register(){
            $index = D("user");
	    $userName = I("userName");
            if(strstr(I("userName"), '@')){
                $user['email'] = I("userName");
            }else if(strlen(I("userName")) == 11 and !I("userName")){
                $user['mobile'] = I("userName");
            }else{
                echo "请输入正确的手机或邮箱！";exit;
            }
	    $user["password"] = md5(I("password").C("PWD_KEY"));
	    $user["regtime"] = date('Y-m-d H:i:s',time());
	    $userInfo = $index->addUser($user);
            $this->display("user/improve");
	}
}
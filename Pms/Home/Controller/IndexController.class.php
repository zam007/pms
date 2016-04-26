<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    
    public function index(){
         $this->display("login");
     }
     
     /**
      * 用户登录
      */
     public function login(){
         $index = D("user");
         $info["user_name"] = I("userName");
         $filed = "login_err";
         //判断用户登录错误次数
         $error = $index->getUserField($user,$filed);
         if($error >= 3){
            $code = I("code");
            if(!check_verify($code)){
               echo "验证码错误";
            }else{
                echo "验证码正确"; 
            }
         }
         $user["user_name"] = I("userName");
         $user["password"] = md5(I("password").C("PWD_KEY"));
         $user["status"] = 1;//用户状态
         $user["flag"] = 1;
         $userInfo = $index->getUser($user);
         
         if($userInfo){
             $update["login_err"] = 0;
             $where["user_id"] = $userInfo["user_id"];
             $index->modify($where,$update);//清空登录错误次数
             SESSION("userName",$userInfo["user_name"]);
             echo "登录成功";
//             $this->display();
         }else{
             $update["login_err"] = $error+1;
             echo $index->modify($info,$update);
             echo "用户名或密码错误";
         }
     }
    
     /**
      * 图片验证码
      */
    Public function verify(){
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
    
}
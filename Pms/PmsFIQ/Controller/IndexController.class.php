<?php
namespace PmsFIQ\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display("login");
    }
    
    public function login(){
        $memberName = I("memberName");
        $pwd = I("pwd");
        $code = I("code");
        
        if(empty($memberName) or empty($pwd)){
            echo "用户名或密码不能为空";exit;
        }
        
        if(empty($code) ){
            echo "验证码不能为空";exit;
        }
        
        if(!check_verify($code)){
               echo "验证码错误";
            }else{
                echo "验证码正确"; 
            }
        $member = D("member");
        $info["member_name"] = $memberName;
        $info["pwd"] = md5(PWD_KEY.$pwd);
        $info["flag"] = 1;
        $memberInfo = $member->getMember($info);
        
        if(empty($memberInfo)){
            echo "用户名货密码错误";exit;
        }else{
            SESSION("id",$memberInfo["id"]);
            $this->display("User/userList");
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
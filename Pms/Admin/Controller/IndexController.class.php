<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display("login");
    }
    
    public function login(){
        $memberName = I("memberName");
        $pwd = I("pwd");
        $code = I("code");
        header("Content-type:text/html;charset=utf-8");
        if(empty($code) ){
            echo "验证码不能为空";exit;
        }
        
        // if(!check_verify($code)){
        //    echo "验证码错误";exit;
        // }
        $member = D("Member");
        $info["member_name"] = $memberName;
        $info["password"] = md5(PWD_KEY.$pwd);
        $info["flag"] = 1;
        $memberInfo = $member->getMember($info);
        if($memberInfo){
            SESSION("member_id",$memberInfo["member_id"]);
            $this->display("User/userList");
        }else{
            echo "用户名或密码错误";exit;
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
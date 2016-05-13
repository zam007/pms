<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends BaseController {
    
    public function improveHtml(){
        $this->display("improve");
    }
    /**
     * 完善资料
     */
    public function improve(){
        $userModel = D("user");
        $info['user_id'] = $this->userId;
        $user = $userModel->getUserField($info);
        $userName = I('userName');
        if(!$user['email']){
            if(strstr($userName, '@')){
                $info['email'] = $userName;
            }else{
                echo "请输入正确的邮箱";exit;
            }
        }else if(!$user['mobile']){
            if(strlen(trim ($userName)) == 11){
                $info['mobile'] = $userName;
            }else{
                echo "请输入正确的手机号";exit;
            }
        }
        $userModel->modify($info['user_id'],$user);
    }

    /**
     * 补全资料
     */
    public function completion(){
        
    }
}
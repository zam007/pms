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
    public function improve($username = '',$password = '',$repassword = '',$verify = ''){
        if (IS_POST) {

            /* 检测验证码 */
            // if(!check_verify($verify)){
            //     $this->error('验证码输入错误！');
            // }

            /* 检测验用户名是手机号码或者邮箱 */
            $index = D("user");
            /**
             * 检测之前输入的是邮箱还是手机号码
             */
            $userId = $this->userId;
            $info = $index->getUserField($userId,'mobile,email');
            /**
             * 如果手机号存在，则存入Email，反之，存为mobile
             */
            if ($info['email']) {
                if (strlen(I("username")) == 11) {
                    $user['mobile'] = I("username");
                } else {
                    echo "请输入正确的手机号码！";exit;
                }

            } else if ($info['mobile']) {
                if (strstr(I("username"), '@')) {
                    $user['email'] = I("username");
                } else {
                    echo "请输入正确的邮箱！"; exit;
                }

            }
            $userInfo = $index->modify($userId,$user);
            /**
             * 密码设置
             */
            $rules = array(
                 array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
                 array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
            );
            if (!$index->validate($rules)->create()) {
                //密码检验不通过，输出错误信息
                exit($this->ajaxReturn($index->getError()));
            }
            $user["password"] = md5(I("password").C("PWD_KEY"));
            $index->modify($userId,$user);
        }
    }

    /**
     * 补全资料
     */
    public function completion(){
            $index = D("user");
            $userId = $this->userId;
            $userInfo = $index->modify($userId,$user);
            $user["name"] = I("name");
            $user["work_id"] = I("work_id");
            $user["status"] = 1;
            echo $user["work_id"];
            echo $user["name"];
            $user["update_time"] = date('Y-m-d H:i:s',time());
            $index->modify($userId,$user);
    }
}
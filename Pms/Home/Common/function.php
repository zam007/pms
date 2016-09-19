<?php

    /** 
     * 验证码检查 
     */  
    function check_verify($code, $id = ""){  
        $verify = new \Think\Verify();  
        return $verify->check($code, $id);  
    }  
    
    /**
    * 邮件发送函数
    */
    function sendMail($to, $title, $content) {
     
        Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
    }
    
    /**
     * 发送手机验证码
     */
    function sendMobile($mobel){
        $uid=C("WINIC_UID");//分配给你的账号
        $pwd=C("WINIC_PWD") ;//密码
        $code = rand(100000,999999);
        saveCode($mobel,$code);
        $content="您本次的验证码是" .$code."【五行财商】";//短信内容
        $sendurl="http://service.winic.org/sys_port/gateway/?id=".$uid."&pwd=".$pwd."&to=".$mobel."&content=".$content."&time=";
        header("Content-type: text/html; charset=utf-8"); 
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $sendurl);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        print_r($data);
    }
    
    function saveCode($key,$code){
        $msgMode = M("msg");
        $msgMode->delete($key);
        $data = array(
            'msg_key'=>$key,
            'code'=>$code,
            'msg_time'=>time()
        );
        return $msgMode->add($data);
    }
    /**
     * 
     * @param type $key
     * @return type 获取验证码
     */
    function getCode($key){
        $msgMode = M("msg");
        return $msgMode->where("msg_key=".$key)->getField("code,msg_time");
    }
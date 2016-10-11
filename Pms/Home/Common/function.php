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
        
        return($mail->Send());
    }
    
    function mailCode($mail){
        $title = "五行财商邮箱验证码";
        $code = rand(100000,999999);
        saveCode($mail,$code);
        $msg="您本次的验证码是" .$code."【五行财商】";//短信内容
        snedMail($mail,$title,$mail);
    }
    
    /**
     * 发送手机验证码
     */
    function sendMobile($mobile){
        $url="http://service.winic.org:8009/sys_port/gateway/index.asp?";
    	$data = "id=%s&pwd=%s&to=%s&content=%s&time=";
    	$id = C("WINIC_UID");//分配给你的账号
    	$pwd = C("WINIC_PWD") ;//密码
        $code = rand(100000,999999);
        saveCode($mobel,$code);
        $msg="您本次的验证码是" .$code."【五行财商】";//短信内容
    	$content = iconv("UTF-8","GB2312",$msg);
    	$rdata = sprintf($data, $id, $pwd, $mobile, $content);
    	
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_POST,1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    	$result = curl_exec($ch);
    	curl_close($ch);
    	$result = substr($result,0,3);
        return $result;
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
<?php
namespace Home\Controller;
use Think\Controller;

class ExamController extends BaseController {
	
	/**
	 * 测试
	 */
    public function userExamController(){
    	$userId = $this->userId;
    	$userModel = D("user");
    	$user['user_id'] = $userId;
        $brith = $userModel->getUserField($user,'brith')['brith'];
        $leval = (time() - strtotime($brith));
    }
    
}
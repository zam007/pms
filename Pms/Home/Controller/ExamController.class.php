<?php
namespace Home\Controller;
use Think\Controller;
use Util\Util;

class ExamController extends BaseController {
	
	/**
	 * 测试
	 */
    public function userExamController(){
    	$userId = $this->userId;
    	$userModel = D("user");
    	$user['user_id'] = $userId;
        $brith = $userModel->getUserField($user,'brith')['brith'];
        //计算年龄
        $util = new Util();
        $age = $util->diffDate($brith, Date('Y-m-d',time()))['year'];
        
        $lavelMode = D("lavel");
        $classfy = $lavelMode->getLavel();
         
    }
    
}
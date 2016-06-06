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
        
        //获取基础难度
        $lavelMode = D("lavel");
        $where['min_age'] = array('egt',$age);
        $where['max_age'] = array('elt',$age);
        $filed = 'leavel_id';
        $leavel = $lavelMode->getLavel($where);
        
        //生成答卷,开始答题
         $examMode = D("exam");
         if($examMode->addSheet($leavel,$userId)){
         	answersController();
         }
    }
    
    //生成试题
    public function answersController(){
    	$userId = $this->userId;
    	$exam = M();
    }
    
}
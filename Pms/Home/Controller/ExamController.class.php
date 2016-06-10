<?php
namespace Home\Controller;
use Think\Controller;
use Util\Util;

class ExamController extends BaseController {
	
	public function __construct(){
		parent::__construct();
		$userId = $this->userId;
		$user = D('user');
		$status = $user->getUserField($userId,'answer');
	}
	/**
	 * 开始测试
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
         	answersController($userId);
         }
    }
    
    //生成试题
    public function answersController($userId){
    	//试题分类
    	$classifySheetMode = M('ClassifySheet');
    	$classifySheet = $classifySheetMode->generateClassifySheet($userId);
    	$questionHave = $classify['question'];
    	
    	
    	//获取同类试题
    	$questionMode = D('question');

    	$where['leavel_id'] = array('eq',$classifySheet['leavel_id']);
    	$where['difficult'] = array('eq',$classifySheet['difficulty']);
    	
    	$question = getQuestion($where);
    	//array_diff()比较数组，返回差集（只比较键值）。
    	
    }
    
}
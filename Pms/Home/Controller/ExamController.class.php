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
         	giveQuestion($userId);
         }
    }
    
    //生成试题
    public function giveQuestion($userId){
    	//试题分类
    	$classifySheetMode = M('classify_sheet');
    	$classifySheet = $classifySheetMode->generateClassifySheet($userId);
    	$questionHave = $classify['question'];
    	//获取同类试题
    	$questionMode = D('question');

    	$where['leavel_id'] = array('eq',$classifySheet['leavel_id']);
    	$where['difficult'] = array('eq',$classifySheet['difficulty']);
    	
    	$question = getQuestion($where);
    	if(!$question){
    		
    	}else{
    		$randQue = array_rand($question);
    		$queNow = array(
    			'question_id'=>$randQue['question_id'],
    			'class_id'=>$randQue['class_id'],
    			'question'=>$randQue['question'],
    			'level_id'=>$randQue['level_id'],
    			'difficulty'=>$randQue['difficulty']
    		);
    	}
    	//array_diff()比较数组，返回差集（只比较键值）。
    	
    }
    
    //答题
    public function answersController(){
    	$userId = $this->userId;
    	$question_id = I('session.question_id',0);
    	$answerSheetId = I('session.answer_sheet_id',0);
    	$classifySheetId = I('session.classify_sheet_id',0);
    	if($question_id){
    		return false;
    	}
    	$questionMode = D('question');
    	$where['question_id'] = $question_id;
    	$question = $questionMode->getQuestion($where,'question_id,level_id,difficulty');
    	
    	$answerMode = D('answer');
    	$where['selected'] = (int)$selected;
    	$answer = $answerMode->getAnser($where,'answer_id,score,inclination_id');
    	
    	$answerSheetMode = M('answer_sheet');
    	$answerSheet = $answerSheetMode->getAnswerSheet($answerSheetId);
    	
    	$classifySheetMode = M('ClassifySheet');
    	$classifySheet = $classifySheetMode->getClassifySheet($answerSheetId);
    	
    	$score = $classifySheet[''] - $answerSheet[''];
    	$sheetQuestion = array(
    		'question_id'=>$question['question_id'],
    		'level_id'=>$question['level_id'],
    		'difficulty'=>$question['difficulty'],
    		'answer_id'=>$answer['answer_id'],
    		'score'=>$answer['score'],
    		'inclination_id'=>$answer['inclination_id']
    	);
    }
    
}
<?php
namespace Home\Controller;
use Think\Controller;
use Util\Util;

class ExamController extends BaseController {
//	protected $answer = 0;
//	public function __construct(){
//		parent::__construct();
//		$userId = $this->userId;
//		
//		
//	}
	/**
	 * 开始测试
	 */
    public function userExam(){
    	$userId = $this->userId;
    	$userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,answer');
        if($user['answer'] == 1){
        	$this->answerQuestion();
        	die();
        }
        $brith = $user['birth'];
        //计算年龄
        $age = Date('Y',time())-substr($brith,0,4);
        //获取基础难度
        $levelMode = D("Level");
        $where['min_age'] = array('elt',$age);
        $where['max_age'] = array('egt',$age);
        $level = $levelMode->getlevel($where,'level_id,answer_num');
        //生成答卷,开始答题
         $examMode = D("exam");
         if($examMode->addSheet($level,$userId)){
         	$this->answerQuestion();
         	die();
         }else{
         	return false;
         }
    }
    
    //生成试题
    /**
     * 1、查询用户状态
     * 2、查询用户试卷
     * 3、生成试卷分类
     * 4、根据试卷分类获取同类型问题
     * 5、剔除已回答问题
     * 6、返回问题
     * Enter description here ...
     */
    public function answerQuestion(){
    	$userId = $this->userId;
        
    	$answerSheetMode = D('answerSheet');
    	
    	//查询用户试卷
    	$field = 'answer_sheet_id,answers,level_id';
    	$where = array(
    		'status'=>1,
    		'user_id'=>$userId
    	);
    	$answerSheet = $answerSheetMode->getAnswerSheet($where,$field);
    	if(!$answerSheet){
    		$this->success('未测试','../index/index',3);
    		die();
    	}
    	//查询用户分类试卷，满足条件 答题数量不等于规定答题数量
    	$classifySheetMode = D('classifySheet');
    	$where = array(
    		'answer_sheet_id' => array('eq',$answerSheet['answer_sheet_id']),
    		'answers'=>array('lt',$answerSheet['answers'])
    	);
    	$field = 'classify_sheet_id,answer_sheet_id,answers,question,difficulty,level_id';
    	$classifySheet = $classifySheetMode->generateClassifySheet($where,$field);
    	
    	if(!$classifySheet){
    		$userMode = D('user');
    		$userMode->modify($userId,array('answer'=>0));
    		$this->success('测试完成','../Exam/report',3);
    		die();
    	}
    	
    	$level = $classifySheet['level_id'];
    	$difficulty = $classifySheet['difficulty'];
    	$questionHave = json_decode($classifySheet['question'],true);
    	$sheetMode = D('sheet');
    	$questionHave = $sheetMode->getSheet();
    	//获取同类试题
    	$questionMode = D('Question');
    	$where['level_id'] = array('eq',$level);
    	$where['difficult'] = array('eq',$difficulty);
    	
    	
//    	//判断是否答过类X型题目题
//    	if($questionHave){
//	    	foreach($questionHave as $res){
//	    		if($res['level_id'] == $level and $res['difficulty'] == $difficulty){
//	    			$questionIds = $res['question_id'].',';
//	    		}
//	    	}
//	    	$questionIds = substr($questionIds,0,-1);
//	    	if($questionIds){
//	    		$where['question_id'] = array('not in',$questionIds);
//	    	}
//	    	$question = $questionMode->getQuestion($where);
//	    	$randQue = $question[array_rand($question)];
//	    	$questionHave[] = array(
//    			'question_id'=>$randQue['question_id'],
//    			'answer_id'=>0,
//    			'level_id'=>$randQue['level_id'],
//    			'difficulty'=>$randQue['difficulty'],
//    			'score'=>0,
//    			'inclination_id'=>''
//    		);
//	    	$queNow['question'] = json_encode($questionHave);
//    	}else{
//    		$question = $questionMode->getQuestion($where);
//    		$randQue = $question[array_rand($question)];
//    		$queNow['question'][] = array(
//    			'question_id'=>$randQue['question_id'],
//    			'answer_id'=>0,
//    			'level_id'=>$randQue['level_id'],
//    			'difficulty'=>$randQue['difficulty'],
//    			'score'=>0,
//    			'inclination_id'=>''
//    		);
//    		$queNow['question'] = json_encode($queNow['question']);
//    		
//    	}
    	$queNow['is_answer'] = 1;
    	$queNow['answers'] = $classifySheet['answers']+1;
    	$classifySheetMode->modify($classifySheet['classify_sheet_id'],$queNow);
    	$answerMode = D('answer');
    	$answerWhere['question_id'] =  $randQue['question_id'];
    	$answer = $answerMode->getAnswer($answerWhere);
    	$where = array(
    		'answer_sheet_id'=>$answerSheet['answer_sheet_id'],
    	);
    	$randQue['num'] = $classifySheetMode->sum($where,'answers');
    	//将答题信息存入session
    	SESSION('answer_sheet_id',$answerSheet['answer_sheet_id']);
    	SESSION('classify_sheet_id',$classifySheet['classify_sheet_id']);
    	SESSION('question_id',$answerWhere['question_id']);
    	$selected = array('A','B','C','D','E','F');
    	
    	$this->assign('selected',$selected);
        $this->assign('question',$randQue);
        $this->assign('answer',$answer);
        $this->display('exam/answer');
    	die();
    }
    
    //答题
    /**
     * 1、从session获取问题id
     * 2、根据提交答案计算得分
     * 3、保存数据
     * Enter description here ...
     */
    public function answers(){
    	$userId = $this->userId;
    	$question_id = I('session.question_id',0);
    	$answerSheetId = I('session.answer_sheet_id',0);
    	$classifySheetId = I('session.classify_sheet_id',0);
    	if(!$question_id){
    		return false;
    	}
        $userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,answer');
        if($user['answer'] != 1){
        	echo '没有答题';
        	die();
//        	$this->redirect('answerQuestion');
        }
    	//获取问题
    	$questionMode = D('Question');
    	$where['question_id'] = $question_id;
    	$question = $questionMode->getQuestionOne($where,'question_id,level_id,difficulty');
    	
    	//获取答案
    	$answerMode = D('Answer');
    	$where['answer_id'] = I('selected',0);
    	$answer = $answerMode->getAnswer($where,'answer_id,score,inclination_id');
    	
    	//获取试卷
    	$answerSheetMode = D('AnswerSheet');
    	$answerInfo['answer_sheet_id'] = array('eq',$answerSheetId);
    	$answerSheet = $answerSheetMode->getAnswerSheet($answerInfo);
    	
    	//获取分类试卷
    	$classifySheetMode = D('ClassifySheet');
    	$info['classify_sheet_id'] = $classifySheetId;
    	$classifySheet = $classifySheetMode->getClassifySheet($info);
    	
    	//计算得分
    	$levelMode = D('Level');
    	$leaveInfo['level_id'] = $question['level_id'];
    	$leaveQuestion = $levelMode->getLevel($leaveInfo,'sort');
    	
    	$leaveInfo['level_id'] = $answerSheet['level_id'];
    	$leaveBase = $levelMode->getLevel($leaveInfo,'sort');
    	
    	//得分计算公式-未完成
    	$score = (($leaveQuestion['sort']-1)*5+$question['difficulty']) - (($leaveBase['sort']-1)*5);
//    	if($score>0){
			
    		$score1 = $answer['score']*$score/3;
//    	}
    	//回答的问题
    	$questionHave = json_decode($classifySheet['question'],true);
    	$lenth = count($questionHave);
    	//当前真正回答的问题-默认为最后一题
    	$questionNow = $questionHave[$lenth-1];
    	if($questionNow['question_id'] == $question_id){
    		$questionHave[$lenth-1]['answer_id'] = $answer['answer_id'];
    		$questionHave[$lenth-1]['score'] = $score1;
    		$questionHave[$lenth-1]['inclination_id'] = $answer['inclination_id'];
    		$sheet['question'] = json_encode($questionHave);
    		//用户答题难度判断
    		if($answer['score']>3){
    			$sheet['correct'] ++;
    			if($classifySheet['correct']<=0){
    				$sheet['correct'] = 1;
    			}else{
    				$sheet['correct'] ++;
    			}
    		}else if($answer['score']<3){
    			if($classifySheet['correct']>=0){
    				$sheet['correct'] = -1;
    			}else{
    				$sheet['correct'] --;
    			}
    		}else{
    			$sheet['correct'] = 0;
    		}
    		//用户答题难度改变
    		if($sheet['correct']>=2){
    			$sheet['difficulty'] = $classifySheet['difficulty']+1;
    			if($sheet['difficulty']>=6){
    				$sort = $leaveQuestion['sort']+1;
    				$level = $levelMode->getLevel(array('score'=>$sort));
    				if($level){
    					$sheet['difficulty'] = 1;
    					$sheet['level_id'] = $level['level_id'];
    				}else{
    					$sheet['difficulty'] = 5;
    				}
    			}
    			
    		}else if($sheet['correct']<=-2){
    			$sheet['difficulty'] = $classifySheet['difficulty']-1;
    			if($sheet['difficulty']<=1){
    				$sort = $leaveQuestion['sort']-1;
    				$level = $levelMode->getLevel(array('score'=>$sort));
    				if($level){
    					$sheet['difficulty'] = 5;
    					$sheet['level_id'] = $level['level_id'];
    				}else{
    					$sheet['difficulty'] = 1;
    				}
    			}
    		}
    		//修改分类答卷
    		$sheet['answers'] = $classifySheet['answers'];
    		$sheet['score'] = $classifySheet['score']+$score1;
    		
    		if($classifySheetMode->modify($classifySheetId,$sheet)){
    			//修改答卷
    			$last = $answerSheet['last_time'];
    			$answerTime = $answerSheet['answer_time'];
    			$time = time()-strtotime($answerSheet['last_time']);
    			if($time > 90){
    				$time = 90;
    			}else if($time < 0){
    				$time = 0;
    			}
    			$res['last_time'] = date('Y-m-d H:i:s',time());
    			$res['answer_time'] = $answerTime+$time;
    			$answerSheetMode->modify($answerSheetId,$res);
    		}
    	}
    	$where = array(
    		'answer_sheet_id' => array('eq',$answerSheet['answer_sheet_id'])
    	);
    	$answerNum = $classifySheetMode->sum($where,'answers');
    	$count = $classifySheetMode->count($where);
    	//用户是否答完所有题目，如答完则计算总分，并保存到答卷
    	
    	if($answerSheet['answers'] * $count == $answerNum['answers']){
    		$score2 = $classifySheetMode->count($where,'score');
    		$info = array(
    			'status'=>2,
    			'score'=>$score2
    		);
    		$answerSheetMode->modify($answerSheetId,$info);
    		$userModel->modify($userId,array('answer'=>0));
    		$this->report();
    		die();
    	}
    	$this->answerQuestion();
    	die();
    }
    
    /**
     * 生成答题报告
     */
    public function report(){
    	echo "=======";die();
    	$userId = $this->userId;
    	$answerSheetId = I('answer_sheet_id');
    	
    	$where = array(
    		'user_id'=>$userId,
    		'answer_sheet_id'=>$answerSheetId
    	);
    	
    	$answerMode = D('answer');
    	//获取用户试卷
    	$answerSheet = getAnswerSheet($where);
    	if(!$answerSheet){
    		//未找到答卷
    		return false;
    	}
    	//分类答卷
    	$classifySheetMode = D('classify_sheet_id');
    	$where = array(
    		'answer_sheet_id'=>$answerSheetId
    	);
    	$classifySheet = getClassifySheet($where);
    	
    	$question = array();
    	foreach($classifySheet as $res){
    		//获取答题
    		$question = array_merge($question,$res['question']);
    		//实际得分
    		$score += $res['score'];
    		//偏向性统计
    		if(!$res['inclination_id']){
    			$inclinationIds[] = $res['inclination_id'];
    		}
    	}
    	$inclination = array_count_values($inclinationIds);
    	foreach($question as $res){
    		
    	}
    	
    }
    
}
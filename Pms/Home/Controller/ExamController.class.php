<?php
namespace Home\Controller;
use Think\Controller;
use Util\Util;

class ExamController extends BaseController {
	protected $answer = 0;
	public function __construct(){
		parent::__construct();
		$userId = $this->userId;
		$user = D('User');
		$answer = $user->getUserField($userId,'answer');
	}
	/**
	 * 开始测试
	 */
    public function userExamController(){
    	if($this->answer == 1){
    		$this->giveQuestion();
    	}
    	$userId = $this->userId;
    	$userModel = D("User");
    	$user['user_id'] = $userId;
        $brith = $userModel->getUserField($user,'brith')['brith'];
        //计算年龄
        $util = new Util();
        $age = $util->diffDate($brith, Date('Y-m-d',time()))['year'];
        
        //获取基础难度
        $lavelMode = D("Lavel");
        $where['min_age'] = array('egt',$age);
        $where['max_age'] = array('elt',$age);
        $filed = 'leavel_id';
        $leavel = $lavelMode->getLavel($where);
        
        //生成答卷,开始答题
         $examMode = D("Exam");
         if($examMode->addSheet($leavel,$userId)){
         	$this->giveQuestion();
         }else{
         	return false;
         }
    }
    
    //生成试题
    public function giveQuestion(){
    	$userId = $this->userId;
    	//试题分类
    	$classifySheetMode = D('ClassifySheet');
    	$classifySheet = $classifySheetMode->generateClassifySheet($userId);
    	$leavel = $classifySheet['leavel_id'];
    	$difficulty = $classifySheet['difficulty'];
    	$questionHave = $classify['question'];
    	//获取同类试题
    	$questionMode = D('Question');
    	
    	$where['leavel_id'] = array('eq',$leavel);
    	$where['difficult'] = array('eq',$difficulty);
    	
    	//判断是否答过类X型题目题
    	if(!$question){
	    	foreach($questionHave as $res){
	    		if($res['leavel_id'] == $leavel and $res['difficulty'] == $difficulty){
	    			$questionIds =$res['question_id'].',';
	    		}
	    	}
	    	$where['question_id'] = array('not in',substr($questionIds,0,-1));
	    	$question = $questionMode->getQuestion($where);
	    	$randQue = array_rand($question);
	    	$info[] = array(
    			'question_id'=>$randQue['question_id'],
    			'level_id'=>$randQue['level_id'],
    			'difficulty'=>$randQue['difficulty'],
    			'difficulty'=>$randQue['difficulty'],
    			'score'=>0,
    			'inclination_id'=>''
    		);
	    	$queNow['question'] = array_merge($questionHave,$info);
    	}else{
    		$question = $questionMode->getQuestion($where);
    		$randQue = array_rand($question);
    		$queNow['question'][] = array(
    			'question_id'=>$randQue['question_id'],
    			'level_id'=>$randQue['level_id'],
    			'difficulty'=>$randQue['difficulty'],
    			'difficulty'=>$randQue['difficulty'],
    			'score'=>0,
    			'inclination_id'=>''
    		);
    		
    	}
    	$queNow['is_answer'] = 1;
    	$classifySheetMode->modify($classifySheet['classify_id'],$queNow);
        $this->assign('question',$randQue);
        $this->redirect('exam/exam_question');
    	
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
    	//获取问题
    	$questionMode = D('Question');
    	$where['question_id'] = $question_id;
    	$question = $questionMode->getQuestion($where,'question_id,leavel_id,difficulty');
    	
    	//获取答案
    	$answerMode = D('Answer');
    	$where['selected'] = (int)$selected;
    	$answer = $answerMode->getAnser($where,'answer_id,score,inclination_id');
    	
    	//获取试卷
    	$answerSheetMode = M('answer_sheet');
    	$answerInfo['answer_sheet_id'] = array('eq',$answerSheetId);
    	$answerSheet = $answerSheetMode->getAnswerSheet($answerInfo);
    	
    	//获取分类试卷
    	$classifySheetMode = M('ClassifySheet');
    	$info['anwer_sheet_id'] = $answerSheetId;
    	$classifySheet = $classifySheetMode->getClassifySheet($info);
    	
    	//计算得分
    	$leavelMode = D('Leavel');
    	$leaveInfo['leavel_id'] = $question['leavel_id'];
    	$leaveQuestion = $leavelMode->getLeavel($leaveInfo,'sort');
    	
    	$leaveInfo['leavel_id'] = $answerSheet['leavel_id'];
    	$leaveBase = $leavelMode->getLeavel($leaveInfo,'sort');
    	
    	//得分计算公式-未完成
    	$score = (($leaveQuestion-1)*5+$question['difficulty']) - (($leaveBase-1)*5);
//    	if($score>0){
			
    		$score1 = $answer['score']*$score/3;
//    	}
    	
    	$questionHave = $classifySheet['question'];
    	$lenth = count($questionHave);
    	$questionNow = $questionHave[$lenth-1];
    	if($questionNow['question_id'] == $question_id){
    		$questionHave[$lenth-1]['answer_id'] = $answer['answer_id'];
    		$questionHave[$lenth-1]['score'] = $score1;
    		$questionHave[$lenth-1]['inclination_id'] = $answer['inclination_id'];
    		$sheet['question'] = $questionHave;
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
    				$leavel = $leavelMode->getLeavel(array('score'=>$sort));
    				if($leavel){
    					$sheet['difficulty'] = 1;
    					$sheet['leavel_id'] = $leavel['leavel_id'];
    				}else{
    					$sheet['difficulty'] = 5;
    				}
    			}
    			
    		}else if($sheet['correct']<=-2){
    			$sheet['difficulty'] = $classifySheet['difficulty']-1;
    			if($sheet['difficulty']<=1){
    				$sort = $leaveQuestion['sort']-1;
    				$leavel = $leavelMode->getLeavel(array('score'=>$sort));
    				if($leavel){
    					$sheet['difficulty'] = 5;
    					$sheet['leavel_id'] = $leavel['leavel_id'];
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
    			$answerMode->modify($answerSheetId,$res);
    		}
    	}
    	$this->giveQuestion();
    }
    
}
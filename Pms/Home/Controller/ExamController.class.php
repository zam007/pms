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
    public function userExam(){
    	if($this->answer == 1){
    		$this->giveQuestion();
    	}
    	$userId = $this->userId;
    	$userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,answer');
        if($user['answer'] == 1){
        	$this->redirect('answerQuestion');
        }
        $brith = $user['birth'];
        //计算年龄
        $age = Date('Y',time())-substr($brith,0,4);
        //获取基础难度
        $lavelMode = D("Lavel");
        $where['min_age'] = array('elt',$age);
        $where['max_age'] = array('egt',$age);
        $field = 'lavel_id';
        $lavel = $lavelMode->getLavel($where,$field);
        //生成答卷,开始答题
         $examMode = D("exam");
         if($examMode->addSheet($lavel['lavel_id'],$userId)){
         	 $this->redirect('answerQuestion');
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
        
    	$answerSheetMode = D('answer_sheet');
    	
    	//查询用户试卷
    	$field = 'answer_sheet_id,answers,lavel_id';
    	$where = array(
    		'status'=>1,
    		'user_id'=>$userId
    	);
    	$answerSheet = $answerSheetMode->getAnswerSheet($where,$field);
    	if(!$answerSheet){
    		return false;
    	}
    	//查询用户分类试卷
    	$classifySheetMode = M('classify_sheet');
    	$where = array(
    		'answer_sheet_id' => array('eq',$answerSheet['answer_sheet_id'])
    	);
    	$filed = 'answers';
    	$answerNum = $classifySheetMode->count($where,$filed);
    	$count = $classifySheetMode->count($where);
    	if($answerSheet['answers'] * $count >= $answerNum){
    		echo '答题已完成';
    		return false;
    	}
//    	if($answerSheet['answers'] * $count - $answerNum == 1){
//    		//最后一题
//    	}
    	
    	$where = array(
    		'answer_sheet_id'=>array('eq',$answerSheet['answer_sheet_id']),
    		'answers'=>array('lt',$count)
    	);
    	$field = 'classify_sheet_id,answer_sheet_id,answers,question,difficulty,lavel_id';
    	$classifySheet = $classifySheetMode->generateClassifySheet($where,$field);
    	
    	$lavel = $classifySheet['lavel_id'];
    	$difficulty = $classifySheet['difficulty'];
    	$questionHave = $classify['question'];
    	//获取同类试题
    	$questionMode = D('Question');
    	
    	$where['leavel_id'] = array('eq',$lavel);
    	$where['difficult'] = array('eq',$difficulty);
    	$question = $questionMode->getQuestion($where);
    	//判断是否答过类X型题目题
    	if(!$questionHave){
	    	foreach($questionHave as $res){
	    		if($res['leavel_id'] == $leavel and $res['difficulty'] == $difficulty){
	    			$questionIds =$res['question_id'].',';
	    		}
	    	}
	    	$where['question_id'] = array('not in',"'".substr($questionIds,0,-1)."'");
	    	$question = $questionMode->getQuestion($where);
	    	$randQue = $question[array_rand($question)];
	    	$info[] = array(
    			'question_id'=>$randQue['question_id'],
    			'level_id'=>$randQue['level_id'],
    			'difficulty'=>$randQue['difficulty'],
    			'score'=>0,
    			'inclination_id'=>''
    		);
	    	$queNow['question'] = array_merge($questionHave,$info);
    	}else{
    		$question = $questionMode->getQuestion($where);
    		$randQue = $question[array_rand($question)];
    		$queNow['question'][] = array(
    			'question_id'=>$randQue['question_id'],
    			'level_id'=>$randQue['level_id'],
    			'difficulty'=>$randQue['difficulty'],
    			'score'=>0,
    			'inclination_id'=>''
    		);
    		
    	}
    	$queNow['is_answer'] = 1;
    	$classifySheetMode->modify($classifySheet['classify_sheet_id'],$queNow);
    	$answerMode = D('answer');
    	$answerWhere['question_id'] =  array('eq',$randQue['question_id']);
    	$answer = $answerMode->getAnswer($answerWhere);
        $this->assign('question',$randQue);
        $this->assign('answer',$answer);
        $this->display('exam/exam_question');
    	
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
    	if($question_id){
    		return false;
    	}
        $userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,answer');
        if($user['answer'] != 1){
        	echo '没有答题';
//        	$this->redirect('answerQuestion');
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
    	//回答的问题
    	$questionHave = $classifySheet['question'];
    	$lenth = count($questionHave);
    	//当前真正回答的问题-默认为最后一题
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
    		$where = array(
    			'classify_sheet_id'=>$classifySheetId
    		);
    		if($classifySheetMode->modify($where,$sheet)){
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
    			$where = array(
    				'answer_sheet_id'=>$answerSheetId
    			);
    			$answerSheetMode->modify($where,$res);
    		}
    	}
    	$where = array(
    		'answer_sheet_id' => array('eq',$answerSheet['answer_sheet_id'])
    	);
    	$answerNum = $classifySheetMode->count($where,'answers');
    	$count = $classifySheetMode->count($where);
    	//用户是否答完所有题目，如答完则计算总分，并保存到答卷
    	if($answerSheet['answers'] * $count == $answerNum){
    		$score2 = $classifySheetMode->count($where,'score');
    		$info = array(
    			'status'=>2,
    			'score'=>$score2
    		);
    		$answerMode->modify($answerSheetId,$info);
    		$this->report();
    	}
    	$this->giveQuestion();
    }
    
    /**
     * 生成答题报告
     */
    public function report(){
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
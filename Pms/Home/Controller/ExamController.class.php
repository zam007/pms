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
    
    /**
     * 生成试题
     * 
     * 1、查询用户状态
     * 2、查询用户试卷
     * 3、生成试卷分类
     * 4、根据试卷分类获取同类型问题
     * 5、剔除已回答问题
     * 6、返回问题
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
    	$field = 'classify_sheet_id,answer_sheet_id,answers,difficulty,level_id,correct';
    	$classifySheet = $classifySheetMode->generateClassifySheet($where,$field);
    	
    	if(!$classifySheet){
    		$userMode = D('user');
    		$userMode->modify($userId,array('answer'=>0));
    		$this->success('测试完成','../Exam/report',3);
    		die();
    	}
    	
    	$level_id = $classifySheet['level_id'];
    	$difficulty = $classifySheet['difficulty'];
    	//已回答题目中该难度的题目
    	$sheetMode = D('sheet');
    	$where = array(
    		'classify_sheet_id' => $classifySheet['classify_sheet_id']
    	);
    	$field = 'sheet_id,question_id';
    	$questionHave = $sheetMode->getSheet($where,$field);
    	//获取同类试题
    	$questionMode = D('Question');
    	$where = array(
    		'level_id' => array('eq',$level_id),
    		'difficult' => array('eq',$difficulty)
    	);
    	//已答题中同类型难度的题目
    	if(!empty($questionHave)){
//    		$questionIds = array_column($questionHave, 'question_id');
    		foreach($questionHave as $res){
    			$questionIds[] = $res['question_id'];
    		}
    		$where['question_id'] = array('not in',join(',',$questionIds));
//    		$question = $questionMode->getQuestion($where);
    	}
    	$question = $questionMode->getQuestion($where);
    	$randQue = $question[array_rand($question)];
    	
    	$sheet = array(
    		'answer_sheet_id' => $answerSheet['answer_sheet_id'],
    		'classify_sheet_id' => $classifySheet['classify_sheet_id'],
    		'question_id' => $randQue['question_id'],
    	);
    	$sheetId = $sheetMode->saveSheet($sheet);
		
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
    	SESSION('sheet_id',$answerSheet['sheet_id']);
    	SESSION('classify_sheet_id',$classifySheet['classify_sheet_id']);
    	SESSION('question_id',$answerWhere['question_id']);
    	$selected = array('A','B','C','D','E','F');
    	
    	$this->assign('selected',$selected);
        $this->assign('question',$randQue);
        $this->assign('answer',$answer);
        $this->display('exam/answer');
    	die();
    }
    
    /**
     * 答题
     * 
     * 1、从session获取问题id
     * 2、根据提交答案计算得分
     * 3、保存数据
     */
    public function answers(){
    	$userId = $this->userId;
    	$question_id = I('session.question_id',0);
    	$answerSheetId = I('session.answer_sheet_id',0);
    	$classifySheetId = I('session.classify_sheet_id',0);
    	$sheetId = I('session.sheet_id',0);
    	$answerId = I('selected',0);
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
    	$where['answer_id'] = $answerId;
    	$answer = $answerMode->getAnswer($where,'answer_id,score,inclination_id');
    	
    	//获取试卷
    	$answerSheetMode = D('AnswerSheet');
    	$answerInfo['answer_sheet_id'] = array('eq',$answerSheetId);
    	$answerSheet = $answerSheetMode->getAnswerSheet($answerInfo);
    	
    	//获取分类试卷
    	$classifySheetMode = D('ClassifySheet');
    	$info['classify_sheet_id'] = $classifySheetId;
    	$classifySheet = $classifySheetMode->getClassifySheet($info);
    	$correct = $classifySheet['correct'];
    	
    	//获取答卷
    	$sheetMode = D('Sheet');
    	$info['sheet_id'] = $sheetId;
    	$sheet = $sheetMode->getSheet($info);
    	
    	//计算得分
    	$levelMode = D('Level');
    	$leaveInfo['level_id'] = $question['level_id'];
    	$leaveQuestion = $levelMode->getLevel($leaveInfo,'sort');
    	$answerScore = (int)$answer['score'];
    	
    	$leaveInfo['level_id'] = $answerSheet['level_id'];
    	$leaveBase = $levelMode->getLevel($leaveInfo,'sort');
    	
    	//得分计算公式-未完成
    	if(empty($leaveQuestion)){
    		$score = (($leaveQuestion['sort']-1)*5+$question['difficulty']) - (($leaveBase['sort']-1)*5);
    		$score1 = $answerScore*$score/3;
    	}else{
    		$score = 0;
    		$score1 = 0;
    	}
    	//修改答卷
    	$update = array(
    		'answer_id' => $answerId,
    		'score' => $score1,
    		'inclination_id' => $answerId,
    		'updatetime' => date('Y-m-d H:i:s',time()),
    		'is_answer' => 1,
    	);
    	$sheetMode->modify($sheetId,$updat);
    	//答题难度曲线
    	if($answerScore>3){
    		if($correct<=0){
    			$correct = 1;
    		}else{
    			$correct ++;
    		}
    	}else if($answerScore<3){
    		if($correct>=0){
    			$correct = -1;
    		}else{
    			$correct --;
    		}
    	}else{
    		$correct = 0;
    	}
    	//用户答题难度改变
    	$levelId = $classifySheet['level_id'];
    	if($correct>=2){
    		$difficulty = $classifySheet['difficulty']+1;
    		if($difficulty>=6){
    			$sort = $leaveQuestion['sort']+1;
    			$level = $levelMode->getLevel(array('sort'=>$sort));
    			if(empty($level)){
    				$difficulty = 1;
    				$levelId = $level['level_id'];
    			}else{
    				$difficulty = 5;
    			}
    		}
    	}else if($correct<=-2){
    		$difficulty = $classifySheet['difficulty']-1;
    		if($difficulty<=1){
    			$sort = $leaveQuestion['sort']-1;
    			$level = $levelMode->getLevel(array('sort'=>$sort));
    			if(empty($level)){
    				$difficulty = 5;
    				$levelId = $level['level_id'];
    			}else{
    				$difficulty = 1;
    			}
    		}
    	}
    	//修改分类答卷
    	$update = array(
    		'correct' => $correct,
    		'level_id' => $levelId,
    		'difficulty' => $difficulty,
    		'updated' => date('Y-m-d H:i:s',time()),
    	);
    	if($classifySheetMode->modify($classifySheetId,$update)){
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
    		$this->report($answerSheetId);
    		die();
    	}
    	$this->answerQuestion();
    	die();
    }
    /**
     * 查询答题报告
     */
    public function reportHtm(){
    	$answerSheetId = (int)I('answer_sheet_id');
    	if($answerSheetId > 0){
    		$this->report($answerSheetId);
    		die();
    	}else{
    		echo "不存在";
    		die();
    	}
    }
    /**
     * 生成答题报告
     *  1、根据答卷id获取答卷
     *  2、获取年龄组实际得分、应得分、平均得分和最高得分
     *  3、根据答卷id获取分类试卷
     *  4、获取年龄组大类试卷实际得分、应得分、平均分、最高得分、得分比
     *  4、获取年龄组小类试卷实际得分、应得分、平均分、得分比
     */
    private function report($answerSheetId){
    	$userId = $this->userId;
    	$where = array(
    		'user_id'=>$userId,
    		'answer_sheet_id'=>$answerSheetId
    	);
    	
    	$answerMode = D('answer');
    	//获取用户试卷
    	$answerSheet = getAnswerSheet($where);
    	if(!$answerSheet){
    		//未找到答卷
    		echo "未找到答卷";
    		return false;
    	}
        //难度分组
        $levelModel = D('level');
        $where = array(
            'level_id' => $answerSheet['level_id'],
        );
        $filed = "answer_num";
        $level = $levelModel->getLevel($where, $filed);
        $data['answer_num'] = $level['answer_num'];
        //实际得分
        $data['total_score'] = $answerSheet['score'];
        //平均分
        $data['avg_score'] = $answerMode->avgScore($answerSheet['level_id']);
        //最高分
        $data['max_score'] = $answerMode->maxScore($answerSheet['level_id']);
        
        $where = array(
            'level_id' => $answerSheet['level_id'],
            'level_id' => array('LT', $score),
        );
        $gtScore = $answerMode->countScore($where);
        $where = array(
            'level_id' => $answerSheet['level_id'],
        );
        $sumScore = $answerMode->countScore($where);
        $data['beat'] = rand($gtScore / $sumScore * 100, 2)."%";
    	$classifyMode = D('classify');
        //获取大类
    	$where = array(
    		'level' => 1,
    	);
        
    	$field = "classify_id,classify_name";
    	$classify = $classifyMode->getClassify($where, $field);
        foreach($classify as $res){
            $classifys[$res['classify_id']] = $res;
        }
    	//分类答卷
    	$classifySheetMode = D('classify_sheet');
    	$where = array(
    		'answer_sheet_id'=>$answerSheetId
    	);
    	$classifySheet = getClassifySheets($where);
    	foreach($classifySheet as $res){
                if($classifys[$res['father_id']] == $res['father_id']){
                    $classifys[$res['father_id']]['classify_sheet'][] = $res;
                }
    	}
        //基础得分
    	$data['basic_score'] = count($classifySheet) * $data['answer_num'] * 3;
        //得分率
        $data['probability_score'] = round($data['total_score'] / $data['basic_score'], 2)."%";
       
    	
    	$sheetMode = D('sheet');
    	$field = "question_id,answer_id,inclination_id,score";
    	$sheet = $sheetMode->getSheetAll($answerSheetId);
    	foreach($sheet as $res){
    		$questionIds[] = $res['question_id'];
    		if((int)$res['answer_id'] != 0){
    			$answerIds[] = $res['answer_id'];
    			$inclinationIds[] = $res['inclination_id'];
    		}
    	}
    	
    	
    	
    	$data = array(
    		'totle_score' => $totleScore,
    		'totle' => $totle,
    		'max_score' => $max,
    		'avg_score' => $avgScore,
    		'classify' => array(
    			
    		)
    	
    	);
    }
    
    
}
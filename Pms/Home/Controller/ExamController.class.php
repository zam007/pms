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
    
    public function confirm(){
    	$userId = $this->userId;
        $userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,from_add,ompany_id,work_id,answer,team_id');
        if(empty($user['birth'])){
            return "完善资料";die();
        }
        $from = C('from');
        $user['from_add'] = $from[$user['from_add']];
        $work = $userModel->getWrok($user['work_id'],'name');
        $user['work'] = $work['name'];
        
        $teamId = (int)$user['team_id'];
        if($teamId != 0){
            $team = D('Team')->getTeamField($teamId,'team_name,nature,attribute');
            $nature = C('nature');
            $attribute = C('attribute');
            $team['nature'] =$nature[$team['nature']];
            $team['attribute'] =$attribute[$team['attribute']];
            $team['sum'] = D('Team')->count($teamId);
        }
        $this->assign('team',$team);
        $this->assign('user',$user);
        $this->display('Exam/info_confirm');
    	die();
    }
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
        //生成订单
        $order = D('order');
        $orderDate = array(
            'user_id' => $userId,
            'order_type' => 1,
            'order_time' => date('Y-m-d H:i:s', time()),
            'order_cost' => 0,
            'is_pay' => 1
        );
        $order->addOrder($orderDate);
        //生成答卷,开始答题
         $examMode = D("exam");
         $sheet_id = $examMode->addSheet($level,$userId);
         if($sheet_id){
            SESSION('sheet_id',$sheet_id);echo  $sheet_id;
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
    public function answer1Question(){
        $userId = $this->userId;
        $answerSheetMode = D('answerSheet');
        $answerSheetId = I('session.sheet_id',0);
        //查询用户试卷
        $field = 'answer_sheet_id,answers,level_id';
        $where = array(
            'status'=>1,
            'user_id'=>$userId,
            'answer_sheet_id'=>$answerSheetId
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
        $max = $answerSheet['answers'] * $classifySheetMode->count(array('answer_sheet_id' => $answerSheet['answer_sheet_id']));
        if(!$classifySheet){
            $userMode = D('user');
            $userMode->modify($userId,array('answer'=>0));
                $this->success('答题报告','report_htm?answer_sheet_id='.$answerSheet['answer_sheet_id']);
            die();
        }
        
        $level_id = $classifySheet['level_id'];
        $difficulty = $classifySheet['difficulty'];
        //获取同类试题
        $questionMode = D('Question');
        $where = array(
            'level_id' => array('eq',$level_id),
            'difficult' => array('eq',$difficulty)
        );
        //已答题中同类型难度的题目
        if(!empty($questionHave)){
//          $questionIds = array_column($questionHave, 'question_id');
            foreach($questionHave as $res){
                $questionIds[] = $res['question_id'];
            }
            $where['question_id'] = array('not in',join(',',$classifySheet['quesitons']));
//          $question = $questionMode->getQuestion($where);
        }
        $question = $questionMode->getQuestion($where);
        $randQue = $question[array_rand($question)];
        
        //保存正在答题
        $questionIds = $classifySheet['questions'].$randQue['question_id'].',';
        $update =array(
                'question' => $questionIds,
        );
        echo $classifySheetMode->modify($classifySheet['classify_sheet_id'], $update);exit;
        
        $queNow['is_answer'] = 1;
        $queNow['answers'] = $classifySheet['answers']+1;
        $classifySheetMode->modify($classifySheet['classify_sheet_id'],$queNow);
        $answerMode = D('answer');
        $answerWhere['question_id'] =  $randQue['question_id'];
        $answer = $answerMode->getAnswer($answerWhere);
        $where = array(
            'answer_sheet_id' => $answerSheetId,
        );
        $num = $classifySheetMode->sum($where, 'answers');print_r($num);
        SESSION('answer_sheet_id',$answerSheet['answer_sheet_id']);
        SESSION('classify_sheet_id', $classifySheet['classify_sheet_id']);
        SESSION('question_id',$randQue['question_id']);
        
        $this->answer($randQue, $answer, $max);
        die();
    }

    /**
     * 跳转答题页面
     * @param type $question 问题
     * @param type $answer  答案
     * @param type $sheetId 答卷id
     */
    private function answer($question, $answer, $max, $num){
        $answerSheetId = I('session.answer_sheet_id',0);
        $selected = array('A','B','C','D','E','F','G','H');
        
        $this->assign('max',$max);
        $this->assign('num',$num);
        $this->assign('selected',$selected);
        $this->assign('question',$question);
        $this->assign('answer',$answer);
        $this->display('exam/answer');
        die();
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
    	$max = $answerSheet['answers'] * $classifySheetMode->count(array('answer_sheet_id' => $answerSheet['answer_sheet_id']));
    	if(!$classifySheet){
    		$userMode = D('user');
    		$userMode->modify($userId,array('answer'=>0));
                $this->success('答题报告','report_htm?answer_sheet_id='.$answerSheet['answer_sheet_id']);
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
//    	$randQue['num'] = $classifySheetMode->sum($where,'answers');
    	 //将答题信息存入session
    	SESSION('answer_sheet_id',$answerSheet['answer_sheet_id']);
    	SESSION('sheet_id',$sheetId);
    	SESSION('classify_sheet_id', $classifySheet['classify_sheet_id']);
    	SESSION('question_id',$randQue['question_id']);
        
        $this->answer($randQue, $answer, $max);
    	die();
    }
    /**
     * 继续答题
     * 说明：用户未填写答案提交或刷新页面，答题时间还未用完时使用
     */
    private function answerGo(){
    	$questionId = I('session.question_id',0);
        $answerSheetId = I('session.answer_sheet_id',0);
        
        $questionMode = D('question');
        $answerMode = D('answer');
        $answerSheetMode = D('answerSheet');
        $classifySheetMode = D('classifySheet');
        
        $question = $questionMode->getQuestionOne(array('question_id' => $questionId));
        $answer = $answerMode->getAnswer(array('question_id' => $questionId));
        $answerSheet = $answerSheetMode->getAnswerSheet(array('answer_sheet_id' => $answerSheetId),'answers,answer_sheet_id');
        $max = $answerSheet['answers'] * $classifySheetMode->count(array('answer_sheet_id' => $answerSheet['answer_sheet_id']));
        $this->answer($question, $answer, $max);
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
    	(int)$answerId = I('selected',0);
        
        $now = C('NOW');
        $date = date('Y-m-d H:i:s',$now);
        
    	if($question_id == 0){
    		return "没有答题1";
    	}
        $userModel = D("User");
        $user = $userModel->getUserField($userId,'birth,answer');
        if($user['answer'] != 1){
        	echo '没有答题';
        	die();
        }
        $questionMode = D('Question');
        $answerMode = D('Answer');
        $classifySheetMode = D('ClassifySheet');
        $answerSheetMode = D('AnswerSheet');
        $levelMode = D('Level');
        
        //获取问题
    	$where['question_id'] = $question_id;
    	$question = $questionMode->getQuestionOne($where,'question_id,level_id,play_time,difficulty');
        
        //获取试卷
    	
    	$answerInfo['answer_sheet_id'] = $answerSheetId;
    	$answerSheet = $answerSheetMode->getAnswerSheet($answerInfo);
        
        if($answerId == 0){
            $t = C('NOW');
            $answerTime = C('ANSWER_TIME');
            $time =  $answerTime + strtotime($answerSheet['last_time']) + $question['play_time'] - $t;
            //如果用户未填写答案且答题时间超过10s，择让用户继续作答
            if($time >= 10){
                $this->answerGo($time);
            }
        }
    	
    	//获取答案
    	
    	$where = array('answer_id' => $answerId);
    	$answer = $answerMode->getAnswer($where,'answer_id,score,inclination_id');
    	$answer = $answer[0];
    	
    	//获取分类试卷
    	
    	$info['classify_sheet_id'] = $classifySheetId;
    	$classifySheet = $classifySheetMode->getClassifySheet($info);
    	$correct = $classifySheet['correct'];
    	
    	//获取答卷
    	$sheetMode = D('Sheet');
    	$info['sheet_id'] = $sheetId;
    	$sheet = $sheetMode->getSheet($info);
    	
    	//计算得分
    	
    	$leaveInfo['level_id'] = $question['level_id'];
    	$leaveQuestion = $levelMode->getLevel($leaveInfo,'sort');
    	$answerScore = (int)$answer['score'];
    	
    	$leaveInfo['level_id'] = $answerSheet['level_id'];
    	$leaveBase = $levelMode->getLevel($leaveInfo,'sort');
    	
    	//得分计算公式-未完成
    	if($leaveQuestion){
    		$score = (($leaveQuestion['sort']-1)*5+$question['difficulty']) - (($leaveBase['sort']-1)*5);
    		$score1 = $answerScore*$score/3;
    	}else{
    		$score = 0;
    		$score1 = 0;
    	}
    	
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
        }else{
            $difficulty = $classifySheet['difficulty'];
        }
        //修改答卷
    	$update = array(
    		'answer_id' => $answerId,
    		'score' => $score1,
    		'inclination_id' => $answerId,
    		'updatetime' => $date,
    		'is_answer' => 1,
    	);
    	$sheetMode->modify($sheetId,$update);
    	//修改分类答卷
    	$update = array(
    		'correct' => $correct,
    		'level_id' => $levelId,
    		'difficulty' => $difficulty,
    		'updated' => $date,
    	);
    	if($classifySheetMode->modify($classifySheetId,$update)){
            //修改答卷
            $answerTime = $answerSheet['answer_time'];
            $time = $now - strtotime($answerSheet['last_time']);
            if($time > 90){
                    $time = 90;
            }else if($time < 0){
                    $time = 0;
            }
            $res['last_time'] = $date;
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
//    		$this->report($answerSheetId);
                $this->success('操作完成','report_htm?answer_sheet_id='.$answerSheetId);
    		die();
    	}
    	$this->answerQuestion();
    	die();
    }
    /**
     * 查询答题报告
     */
    public function report_htm(){
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
            'answer_sheet_id' => $answerSheetId,
            'user_id' => $userId
    	);
    	$answerSheetMode = D('answer_sheet');
    	//获取用户试卷
        $answerSheet = $answerSheetMode->getAnswerSheet($where);
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
        $data['avg_score'] = $answerSheetMode->avgScore($answerSheet['level_id']);
        //标准差
        $data['SD_score'] = $data['total_score'] - $data['answer_num']*3*5;
        //相对得分
        $data['relative_score'] = $data['total_score'] - $data['avg_score'];
        //最高分
        $data['max_score'] = $answerSheetMode->maxScore($answerSheet['level_id']);
        
        $where = array(
            'level_id' => $answerSheet['level_id'],
            'level_id' => array('LT', $score),
        );
        $gtScore = $answerSheetMode->countScore($where);
        $where = array(
            'level_id' => $answerSheet['level_id'],
        );
        $sumScore = $answerSheetMode->countScore($where);
        $data['beat'] = rand($gtScore / $sumScore * 100, 2)."%";
    	$classifyMode = D('classify');
        //获取大类
    	$where = array(
    		'level' => 1,
    	);
        
    	$field = "classify_id,classify_name, en";
    	$classify = $classifyMode->getClassify($where, $field);
        foreach($classify as $r){
            $classifys[$r['classify_id']] = $r;
        }
    	//分类答卷
    	$classifySheetMode = D('classify_sheet');
    	$where = array(
    		'answer_sheet_id'=>$answerSheetId
    	);
        $field = "classify_sheet.classify_id,classify.classify_name,classify_sheet.score,classify.father_id";
    	$classifySheet = $classifySheetMode->getClassifySheets($where);
    	foreach($classifySheet as $key=>$res){
                if($classifys[$res['father_id']]['classify_id'] == $res['father_id']){
                    //总答题数
                    $val['answer_num'] = $data['answer_num'];
                    $classifys[$res['father_id']]['count_answer'] += $data['answer_num'];
                    //基础分
                    $val['basic_score'] = $data['answer_num'] * 3;
                    $classifys[$res['father_id']]['basic_score'] += $res['basic_score'];
                    //实际得分
                    $val['total_score'] = $res['score'];
                    $classifys[$res['father_id']]['total_score'] += $res['score'];
                    //得分率
                    $val['probability_score'] = round($val['total_score'] / $data['answer_num'] * 5, 2)*100;
                    $classifys[$res['father_id']]['probability_score'] = round($classifys[$res['father_id']]['total_score']/($data['answer_num']*5))*100;
                    //年龄组平均得分
                    $where = array(
                        'classify_id' => $res['classify_id'],
                        'answer_sheet.level_id' => $answerSheet['level_id']
                    );
                    $val['avg_score'] = round($classifySheetMode->avgScore($where),2);
                    $classifys[$res['father_id']]['avg_score'] += $val['avg_score'];
                    //相对得分
                    $val['relative_score'] = $val['total_score'] - $val['avg_score'];
                    //标准差
                    $classifys[$res['father_id']]['SD_score'] = $val['total_score'] - $data['answer_num']*3;
                    
                    $classifys[$res['father_id']]['relative_score'] += $val['relative_score'];
                    $classifys[$res['father_id']]['classify'][] = $val;
                    //数组重新排序
                    $classifys[$res['father_id']]['classify'] = $this->my_sort($classifys[$res['father_id']]['classify'],'probability_score',SORT_DESC,SORT_NUMERIC);

                }
    	}
        //基础得分
    	$data['basic_score'] = count($classifySheet) * $data['answer_num'] * 5;
        //得分率
        $data['probability_score'] = round($data['total_score'] / $data['basic_score'], 2)."%";
       
    	
    	$sheetMode = D('sheet');
    	$field = "inclination,inclination.inclination_id";
    	$sheet = $sheetMode->getInclination($answerSheetId,$field);
        $inclination = array();
    	foreach($sheet as $res){
            if($inclination[$res['inclination_id']]['inclination_id'] != $res['inclination_id']){
                $inclination[$res['inclination_id']]['count'] ++;
                $inclination[$res['inclination_id']]['inclination'] = $res['inclination'];
            }
    	}
        //总体分析
        $data['analysis'] = "总体分析";
        //结果说明
        $data['result'] = "结果说明";
        $this->assign('classifys',$classifys);
        $this->assign('data',$data); 
        $this->display("Exam/report");
    	die();
    }
    
    
    
    public function ajaxExam(){
        $answerSheetId = 147;I('answer_sheet_id');
        $classifySheetMode = D('classify_sheet');
        $classifyMode = D('classify');
    	$answerSheetMode = D('answer_sheet');
        $levelModel = D('level');
        
        
        $answerNum = $level['answer_num'];
        $where = array(
            'answer_sheet_id' => $answerSheetId
    	);
    	//获取用户试卷
        $answerSheet = $answerSheetMode->getAnswerSheet($where);
        
        $where = array(
            'level_id' => $answerSheet['level_id'],
        );
        $filed = "answer_num";
        $level = $levelModel->getLevel($where, $filed);
        
        //获取大类
    	$where = array(
    		'level' => 1,
    	);
    	$field = "classify_id, classify_name, en";
    	$classify = $classifyMode->getClassify($where, $field);
        foreach($classify as $r){
            $classifys[$r['classify_id']] = $r;
        }
        $where = array(
    		'answer_sheet_id'=>$answerSheetId
    	);
        $field = "classify_sheet.classify_id,classify.classify_name,classify_sheet.score,classify.father_id";
    	$classifySheet = $classifySheetMode->getClassifySheets($where);
        $relative = array();
    	foreach($classifySheet as $key=>$res){
                if($classifys[$res['father_id']]['classify_id'] == $res['father_id']){
                    //年龄组平均得分
                    $where = array(
                        'classify_id' => $res['classify_id'],
                        'answer_sheet.level_id' => $answerSheet['level_id']
                    );
                    $val['avg_score'] = round($classifySheetMode->avgScore($where),2);
                    $classifys[$res['father_id']]['avg_score'] += $val['avg_score'];
                    //实际得分
                    $val['total_score'] = (int)$res['score'];
                    $classifys[$res['father_id']]['total_score'] += $res['score'];
                    
                    //得分率
                    $val['probability_score'] = round($val['total_score'] / $answerNum * 5, 2)*100;
                    $classifys[$res['father_id']]['probability_score'] = round($classifys[$res['father_id']]['total_score']/($answerNum*5) )*100;
                    
                    $val['name'] = $res['classify_name'];
                    $classifys[$res['father_id']]['classify'][] = $val;
                    //相对得分
                    $relative[] = array(
                        'name' => $res['classify_name'],
                        'score' => $val['total_score'] - $val['avg_score']
                    );
                    $relative = $this->my_sort($relative,'score',SORT_DESC,SORT_NUMERIC);
                    unset($val);
                    
                }
    	}
        $data['classifys'] = $classifys;
        $data['relative'] = $relative;
        echo json_encode($data);
    }
    
    function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC ){   
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    }  
    
}
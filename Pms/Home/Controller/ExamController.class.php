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
         $examMode = D("Exam");
         $examId = $examMode->add($level,$userId);
         if($examId){
            SESSION('exam_id',$examId);
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
        $examMode = D('Exam');
        $examId = I('session.exam_id',0);
        //查询用户试卷
        $field = 'exam_id,answers,level_id';
        $where = array(
            'status'=>1,
            'user_id'=>$userId,
            'exam_id'=>$examId
        );
        $exam = $examMode->getExam($where,$field);
        if(!$exam){
            $this->success('未测试','../Index/index',3);
            die();
        }
        //查询用户分类试卷，满足条件 答题数量不等于规定答题数量
        $sheetMode = D('sheet');
        $where = array(
            'exam_id' => array('eq',$exam['exam_id']),
            'answers'=>array('lt',$exam['answers'])
        );
        $field = 'sheet_id,exam_id,answers,difficulty,level_id,correct';
        $sheet = $sheetMode->generateSheet($where,$field);
        $max = $exam['answers'] * $sheetMode->count(array('exam_id' => $exam['exam_id']));
        if(!$sheet){
            $userMode = D('user');
            $userMode->modify($userId,array('answer'=>0));
            $this->success('答题报告','report_htm?exam_id='.$exam['exam_id']);
            die();
        }
        
        $level_id = $sheet['level_id'];
        $difficulty = $sheet['difficulty'];
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
            $where['question_id'] = array('not in',join(',',$sheet['quesitons']));
//          $question = $questionMode->getQuestion($where);
        }
        $question = $questionMode->getQuestion($where);
        $randQue = $question[array_rand($question)];
        
        //保存正在答题
        $questionIds = $sheet['questions'].$randQue['question_id'].',';
        $update =array(
                'question' => $questionIds,
        );
        
        
        $queNow['is_answer'] = 1;
        $queNow['answers'] = $sheet['answers']+1;
        $sheetMode->modify($sheet['sheet_id'],$queNow);
        $answerMode = D('answer');
        $answerWhere['question_id'] =  $randQue['question_id'];
        $answer = $answerMode->getAnswer($answerWhere);
        
        SESSION('exam_id',$exam['exam_id']);
        SESSION('sheet_id', $sheet['sheet_id']);
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
    private function answer($question, $answer, $max){
        $examId = I('session.exam_id',0);
        $selected = array('A','B','C','D','E','F','G','H');
        //已答题数量
        $sheetLogModel = D(sheetLog);
        $num = $sheetLogModel->count(array('exam_id' => $examId));
        $this->assign('max',$max);
        $this->assign('num',$num+1);
        $this->assign('selected',$selected);
        $this->assign('question',$question);
        $this->assign('answer',$answer);
        $this->display('Exam/answer');
        die();
    }
    
    /**
     * 继续答题
     * 说明：用户未填写答案提交或刷新页面，答题时间还未用完时使用
     */
    private function answerGo(){
    	$questionId = I('session.question_id',0);
        $examId = I('session.exam_id',0);
        
        $questionMode = D('question');
        $answerMode = D('answer');
        $examMode = D('exam');
        $sheetMode = D('sheet');
        
        $question = $questionMode->getQuestionOne(array('question_id' => $questionId));
        $answer = $answerMode->getAnswer(array('question_id' => $questionId));
        $exam = $examMode->getExam(array('exam_id' => $examId),'answers,exam_id');
        $max = $exam['answers'] * $sheetMode->count(array('exam_id' => $exam['exam_id']));
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
        $questionId = I('session.question_id',0);
        $examId = I('session.exam_id',0);
        $sheetId = I('session.sheet_id',0);
        (int)$answerId = I('selected',0);
        
        $now = C('NOW');
        $date = date('Y-m-d H:i:s',$now);
        
        if($questionId == 0){
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
        $sheetMode = D('sheet');
        $examMode = D('exam');
        $levelMode = D('Level');
        
        //获取问题
        $where['question_id'] = $questionId;
        $question = $questionMode->getQuestionOne($where,'question_id,level_id,play_time,difficulty');
        
        //获取试卷
        
        $answerInfo['exam_id'] = $examId;
        $exam = $examMode->getExam($answerInfo);
        
        if($answerId == 0){
            $t = C('NOW');
            $answerTime = C('ANSWER_TIME');
            $time =  $answerTime + strtotime($exam['last_time']) + $question['play_time'] - $t;
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
        
        $info['sheet_id'] = $sheetId;
        $sheet = $sheetMode->getSheet($info);
        $correct = $sheet['correct'];
        
        
        //计算得分
        
        $leaveInfo['level_id'] = $question['level_id'];
        $leaveQuestion = $levelMode->getLevel($leaveInfo,'sort');
        $answerScore = (int)$answer['score'];
        
        $leaveInfo['level_id'] = $exam['level_id'];
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
        $levelId = $sheet['level_id'];
        if($correct>=2){
            $difficulty = $sheet['difficulty']+1;
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
            $difficulty = $sheet['difficulty']-1;
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
            $difficulty = $sheet['difficulty'];
        }
        //答题日志
        $sheetLog = array(
            'question_id' => $questionId,
            'answer_id' => $answerId,
            'sheet_id' => $sheetId,
            'father_id' => $sheet['father_id'],
            'classify_id' => $sheet['classify_id'],
            'exam_id' => $examId,
            'score' => $score1,
            'inclination_id' => $answer['inclination_id'],
            'updatetime' => $date,
        );
        // $sheetLogMode = D('SheetLog');
        // $sheetLogMode->add($sheetLog);
        //修改分类答卷
        $where = array(
            'sheet_id' => $sheetId,
        );
        $sheetLogMode = D('SheetLog');
        $sheetScore = $sheetLogMode->sum($where,'score');
        $update = array(
            'correct' => $correct,
            'level_id' => $levelId,
            'difficulty' => $difficulty,
            'updated' => $date,
            'score' => (int)$sheetScore,
        );
        // if($sheetMode->modify($sheetId,$update)){
        //     //修改答卷
            
        //     $examMode->modify($examId,$res);
        // }
        $answerTime = $exam['answer_time'];
        $time = $now - strtotime($exam['last_time']);
        if($time > 90){
                $time = 90;
        }else if($time < 0){
                $time = 0;
        }
        $where = array(
            'exam_id' => $examId,
        );
        $examScore = $sheetLogMode->sum($where,'score');
        $res['last_time'] = $date;
        $res['answer_time'] = $answerTime+$time;
        $res = array(
            'last_time' => (string)$date,
            'answer_time' => (string)$answerTime+$time,
            'score' => (int)$examScore,
        );
        if(!$examMode->answer($examId,$sheetId,$sheetLog,$update,$res)){
            echo $examId;exit;
        }
        
        $answerNum = $sheetMode->sum($exam['exam_id'],'answers');
        $count = $sheetMode->count($where);
        //用户是否答完所有题目，如答完则计算总分，并保存到答卷
        
        if($exam['answers'] * $count == $answerNum['answers']){
            $score2 = $sheetMode->count($where,'score');
            $info = array(
                'status'=>2,
                'score'=>$score2
            );
            $examMode->modify($examId,$info);
            $userModel->modify($userId,array('answer'=>0));
//          $this->report($examId);
                $this->success('操作完成','report_htm?exam_id='.$examId);
            die();
        }
        $this->answerQuestion();
        die();
    }

    /**
     * 查询答题报告
     */
    public function report_htm(){
    	$examId = (int)I('exam_id');
    	if($examId > 0){
    		$this->report($examId);
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
    private function report($examId){
        
    	$userId = $this->userId;
    	$where = array(
            'exam_id' => $examId,
            'user_id' => $userId
    	);
    	$examMode = D('exam');
    	//获取用户试卷
        $exam = $examMode->getExam($where);
    	if(!$exam){
    		//未找到答卷
    		echo "未找到答卷";
    		return false;
    	}
        //难度分组
        $levelModel = D('level');
        $where = array(
            'level_id' => $exam['level_id'],
        );
        $filed = "answer_num";
        $level = $levelModel->getLevel($where, $filed);
        $data['answer_num'] = $level['answer_num'];
        //实际得分
        $data['total_score'] = $exam['score'];
        //平均分
        $data['avg_score'] = $examMode->avgScore($exam['level_id']);
        //标准差
        $data['SD_score'] = $data['total_score'] - $data['answer_num']*3*5;
        //相对得分
        $data['relative_score'] = $data['total_score'] - $data['avg_score'];
        //最高分
        $data['max_score'] = $examMode->maxScore($exam['level_id']);
        
        $where = array(
            'level_id' => $exam['level_id'],
            'level_id' => array('LT', $score),
        );
        $gtScore = $examMode->countScore($where);
        $where = array(
            'level_id' => $exam['level_id'],
        );
        $sumScore = $examMode->countScore($where);
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
    	$sheetMode = D('sheet');
    	$where = array(
    		'exam_id'=>$examId
    	);
        $field = "sheet.classify_id,classify.classify_name,sheet.score,classify.father_id";
    	$sheet = $sheetMode->getSheets($where);
    	foreach($sheet as $key=>$res){
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
                        'exam.level_id' => $exam['level_id']
                    );
                    $val['avg_score'] = round($sheetMode->avgScore($where),2);
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
    	$data['basic_score'] = count($sheet) * $data['answer_num'] * 5;
        //得分率
        $data['probability_score'] = round($data['total_score'] / $data['basic_score'], 2)."%";
       
    	
    	$sheetLogMode = D('sheetLog');
    	$field = "inclination,inclination.inclination_id";
    	$sheetLog = $sheetLogMode->getInclination($examId,$field);
        $inclination = array();
    	foreach($sheetLog as $res){
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
        $examId = I('exam_id');
        $sheetMode = D('sheet');
        $classifyMode = D('classify');
    	$examMode = D('exam');
        $levelModel = D('level');
        
        
        $answerNum = $level['answer_num'];
        $where = array(
            'exam_id' => $examId
    	);
    	//获取用户试卷
        $exam = $examMode->getExam($where);
        
        $where = array(
            'level_id' => $exam['level_id'],
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
    		'exam_id'=>$examId
    	);
        $field = "sheet.classify_id,classify.classify_name,sheet.score,classify.father_id";
    	$sheet = $sheetMode->getSheets($where);
        $relative = array();
    	foreach($sheet as $key=>$res){
                if($classifys[$res['father_id']]['classify_id'] == $res['father_id']){
                    //年龄组平均得分
                    $where = array(
                        'classify_id' => $res['classify_id'],
                        'exam.level_id' => $exam['level_id']
                    );
                    $val['avg_score'] = round($sheetMode->avgScore($where),2);
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
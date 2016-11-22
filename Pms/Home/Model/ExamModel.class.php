<?php
namespace Home\Model;
use Think\Model;
class ExamModel extends Model {
    protected $tablePrefix = '';
//    protected $tableName = 'exam';
    Protected $autoCheckFields = false;
    // public function __construct() {
    //     parent::__construct();
    //     $this->$autoCheckFields;
    // }
    /**
     * 生成试卷
     */
    private function getM(){
        return M("exam");
    }
    public function add($level,$userId){
    	$exam = $this->getM();
    	$exam->startTrans();
    	$examInfo = array(
            'user_id' => $userId,
            'level_id' => $level['level_id'],
            'answers' => $level['answer_num'],
            'difficulty' => 3,
            'relative_difficulty' => 3,
            'start_time' => date('Y-m-d H:i:s',time())
    	);
    	$examId = $exam->add($examInfo);
    	if(!$examId){
            $exam->rollback();
            return false;
    	}
    	//查询问题分类
    	$classify = M("classify");
    	$classification = $classify->field('classify_id,father_id')->where('father_id != 0 and flag=1')->select();
    	if(!$classification){
            $sheet->rollback();
            return false;
    	}
    	//添加分类试卷
    	$sheet = M("sheet");
    	foreach($classification as $res){
            $info = array(
                'exam_id' => $examId,
                'classify_id' => $res['classify_id'],
                'father_id' => $res['father_id'],
                'level_id' => $level['level_id'],
                'difficulty' => 3
            );
            if(!$sheet->add($info)){
                $exam->rollback();
                return false;
            }
    	}
    	//修改用户状态
    	$user = M("user");
    	$userInfo = array(
            'answer'=>1,
            'update_time'=>date('Y-m-d H:i:s',time())
    	);
    	if(!$user->where('user_id='.$userId)->save($userInfo)){
            $exam->rollback();
            return false;
    	}
    	$exam->commit();
    	return $examId;
    }

    public function getExam($info,$filed = '*') {
        $exam = $this->getM();
        $info['flag'] = 1;
        return $exam->order('exam_id desc')->field($filed)->where($info)->find();
    }
    
    public function modify($examId,$update){
        $exam = $this->getM();
        return $exam->where('exam_id='.$examId)->save($update);
        // $i = $exam->where('exam_id='.$examId)->save($update);
        // echo $exam->getlastsql();echo $i; return $i;
    }
    
    public function avgScore($levelId){
        $exam = $this->getM();
        return $exam->where('level_id='.$levelId)->avg('score');
    }
    
    public function maxScore($levelId){
        $exam = $this->getM();
        return $exam->where('level_id='.$levelId)->max('score');
    }
    
    public function countScore($where){
        $exam = $this->getM();
        return $exam->where($where)->count();
    }
    public function record($userId,$type = 1,$page = 1){
        if((int)$page <= 1){
            $page = 1;
        }
        $exam = $this->getM();
        $pageMin = ($page-1)*20;
        $pageMax = $page*20;
        $data['count'] = $exam->where('type='.$type)->count();
        $data['page_max'] = ceil($data['count']/20);
        $where = array(
            'type' => $type,
            'status' => 2,
        );
        $data['data'] = $exam->where($where)->order('exam_id desc')->limit($pageMin,$pageMax)->select();
        return $data;
    }

    public function answer($examId,$sheetId,$sheetLog,$sheet,$res){
        $exam = $this->getM();
        $exam->startTrans();
        //答题日志
        $sheetLogMode = D('SheetLog');
        $logId = $sheetLogMode->add($sheetLog);
        if($logId < 0 ){
            $exam->rollback();
            return false;
        }
        //修改分类答卷
        $sheetMode = D('Sheet');
        $sheetMode->modify($sheetId,$sheet);
         
        //修改答卷
        if(!$this->modify($examId,$res)){
            $exam->rollback();
            return false;
        }
        $exam->commit();
        return true;
    }
}
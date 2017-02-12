<?php
namespace Admin\Model;
use Think\Model;
class ExamModel extends Model {
    protected $tablePrefix = '';
    Protected $autoCheckFields = false;

    public function __construct() {
        parent::__construct();
        $this->$autoCheckFields;
    }

    public function getAnswer($ids){
        $Model = M("Answer");
        $cond['question_id']=array('in',$ids);
        return $Model->where($cond)->select();
    }

    public function getIncl($ids){
        $Model = M("inclination"); 
        $cond['inclination_id']=array('in',$ids);
        return $Model->where($cond)->select();
    }

    public function inclList(){
        $Model = M("inclination"); 
        $cond['flag'] = 1;
        return $Model->where($cond)->select();
    }
    
    public function lvList(){
        $Model = M("level");
        $cond['flag'] = 1;
        return $Model->where($cond)->select();
    }

    public function getClassily(){
        $Model = M("classify"); 
        $cond['flag'] = 1;
        $cond['level'] = 1;
        $classify = $Model->where($cond)->select();
        $classify = array_column($classify,null,'classify_id');

        $cond['level'] = 2;
        $cl = $Model->where($cond)->select();
        foreach($cl as $info){
            $classify[$info['father_id']]['cla'][] = $info;
        }
        return $classify;
    }

    public function classifyList(){
        $Model = M("classify"); 
        $cond['flag'] = 1;
        $cond['level'] = 2;
        $classify = $Model->where($cond)->select();
        return array_column($classify,null,'classify_id');
    }

    public function saveQuestion($data){
        $ansModel = M('answer');
        $queModel = M('question');
        $queModel->startTrans();
        $question = $data['question'];
        $info = [
            'question_id'=>$question['question'],
            'level_id'=>$question['level'],
            'difficulty'=>$question['dif'],
            'classify_id'=>$question['classify'],
        ];
        $questionId = $queModel->add($info);
        if(!$questionId){
            $queModel->rollback();
            return false;
        }
        foreach($data['answer'] as $ans){
            $answer = [
                'question_id'=>$questionId,
                'answer'=>$ans['ans'],
                'inclination_id'=>$ans['inc'],
                'score'=>$ans['score'],
            ];
            if(!$ansModel->add($answer)){
                $queModel->rollback();
                return false;
            }
        }
        $queModel->commit();
        return true;
    }
}
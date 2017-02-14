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

    public function saveInclination($title){
        $m = M('inclination');
        $info = [
            'inclination'=>$title,
        ];
        return $m->add($info);

    }

    public function importQuestion($list, $levelId){
        $classify = $this->classifyList();
        $classify = array_column($classify,null,'classify_name');
        $inclination = $this->inclList();
        $inclination = array_column($inclination,null,'inclination');

        $questionMode = M('question');
        $answerModel = M('answer');
        foreach($list as $key=>$que){
        $questionMode->startTrans();
            $question = [
                'question'=>$que['E'],
                'level_id'=>$levelId,
                'difficulty'=>$que['D'],
                'classify_id'=>$classify[$que['C']]['classify_id'],
            ];
            $questionId = $questionMode->add($question);
            if($questionId === false){
                $questionMode->rollback();
                return $key;
            }
            $title = $que['N'];
            // var_dump($inclination[$que['N1']]) ;
            if(!isset($inclination[$title])){
                $id = $this->saveInclination($title);
                if($id !== false){
                    $inclination[$title] = [
                        'inclination'=>$title,
                        'inclination_id'=>$id,
                    ];
                }else{
                    $questionMode->rollback();
                    return $key;
                }
                
            }
            $answer[] = [
                'question_id'=>$questionId,
                'answer'=>$que['F'],
                'inclination_id'=>$inclination[$que['N']]['inclination_id'],
                'score'=>$que['J'],
            ];
            $title = $que['O'];
            // var_dump($inclination[$que['N1']]) ;
            if(!isset($inclination[$title])){
                $id = $this->saveInclination($title);
                if($id !== false){
                    $inclination[$title] = [
                        'inclination'=>$title,
                        'inclination_id'=>$id,
                    ];
                }else{ 
                    $questionMode->rollback();
                    return $key;
                }
                
            }
            $answer[] = [
                'question_id'=>$questionId,
                'answer'=>$que['G'],
                'inclination_id'=>$inclination[$que['O']]['inclination_id'],
                'score'=>$que['K'],
            ];$title = $que['P'];
            // var_dump($inclination[$que['N1']]) ;
            if(!isset($inclination[$title])){
                $id = $this->saveInclination($title);
                if($id !== false){
                    $inclination[$title] = [
                        'inclination'=>$title,
                        'inclination_id'=>$id,
                    ];
                }else{ 
                    $questionMode->rollback();
                    return $key;
                }
                
            }
            $answer[] = [
                'question_id'=>$questionId,
                'answer'=>$que['H'],
                'inclination_id'=>$inclination[$que['P']]['inclination_id'],
                'score'=>$que['L'],
            ];$title = $que['P'];
            // var_dump($inclination[$que['N1']]) ;
            if(!isset($inclination[$title])){
                $id = $this->saveInclination($title);
                if($id !== false){
                    $inclination[$title] = [
                        'inclination'=>$title,
                        'inclination_id'=>$id,
                    ];
                }else{ 
                    $questionMode->rollback();
                    return $key;
                }
                
            }
            $answer[] = [
                'question_id'=>$questionId,
                'answer'=>$que['I'],
                'inclination_id'=>$inclination[$que['Q']]['inclination_id'],
                'score'=>$que['M'],
            ];
            if($answerModel->addAll($answer) === false){ 
                $questionMode->rollback();
                return $key;
            }
            $questionMode->commit();
            unset($answer);
        }

        return true;
    }
}
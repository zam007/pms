<?php
namespace Home\Model;
use Think\Model;
class AnswerSheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;

    public function getAnswerSheet($info,$filed = '*') {
        $answerSheet = M("answer_sheet"); 
        $info['flag'] = 1;
        return $answerSheet->order('start_time desc')->field($filed)->where($info)->find();
    }
    
    public function modify($answerSheetId,$update){
        $answerSheet = M("answer_sheet"); 
        return $answerSheet->where('answer_sheet_id='.$answerSheetId)->save($update);
    }
}
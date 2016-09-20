<?php
namespace Home\Model;
use Think\Model;
class AnswerSheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;

    public function getAnswerSheet($info,$filed = '*') {
        $answerSheet = M("answer_sheet"); 
        $info['flag'] = 1;
        return $answerSheet->field($filed)->where($info)->find();
    }
    
    public function modify($answerSheetId,$update){
        $answerSheet = M("answer_sheet"); 
        return $answerSheet->where('answer_sheet_id='.$answerSheetId)->save($update);
    }
    
    public function avgScore($levelId){
        $answerSheet = M("answer_sheet"); 
        return $answerSheet->where('level_id='.$levelId)->avg('score');
    }
    
    public function maxScore($levelId){
        $answerSheet = M("answer_sheet"); 
        return $answerSheet->where('level_id='.$levelId)->max('score');
    }
    
    public function countScore($where){
        $answerSheet = M("answer_sheet");
        return $answerSheet->where($where)->count();
    }
    public function record($userId,$type = 1,$page = 1){
        if((int)$page <= 1){
            $page = 1;
        }
        $answerSheet = M("answer_sheet");
        $pageMin = ($page-1)*20;
        $pageMax = $page*20;
        $data['count'] = $answerSheet->where('type='.$type)->count();
        $data['page_max'] = ceil($data['count']/20);
        $where = array(
            'type' => $type,
            'status' => 2,
        );
        $data['data'] = $answerSheet->where($where)->order('answer_sheet_id desc')->limit($pageMin,$pageMax)->select();
        return $data;
    }
}
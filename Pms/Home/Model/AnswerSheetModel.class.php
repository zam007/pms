<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
	

    public function getAnswerSheet($info,$filed = '*') {
        $user = M("answer_sheet"); 
        $userInfo = $user->filed($filed)->where($info)->find();
        return $userInfo;
    }
    
    public function modify($answerSheetId,$update){
        $info = M("classify_sheet"); 
        return $info->where('answer_sheet_id='.$answerSheetId)->save($update);
    }
}
<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    //查询试卷
    public function addSheet($leavel,$userId){
    	$answerSheet = M("answer_sheet");
    	$answer = $answerSheet->where('is_over=0 and user_id='.$userId)->find('sheet_id,answers');
    	
    	$classifySheet = M('classify_sheet');
    	$answerNum = $answerSheet->where('sheet_id='.$answer['sheet_id'])->count('answers');
    	$count = $answerSheet->count();
    	if($sheet['answers']*$count <= $answerNum){
    		return false;
    	}
    	
    	$classify
    }

}
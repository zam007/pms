<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    //生成答卷类型
    public function generateClassifySheet($userId){
    	//查询用户试卷
    	$answerSheet = M("answer_sheet");
    	$answer = $answerSheet->field('answer_sheet_id,answers,lavel_id')->where('is_over=0 and user_id='.$userId)->find();
    	if(!$answer){
    		return false;
    	}
    	//查询用户分类试卷
    	$classifySheet = M('classify_sheet');
    	$answerNum = $classifySheet->where('answer_sheet_id='.$answer['answer_sheet_id'])->count();
    	$count = $classifySheet->count();
    	if($answer['answers']*$count >= $answerNum){
    		return false;
    	}
    	$where['answer_sheet_id'] = array('eq',$answer['answer_sheet_id']);
    	$where['answers'] = array('lt',$count);
    	$classify = $classifySheet->field('classify_sheet_id,answer_sheet_id,answers,question,difficulty')->where($where)->select();
    	
    	return $classify[array_rand($classify)];//随机数组
    }

	public function getClassifySheet($info,$filed = '*') {
        $info = M("classify_sheet"); 
        return $info->field($filed)->where($info)->find();
    }
    
    public function modify( $classifySheetId,$update){
        $info = M("classify_sheet"); 
        return $info->where('classify_sheet_id='.$classifySheetId)->save($update);
    }
}
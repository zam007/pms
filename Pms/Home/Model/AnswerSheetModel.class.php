<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
	//生成试题
    public function generateClassifySheet($classify,$userId){
    	//查询用户试卷
    	$answerSheet = M("answer_sheet");
    	$answer = $answerSheet->where('is_over=0 and user_id='.$userId)->find('sheet_id,answers,leavel_id');
    	if(!$answer){
    		return false;
    	}
    	//查询用户分类试卷
    	$classifySheet = M('classify_sheet');
    	$answerNum = $classifySheet->where('sheet_id='.$answer['sheet_id'])->count('answers');
    	$count = $classifySheet->count();
    	if($sheet['answers']*$count <= $answerNum){
    		return false;
    	}
    	$where['sheet_id'] = array('eq',$answer['sheet_id']);
    	$where['answers'] = array('lt',$count);
    	$classify = $classifySheet->where($where)->select('classify_sheet_id,answers,question,difficulty');
    	return array_rand($classify);//随机数组
    }

}
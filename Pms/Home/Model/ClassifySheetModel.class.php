<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    
    public function generateClassifySheet($where,$field= '*'){
    	$classifySheet = M("classify_sheet"); 
    	$where['flag'] = array('eq',1); 
    	$classify = $classifySheet->field($field)->where($where)->select();
    	return $classify[array_rand($classify)];//随机数组
    }

	public function getClassifySheet($info,$field = '*') {
        $classifySheet = M("classify_sheet"); 
    	$where['flag'] = array('eq',1); 
        return $classifySheet->field($field)->where($info)->find();
    }

	public function getClassifySheets($info,$field = '*') {
        $classifySheet = M("classify_sheet"); 
    	$where['flag'] = array('eq',1); 
        return $classifySheet->field($field)->where($info)->find();
    }
    
    public function modify( $classifySheetId,$update){
        $classifySheet = M("classify_sheet");
    	$where['flag'] = array('eq',1); 
        return $classifySheet->where('classify_sheet_id='.$classifySheetId)->save($update); 
    }
    
    public function sum($where,$field = '*'){
    	$classifySheet = M('classify_sheet');
    	$where['flag'] = array('eq',1); 
    	return $classifySheet->where($where)->sum($field);
    }
    
    public function count($where,$field = '*'){
    	$classifySheet = M('classify_sheet');
    	$where['flag'] = array('eq',1); 
    	return $classifySheet->where($where)->count();
    }
    
    public function avgScore($where){
        $classifySheet = M('classify_sheet');
        return $answerSheet->join('answer_sheet on answer_sheet.answer_sheet_id = classify_sheet.answer_sheet_id')->where($where)->avg('score');
    }
}
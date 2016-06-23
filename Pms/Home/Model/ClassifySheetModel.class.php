<?php
namespace Home\Model;
use Think\Model;
class ClassifySheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    //生成答卷类型
    
    public function generateClassifySheet($answerSheetId,$count,$field= '*'){
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
}
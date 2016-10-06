<?php
namespace Home\Model;
use Think\Model;
class SheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    
	public function getSheet($info,$field = '*') {
        $sheet = M("sheet"); 
        return $sheet->field($field)->where($info)->find();
    }
    
    public function saveSheet($data){
        $sheet = M("sheet"); 
        return $sheet->add($data);
    }
    
    public function modify( $sheetId,$update){
        $sheet = M("sheet"); 
        return $sheet->where('sheet_id='.$sheetId)->save($update); 
//        $sheet->where('sheet_id='.$sheetId)->save($update); 
//        echo $sheet->getlastsql();exit;
    }
    
    public function sum($where,$field = '*'){
    	$sheet = M('sheet');
    	return $sheet->where($where)->sum($field);
    }
    
    public function count($where,$field = '*'){
    	$sheet = M('sheet');
    	return $sheet->where($where)->count();
    }
    /**
     * 获取试卷全部答题
     * return array 
     */
    public function getSheetAll($answerSheetId,$field = '*'){
    	$sheet = M("sheet"); 
        return $sheet->field($field)->where('answer_sheet_id='.$answerSheetId)->select();
    }
    
    public function getInclination($answerSheetId,$field = '*'){
        $sheet = M("sheet"); 
        return $sheet->field($field)->join('inclination on inclination.inclination_id = sheet.inclination_id')->where('answer_sheet_id='.$answerSheetId)->select();
    }
}






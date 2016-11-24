<?php
namespace Home\Model;
use Think\Model;
class SheetLogModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    private function getM(){
        return M("sheet_log"); 
    }
    
	public function getSheetLog($info,$field = '*') {
        $sheet = $this->getM(); 
        return $sheet->field($field)->where($info)->find();
    }
    
    public function save($data){
        $sheet = $this->getM(); 
        return $sheet->add($data);
    }
    
    public function modify( $sheetId,$update){
        $sheet = $this->getM(); 
        return $sheet->where('sheet_id='.$sheetId)->save($update); 
    }
    
    public function sum($where,$field = '*'){
        $sheet = $this->getM(); 
    	return $sheet->where($where)->sum($field);
    }
    
    public function count($where){
        $sheet = $this->getM(); 
    	return $sheet->where($where)->count();
    }
    /**
     * 获取试卷全部答题
     * return array 
     */
    public function getSheetAll($examId,$field = '*'){
        $sheet = $this->getM(); 
        return $sheet->field($field)->where('exam_id='.$examId)->select();
    }
    
    public function getInclination($examId,$field = '*'){
        $sheet = $this->getM(); 
        return $sheet->field($field)->join('inclination on inclination.inclination_id = sheet_log.inclination_id')->where('exam_id='.$examId)->select();
    }
}






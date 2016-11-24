<?php
namespace Home\Model;
use Think\Model;
class sheetModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    private function getM(){
        return M("sheet"); 
    }
    
    public function generateSheet($where,$field = '*'){
    	$sheet = $this->getM(); 
    	$where['flag'] = array('eq',1); 
    	$classify = $sheet->field($field)->where($where)->select();
    	return $classify[array_rand($classify)];//随机数组
    }

    public function getSheet($info,$field = '*') {
        $sheet = $this->getM(); 
    	$info['flag'] = array('eq',1); 
        return $sheet->field($field)->where($info)->find();
    }

    public function getSheets($info,$field = '*') {
        $sheet = $this->getM(); 
    	$info['flag'] = array('eq',1); 
        return $sheet->alias('a')->field($field)->join('left join classify as b on b.classify_id = a.classify_id')->where($info)->select();
    }
    
    public function modify( $sheetId,$update){
        $sheet = $this->getM(); 
        return $sheet->where('sheet_id='.$sheetId)->save($update); 
    }
    
    public function sum($examId,$field = '*'){
    	$sheet = $this->getM();
    	return $sheet->where('exam_id = '.$examId)->sum($field);
    }
    
    public function count($where){
    	$sheet = $this->getM(); 
    	$where['flag'] = array('eq',1); 
    	return $sheet->where($where)->count();
    }
    
    public function avgScore($where){
        $sheet = $this->getM(); 
        return $sheet->join('exam on exam.exam_id = sheet.exam_id')->where($where)->avg('sheet.score');
    }

    
}
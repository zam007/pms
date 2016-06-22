<?php
namespace Home\Model;
use Think\Model;
class AnswerModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getAnswer($where,$filed = "*") {
        $answer = M("answer");
    	$where['flag'] = array('eq',1); 
        return $answer->field($filed)->where($where)->select();
    }
    
	public function count($where,$fleld = '*'){
    	$answer = M('answer');
    	$where['flag'] = array('eq',1); 
    	return $classifySheet->where($where)->count($fleld);
    }
}
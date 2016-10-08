<?php
namespace Home\Model;
use Think\Model;
class ClassifyModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    


    public function getClassify($info,$field = '*') {
        $classifySheet = M("classify"); 
    	$where['flag'] = array('eq',1); 
        return $classifySheet->field($field)->where($info)->select();
    }

}
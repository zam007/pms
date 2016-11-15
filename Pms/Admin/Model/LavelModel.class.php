<?php
namespace PmsFIQ\Model;
use Think\Model;
class LavelModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    
    public function getLavel($info) {
        $lavel = M("lavel"); 
        $data = $lavel->where($info)->select('class_num,answer_num');
        return $data;
    }


}
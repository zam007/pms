<?php
namespace Home\Model;
use Think\Model;
class AnswerModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getAnswer($where,$filed = "*") {
        $answer = M("answer");
        return $answer->field($filed)->where($where)->select();
    }

}
<?php
namespace Home\Model;
use Think\Model;
class QuestionModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getQuestion($where,$filed = '*') {
        $question = M("question");
        return $question->filed($filed)->where($where)->find();
    }

}
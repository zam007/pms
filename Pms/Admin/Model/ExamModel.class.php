<?php
namespace Admin\Model;
use Think\Model;
class ExamModel extends Model {
    protected $tablePrefix = '';
    Protected $autoCheckFields = false;
    public function __construct() {
        parent::__construct();
        $this->$autoCheckFields;
    }
    public function getAnswer($ids){
        $Model = M("Answer"); // 实例化User对象
        $cond['question_id']=array('in',$ids);
        return $Model->where($cond)->select();
    }
    public function getIncl($ids){
        $Model = M("inclination"); // 实例化User对象
        $cond['inclination_id']=array('in',$ids);
        return $Model->where($cond)->select();
    }

}
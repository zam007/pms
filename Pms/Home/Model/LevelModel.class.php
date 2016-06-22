<?php
namespace Home\Model;
use Think\Model;
class LevelModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getLevel($where,$filed = '*') {
        $lavel = M("Level");
        return $lavel->field($filed)->where($where)->find();
    }

}
<?php
namespace Home\Model;
use Think\Model;
class LevelModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;

    public function getLevel($where,$filed = '*') {
        $level = M("Level");
        return $level->field($filed)->where($where)->find();
    }

}
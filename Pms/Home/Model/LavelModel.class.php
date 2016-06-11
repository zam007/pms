<?php
namespace Home\Model;
use Think\Model;
class LavelModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getLavel($where,$filed = '*') {
        $lavel = M("Lavel");
        return $lavel->filed($filed)->where($where)->find();
    }

}
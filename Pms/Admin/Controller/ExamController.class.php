<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
class ExamController extends BaseController {
    public function examList() {
        $m = M('Question');      
        $where = array(
            'flag' => 1,
            );
        $count = $m->where($where)->count();
        $p = getpage($count);
        $list = $m->field(true)->where($where)->limit($p->firstRow, $p->listRows)->select();

        $questions = array_column($list,'question_id');
        $ids = join($questions,',');
        $examModel = D('Exam');
        $answers = $examModel->getAnswer($ids);

        $inclIds = join(array_unique(array_column($answers,'inclination_id')),',');
        $incl = $examModel->getIncl($inclIds);

        $list = array_column($list,null,'question_id');
        foreach($answers as $ans){
            $list[$ans['question_id']]['answers'][] = $ans;
        }
        
        $this->assign('list', $list); // 赋值数据集
        $this->assign('incl', $incl); 
        $this->assign('page', $p->show()); // 赋值分页输出
        $this->display('guanlishiti');
    }
}
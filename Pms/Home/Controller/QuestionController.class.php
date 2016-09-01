<?php
namespace Home\Controller;
use Think\Controller;

class QuestionController extends Controller {

    public function getQuestionController($leavelId,$difficult){
	$question = D('question');
        $where['leavel_id'] = $leavelId;
        $where['difficult'] = $difficult;
        return $question->getQuestion($where);
    }

}
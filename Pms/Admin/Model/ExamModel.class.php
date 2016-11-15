<?php
namespace Home\Model;
use Think\Model;
class ExamModel extends Model {
    protected $tablePrefix = '';
    Protected $autoCheckFields = false;
    public function __construct() {
        parent::__construct();
        $this->$autoCheckFields;
    }
    /**
     * 生成试卷
     */
    public function addSheet($level,$userId){
    	$sheet = M("answer_sheet");
    	$sheet->startTrans();
    	$sheetInfo = array(
            'user_id' => $userId,
            'level_id' => $level['level_id'],
            'answers' => $level['answer_num'],
            'difficulty' => 3,
            'relative_difficulty' => 3,
            'start_time' => date('Y-m-d H:i:s',time())
    	);
    	$sheetId = $sheet->add($sheetInfo);
    	if(!$sheetId){
            $sheet->rollback();
            return false;
    	}
    	//查询问题分类
    	$classify = M("classify");
    	$classification = $classify->field('classify_id')->where('level=2 and flag=1')->select();
    	if(!$classification){
            $sheet->rollback();
            return false;
    	}
    	//添加分类试卷
    	$classifySheet = M("classify_sheet");
    	foreach($classification as $res){
            $info = array(
                'answer_sheet_id' => $sheetId,
                'classify_id' => $res['classify_id'],
                'level_id' => $level['level_id'],
                'difficulty' => 3
            );
            if(!$classifySheet->add($info)){
                $sheet->rollback();
                return false;
            }
    	}
    	//修改用户状态
    	$user = M("user");
    	$userInfo = array(
            'answer'=>1,
            'update_time'=>date('Y-m-d H:i:s',time())
    	);
    	if(!$user->where('user_id='.$userId)->save($userInfo)){
            $sheet->rollback();
            return false;
    	}
    	$sheet->commit();
    	return true;
    }
}
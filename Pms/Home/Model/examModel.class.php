<?php
namespace Home\Model;
use Think\Model;
class ExamModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    //生成试卷
    public function addSheet($leavel,$userId){
    	$sheet = M("answer_sheet");
    	$sheet->startTrans();
    	$sheetInfo = array(
    		'user_id' => $userId,
    		'leavel_id' => $leavel,
    		'start_time' => date('Y-m-d H:i:s',time())
    	);
    	$sheet_id = $sheet->add($sheetInfo);
    	if(!$id){
    		$sheet->rollback();
    		return false;
    	}
    	$classify = M("classify");
    	$classification = $classify->where('lavel=2 and flag=1')->find('classify_id');
    	if(!$classification){
    		$sheet->rollback();
    		return false;
    	}
    	$classifySheet = M("classify_sheet");
    	foreach($classification as $res){
    		$info = array(
    			'sheet_id' => $sheet_id,
	    		'classify_id' => $res['classify_id'],
	    		'difficulty' => 3
    		);
    		if(!$classifySheet->add($sheetInfo)){
    			$sheet->rollback();
    			return false;
    		}
    	}
    	$user = M("user");
    	if($user->where('user_id='.$userId)->setField('answer',1)){
    		$sheet->rollback();
    		return false;
    	}
    	$sheet->commit();
    	return true;
    }

}
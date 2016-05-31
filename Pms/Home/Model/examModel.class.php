<?php
namespace Home\Model;
use Think\Model;
class ExamModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function addSheet($age){
    	$sheet = M("answer_sheet");
    	$sheetInfo = array(
    		'user_id' => $userId,
    		'base_difficulty' => $userId,
    		'answers' => 0,
    		'score' => 0,
    		'flag' => 1
    	);
    	$id = $sheet->add($sheetInfo);
    	if($id){
    		
    	}
    	$classify = M("classify");
    	$classification = $classify->where('lavel=2 and flag=1')->select('classify_id,classify_name');
    	if($classification){
    		
    	}
    	foreach($classification as $res){
    		$info = array(
    			
    		);
    	}
    }

}
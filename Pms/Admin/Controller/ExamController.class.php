<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
use Org\Util\PHPExcel; 
class ExamController extends BaseController {

    public function readExcel(){
        $levelId = (int)I('level');
        if($levelId<=0){
            $this->error('请选择适用年龄');
        }
        set_time_limit(0);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx');// 设置附件上传类型
        $upload->rootPath  =     './Public/excel/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息

            $this->error($upload->getError());
        }
        
        $path = realpath(__ROOT__);
        $filename = $path."/Public/excel/".$info['file_stu']['savepath'].$info['file_stu']['savename'];
        // echo $filename;exit;
        import("Org.Util.PHPExcel");
        $phpexcel = new \PHPExcel();
        //如果excel文件后缀名为.xlsx，导入这下类
        import("Org.Util.PHPExcel.Reader.Excel2007");

        $PHPReader=new \PHPExcel_Reader_Excel2007();
        
        // $PHPReader=new \PHPExcel_Reader_Excel5();
        $PHPExcel=$PHPReader->load($filename);
        $currentSheet = $PHPExcel->getSheet(1);
        //获取总行数
        $allRow=$currentSheet->getHighestRow();

        if($allRow > 1100){
            $this->error('当前上传数据为'.$allRow.',超过最大上传限制(限制上传1000)');
        }
        $allColumn=$currentSheet->getHighestColumn();

        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for($currentRow=3;$currentRow<$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn='B';$currentColumn<=$allColumn;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                //读取到的数据，保存到数组$arr中
                $arr[$currentRow][$currentColumn]=$currentSheet->getCell($address)->getValue();
            }
        }

        $model = D('exam');
        $key = $model->importQuestion($arr, $levelId);

        if($key !== true){
            $this->error('数据导入失败,请检查第'.$key.'行数据');
        }
        $this->success('批量导入成功', 'examList');
    }
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
        
        $classify = $examModel->classifyList();
        $level = array_column($examModel->lvList(),null,'level_id');
        $this->assign('level', $level);
        $this->assign('list', $list); 
        $this->assign('incl', $incl); 
        $this->assign('page', $p->show()); 
        $this->assign('classify', $classify);
        $this->display('guanlishiti');
    }

    public function addExam(){
        $model = D("exam");
        //年龄段
        $level = $model->lvList();
        $incl = $model->inclList();
        $classify = $model->getClassily();
        $en = ['A','B','C','D'];
        $this->assign('classify', $classify);
        $this->assign('en', $en);
        $this->assign('incl', $incl);
        $this->assign('level', $level);
        $this->display('tianjiashiti');
    }

    public function updateExam(){
        $model = D("exam");
        $id = (int)I('id');
        //年龄段
        $level = $model->lvList();
        $incl = $model->inclList();
        $classify = $model->getClassily();
        $data = $model->getQuestion($id);
        if (!$data) {
            $this->error('试题不存在');
        }
        $en = ['A','B','C','D'];
        $this->assign('classify', $classify);
        $this->assign('en', $en);
        $this->assign('incl', $incl);
        $this->assign('level', $level);
        $this->assign('data', $data);
        $this->display('update');
    }

    public function del(){
        $model = D("exam");
        $id = (int)I('id');
        if($id <= 0 ){
            $this->error('试题不存在');
        }
        if($model->delQuestion($id) === false){
           $this->error('删除试题错误'); 
        }
        $this->success('删除成功', '/Admin/Exam/examList');
    }

    public function addQuestion(){
        $data = $_POST;
        $id = I('id');
        $model = D("exam");
        // print_r($data);
        $data['url'] = '';
        if($data['question']['type'] != 0){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg','mp3','mp4');// 设置附件上传类型
            $upload->rootPath  =     './Public/upload/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            
            $question = false;
            $flag = 0;
            if($id>0){
                $question = $model->getQuestion($id);
            }
            if($question){
                if($question['type'] != 0){
                    $flag = 1;
                }
            }
            // 上传文件 
            $info   =   $upload->upload();
            if(!$info and $flag == 0) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }
            if($info){
                $data['url'] = "/Public/upload/".$info['url']['savepath'].$info['url']['savename'];
            }else{
                $data['url'] = $question['file'];
            }
        }
        if($id>0){
            if($model->saveQuestion($id,$data)){
                 $this->success('修改成功', 'examList');
            }else{
                $this->error('修改失败');
            }
        }else{
            if($model->addQuestion($data)){
                 $this->success('添加成功', 'examList');
            }else{
                $this->error('添加失败');
            }
        }
    }

}
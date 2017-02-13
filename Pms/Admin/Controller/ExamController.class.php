<?php
namespace Admin\Controller;
use Think\Controller;
use Util\Util;
use Org\Util\PHPExcel; 
class ExamController extends BaseController {

    public function readExcel(){
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
        }else{// 上传成功
            // print_r($info);exit;
            // $this->success('上传成功！');
        }
        // print_r($info);
        
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
        if($allRow > 1005){
            echo "数据过多";die();
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

        // echo $allRow;exit;
        // $arr = (array_slice($arr,3,100) );

        $model = D('exam');
        $key = $model->importQuestion($arr);

        if($key !== true){
            echo $key;
        }
        $this->success('批量导入成功', 'examList');
        // echo json_encode($arr);
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
        $this->assign('classify', $classify);
        $this->assign('incl', $incl);
        $this->assign('level', $level);
        $this->display('tianjiashiti');
    }

    public function addQuestion(){
        $data = $_POST;
        $model = D("exam");
        if($model->saveQuestion($data)){
             $this->success('新增成功', 'examList');
        }else{
            $this->success('添加失败', 'addExam');
        }
    }

    public function addQuestionList(){

    }
}
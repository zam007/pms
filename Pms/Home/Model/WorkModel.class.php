?php
namespace Home\Model;
use Think\Model;
class WorkModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    public function getWork($info,$filed = '*') {
        $work = M("Work"); // 实例化Work对象
        $workInfo = $work->field($filed)->where($info)->find();
        return $workInfo;
    }

    public function getWorkField($workId,$filed = '*'){
        $work = M("Work"); // 实例化Work对象
        $workInfo = $work->field($filed)->where('work_id='.$workId)->find();
        return $workInfo;
    }


    public function getWrok($workId,$filed){
        $work = M("Work");
        return $work->field($filed)->where('work_id='.$workId)->find();
    }

    public function workList(){
        $work = M("Work");
        return $work->select();
    }
}
<?php
namespace Home\Model;
use Think\Model;
class TeamModel extends Model {
    protected $tablePrefix = '';
    protected $patchValidate = true;
    public function getTeam($info,$filed = '*') {
        $team = M("Team"); // 实例化team对象
        $teamInfo = $team->field($filed)->where($info)->find();
        // return $teamInfo->getlastsql();
        return $teamInfo;
    }

    public function getTeamField($teamId,$filed = '*'){
        $team = M("Team"); // 实例化team对象
        $teamInfo = $team->field($filed)->where('team_id='.$teamId)->find();
        return $teamInfo;
    }

    /**
     * 修改用户
     * @param type $teamId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $teamId,$update){
        $team = M("Team"); // 实例化team对象
        #$update["update_time"] = date("Y-m-d H:i:s", time());
        return $team->where('team_id='.$teamId)->save($update);
    }

    public function addteam($data){
        $team = M("Team");//实例化team对象
        return $team->add($data);
    }
    
    public function count($teamId){
        $team = M("team_user");//实例化team对象
        return $team->where('team_id='.$teamId)->count();
    }
}
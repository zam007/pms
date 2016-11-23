<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    protected $userId = 0;
    protected $teamId = 0;
    public function __construct(){
    	parent::__construct();
         $userId = I('session.user_id',0);
         if($userId == 0){
         	$this->error('您还未登录！','../Index/index');
         }
         $sessionId = I('session.session_id',0);
         // $lastSession = I('session.last_session_id',-1);
         // if($sessionId != $lastSession){
         // 	$this->error('您的账号在其他地方登录，如非本人操作，请修改密码！','Index/index');
         // }
         $this->userId = $userId;
         $this->teamId = I('session.team_id',0);
         $this->assign('config',C('constant')); 
     }

     //根据出生日期获取年龄
    public function age($birthday){
        list($year,$month,$day) = explode("-",$birthday);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff  = date("d") - $day;
        if ($day_diff < 0 || $month_diff < 0)
            $year_diff--;
        return $year_diff;
    }
}
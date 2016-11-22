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
     }
}
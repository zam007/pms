<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    protected $userId = 0;
    public function __construct(){
    	parent::__construct();
         $userId = I('session.user_id',0);
         if($userId == 0){
             $this->success('您还未登录！','Index/index');eixt;
         }
         $this->userId = $userId;
     }

}
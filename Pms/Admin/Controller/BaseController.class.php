<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
    protected $memberId = 0;
    public function __construct(){
    	parent::__construct();
         $memberId = I('session.member_id',0);
         if($memberId == 0){
         	$this->error('您还未登录！','../Index/index');
         }
         $this->memberId = $memberId;
     }

}
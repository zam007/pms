<?php
namespace PmsFIQ\Controller;
use Think\Controller;

class BaseController extends Controller {
    protected $userId = 0;
    public function __construct(){
    	// parent::__construct();
     //     $memberId = I('member_id',0);
     //     if($memberId == 0){
     //     	$this->error('您还未登录！','../Index/index');
     //     }
     //     $this->memberId = $memberId;
     // }

}
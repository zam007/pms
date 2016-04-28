<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    
    public function __construct(){
         $userId = (int)$_SESSION["user_id"];
         if($userId == 0){
             $this->success('您还未登录！', 'Index/index');
         }
     }

}
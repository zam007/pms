<?php
namespace Home\Controller;
use Think\Controller;
use Home\IndexModel;
class IndexController extends Controller {
    public function index(){
         $this->display("login");
     }
     public function login(){
         $user["user_name"] = I("userName");
         $user["password"] = md5(I("password").C("pwd_md5"));
         $index = D("Index");
         $index->login($user);
     }
}
<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
         echo '你好,自动部署已经测试成功，可以投入使用！！！';
     }
    public function index1(){
         echo '你好，这是服务器的生产环境测试语句22';
     }
}
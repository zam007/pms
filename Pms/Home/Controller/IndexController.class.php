<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
         echo '你好，这是服务器的生产环境1，6655227';
     }
    public function index1(){
         echo '你好，这是服务器的生产环境测试语句22';
     }
}
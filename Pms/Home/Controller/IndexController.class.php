<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
         echo '数据库设计阶段，敬请期待kaishi！';
     }
    public function index1(){
         echo '你好，这是服务器的生产环境测试语句22';
     }
}
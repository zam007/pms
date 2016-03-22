<?php
namespace Home\Controller;
use Think\Controller;
class PushController extends Controller {
    public function push(){
        error_reporting ( E_ALL );
		$dir = '/www/test1/';//该目录为git检出目录
		$handle = popen('cd '.$dir.' && git pull 2>&1','r');
		$read = stream_get_contents($handle);
		printf($read);
		pclose($handle);
    }
}
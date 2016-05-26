<?php
namespace Util;
/**
 * 公用工具
 */
class Util {

	/**
	 * 计算时间差，年月日
	 */
    public function diffDate($date1,$date2){ 
		$datestart= date('Y-m-d',strtotime($date1));
		
		if(strtotime($datestart)>strtotime($date2)){ 
			$tmp=$date2; 
			$date2=$datestart; 
			$datestart=$tmp; 
		} 
			list($Y1,$m1,$d1)=explode('-',$datestart); 
			list($Y2,$m2,$d2)=explode('-',$date2); 
			$Y=$Y2-$Y1; 
			$m=$m2-$m1; 
			$d=$d2-$d1; 
		if($d<0){ 
			$d+=(int)date('t',strtotime("-1 month $date2")); 
			$m--; 
		} 
		if($m<0){ 
			$m+=12; 
			$y--; 
		} 
		
		$time['year'] = $y;
		$time['moon'] = $m;
		$time['day'] = $d;
		return $time;
	} 
}
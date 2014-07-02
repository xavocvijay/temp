<?php

namespace xsocialApp;

class Controller_Profiler extends \AbstractController{
	public $execution_process=array();
	function logMe($where_am_i){
		if(!@$this->api->last_time) $this->api->last_time = time() + microtime();
		$current_time = time() + microtime();
		$this->execution_process[]=array(
				'spot' => $where_am_i,
				'time_taken' => $current_time - $this->api->last_time
			);
		$this->api->last_time = $current_time;
	}

	function __destruct(){
		if($this->api->isAjaxOutput()) return;
		// echo "<pre>";
		// 	print_r($this->execution_process);
		// echo "</pre>";
	}
}

<?php

class page_xsocialApp_page_activityHandler extends Page{
	
	function init(){
		parent::init();

		$this->add('xsocialApp/Plugins_AuthenticationCheck')->AuthenticatePage($this,$this);
		
		$task=$_GET['task'];

		if(!$task)
			throw new Exception("Must define task in query string", 1);

		call_user_method($task, $this);

			
	}

	function like_me(){
		$activity_id = $_GET['activity_id'];
		
		$activity=$this->add('xsocialApp/Model_Activity');
		$activity->load($activity_id);
		
		if($activity->is_liked()){
			$activity->unlike_it();
			$this->api->hook('activity_unliked',array($activity_id,$activity));
			$this->js()->_selector('#like_for_'.$activity_id)->text('Like')->execute();
		}
		else{
			$activity->like_it();
			$this->api->hook('activity_liked',array($activity_id,$activity));
			$this->js()->_selector('#like_for_'.$activity_id)->text('UnLike')->execute();
		}

	}

	function remove_me(){
		$activity_id = $_GET['activity_id'];
		
		$activity=$this->add('xsocialApp/Model_Activity');
		$activity->load($activity_id);
		$this->api->hook('activity_beforeDelete',array($activity_id,$activity));
			$activity->delete();
		$this->api->hook('activity_afterDelete');
		$this->js()->_selector('#activity_view_'.$activity_id)->slideUp('slow')->execute();
	}
}
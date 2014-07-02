<?php
namespace xsocialApp;

class View_Activity_DeleteActivity extends \View{
	public $activity_id=null;
	function init(){
		parent::init();
		$this->set('delete');
		$this->api->stickyGET('activity_id');
		
		if($_GET['activity_id']){
			$activity=$this->add('xsocialApp/Model_Activity');
			$activity->load($_GET['activity_id']);
			// // throw new \Exception($activity['activity_details']);
			$activity->delete();
			$this->js()->_selector('.view_activity')->trigger('reload')->execute();
		}


	

	}

}
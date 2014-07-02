<?php

class page_xsocialApp_page_activitypages_activity extends Page{
	function init(){
		parent::init();

		$this->api->stickyGET('activity_id');

		$activity=$this->add('xsocialApp/Model_Activity');
		$activity->addCondition('id',$_GET['activity_id']);

		$v=$this->add('xsocialApp/View_Activity_List');
		$v->setModel($activity);
	}
	
}

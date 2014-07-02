<?php

class page_xsocialApp_page_activitypages_friendsrequests extends Page{
	function init(){
		parent::init();

		$requests_from = $this->api->xsocialauth->model->getAllFollowing();
		foreach ($requests_from as $follower_id) {
			$this->add('xsocialApp/View_Notifications_Friends_Request',array('request_from_id'=>$follower_id));
		}

	}
}

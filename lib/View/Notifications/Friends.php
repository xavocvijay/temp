<?php

namespace xsocialApp;

class View_Notifications_Friends extends \View{

function init(){
	parent::init();

	$friend_requests=$this->api->xsocialauth->model->getAllFollowing();
	
	$count=count($friend_requests);
	
	if($count > 0) //for set color when friend request is more than 1
		$this->add('View')->set($count)->setStyle('background','#A81010');
	else
		$this->add('View')->set($count);

	$this->js('click')->univ()->frameURL('Friend List',$this->api->url('xsocialApp_page_activitypages_friendsrequests'));

	}
	
}
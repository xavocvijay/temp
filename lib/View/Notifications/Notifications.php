<?php

namespace xsocialApp;

class View_Notifications_Notifications extends \View{

function init(){
	parent::init();

	$user=$this->api->xsocialauth->model;

	if($_GET['notification_reloaded']){
		$user->getNotified($_GET['till_id']);
	}

	$count=$user->getNotifications(true);
	
	if($count['count'] > 0)
		$this->add('View')->set($count['count'])->setStyle('background','#A81010');
	else	
		$this->add('View')->set($count['count']);
	
	
	$this->js('click')->univ()->frameURL('Notifications',$this->api->url('xsocialApp_page_activitypages_notification'));
	
	$this->addClass('notification');
	$this->js('reload')->reload(array('notification_reloaded'=>1,'till_id'=>$count['max_id']));
	}
}

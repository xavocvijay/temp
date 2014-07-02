<?php

namespace xsocialApp;
class View_Notifications_NotificationView extends \View{

function init(){
	parent::init();
	
	}
	function defaultTemplate(){
		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		$this->api->pathfinder->addLocation(
			$this->api->locate('addons',__NAMESPACE__),
			array(
		  		'template'=>'templates',
		  		'css'=>'templates/css'
				)
			)->setParent($l);
		return array('view/xsocial-notificationview');
	}
}
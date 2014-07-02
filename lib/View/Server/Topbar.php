<?php

namespace xsocialApp;

class View_Server_Topbar extends \View{
	function init(){
		parent::init();
		// echo "<pre>";
		// print_r($_SESSION);
		// echo "</pre>";
		$this->add('View',null,'member_login')->set($this->api->cu_name);//add current login member name 

		$btn = $this->add('Button',null,'logout')->set('logout')->setAttr(array('title'=>'Logout','data-toggle'=>'tooltip', 'data-placement'=>'bottom'));
		if($btn->isClicked()){
			// throw new \Exception($btn->js('click'));
			$this->api->xsocialauth->logout();
			$this->api->redirect($this->api->url(null,array('subpage'=>'xsocial-logout')));
		}

		$this->add('xsocialApp/View_Search',null,'search');
		$this->add('xsocialApp/View_Coin',null,'coin');
		$this->add('xsocialApp/View_Notifications_Friends',null,'friends');
		$this->add('xsocialApp/View_Messages',null,'message_count');
		$this->add('xsocialApp/View_Notifications_Notifications',null,'notification');
		
		//$home_btn=$this->add('Button',null,'home')->set('Home');
		//$profile_btn=$this->add('Button',null,'profile')->set('Profile');
		
		$this->add('xsocialApp/View_Settings',null,'setting');

		//$home_btn->js('click')->univ()->redirect($this->api->url(null,array('subpage'=>'xsocial-dashboard')));
		
		//$profile_btn->js('click')->univ()->redirect($this->api->url(null,array('subpage'=>'xsocial-profile')));
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
		return array('view/xsocial-topbar');
	}
}

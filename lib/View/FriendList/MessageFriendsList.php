<?php

namespace xsocialApp;

class View_FriendList_MessageFriendsList extends \CompleteLister{

	function setSource($source){

		parent::setSource($source);
		$this->template->trySet('all',$this->api->url(null));
	}
	
	function formatRow(){
		$this->current_row_html['url']=$this->api->url(null,array('subpage'=>'xsocial-messages','member_id'=>$this->model->id));
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
		return array('view/xsocial-messagefriendslist');
	}


}


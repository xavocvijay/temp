<?php

namespace xsocialApp;

class View_FriendList_AllFriends extends \CompleteLister{


	function formatRow(){
		$this->current_row_html['url']=$this->api->url(null,array('subpage'=>'xsocial-profile','profile_of'=>$this->model->id));
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
		return array('view/xsocial-allfriendslist');
	}


}


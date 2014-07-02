<?php

namespace xsocialApp;

class View_FriendList_FriendRequest extends \View{
	function init(){
		parent::init();

		foreach ($followers=$this->add('xsocialApp/Model_Friends') as $junk) {
				 $followers->addCondition('request_to_id',$this->api->cu_id);
				 $followers->addCondition('is_accepted',false);
				 $friends->tryLoadAny();							
				 $this->add('xsocialApp/View_ProfilePic',array('member_id'=>$this->api->cu_id),'profile_pic');
				 $this->add('xsocialApp/View_FriendsBtn_PendingApproval',null,'FriendsBtn');
			
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
		return array('view/xsocial-friendlist');
	}


}


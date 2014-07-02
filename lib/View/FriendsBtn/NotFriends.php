<?php

namespace xsocialApp;

class View_FriendsBtn_NotFriends extends \View{
	public $member;
	function init(){
		parent::init();
		$btn = $this->add('Button')->set('Send Friend Request');
		
		if($btn->isClicked()){
		// throw new \Exception("Error Processing Request", 1);
			$this->member->sendFriendRequest();
			$this->owner->owner->js()->reload()->execute();
			$this->js()->univ()->alert('hi')->execute();
		}
	}
}


<?php

namespace xsocialApp;

class View_FriendsBtn_Following extends \View{
	public $member;
	function init(){
		parent::init();
		$btn = $this->add('Button')->set('Following (Cancle Friend Request)');
		if($btn->isClicked()){
			$this->member->unFriend();
			$this->owner->owner->js()->reload()->execute();
			}
	}
}


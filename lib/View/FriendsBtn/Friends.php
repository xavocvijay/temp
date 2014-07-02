<?php

namespace xsocialApp;

class View_FriendsBtn_Friends extends \View{
	public $member;
	function init(){
		parent::init();
		$btn = $this->add('Button')->set('Unfriend');
		if($btn->isClicked()){
			$this->member->unFriend();
			$this->owner->owner->js()->reload()->execute();
			// $this->js()->univ()->alert('Unfriend Succesfully')->execute();
		}
	}
}


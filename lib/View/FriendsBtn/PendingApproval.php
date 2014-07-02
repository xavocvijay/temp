<?php

namespace xsocialApp;

class View_FriendsBtn_PendingApproval extends \View{
	public $member;
	function init(){
		parent::init();
		$denied = $this->add('Button')->set('Denied');
		$approved = $this->add('Button')->set('Approved');
		
		if($denied->isClicked()){
			$this->member->unFriend();
			$this->owner->owner->js()->hide('slow')->execute();
		}
		if($approved->isClicked()){
			$this->member->approveRequest();			
			$this->owner->owner->js()->hide('slow')->execute();
		}
	}
}


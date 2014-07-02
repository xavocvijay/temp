<?php
namespace xsocialApp;

class View_FriendRequestBtn extends \View{
	public $related_with; // Loaded Member Model with relation of current user has to find out

	function init(){
		parent::init();
	}

	function recursiveRender(){
			
		$current_status = $this->related_with->getFriendStatus();
		// echo "<pre>";
		// echo print_r($current_status);
		// echo "</pre>";
		$this->add('xsocialApp/View_FriendsBtn_'.$current_status,array('member'=>$this->related_with));
		parent::recursiveRender();
	}

}


<?php

namespace xsocialApp;

class View_Server_MessageFriendsList extends \View{
	function init(){
		parent::init();
			
		$all_friends=$this->api->xsocialauth->model->getAllFriendsDetails();		
		$act_lister=$this->add('xsocialApp/View_FriendList_MessageFriendsList');

		$act_lister->setSource($all_friends);


	}

	
}
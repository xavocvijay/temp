<?php

namespace xsocialApp;

class View_Server_AllFriends extends \View{
	function init(){
		parent::init();

		if($_GET['profile_of']){
			$member=$this->add('xsocialApp/Model_MemberAll');
			$member->load($_GET['profile_of']);
		}
		else
			$member=$this->api->xsocialauth->model; 
		
		$all_friends=$member->getAllFriendsDetails();		
				
		$count=0;
		foreach ($all_friends as $variable ) {
			$count++;
		}


		$act_lister=$this->add('xsocialApp/View_FriendList_AllFriends');
		$act_lister->setSource($all_friends);

		$act_lister->template->trySet('count',$count);

	}

	
}
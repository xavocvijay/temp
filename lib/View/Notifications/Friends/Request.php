<?php

namespace xsocialApp;


class View_Notifications_Friends_Request extends \View{
	public $request_from_id;
	function init(){
		parent::init();
		$cols= $this->add('Columns');
		$profile_col  = $cols->addColumn(4);
		$details_col = $cols->addColumn(8);

		$profilePic = $profile_col->add('xsocialApp/View_ProfilePic',array('member_id'=>$this->request_from_id));
		$details_col->add('View')->set($profilePic->member['name']);
		$details_col->add('xsocialApp/View_FriendsBtn_PendingApproval',array('member'=>$profilePic->member));

	}
}
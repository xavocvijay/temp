<?php

namespace xsocialApp;

class View_Server_Activity extends \View{
	function init(){
		parent::init();
		
		// $a=$this->add('xsocialApp/Model_Activity');
		// $a->debug()->tryLoadAny();
		// return;

		$me = $this->api->xsocialauth->model;
		if($_GET['profile_of'])
			$me = $this->add('xsocialApp/Model_MemberAll')->load($_GET['profile_of']);
		
		$friends=$me->getAllFriends();

		$activities_2_show = $this->add('xsocialApp/Model_Activity');
		
		$activities_2_show->addCondition('activity_type',array('StatusUpadate','updateProfilePic','updateCoverPage'));

		$activities_2_show->_dsql()->where(
				$activities_2_show->_dsql()->orExpr()
				->where('from_member_id',$me->id)
				->where('related_member_id',$me->id)
				->where('from_member_id',$friends)
				->where('related_member_id',$friends)
			);

		$activities_2_show->_dsql()->having(
				'visibility = 100 or (visibility = 50 and is_by_friend = 1) or (visibility = 10 and from_member_id = '.$this->api->xsocialauth->model->id.')'
			);

		// FILTER MODEL WITH ALL POSSIBLE CONDITIONS AND PAGINATOR VALUES

		// $activities_2_show->addCondition('from_member_id',$this->api->xsocialauth->model->id);


		$act_lister=$this->add('xsocialApp/View_Activity_List');
		// $activities_2_show->debug();
		$activities_2_show->setOrder('id','desc');

		$act_lister->setModel($activities_2_show);
		$act_lister->add('misc/Controller_AutoPaginator')->setLimit(5);
		
		// $act_lister->template->trySet('activity_detail','kkj');
	}

	
}
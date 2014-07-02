<?php

namespace xsocialApp;

class Plugins_OnPostCardShared extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();

		$this->addHook('OnPostCardShared',$this);
	}

	function OnPostCardShared($obj,$member_id){
		$member=$this->add('xsocialApp/Model_MemberAll');
		$member->load($member_id);

		$point=$this->add('xsocialApp/Model_Point');
		$point->addCondition('name','OnPostCardShared');
		$point->loadAny();


		$pointtransaction=$this->add('xsocialApp/Model_PointTransaction');

		$pointtransaction['point_id']=$point->id;
		$pointtransaction['member_id']=$member->id;
		$pointtransaction['points']=$point['points'];
		$pointtransaction->save();
				
		

	}
 function getDefaultParams($new_epan){}
}
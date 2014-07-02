<?php

namespace xsocialApp;

class Plugins_OnVerificationRefeCheck extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();
		$this->addHook('OnVerificationRefeCheck',$this);
	}

	function OnVerificationRefeCheck($obj,$member_id,$member){
		throw $this->exception('I M Refer Check', 'ValidityCheck')->setField('FieldName');
		
		if(!$member['referId']) return;

		$refferer = $this->add('xsocialApp/Model_MemberAll')->load($member['referId']);
		$old_ref_count  = $this->add('xsocialApp/Model_MemberAll')
					->addCondition('referId',$refferer->id)
					->addCondition('is_verify',true)
					->count()->getOne();

		if($old_ref_count !=2) return;

		// This is third reference .. now add points ..
		
		if($member->count()->getOne()){

			$point=$this->add('xsocialApp/Model_Point');
			$point->addCondition('name','OnVerificationRefeCheck');
			$point->loadAny();


			$pointtransaction=$this->add('xsocialApp/Model_PointTransaction');

			$pointtransaction['point_id']=$point->id;
			$pointtransaction['member_id']=$refferer->id;
			$pointtransaction['points']=$point['points'];
			$pointtransaction->save();
		}
		

	}
 function getDefaultParams($new_epan){}
}
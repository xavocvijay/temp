<?php

namespace xsocialApp;

class Plugins_OnVerificationPoints extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();

		$this->addHook('verifymember_register',$this);
	}

	function verifymember_register($obj,$member_id,$member){
		throw $this->exception('I MRegister', 'ValidityCheck')->setField('FieldName');

		$point=$this->add('xsocialApp/Model_Point');
		$point->addCondition('name','OnVerification');
		$point->loadAny();


		$pointtransaction=$this->add('xsocialApp/Model_PointTransaction');

		$pointtransaction['point_id']=$point->id;
		$pointtransaction['member_id']=$member_id;
		$pointtransaction['points']=$point['points'];
		$pointtransaction->save();
				
		

	}
 function getDefaultParams($new_epan){}
}
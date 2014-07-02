<?php

class page_xsocialApp_page_birthdaywish extends Page{

	function init(){
		parent::init();

		$member=$this->add('xsocialApp/Model_MemberAll');
		$member->addExpression('birth_day','DAYOFMONTH(date_of_birth)');
		$member->addExpression('birth_month','MONTH(date_of_birth)');

		$member->addCondition('birth_month',date("n"));
		$member->addCondition('birth_day',date('j'));

		foreach ($member as $junk) {
			
			$member->sendBirthdayWishMail();

			$point=$this->add('xsocialApp/Model_Point');
			$point->addCondition('name','OnBirthday');
			$point->loadAny();


			$pointtransaction=$this->add('xsocialApp/Model_PointTransaction');

			$pointtransaction['point_id']=$point->id;
			$pointtransaction['member_id']=$member->id;
			$pointtransaction['points']=$point['points'];
			$pointtransaction->save();
				
		}

	}

}
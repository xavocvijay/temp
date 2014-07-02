<?php

namespace xsocialApp;

class Plugins_OnContinueLogin extends \componentBase\Plugin{
	public $namespace = 'xsocialApp';

	function init(){
		parent::init();
		$this->addHook('OnContinueLogin',$this);
	}

	function OnContinueLogin($obj,$member_id,$member){
		// throw $this->exception('I Continues Login', 'ValidityCheck')->setField('FieldName');
			
			// If user has not been logged in before on the same day
			$todays_previous_login=$this->add('xsocialApp/Model_MemberAttendanceLog');
			$todays_previous_login->addCondition('login_on','not like',date('Y-m-d').'%');
			$todays_previous_login->addCondition('member_id',$member_id);
			$todays_previous_login->tryLoadAny();

			// Always make an login entry in attendance
			$attendance=$this->add('xsocialApp/Model_MemberAttendanceLog');
			$attendance['member_id']=$member_id['id'];
			$attendance->save();

			// GRap POint ID  of required point system :: 
			// Not taking points just id .. points hardcoded here
			$point=$this->add('xsocialApp/Model_Point');
			$point->addCondition('name','OnContinueLogin');
			$point->loadAny();

			// Make a new entry of point transaction IF REQUIRED
			$pointtransaction=$this->add('xsocialApp/Model_PointTransaction');

			$yesterday_date=date('Y-m-d', strtotime(date('Y-m-d') . " - 1 day"));
			$yester_day_attendance=$this->add('xsocialApp/Model_MemberAttendanceLog');
			$yester_day_attendance->addCondition('login_on','like',$yesterday_date.'%');
			$yester_day_attendance->addCondition('login_on','not like',date('Y-m-d').'%');
			$yester_day_attendance->addCondition('member_id',$member_id);
			$yester_day_attendance->tryLoadAny();

			
			// IF  Member logged in on yesterday
				// increace continues_days in memebr
				// if continues days >=30
					// Add points and make continues_days = 0
			// else
				// NOT logged in yesterday .. how many continues days he/she has been logged( cd= from member['continues_days'])
				// if(cd >=10 and cd <=20)
					// give 10 points
				// if(cd>20 and cd <=30)
					// give 25 points
				// make conntinues_points = 0

			if($yester_day_attendance->loaded()){
				if(!$todays_previous_login->loaded()){
					$member['continues_days']=$member['continues_days']+1;
					if($member['continues_days'] >= 30){
						$member['continues_days']=0;

						$pointtransaction['point_id']=$point->id;
						$pointtransaction['member_id']=$member->id;
						$pointtransaction['points']=40;
						$pointtransaction->save();
					}
					$member->save();
				}
			}else{
				// Continues days were breaked and
				// Member not logged in yesterday
				if($member['continues_days'] >= 10 and $member['continues_days'] <= 20){
					$pointtransaction['point_id']=$point->id;
					$pointtransaction['member_id']=$member->id;
					$pointtransaction['points']=10;
					$pointtransaction->save();
				}

				if($member['continues_days'] > 20 and $member['continues_days'] <= 30){
					$pointtransaction['point_id']=$point->id;
					$pointtransaction['member_id']=$member_id->id;
					$pointtransaction['points']=25;
					$pointtransaction->save();
				}

				$member['continues_days']=0;
				$member->save();

			}
	}
 function getDefaultParams($new_epan){}
}
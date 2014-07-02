<?php
namespace xsocialApp;
class Model_AllActiveMembers extends Model_MemberAll{
	function init(){
		parent::init();

		$this->addCondition('is_active',true);
	}

	

	function tryLogin($emailID,$password){
		$member=$this->add('xsocialApp/Model_AllActiveMembers');

		$member->addCondition('emailID',$emailID); 
		$member->addCondition('password',$password);
		$member->tryLoadAny();
		if($member->loaded()){
			$this->api->memorize('logged_in_social_user',$emailID);
			$this->api->exec_plugins('OnContinueLogin',array($member->id,$member));
			return true;
			}
			else{
				$this->api->forget('logged_in_social_user',$emailID);
				return false;
			}
	}
}
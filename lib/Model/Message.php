<?php

namespace xsocialApp;

class Model_Message extends \Model_Table{
	public $table="xsocialApp_messages";

	function init(){
		parent::init();

			$this->hasOne('xsocialApp/MemberAll','message_from_member_id');
			$this->hasOne('xsocialApp/MemberAll','message_to_member_id');
			$this->addField('message')->type('text')->caption('Message');
			$this->addField('send_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));
			$this->addField('is_read')->type('boolean')->defaultValue(false);

			$this->addExpression('from_member_profile_pic')->set(function($m,$q){

				return $m->refSQL('message_from_member_id')->fieldQuery('profile_pic');
			});
			
			$this->add('dynamic_model/Controller_AutoCreator');


		
	}
}
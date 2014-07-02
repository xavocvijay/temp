<?php
namespace xsocialApp;
class Model_Friends extends \Model_Table{
	public $table= "xsocialApp_friends";
	function init(){
		parent::init();

		$this->hasOne('xsocialApp/MemberAll','request_from_id');
		$this->hasOne('xsocialApp/MemberAll','request_to_id');
		$this->addField('is_accepted')->type('boolean')->defaultValue(false);
		$this->addField('accepted_on')->type('datetime')->defaultValue(null);
		$this->addField('send_on')->type('datetime')->defaultValue(null);
		$this->addField('is_removed_from_notification')->type('boolean')->defaultValue(false);

		$this->add('dynamic_model/Controller_AutoCreator');

	}
}
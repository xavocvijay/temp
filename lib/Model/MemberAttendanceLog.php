<?php
namespace xsocialApp;
class Model_MemberAttendanceLog extends \Model_Table{
	public $table= "xsocialApp_members_attendance_log";
	function init(){
		parent::init();

		$this->hasOne('xsocialApp/MemberAll','member_id');
		$this->addField('login_on')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->add('dynamic_model/Controller_AutoCreator');

	}
}